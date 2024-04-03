<?php
session_start();
include_once("conexao.php");

$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$sexo = $_POST['sexo'];
$instituicao = $_POST['instituicao'];
$curso = $_POST['curso'];
$cpf = $_POST['cpf'];
$cpf_sem_formato = preg_replace('/[^0-9]/', '', $cpf);
$validade = $_POST['validade'];

// Diretório onde as fotos serão armazenadas
$diretorio_destino = "./src/img/uploads/"; // Certifique-se de que este diretório seja válido

// Insira os dados na tabela (sem o caminho da imagem) e obtenha o ID inserido
$sql = "INSERT INTO estudante (nome, data_nascimento, sexo, instituicao, curso, cpf, validade, modificado, criado) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $nome, $data_nascimento, $sexo, $instituicao, $curso, $cpf_sem_formato, $validade);

if (mysqli_stmt_execute($stmt)) {
    // Obtenha o ID inserido
    $id_aluno = mysqli_insert_id($conn);

    // Nome do arquivo original
    $nome_arquivo_original = $_FILES['imagem']['name'];

    // Caminho completo do arquivo original no servidor
    $caminho_arquivo_original = $diretorio_destino . $nome_arquivo_original;

    // Move o arquivo original para o diretório de destino
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo_original)) {

        // Compactar e redimensionar a imagem nativamente em PHP
        $novo_nome_arquivo = $id_aluno . ".jpg";
        $caminho_arquivo = $diretorio_destino . $novo_nome_arquivo;

        // Corrige a orientação da imagem usando a biblioteca exif
        fixImageOrientation($caminho_arquivo_original, $caminho_arquivo);

        // Redimensiona e comprime a imagem
        redimensionarEComprimirImagem($caminho_arquivo, 995, 1293, 40);

        // Atualize o campo de imagem com o nome do arquivo
        $sql_update = "UPDATE estudante SET imagem = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "si", $caminho_arquivo, $id_aluno);
        mysqli_stmt_execute($stmt_update);

        $_SESSION['msg'] = "<p style='color:green;'>Aluno cadastrado com sucesso!</p>";
        header("Location: .src/aluno/cad_aluno");
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Erro ao enviar a foto.</p>";
        header("Location: .src/aluno/cad_aluno");
    }

    // Feche as declarações
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_update);
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar aluno: " . mysqli_error($conn) . "</p>";
    header("Location: .src/aluno/cad_aluno");
}

function fixImageOrientation($source, $destination) {
    $info = @exif_read_data($source);

    if ($info && isset($info['Orientation'])) {
        $orientation = $info['Orientation'];

        if ($orientation != 1) {
            $image = imagecreatefromjpeg($source);

            switch ($orientation) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;

                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;

                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }

            imagejpeg($image, $destination, 20);
            imagedestroy($image);
        } else {
            // Se não precisar de rotação, apenas copie o arquivo
            copy($source, $destination);
        }
    } else {
        // Caso não seja possível obter informações de orientação, copie o arquivo
        copy($source, $destination);
    }
}

// Função para redimensionar e comprimir a imagem
function redimensionarEComprimirImagem($caminho, $largura, $altura, $qualidade)
{
    list($largura_original, $altura_original) = getimagesize($caminho);
    $nova_imagem = imagecreatetruecolor($largura, $altura);
    $imagem_original = imagecreatefromjpeg($caminho);
    imagecopyresampled($nova_imagem, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_original, $altura_original);
    imagejpeg($nova_imagem, $caminho, $qualidade);
    imagedestroy($nova_imagem);
    imagedestroy($imagem_original);
}
?>

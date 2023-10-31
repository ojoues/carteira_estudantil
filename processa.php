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

// Conecte-se ao banco de dados (você deve já ter uma conexão estabelecida)
include_once("conexao.php"); // Inclua o arquivo de conexão com o banco de dados

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
		// Copiar a imagem original para um novo arquivo
		$nome_arquivo = $id_aluno . ".jpg";
		$caminho_arquivo = $diretorio_destino . $nome_arquivo;
		copy($caminho_arquivo_original, $caminho_arquivo);

		// Redimensionar a imagem
		$imagem_redimensionada = redimensionarImagem($caminho_arquivo, 995, 1293);

		// Salvar a imagem redimensionada no diretório de destino
		imagejpeg($imagem_redimensionada, $caminho_arquivo);

		// Excluir a imagem original
		if (file_exists($caminho_arquivo_original)) {
			unlink($caminho_arquivo_original);
		}

		// Atualize o campo de imagem com o nome do arquivo
		$sql_update = "UPDATE estudante SET imagem = ? WHERE id = ?";
		$stmt_update = mysqli_prepare($conn, $sql_update);
		mysqli_stmt_bind_param($stmt_update, "si", $caminho_arquivo, $id_aluno);
		mysqli_stmt_execute($stmt_update);

		$_SESSION['msg'] = "<p style='color:green;'>Aluno cadastrado com sucesso!</p>";
		header("Location: cad_aluno");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Erro ao enviar a foto.</p>";
		header("Location: cad_aluno");
	}

	// Feche as declarações
	mysqli_stmt_close($stmt);
	mysqli_stmt_close($stmt_update);
} else {
	$_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar aluno: " . mysqli_error($conn) . "</p>";
	header("Location: cad_aluno");
}

// Função para redimensionar a imagem
function redimensionarImagem($caminho, $largura, $altura)
{
	list($largura_original, $altura_original) = getimagesize($caminho);
	$nova_imagem = imagecreatetruecolor($largura, $altura);

	$imagem_original = imagecreatefromjpeg($caminho);

	// Calcula as proporções
	$proporcao = $largura / $altura;

	$nova_largura = $largura_original;
	$nova_altura = $altura_original;

	if ($largura_original / $altura_original > $proporcao) {
		$nova_largura = $altura_original * $proporcao;
	} else {
		$nova_altura = $largura_original / $proporcao;
	}

	$deslocamento_x = ($largura_original - $nova_largura) / 2;
	$deslocamento_y = ($altura_original - $nova_altura) / 2;

	imagecopyresampled($nova_imagem, $imagem_original, 0, 0, $deslocamento_x, $deslocamento_y, $largura, $altura, $nova_largura, $nova_altura);

	return $nova_imagem;
}

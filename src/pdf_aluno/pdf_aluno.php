<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <?php
    $pageTitle = "Carteira Estudantil";
    if (isset($_GET['id'])) {
        $pageTitle .= " - " . $_GET['id'];
    }
    ?>
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/removeAds.css">
</head>

<body>

    <div class="container">
        <?php

        // Verifique o tempo de inatividade permitido (em segundos)
        $inatividade_permitida = 600; // 30 minutos (pode ajustar conforme necessário)

        // Verifique se a sessão expirou devido à inatividade
        if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso']) > $inatividade_permitida) {
            // Sessão expirou, redirecione o usuário para a página de login
            session_destroy();
            header("Location: ../../login");
            exit();
        }

        // Atualize o tempo do último acesso
        $_SESSION['ultimo_acesso'] = time();

        // Obtém o nome do usuário logado, se estiver disponível na sessão
        $usuario_nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : "Usuário Desconhecido";

        // Incluir conexão com BD
        include_once '../../conexao.php';

        // Array com os nomes dos meses em português
        $meses = array(
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );

        // Obtenha o número do mês atual (de 1 a 12)
        $mesAtual = date('n');

        // Obtenha o ano atual
        $anoAtual = date('Y');

        // Calcula o ano seguinte
        $anoSeguinte = $anoAtual + 1;

        // Verifique se o parâmetro 'id' foi passado na URL
        if (isset($_GET['id'])) {
            // Obtenha o ID do estudante da URL
            $idEstudante = $_GET['id'];

            // QUERY para recuperar o registro do banco de dados com base no ID
            $query_estudante = "SELECT imagem, nome, instituicao, curso, data_nascimento, sexo, cpf, id FROM estudante WHERE id = ?";

            // Prepara a QUERY
            $result_estudante = $conn->prepare($query_estudante);

            // Vincule o valor do parâmetro 'id' à consulta
            $result_estudante->bind_param('i', $idEstudante);

            // Executar a QUERY
            $result_estudante->execute();

            // Obter resultado
            $result_estudante->bind_result($imagem, $nome, $instituicao, $curso, $data_nascimento, $sexo, $cpf, $id);
            $result_estudante->fetch();

            if ($nome) {
                echo "<div class='input-container'>";

                echo "<p class='titulo font-tailwind sombra inline-input cabecalho'>dne</p>";
                echo "<p class='titulo2 font-tailwind sombra inline-input cabecalho2'>Documento<br>Nacional<br>do Estudante</p>";
                echo "<img class='img-unec inline-input' src='img/unec.png'>";

                echo "</div>";

                echo "<div class='input-container'>";

                echo "<div class='inline-input'>";
                // Ajuste o caminho relativo para a imagem
                $imagemRelativa = '../img/uploads/' . basename($imagem);
                echo "<img class='imagem-aluno sombra' src='" . $imagemRelativa . "'><br>";
                echo "</div>";

                echo "<div class='inline-input informacoes sombra'>";
                echo "<p class='subtitulo'>Nome:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $nome . "</p>";

                echo "<p class='subtitulo'>Instituição de Ensino:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $instituicao . "</p>";

                echo "<p class='subtitulo'>Curso/Série:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $curso . "</p>";

                $formattedData = date('d/m/Y', strtotime($data_nascimento)); // Formata a data
                echo "<p class='subtitulo'>Data de Nascimento:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $formattedData . "</p>";

                // Verificar o valor da coluna "sexo" e atribuir o texto apropriado
                $sexoTexto = ($sexo == 1) ? "Masculino" : ($sexo == 0 ? "Feminino" : "Não especificado");
                echo "<p class='subtitulo'>Sexo:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $sexoTexto . "</p>";

                echo "<p class='subtitulo'>ID:</p>";
                echo "<p class='texto-padrao maiusculo'>" . (string)$id . "</p>";

                // Formatar o CPF com a máscara
                $cpfFormatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
                echo "<p class='subtitulo'>CPF:</p>";
                echo "<p class='texto-padrao maiusculo'>" . $cpfFormatado . "</p>";

                echo "</div>";
                echo "</div>";

                echo "</div>";

                echo "<div class='year-text'>";
                echo "<div class='left-content'>";
                echo "<div class='qr-code'>";
                //Gerador de QR Code
                $id = isset($_GET['id']) ? $_GET['id'] : null;

                // Link estático do site
                $siteLink = "https://conjunct-recipient.000webhostapp.com/pesquisar?aluno=";

                // Concatenar o link estático com o ID do aluno
                $linkCompleto = $siteLink . $id;

                // Função para gerar um QR Code com base no link completo
                function generateQRCodeForAluno($linkCompleto, $size = 100)
                {
                    // Montar o URL do Google Charts API
                    $url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($linkCompleto);

                    // Exibir a imagem do QR Code
                    echo '<img src="' . $url . '" alt="QR Code">';
                }

                if ($id !== null) {
                    generateQRCodeForAluno($linkCompleto);
                } else {
                    echo "ID do aluno não fornecido via GET.";
                }

                echo "</div>";
                echo "</div>";

                echo "<div class='right-content'>";
                echo "<p class='ano-branco sombra'>" . $anoAtual . "</p>";
                echo "<p class='abaixo-ano-branco maiusculo'>Válido até " . $meses[$mesAtual] . " de " . $anoSeguinte . "</p>";

                echo "</div>";
                echo "</div>";

                echo "<br>";
                echo "<br>";

                echo "<div class='image-container'>";
                echo "<img class='logo logo1' src='img/unec.png'>";
                echo "<img class='logo logo2' src='img/prepara-cursos.png'>";
                echo "<img class='logo logo3' src='img/ampla.png'>";
                echo "</div>";

                echo "<div class='text-center'>";

                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";

                echo "<p class='texto-padrao sombra'>Documento padronizado nacionalmente conforme a Lei 12.933/2013</p>";

                echo "<p class='texto-padrao sombra'>Válido em todo território nacional até " . $meses[$mesAtual] . " de " . $anoSeguinte . "</p>";

                echo "</div>";

                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";

                echo "<div class='year-text'>";

                echo "<div class='left-content'>";
                echo "<p class='ano sombra'>" . $anoAtual . "</p>";
                echo "</div>";

                echo "<div class='right-content'>";
                echo "<p class='texto-padrao sombra'><br><br>Salvação só em Jesus</p>";
                echo "</div>";

                echo "</div>";
            } else {
                echo "<p>Estudante não encontrado.</p>";
            }
        } else {
            echo "<p>ID do estudante não fornecido.</p>";
        }
        ?>
    </div>

</body>

</html>
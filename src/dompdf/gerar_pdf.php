<?php
// Carregar o Composer
require './vendor/autoload.php';

// Incluir conexao com BD
include_once './conexao.php';

// Importar o namespace Dompdf
use Dompdf\Dompdf;

// Função para formatar CPF com máscara
function formatarCpf($cpf)
{
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

// Verifique se o parâmetro 'id' foi passado na URL
if (isset($_GET['id'])) {
    // Obtenha o ID do estudante da URL
    $idEstudante = $_GET['id'];

    // QUERY para recuperar o registro do banco de dados com base no ID
    $query_estudante = "SELECT imagem, nome, instituicao, curso, data_nascimento, sexo, cpf, id FROM estudante WHERE id = :id";

    // Prepara a QUERY
    $result_estudante = $conn->prepare($query_estudante);

    // Associe o valor do parâmetro 'id' à consulta
    $result_estudante->bindParam(':id', $idEstudante, PDO::PARAM_INT);

    // Executar a QUERY
    $result_estudante->execute();

    // Ler o registro do estudante retornado do BD
    $row_estudante = $result_estudante->fetch(PDO::FETCH_ASSOC);

    if ($row_estudante) {
        // Informacoes para o PDF
        $dados = "<!DOCTYPE html>";
        $dados .= "<html lang='pt-br'>";
        $dados .= "<head>";
        $dados .= "<meta charset='UTF-8'>";
        $dados .= "<title>Carteira Estudantil</title>";
        $dados .= "<style>";
        $dados .= "</style>";
        $dados .= "</head>";
        $dados .= "<body>";

        // Ajuste o caminho relativo para a imagem
        $imagemRelativa = '../img/uploads/' . basename($row_estudante['imagem']);

        $dados .= "<div class='left-column'>";
        $dados .= "</div>";
        $dados .= "<div class='right-column'>";
        $dados .= "<h1>DNE</h1>";
        $dados .= "<h4>Documento Nacional do Estudante</h4>";
        $dados .= "<img src='" . $imagemRelativa . "'><br>";
        $dados .= "$imagemRelativa<br>";
        $dados .= "Nome: " . $row_estudante['nome'] . "<br>";
        $dados .= "Instituição de Ensino: " . $row_estudante['instituicao'] . "<br>";
        $dados .= "Curso/Série: " . $row_estudante['curso'] . "<br>";
        $formattedData = date('d/m/Y', strtotime($row_estudante['data_nascimento'])); // Formata a data
        $dados .= "Data de Nascimento: " . $formattedData . "<br>";

        // Verificar o valor da coluna "sexo" e atribuir o texto apropriado
        if ($row_estudante['sexo'] == 1) {
            $sexoTexto = "Masculino";
        } elseif ($row_estudante['sexo'] == 0) {
            $sexoTexto = "Feminino";
        } else {
            $sexoTexto = "Não especificado"; // Adicione uma opção padrão caso o valor não seja 0 nem 1
        }

        $dados .= "Sexo: " . $sexoTexto . "<br>";

        // Formatar o CPF com a máscara
        $cpfFormatado = formatarCpf($row_estudante['cpf']);
        $dados .= "CPF: " . $cpfFormatado . "<br>";

        $dados .= "ID: " . $row_estudante['id'] . "<br>";

        $dados .= "<hr>";
        $dados .= "Documento padronizado nacionalmente conforme a Lei 12.933/2013<br>";
        $dados .= "Válido em todo território nacional até o mês de ano";
        $dados .= "</div>";

        $dados .= "</body>";
        $dados .= "</html>";

        // Instanciar e usar a classe dompdf
        $dompdf = new Dompdf(['enable_remote' => true]);

        // Instanciar o método loadHtml e enviar o conteúdo do PDF
        $dompdf->loadHtml($dados);

        // Configurar o tamanho e a orientação do papel para portrait (retrato)
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar o HTML como PDF
        $dompdf->render();

        // Gerar o PDF e especificar o nome do arquivo
        $dompdf->stream("Carteira Estudantil (" . $row_estudante['id'] . ").pdf");
    } else {
        echo "<p>Estudante não encontrado.</p>";
    }
} else {
    echo "<p>ID do estudante não fornecido.</p>";
}

<?php

include_once("conexao.php");

$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

$pesquisar = $_GET['aluno'];
$result = "SELECT * FROM estudante WHERE id LIKE '$pesquisar'";
$resultado_alunos = mysqli_query($conn, $result);

// Verifica se há algum registro correspondente
if (mysqli_num_rows($resultado_alunos) == 0) {
    header("Location: index?mensagem=Aluno(a)%20não%20cadastrado(a)");
    exit;
}

function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
        if ($mask[$i] == '#') {
            if (isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if (isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }

    return $maskared;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <link rel="stylesheet" href="src/css/removeAds.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="src/css/style.css" />

    <title>Informações do Aluno</title>
    <meta charset="UTF-8" />
</head>

<body>
    <div class="container">
        <div class="alert">
            <?php
            // Armazena os resultados da consulta em um array
            $alunos = array();
            while ($rows_alunos = mysqli_fetch_array($resultado_alunos)) {
                $alunos[] = $rows_alunos;
            }

            // Percorre o array e exibe os resultados
            foreach ($alunos as $row_aluno) {
                echo '<div class="alert" style="background-color: ' . ($row_aluno['validade'] ? '#4caf50' : '#df0000') . '">';
                echo $row_aluno['validade'] ? 'Documento Válido' : 'Documento Inválido';
                echo '</div>';
            }
            ?>
        </div>
        <div class="row">
            <?php
            // Percorre o array novamente para exibir os outros dados
            foreach ($alunos as $row_aluno) {
            ?>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <img src="<?php
                                echo $row_aluno['imagem'];
                                ?>" class=" img img-responsive img-thumbnail" width="200" alt="Foto do aluno" /><br><br>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                    <h3>Nome:</h3>
                    <p class="info">
                        <?php echo $row_aluno['nome']; ?>
                    </p>
                    <h3>Data de Nascimento:</h3>
                    <p class="info">
                        <?php
                        $mysqlData = $row_aluno['data_nascimento']; // Data do MySQL no formato 'YYYY-MM-DD'
                        $formattedData = date_format(date_create($mysqlData), 'd/m/Y'); // Formata para 'dd/mm/yyyy'
                        echo $formattedData;
                        ?>
                    </p>
                    <h3>Sexo:</h3>
                    <p class="info">
                        <?php
                        foreach ($alunos as $row_aluno) {
                            echo $row_aluno['sexo'] ? 'Masculino' : 'Feminino';
                        }
                        ?>
                    </p>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                    <h3>Instituição:</h3>
                    <p class="info">
                        <?php echo $row_aluno['instituicao']; ?>
                    </p>
                    <h3>Curso/Série/Ensino:</h3>
                    <p class="info">
                        <?php echo $row_aluno['curso']; ?>
                    </p>
                    <h3>CPF:</h3>
                    <p class="info">
                        <?php
                        $cpf = mask($row_aluno['cpf'], '###.###.###-##');
                        echo $cpf;
                        ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>
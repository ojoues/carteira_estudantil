<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Área administrativa</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Área Administrativa</div>
                    <div class="card-body">
                        <a href="cad_aluno.php">Cadastrar aluno</a><br><br>
                        <a href="excluir_aluno.php">Listar cadastros de alunos</a><br><br>
                        <a href="./src/user/cad_usuario.php">Cadastrar usuário</a><br><br>
                        <a href="./src/user/index.php">Listar cadastros de usuários</a><br><br>
                        <a href="sair.php">Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclua os scripts do Bootstrap no final da página, antes do fechamento da tag </body> -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
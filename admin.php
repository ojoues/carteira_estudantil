<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login");
    exit();
}

// Obtém o nome do usuário logado, se estiver disponível na sessão
$usuario_nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : "Usuário Desconhecido";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/removeAds.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Área administrativa</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <span class="navbar-brand"><?php echo $usuario_nome; ?></span><!-- Esta será exibida apenas em telas pequenas -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="sair">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Área Administrativa</div>
                    <div class="card-body">
                        <a href="cad_aluno">Cadastrar aluno(a)</a><br><br>
                        <a href="excluir_aluno">Listar cadastros de alunos(as)</a><br><br>
                        <hr>
                        <a href="./src/user/cad_usuario">Cadastrar usuário(a)</a><br><br>
                        <a href="./src/user/index">Listar cadastros de usuários(as)</a><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclua os scripts do Bootstrap no final da página, antes do fechamento da tag </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <?php
    include('dark_mode.php');
    ?>
</body>

</html>
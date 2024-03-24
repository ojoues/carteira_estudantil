<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../login");
	exit();
}

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

include_once("../../conexao.php");

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/removeAds.css">
	<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Cadastro de Usuário</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container">
			<a class="navbar-brand" href="#"><?php echo $usuario_nome; ?></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<a class="nav-link" aria-current="page" href="../user/">Listar Usuários(as)</a>
					<a class="nav-link" href="../../admin">Área administrativa</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Cadastro de usuário</div>
					<?php
					if (isset($_SESSION['msg'])) {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					<div class="card-body">
						<form method="POST" action="proc_cad_usuario">

							<div class="form-group">
								<label for="nome">Nome: </label>
								<input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome completo" oninput="capitalizeFirstLetter(this)" required autofocus><br>
							</div>

							<div class="form-group">
								<label for="email">E-mail: </label>
								<input type="email" class="form-control" name="email" id="email" placeholder="Digite o seu e-mail" required><br>
							</div>

							<div class="form-group">
								<label for="usuario">Usuário: </label>
								<input type="text" class="form-control" name="usuario" id="usuario" placeholder="Digite o seu usuário" required><br>
							</div>

							<div class="form-group">
								<label for="senha">Senha: </label>
								<input type="password" class="form-control" name="senha" id="senha" placeholder="Digite a sua senha" required><br>
							</div>

							<input type="submit" class="btn btn-primary" value="Cadastrar">
							<button type="button" name="btnCancel" class="btn btn-danger" value="Cancelar" onclick="voltar()">Cancelar</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="../js/capitalizeFirstLetter.js"></script>
	<script src="../js/navegarEmAbas.js"></script>

	<?php
	include('../../dark_mode.php');
	?>
</body>

</html>
<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../login");
	exit();
}

include_once("../../conexao.php");

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/removeAds.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Cadastro de Usuário</title>
</head>

<body>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<a href="index" class="btn btn-primary">Listar Usuários</a><br><br>
				<a href="../../admin" class="btn btn-primary">Área administrativa</a><br><br>
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
								<input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome completo" required autofocus><br>
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
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<?php
	include('../../dark_mode.php');
	?>
</body>

</html>
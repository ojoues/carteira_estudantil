<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../login.php");
	exit();
}

include_once("../../conexao.php");

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Cadastro de Usuário</title>
</head>

<body>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<a href="index.php" class="btn btn-primary">Listar</a><br><br>
				<a href="../../admin.php" class="btn btn-primary">Área administrativa</a><br><br>
				<div class="card">
					<div class="card-header">Cadastro de usuário</div>
					<?php
					if (isset($_SESSION['msg'])) {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					<div class="card-body">
						<form method="POST" action="proc_cad_usuario.php">

							<div class="form-group">
								<label for="nome">Nome: </label>
								<input type="text" class="form-control" name="nome" placeholder="Digite o nome completo" required><br><br>
							</div>

							<div class="form-group">
								<label for="email">E-mail: </label>
								<input type="email" class="form-control" name="email" placeholder="Digite o seu e-mail" required><br><br>
							</div>

							<div class="form-group">
								<label for="usuario">Usuário: </label>
								<input type="text" class="form-control" name="usuario" placeholder="Digite o seu usuário" required><br><br>
							</div>

							<div class="form-group">
								<label for="senha">Senha: </label>
								<input type="password" class="form-control" name="senha" placeholder="Digite a sua senha" required><br><br>
							</div>

							<input type="submit" class="btn btn-primary" value="Cadastrar">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Inclua os scripts do Bootstrap no final da página, antes do fechamento da tag </body> -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

	<script src="../../src/js/script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
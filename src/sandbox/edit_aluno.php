<?php
session_start();
include_once("conexao.php");
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$result_usuario = "SELECT * FROM estudante WHERE id = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Editar cadastro</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<a href="excluir_aluno.php">Listar</a><br>
				<div class="card">
					<div class="card-header">Editar cadastro de aluno</div>
				</div>
				<?php
				if (isset($_SESSION['msg'])) {
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				}
				?>
				<div class="card-body">
					<form method="POST" action="processa.php" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>">
						<div class="form-group">
							<label for="nome">Nome:</label>
							<input type="text" class="form-control" name="nome" placeholder="Digite o nome completo">
						</div>
						<div class="form-group">
							<label for="email">Data de Nascimento:</label>
							<input type="text" class="form-control" name="data_nascimento" placeholder="00/00/0000" value="<?php echo $row_usuario['data_nascimento']; ?>">
						</div>
						<button type="submit" class="btn btn-primary" value="Editar">Editar</button><br><br>

						<a href="admin.php">Voltar</a>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
	</div>

	<!-- Inclua os scripts do Bootstrap no final da página, antes do fechamento da tag </body> -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

	<script src="./src/js/script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])) {
	$caminho_arquivo = "./src/img/uploads/" . $_FILES["imagem"]["name"];
	if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
	}
	// Resto do seu código de inserção no banco de dados
	if (rename($_FILES["imagem"]["tmp_name"], $caminho_arquivo)) {
		// O arquivo foi renomeado com sucesso
	} else {
		// Ocorreu um erro ao renomear o arquivo
	}
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="src/css/removeAds.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Cadastro de Aluno</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">

				<a href="excluir_aluno" class="btn btn-primary">Listar Alunos</a><br><br>
				<a href="admin" class="btn btn-primary">Área administrativa</a><br><br>
				<div class="card">
					<div class="card-header">Cadastro de aluno</div>
					<?php
					if (isset($_SESSION['msg'])) {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					<div class="card-body">
						<form method="POST" action="processa" enctype="multipart/form-data">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" class="form-control" name="nome" id="nome" maxlength="220" placeholder="Ex.: João Paulo" required autofocus>
							</div><br>

							<div class="form-group">
								<label for="data_nascimento">Data de Nascimento:</label>
								<input type="date" class="form-control" name="data_nascimento" id="data_nascimento" maxlength="8" required>
							</div><br>

							<label for="sexo">Gênero:</label>
							<select class="custom-select my-1 mr-sm-2" name="sexo" id="sexo" required>
								<option selected>Selecione...</option>
								<option value="0">Feminino</option>
								<option value="1">Masculino</option>
							</select><br><br>


							<div class="form-group">
								<label for="instituicao">Instituição:</label>
								<input type="text" class="form-control" name="instituicao" id="instituicao" maxlength="220" placeholder="Instituição" required>
							</div><br>

							<div class="form-group">
								<label for="curso">Curso:</label>
								<input type="text" class="form-control" name="curso" id="curso" maxlength="220" placeholder="Curso" required>
							</div><br>

							<div class="form-group">
								<label for="cpf">CPF:</label>
								<input type="text" class="form-control" name="cpf" id="cpf" maxlength="11" placeholder="___.___.___-__" required>
							</div><br>

							<label for="validade">Validade:</label>
							<select class="custom-select my-1 mr-sm-2" id="validade" name="validade" required>
								<option selected>Selecione...</option>
								<option value="0">Documento Inválido</option>
								<option value="1">Documento Válido</option>
							</select><br><br>


							<div class="form-group">
								<label for="imagem">Foto do Aluno:</label>
								<input type="file" class="form-control-file" name="imagem" id="imagem" accept=".jpg, .jpeg" required>
							</div><br>

							<button type="submit" name="btnLogin" class="btn btn-primary" value="Acessar">Cadastrar</button><br><br>

							<a href="admin">Voltar</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<?php
	include('dark_mode.php');
	?>
</body>

</html>
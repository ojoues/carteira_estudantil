<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

// Verifique o tempo de inatividade permitido (em segundos)
$inatividade_permitida = 600; // 30 minutos (pode ajustar conforme necessário)

// Verifique se a sessão expirou devido à inatividade
if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso']) > $inatividade_permitida) {
	// Sessão expirou, redirecione o usuário para a página de login
	session_destroy();
	header("Location: login");
	exit();
}

// Atualize o tempo do último acesso
$_SESSION['ultimo_acesso'] = time();

// Obtém o nome do usuário logado, se estiver disponível na sessão
$usuario_nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : "Usuário Desconhecido";

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
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container">
			<a class="navbar-brand" href="#"><?php echo $usuario_nome; ?></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<a class="nav-link" aria-current="page" href="excluir_aluno">Listar Alunos(as)</a>
				</div>
				<div class="navbar-nav">
					<a class="nav-link" aria-current="page" href="admin">Área administrativa</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
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
								<input type="text" class="form-control" name="nome" id="nome" maxlength="220" placeholder="Ex.: João Paulo" oninput="capitalizeFirstLetter(this)" required autofocus>
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
								<input type="text" class="form-control" name="instituicao" id="instituicao" maxlength="220" placeholder="Instituição" oninput="capitalizeFirstLetter(this)" required>
							</div><br>

							<div class="form-group">
								<label for="curso">Curso:</label>
								<input type="text" class="form-control" name="curso" id="curso" maxlength="220" placeholder="Curso" oninput="capitalizeFirstLetter(this)" required>
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
								<label for="imagem">Foto do Aluno:</label><br>
								<input type="file" class="form-control-file" name="imagem" id="imagem" accept=".jpg, .jpeg" required>
							</div><br>

							<button type="submit" name="btnLogin" class="btn btn-primary" value="Acessar">Cadastrar</button><br><br>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<br>
	<br>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="src/js/capitalizeFirstLetter.js"></script>

	<?php
	include('dark_mode.php');
	?>
</body>

</html>
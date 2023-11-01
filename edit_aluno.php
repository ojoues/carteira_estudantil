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

include_once("conexao.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Use intval para obter um valor inteiro
$result_usuario = mysqli_query($conn, "SELECT * FROM estudante WHERE id = $id");

if (!$result_usuario) {
	$_SESSION['msg'] = "<p style='color:red;'>Erro ao buscar o estudante no banco de dados.</p>";
	header("Location: excluir_aluno");
	exit();
}

$row_usuario = mysqli_fetch_assoc($result_usuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// ...

	// Verifica se uma nova imagem foi enviada
	if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"]["name"])) {
		$caminho_arquivo = "./src/img/uploads/" . $_FILES["imagem"]["name"];

		// Move o arquivo para o diretório de uploads
		if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
			// Atualiza o caminho da imagem no banco de dados
			$atualiza_imagem = "UPDATE estudante SET caminho_imagem='$caminho_arquivo' WHERE id=$id";
			$resultado_atualiza_imagem = mysqli_query($conn, $atualiza_imagem);

			if (!$resultado_atualiza_imagem) {
				$_SESSION['msg'] = "<p style='color:red;'>Erro ao atualizar o caminho da imagem no banco de dados.</p>";
				header("Location: edit_aluno?id=$id");
				exit();
			}
		} else {
			$_SESSION['msg'] = "<p style='color:red;'>Erro ao fazer upload da imagem.</p>";
			header("Location: edit_aluno?id=$id");
			exit();
		}
	}

	// ...
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
					<a class="nav-link" aria-current="page" href="cad_aluno">Cadastrar novo Aluno(a)</a>
					<a class="nav-link" href="excluir_aluno">Listar Usuários(as)</a>
					<a class="nav-link" href="admin">Área Administrativa</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Editar Cadastro</div>
					<?php
					if (isset($_SESSION['msg'])) {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					<div class="card-body">
						<form method="POST" action="proc_edit_aluno" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" class="form-control" name="nome" maxlength="220" value="<?php echo $row_usuario['nome']; ?>" oninput="capitalizeFirstLetter(this)" required><br>
							</div>
							<div class="form-group">
								<label for="email">Data de Nascimento:</label>
								<input type="date" class="form-control" name="data_nascimento" maxlength="8" value="<?php echo $row_usuario['data_nascimento']; ?>" required><br>
							</div>

							<label for="sexo">Gênero:</label>
							<select class="custom-select my-1 mr-sm-2" name="sexo" id="sexo" required>
								<option value="0" <?php if ($row_usuario['sexo'] == '0') echo 'selected'; ?>>Feminino</option>
								<option value="1" <?php if ($row_usuario['sexo'] == '1') echo 'selected'; ?>>Masculino</option>
							</select><br><br>

							<div class="form-group">
								<label for="instituicao">Instituição:</label>
								<input type="text" class="form-control" name="instituicao" maxlength="220" value="<?php echo $row_usuario['instituicao']; ?>" oninput="capitalizeFirstLetter(this)" required><br>
							</div>

							<div class="form-group">
								<label for="curso">Curso:</label>
								<input type="text" class="form-control" name="curso" maxlength="220" value="<?php echo $row_usuario['curso']; ?>" oninput="capitalizeFirstLetter(this)" required><br>
							</div>

							<div class="form-group">
								<label for="cpf">CPF:</label>
								<input type="text" class="form-control" name="cpf" maxlength="11" value="<?php echo $row_usuario['cpf']; ?>" required><br>
							</div>

							<label for="validade">Validade:</label>
							<select class="custom-select my-1 mr-sm-2" name="validade" id="validade" required>
								<option value="0" <?php if ($row_usuario['validade'] == '0') echo 'selected'; ?>>Documento Inválido</option>
								<option value="1" <?php if ($row_usuario['validade'] == '1') echo 'selected'; ?>>Documento Válido</option>
							</select><br>

							<div class="form-group">
								<label for="imagem">Imagem:</label><br>
								<input type="file" class="form-control-file" name="imagem" accept=".jpg, .jpeg"><br><br>
							</div>

							<button type="submit" class="btn btn-primary" value="Corrigir">Corrigir</button><br><br>
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

	<script src="src/js/capitalizeFirstLetter.js"></script>

	<?php
	include('dark_mode.php');
	?>
</body>

</html>
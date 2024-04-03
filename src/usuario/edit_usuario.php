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

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$result_usuario = "SELECT * FROM usuarios WHERE id = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Editar Usuário</title>
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
					<a class="nav-link" aria-current="page" href="cad_usuario"><i class="fa-solid fa-user-shield"></i> Cadastrar Usuário(a)</a>
					<a class="nav-link" href="../usuario/"><i class="fa-solid fa-list-check"></i> Listar Usuários(as)</a>
					<a class="nav-link" href="../../admin"><i class="fa-solid fa-user"></i> Área Administrativa</a>
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
						<form method="POST" action="proc_edit_usuario">
							<input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>">

							<div class="form-group">
								<label>Nome: </label>
								<input type="text" name="nome" class="form-control" placeholder="Digite o nome completo" value="<?php echo $row_usuario['nome']; ?>" oninput="capitalizeFirstLetter(this)"><br>
							</div>

							<div class="form-group">
								<label>Usuário: </label>
								<input type="text" name="usuario" class="form-control" placeholder="Digite o seu usuário" value="<?php echo $row_usuario['usuario']; ?>"><br>
							</div>

							<div class="form-group">
								<label>E-mail: </label>
								<input type="email" name="email" class="form-control" placeholder="Digite o seu e-mail" value="<?php echo $row_usuario['email']; ?>"><br>
							</div>

							<div class="form-group">
								<label>Nova Senha: </label>
								<input type="password" name="nova_senha" class="form-control" placeholder="Digite a nova senha"><br>
							</div>

							<button type="submit" name="btnCorrigir" class="btn btn-primary" value="Corrigir" onclick="voltar()"><i class="fa-solid fa-pen-to-square"></i> Corrigir</button>
							<button type="button" name="btnCancel" class="btn btn-danger" value="Cancelar" onclick="voltar()"><i class="fa-solid fa-square-xmark"></i> Cancelar</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<br>
	<br>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

	<script src="../js/capitalizeFirstLetter.js"></script>
	<script src="../js/navegarEmAbas.js"></script>
</body>

</html>
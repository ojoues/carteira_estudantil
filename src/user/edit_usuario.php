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
	<link rel="stylesheet" href="../css/removeAds.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Editar Usuário</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<a href="cad_usuario" class="btn btn-primary">Cadastrar</a><br><br>
				<a href="index" class="btn btn-primary">Listar Usuários</a><br><br>
				<div class="card">
					<div class="card-header">Editar usuário</div>
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
								<input type="text" name="nome" class="form-control" placeholder="Digite o nome completo" value="<?php echo $row_usuario['nome']; ?>"><br><br>
							</div>

							<div class="form-group">
								<label>Usuário: </label>
								<input type="text" name="usuario" class="form-control" placeholder="Digite o seu usuário" value="<?php echo $row_usuario['usuario']; ?>"><br><br>
							</div>

							<div class="form-group">
								<label>E-mail: </label>
								<input type="email" name="email" class="form-control" placeholder="Digite o seu e-mail" value="<?php echo $row_usuario['email']; ?>"><br><br>
							</div>

							<div class="form-group">
								<label>Nova Senha: </label>
								<input type="password" name="nova_senha" class="form-control" placeholder="Digite a nova senha"><br><br>
							</div>

							<input type="submit" class="btn btn-primary" value="Corrigir">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<?php
	include('../../dark_mode.php');
	?>
</body>

</html>
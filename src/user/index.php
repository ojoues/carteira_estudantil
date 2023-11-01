<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../login");
	exit();
}

// Verifique o tempo de inatividade permitido (em segundos)
$inatividade_permitida = 600; // 10 minutos (pode ajustar conforme necessário)

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
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Listar Usuários</title>
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
					<a class="nav-link" aria-current="page" href="cad_usuario">Cadastrar Usuário(a)</a>
					<a class="nav-link" href="../../admin">Área administrativa</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-8">
				<h1>Listar Usuários</h1>


				<?php
				if (isset($_SESSION['msg'])) {
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				}

				//Receber o número da página
				$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
				$pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

				//Setar a quantidade de itens por pagina
				$qnt_result_pg = 3;

				//calcular o inicio visualização
				$inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

				$result_usuarios = "SELECT * FROM usuarios ORDER BY criado DESC LIMIT $inicio, $qnt_result_pg";

				$resultado_usuarios = mysqli_query($conn, $result_usuarios);
				while ($row_usuario = mysqli_fetch_assoc($resultado_usuarios)) {
					echo "<hr>";
					echo "ID: " . $row_usuario['id'] . "<br>";
					echo "Nome: " . $row_usuario['nome'] . "<br>";
					echo "Usuário: " . $row_usuario['usuario'] . "<br>";
					echo "E-mail: " . $row_usuario['email'] . "<br>";
					echo "<a href='edit_usuario?id=" . $row_usuario['id'] . "' class='btn btn-primary'>Editar</a>";
					echo " ";
					echo "<a href='proc_apagar_usuario?id=" . htmlspecialchars($row_usuario['id'], ENT_QUOTES, 'UTF-8') . "' data-user-id='" . htmlspecialchars($row_usuario['id'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-danger delete-button confirm-delete' data-nome='" . htmlspecialchars_decode(htmlspecialchars($row_usuario['nome'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES) . "'>Apagar</a>";


					echo "<hr>";
				}

				//Somar a quantidade de usuários
				$result_pg = "SELECT COUNT(id) AS num_result FROM usuarios";
				$resultado_pg = mysqli_query($conn, $result_pg);
				$row_pg = mysqli_fetch_assoc($resultado_pg);
				//echo $row_pg['num_result'];
				//Quantidade de pagina 
				$quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

				//Limitar os link antes depois
				$max_links = 2;
				echo "<a href='index?pagina=1'>Primeira</a> ";

				for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
					if ($pag_ant >= 1) {
						echo "<a href='index?pagina=$pag_ant'>$pag_ant</a> ";
					}
				}

				echo "$pagina ";

				for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
					if ($pag_dep <= $quantidade_pg) {
						echo "<a href='index?pagina=$pag_dep'>$pag_dep</a> ";
					}
				}

				echo "<a href='index?pagina=$quantidade_pg'>Última</a>";

				?>
			</div>
		</div>
	</div>

	<br>
	<br>
	<script src="../js/confirmaExcluirUsuario.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

	<?php
	include('../../dark_mode.php');
	?>
</body>

</html>
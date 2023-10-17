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
	<title>Listar Usuários</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-8">
				<a href="cad_usuario" class="btn btn-primary">Cadastrar</a><br><br>
				<a href="../../admin" class="btn btn-primary">Área administrativa</a><br><br>
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
	<script src="../js/confirmaExcluirUsuario.js"></script>

	<?php
	include('../../dark_mode.php');
	?>
</body>

</html>
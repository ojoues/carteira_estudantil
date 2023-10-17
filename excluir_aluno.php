<?php
session_start();
include_once("conexao.php");

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="src/css/removeAds.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Lista de cadastros</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-8">
				<a href="cad_aluno" class="btn btn-primary">Cadastrar</a><br><br>
				<a href="admin" class="btn btn-primary">Área administrativa</a><br><br>
				<h1>Alunos cadastrados</h1>

				<?php
				if (isset($_SESSION['msg'])) {
					echo '<div class="alert alert-info">' . $_SESSION['msg'] . '</div>';
					unset($_SESSION['msg']);
				}

				$qnt_result_pg = 3;
				$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
				$pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
				$inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

				$query = "SELECT * FROM estudante ORDER BY criado DESC LIMIT $inicio, $qnt_result_pg";

				$result = mysqli_query($conn, $query);

				if (!$result) {
					die('Erro na consulta SQL: ' . mysqli_error($conn));
				}

				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						echo "<hr>";
						echo "ID: " . $row['id'] . "<br>";
						echo "Nome: " . $row['nome'] . "<br>";
						echo "CPF: " . substr($row['cpf'], 0, 3) . '.' . substr($row['cpf'], 3, 3) . '.' . substr($row['cpf'], 6, 3) . '-' . substr($row['cpf'], 9, 2) . "<br>";
						echo "<a href='edit_aluno?id=" . $row['id'] . "' class='btn btn-primary'>Editar</a>";
						echo " ";
						echo "<a href='proc_apagar_aluno?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-danger delete-button confirm-delete' data-nome='" . html_entity_decode($row['nome'], ENT_QUOTES, 'UTF-8') . "'>Apagar</a>";

						echo " ";
						echo "<a href='pesquisar?aluno=" . $row['id'] . "' class='btn btn-success' target='_blank'>Visualizar</a>";
						echo " ";
						echo "<a href='src/dompdf/gerar_pdf?id=" . $row['id'] . "' class='btn btn-info'>Gerar PDF</a>";
						echo "<hr>";
					}
				} else {
					echo "<p>Nenhum aluno cadastrado.</p>";
				}

				echo "<br><br>";

				//Somar a quantidade de usuários
				$result_pg = "SELECT COUNT(id) AS num_result FROM estudante";
				$resultado_pg = mysqli_query($conn, $result_pg);
				$row_pg = mysqli_fetch_assoc($resultado_pg);
				//echo $row_pg['num_result'];
				//Quantidade de pagina 
				$quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

				//Limitar os link antes depois
				$max_links = 2;
				echo "<a href='excluir_aluno?pagina=1'>Primeira</a> ";

				for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
					if ($pag_ant >= 1) {
						echo "<a href='excluir_aluno?pagina=$pag_ant'>$pag_ant</a> ";
					}
				}

				echo "$pagina ";

				for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
					if ($pag_dep <= $quantidade_pg) {
						echo "<a href='excluir_aluno?pagina=$pag_dep'>$pag_dep</a> ";
					}
				}

				echo "<a href='excluir_aluno?pagina=$quantidade_pg'>Última</a>";
				?>
				</ul>
				</nav>
			</div>
		</div>
	</div>
	<script src="src/js/confirmaExcluirAluno.js"></script>

	<?php
	include('dark_mode.php');
	?>
</body>

</html>
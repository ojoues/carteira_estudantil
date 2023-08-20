<?php
session_start();
include_once("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Lista de cadastros</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-8">
				<a href="cad_aluno.php" class="btn btn-primary">Cadastrar</a><br><br>
				<a href="admin.php" class="btn btn-primary">Área administrativa</a><br><br>
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

				$query = "SELECT * FROM estudante LIMIT $inicio, $qnt_result_pg";
				$result = mysqli_query($conn, $query);

				if (!$result) {
					die('Erro na consulta SQL: ' . mysqli_error($conn));
				}

				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						echo "<hr>";
						echo "ID: " . $row['id'] . "<br>";
						echo "Nome: " . $row['nome'] . "<br>";
						echo "CPF: " . $row['cpf'] . "<br>";
						echo "<a href='edit_aluno.php?id=" . $row['id'] . "' class='btn btn-primary'>Editar</a>";
						echo " ";
						echo "<a href='proc_apagar_aluno.php?id=" . $row['id'] . "' class='btn btn-danger'>Apagar</a>";
						echo " ";
						echo "<a href='pesquisar.php?aluno=" . $row['id'] . "' class='btn btn-success' target='_blank'>Visualizar</a>";
					}
				} else {
					echo "<p>Nenhum aluno cadastrado.</p>";
				}

				echo "<br><br>";

				$query = "SELECT COUNT(id) AS num_result FROM estudante";
				$result = mysqli_query($conn, $query);

				if (!$result) {
					die('Erro na consulta SQL: ' . mysqli_error($conn));
				}

				$row_pg = mysqli_fetch_assoc($result);
				$quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);
				?>

				<nav aria-label="Navegação de páginas">
					<ul class="pagination">
						<?php
						if ($pagina > 1) {
							echo "<li class='page-item'><a class='page-link' href='excluir_aluno.php?pagina=1'>Primeira</a></li>";
							$pagina_ant = $pagina - 1;
							echo "<li class='page-item'><a class='page-link' href='excluir_aluno.php?pagina=$pagina_ant'>Anterior</a></li>";
						}

						for ($pag = max(1, $pagina - 2); $pag <= min($pagina + 2, $quantidade_pg); $pag++) {
							if ($pagina == $pag) {
								echo "<li class='page-item active'><span class='page-link'>$pag</span></li>";
							} else {
								echo "<li class='page-item'><a class='page-link' href='excluir_aluno.php?pagina=$pag'>$pag</a></li>";
							}
						}

						if ($pagina < $quantidade_pg) {
							$pagina_dep = $pagina + 1;
							echo "<li class='page-item'><a class='page-link' href='excluir_aluno.php?pagina=$pagina_dep'>Próxima</a></li>";
							echo "<li class='page-item'><a class='page-link' href='excluir_aluno.php?pagina=$quantidade_pg'>Última</a></li>";
						}
						?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</body>

</html>
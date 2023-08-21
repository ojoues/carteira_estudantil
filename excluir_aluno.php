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
						echo "CPF: " . substr($row['cpf'], 0, 3) . '.' . substr($row['cpf'], 3, 3) . '.' . substr($row['cpf'], 6, 3) . '-' . substr($row['cpf'], 9, 2) . "<br>";
						echo "<a href='edit_aluno.php?id=" . $row['id'] . "' class='btn btn-primary'>Editar</a>";
						echo " ";
						echo "<a href='proc_apagar_aluno.php?id=" . $row['id'] . "' class='btn btn-danger'>Apagar</a>";
						echo " ";
						echo "<a href='pesquisar.php?aluno=" . $row['id'] . "' class='btn btn-success' target='_blank'>Visualizar</a>";
						echo " ";
						echo "<a href='./src/dompdf/gerar_pdf.php?id=" . $row['id'] . "' class='btn btn-info' target='_blank'>Gerar PDF</a>";
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
				echo "<a href='excluir_aluno.php?pagina=1'>Primeira</a> ";

				for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
					if ($pag_ant >= 1) {
						echo "<a href='excluir_aluno.php?pagina=$pag_ant'>$pag_ant</a> ";
					}
				}

				echo "$pagina ";

				for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
					if ($pag_dep <= $quantidade_pg) {
						echo "<a href='excluir_aluno.php?pagina=$pag_dep'>$pag_dep</a> ";
					}
				}

				echo "<a href='excluir_aluno.php?pagina=$quantidade_pg'>Última</a>";
						?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</body>

</html>
<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../src/admin/login.php");
	exit();
}

include_once("../../conexao.php");

$id = $_GET['id']; // Use $_GET para obter o ID da URL
$result_usuario = "SELECT * FROM estudante WHERE id = '$id'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);

if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"]["name"])) { // Verifique se a imagem foi enviada corretamente
	$caminho_arquivo = "./src/img/uploads/" . $_FILES["imagem"]["name"];

	// Move o arquivo para o diretório de uploads
	if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
		// Atualiza o caminho da imagem no banco de dados
		$atualiza_imagem = "UPDATE estudante SET caminho_imagem='$caminho_arquivo' WHERE id='$id'";
		$resultado_atualiza_imagem = mysqli_query($conn, $atualiza_imagem);

		if (!$resultado_atualiza_imagem) {
			$_SESSION['msg'] = "<p style='color:red;'>Erro ao atualizar o caminho da imagem no banco de dados.</p>";
			header("Location: editar.php?id=$id");
			exit();
		}
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Erro ao fazer upload da imagem.</p>";
		header("Location: editar.php?id=$id");
		exit();
	}
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Cadastro de Aluno</title>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center mt-5">
			<div class="col-md-6">
				<a href="excluir_aluno.php" class="btn btn-primary">Listar</a><br><br>
				<div class="card">
					<div class="card-header">Editar cadastro de aluno</div>
					<?php
					if (isset($_SESSION['msg'])) {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					<div class="card-body">
						<form method="POST" action="proc_edit_aluno.php" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" class="form-control" name="nome" maxlength="220" value="<?php echo $row_usuario['nome']; ?>" required>
							</div>
							<div class="form-group">
								<label for="email">Data de Nascimento:</label>
								<input type="date" class="form-control" name="data_nascimento" maxlength="8" value="<?php echo $row_usuario['data_nascimento']; ?>" required>
							</div>

							<label for="sexo">Gênero:</label>
							<select class="custom-select my-1 mr-sm-2" name="sexo" id="sexo" required>
								<option value="0" <?php if ($row_usuario['sexo'] == '0') echo 'selected'; ?>>Feminino</option>
								<option value="1" <?php if ($row_usuario['sexo'] == '1') echo 'selected'; ?>>Masculino</option>
							</select>


							<div class="form-group">
								<label for="instituicao">Instituição:</label>
								<input type="text" class="form-control" name="instituicao" maxlength="220" value="<?php echo $row_usuario['instituicao']; ?>" required>
							</div>
							<div class="form-group">
								<label for="curso">Curso:</label>
								<input type="text" class="form-control" name="curso" maxlength="220" value="<?php echo $row_usuario['curso']; ?>" required>
							</div>

							<div class="form-group">
								<label for="cpf">CPF:</label>
								<input type="text" class="form-control" name="cpf" maxlength="14" oninput="formatarCpf(this)" value="<?php echo $row_usuario['cpf']; ?>" required>
							</div>

							<label for="validade">Validade:</label>
							<select class="custom-select my-1 mr-sm-2" name="validade" id="validade" required>
								<option value="0" <?php if ($row_usuario['validade'] == '0') echo 'selected'; ?>>Documento Inválido</option>
								<option value="1" <?php if ($row_usuario['validade'] == '1') echo 'selected'; ?>>Documento Válido</option>
							</select>


							<div class="form-group">
								<label for="imagem">Foto do Aluno:</label>
								<input type="file" class="form-control-file" name="imagem" accept="image/*" value="<?php echo $row_usuario['imagem']; ?>" required>
							</div>
							<button type="submit" class="btn btn-primary" value="Acessar">Corrigir</button><br><br>

							<a href="admin.php">Voltar</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Inclua os scripts do Bootstrap no final da página, antes do fechamento da tag </body> -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

	<script src="./src/js/script.js"></script>
</body>

</html>
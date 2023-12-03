<?php
session_start();
include_once("conexao.php");

$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$sexo = $_POST['sexo'];
$instituicao = $_POST['instituicao'];
$curso = $_POST['curso'];
$cpf = $_POST['cpf'];
$cpf_sem_formato = preg_replace('/[^0-9]/', '', $cpf);
$validade = $_POST['validade'];

// Diretório onde as fotos serão armazenadas
$diretorio_destino = "./src/img/uploads/"; // Certifique-se de que este diretório seja válido

// Insira os dados na tabela (sem o caminho da imagem) e obtenha o ID inserido
$sql = "INSERT INTO estudante (nome, data_nascimento, sexo, instituicao, curso, cpf, validade, modificado, criado) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $nome, $data_nascimento, $sexo, $instituicao, $curso, $cpf_sem_formato, $validade);

if (mysqli_stmt_execute($stmt)) {
	// Obtenha o ID inserido
	$id_aluno = mysqli_insert_id($conn);

	// Nome do arquivo original
	$nome_arquivo_original = $_FILES['imagem']['name'];

	// Caminho completo do arquivo original no servidor
	$caminho_arquivo_original = $diretorio_destino . $nome_arquivo_original;

	// Move o arquivo original para o diretório de destino
	if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo_original)) {

		// Renomeie a imagem redimensionada
		$novo_nome_arquivo = $id_aluno . ".jpg";
		$caminho_arquivo = $diretorio_destino . $novo_nome_arquivo;

		// Renomeie o arquivo
		rename($caminho_arquivo_original, $caminho_arquivo);

		// Atualize o campo de imagem com o nome do arquivo
		$sql_update = "UPDATE estudante SET imagem = ? WHERE id = ?";
		$stmt_update = mysqli_prepare($conn, $sql_update);
		mysqli_stmt_bind_param($stmt_update, "si", $caminho_arquivo, $id_aluno);
		mysqli_stmt_execute($stmt_update);

		$_SESSION['msg'] = "<p style='color:green;'>Aluno cadastrado com sucesso!</p>";
		header("Location: cad_aluno");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Erro ao enviar a foto.</p>";
		header("Location: cad_aluno");
	}

	// Feche as declarações
	mysqli_stmt_close($stmt);
	mysqli_stmt_close($stmt_update);
} else {
	$_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar aluno: " . mysqli_error($conn) . "</p>";
	header("Location: cad_aluno");
}

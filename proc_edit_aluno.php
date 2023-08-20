<?php
session_start();
include_once("conexao.php");

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$instituicao = filter_input(INPUT_POST, 'instituicao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cpf_sem_formato = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$validade = filter_input(INPUT_POST, 'validade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Diretório onde as fotos serão armazenadas (Link do banco)
$diretorio_destino = "./src/img/uploads/";

// Nome do arquivo original
$nome_arquivo = $_FILES['imagem']['name'];

// Caminho completo do arquivo no servidor
$caminho_arquivo = $diretorio_destino . $nome_arquivo;

$result_usuario = "UPDATE estudante SET nome='$nome', data_nascimento='$data_nascimento', sexo='$sexo', instituicao='$instituicao', curso='$curso', cpf='$cpf_sem_formato', validade='$validade', caminho_imagem='$caminho_arquivo', modificado=NOW() WHERE id='$id'";

$resultado_usuario = mysqli_query($conn, $result_usuario);

if (mysqli_affected_rows($conn)) {

	// Move a imagem para o diretório de destino
	if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
		$_SESSION['msg'] = "<p style='color:green;'> Aluno editado com sucesso!</p>";
		header("Location: excluir_aluno.php");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'> Erro ao fazer upload da imagem.</p>";
		header("Location: edit_aluno.php?id=$id");
	}
} else {
	$_SESSION['msg'] = "<p style='color:red;'> Aluno não foi editado com sucesso!</p>";
	header("Location: edit_aluno.php?id=$id");
}

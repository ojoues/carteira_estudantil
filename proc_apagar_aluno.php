<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login");
    exit();
}

include_once("conexao.php");
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
	$result_usuario = "DELETE FROM estudante WHERE id='$id'";
	$resultado_usuario = mysqli_query($conn, $result_usuario);
	if (mysqli_affected_rows($conn)) {
		$_SESSION['msg'] = "<p style='color:green;'>Usuário apagado com sucesso!</p>";
		header("Location: excluir_aluno");
	} else {

		$_SESSION['msg'] = "<p style='color:red;'>Erro o usuário não foi apagado com sucesso</p>";
		header("Location: excluir_aluno");
	}
} else {
	$_SESSION['msg'] = "<p style='color:red;'>Necessário selecionar um usuário</p>";
	header("Location: excluir_aluno");
}

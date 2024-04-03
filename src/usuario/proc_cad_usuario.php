<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: ../../login");
	exit();
}

include_once("../../conexao.php");

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Verifique se o usuário já existe
$stmt_verificar = mysqli_prepare($conn, "SELECT id FROM usuarios WHERE usuario = ?");
mysqli_stmt_bind_param($stmt_verificar, "s", $usuario);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_store_result($stmt_verificar);

if (mysqli_stmt_num_rows($stmt_verificar) > 0) {
	// O usuário já existe, redirecione de volta para a página de cadastro com uma mensagem de erro
	$_SESSION['msg'] = "<p style='color:red;'>Usuário '$usuario' já existe</p>";
	header("Location: cad_usuario");
	exit();
}

// Criptografar a senha usando password_hash
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

// Prepare a consulta SQL para inserir o novo usuário
$stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nome, email, usuario, senha, modificado, criado) VALUES (?, ?, ?, ?, NOW(), NOW())");

if ($stmt) {
	mysqli_stmt_bind_param($stmt, "ssss", $nome, $email, $usuario, $senhaCriptografada);
	$resultado_usuario = mysqli_stmt_execute($stmt);

	if ($resultado_usuario) {
		$_SESSION['msg'] = "<p style='color:green;'>Usuário '$usuario' cadastrado com sucesso</p>";
		header("Location: index");
		exit();
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Usuário '$usuario' não foi cadastrado com sucesso</p>";
		header("Location: cad_usuario");
		exit();
	}

	mysqli_stmt_close($stmt);
}

mysqli_close($conn);

<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../src/admin/login.php");
    exit();
}

include_once("../../conexao.php");

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Criptografar a senha usando password_hash
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

$result_usuario = "INSERT INTO usuarios (nome, email, usuario, senha, modificado) VALUES ('$nome', '$email', '$usuario', '$senhaCriptografada', NOW())";
$resultado_usuario = mysqli_query($conn, $result_usuario);

if (mysqli_insert_id($conn)) {
	$_SESSION['msg'] = "<p style='color:green;'>Usuário cadastrado com sucesso</p>";
	header("Location: ../../src/user/index.php");
} else {
	$_SESSION['msg'] = "<p style='color:red;'>Usuário não foi cadastrado com sucesso</p>";
	header("Location: ../../src/user/cad_usuario.php");
}

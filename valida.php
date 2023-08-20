<?php
session_start();
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Validação alfanumérica
	$senha = $_POST['senha'];

	if (!empty($usuario) && !empty($senha)) {
		$stmt = $conn->prepare("SELECT id, nome, email, senha FROM usuarios WHERE usuario = ?");
		$stmt->bind_param("s", $usuario);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows === 1) {
			$row = $result->fetch_assoc();
			if (password_verify($senha, $row['senha'])) {
				$_SESSION['usuario_id'] = $row['id'];
				$_SESSION['usuario_nome'] = $row['nome'];
				header("Location: admin.php");
				exit();
			} else {
				$_SESSION['msg'] = "Senha incorreta para o usuário '$usuario'.";
				header("Location: login.php");
			}
		} else {
			$_SESSION['msg'] = "Usuário '$usuario' não encontrado.";
			header("Location: login.php");
		}
	} else {
		$_SESSION['msg'] = "Por favor, preencha todos os campos.";
	}
}

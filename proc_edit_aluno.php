<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Use intval para obter um valor inteiro
	$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$instituicao = filter_input(INPUT_POST, 'instituicao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$validade = filter_input(INPUT_POST, 'validade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Defina a data e hora do momento
	$modificado = date('Y-m-d H:i:s');

	// Verifique se uma imagem foi enviada
	if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"]["name"])) {
		$caminho_arquivo = "./src/img/uploads/" . $_FILES["imagem"]["name"];

		// Move o arquivo para o diretório de uploads
		if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
			// Atualiza o caminho da imagem no banco de dados
			$atualiza_imagem = "UPDATE estudante SET imagem='$caminho_arquivo' WHERE id=$id";
			$resultado_atualiza_imagem = mysqli_query($conn, $atualiza_imagem);

			if (!$resultado_atualiza_imagem) {
				$_SESSION['msg'] = "<p style='color:red;'>Erro ao atualizar o caminho da imagem no banco de dados.</p>";
				header("Location: edit_aluno?id=$id");
				exit();
			}
		} else {
			$_SESSION['msg'] = "<p style='color:red;'>Erro ao fazer upload da imagem.</p>";
			header("Location: edit_aluno?id=$id");
			exit();
		}
	}

	// Prepare a consulta SQL para atualizar os outros campos
	$stmt = $conn->prepare("UPDATE estudante SET nome = ?, data_nascimento = ?, sexo = ?, instituicao = ?, curso = ?, cpf = ?, validade = ?, modificado = ? WHERE id = ?");

	if (!$stmt) {
		die('Erro na preparação da consulta: ' . mysqli_error($conn));
	}

	// Vincule os parâmetros
	if (!$stmt->bind_param("ssssssssi", $nome, $data_nascimento, $sexo, $instituicao, $curso, $cpf, $validade, $modificado, $id)) {
		die('Erro ao vincular parâmetros: ' . mysqli_error($conn));
	}

	// Execute a consulta
	if ($stmt->execute()) {
		$_SESSION['msg'] = "<p style='color:green;'>Aluno(a) '$nome' editado com sucesso!</p>";
		header("Location: excluir_aluno");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Aluno(a) '$nome' não foi editado com sucesso!</p>";
		header("Location: edit_aluno?id=$id");
	}

	$stmt->close();
	$conn->close();
	exit();
}

<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

include_once("conexao.php");
$diretorio_destino = "./src/img/uploads/"; // Diretório de destino das imagens

// Função para redimensionar a imagem
function redimensionarImagem($caminho, $largura, $altura)
{
	list($largura_original, $altura_original) = getimagesize($caminho);
	$nova_imagem = imagecreatetruecolor($largura, $altura);
	$imagem_original = imagecreatefromjpeg($caminho);
	$proporcao = $largura / $altura;
	$nova_largura = $largura_original;
	$nova_altura = $altura_original;

	if ($largura_original / $altura_original > $proporcao) {
		$nova_largura = $altura_original * $proporcao;
	} else {
		$nova_altura = $largura_original / $proporcao;
	}

	$deslocamento_x = ($largura_original - $nova_largura) / 2;
	$deslocamento_y = ($altura_original - $nova_altura) / 2;

	imagecopyresampled($nova_imagem, $imagem_original, 0, 0, $deslocamento_x, $deslocamento_y, $largura, $altura, $nova_largura, $nova_altura);

	return $nova_imagem;
}

// Função para corrigir a orientação da imagem usando ImageMagick
function corrigirOrientacaoImagem($caminho)
{
	if (extension_loaded('imagick')) {
		$imagem = new Imagick($caminho);
		$imagem->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
		$imagem->writeImage($caminho);
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
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
		$extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);

		// Gere um novo nome para a imagem usando o ID do aluno
		$novo_nome_imagem = $id . ".jpg";
		$caminho_arquivo = $diretorio_destino . $novo_nome_imagem;

		// Move o arquivo para o diretório de uploads
		if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
			// Corrija a orientação da imagem usando ImageMagick
			corrigirOrientacaoImagem($caminho_arquivo);

			// Redimensione a imagem
			redimensionarImagem($caminho_arquivo, 995, 1293);

			// Atualize o caminho da imagem no banco de dados
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
		header("Location: aluno");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Aluno(a) '$nome' não foi editado com sucesso!</p>";
		header("Location: edit_aluno?id=$id");
	}

	$stmt->close();
	$conn->close();
	exit();
}

<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header("Location: login");
	exit();
}

include_once("conexao.php");
$diretorio_destino = "./src/img/uploads/"; // Diretório de destino das imagens

// Função para redimensionar e comprimir a imagem
function redimensionarEComprimirImagem($caminho, $largura, $altura, $qualidade)
{
	list($largura_original, $altura_original) = getimagesize($caminho);
	$nova_imagem = imagecreatetruecolor($largura, $altura);
	$imagem_original = imagecreatefromjpeg($caminho);
	imagecopyresampled($nova_imagem, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_original, $altura_original);
	imagejpeg($nova_imagem, $caminho, $qualidade);
	imagedestroy($nova_imagem);
	imagedestroy($imagem_original);
}

// Função para corrigir a orientação da imagem
function corrigirOrientacaoImagem($caminho)
{
	$info = @exif_read_data($caminho);

	if ($info && isset($info['Orientation'])) {
		$orientation = $info['Orientation'];

		if ($orientation != 1) {
			$image = imagecreatefromjpeg($caminho);

			switch ($orientation) {
				case 3:
					$image = imagerotate($image, 180, 0);
					break;

				case 6:
					$image = imagerotate($image, -90, 0);
					break;

				case 8:
					$image = imagerotate($image, 90, 0);
					break;
			}

			imagejpeg($image, $caminho, 20);
			imagedestroy($image);
		} else {
			// Se não precisar de rotação, apenas copie o arquivo
			copy($caminho, $caminho);
		}
	} else {
		// Caso não seja possível obter informações de orientação, copie o arquivo
		copy($caminho, $caminho);
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
			// Corrija a orientação da imagem
			corrigirOrientacaoImagem($caminho_arquivo);

			// Redimensione e comprima a imagem
			redimensionarEComprimirImagem($caminho_arquivo, 995, 1293, 40);

			// Atualize o caminho da imagem no banco de dados
			$atualiza_imagem = "UPDATE estudante SET imagem='$caminho_arquivo' WHERE id=$id";
			$resultado_atualiza_imagem = mysqli_query($conn, $atualiza_imagem);

			if (!$resultado_atualiza_imagem) {
				$_SESSION['msg'] = "<p style='color:red;'>Erro ao atualizar o caminho da imagem no banco de dados.</p>";
				header("Location: src/aluno/edit_aluno?id=$id");
				exit();
			}
		} else {
			$_SESSION['msg'] = "<p style='color:red;'>Erro ao fazer upload da imagem.</p>";
			header("Location: src/aluno/edit_aluno?id=$id");
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
		header("Location: src/aluno/");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Aluno(a) '$nome' não foi editado com sucesso!</p>";
		header("Location: src/aluno/edit_aluno?id=$id");
	}

	$stmt->close();
	$conn->close();
	exit();
}

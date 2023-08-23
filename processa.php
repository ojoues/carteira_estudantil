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
$modificado = $_POST['modificado'];
$criado = $_POST['criado'];

// Diretório onde as fotos serão armazenadas (Link do banco)
$diretorio_destino = "./src/img/uploads/";

// Nome do arquivo original
$nome_arquivo = $_FILES['imagem']['name'];

// Caminho completo do arquivo no servidor
$caminho_arquivo = $diretorio_destino . $nome_arquivo;

// Move o arquivo para o diretório de destino
if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
	// Conecte-se ao banco de dados (você deve já ter uma conexão estabelecida)
	include_once("conexao.php"); // Inclua o arquivo de conexão com o banco de dados

	// Insira o caminho do arquivo no banco de dados (supondo que você tem uma tabela chamada 'estudante')
	$sql = "INSERT INTO estudante (nome, data_nascimento, sexo, instituicao, curso, cpf, validade, imagem, modificado, criado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "ssssssss", $nome, $data_nascimento, $sexo, $instituicao, $curso, $cpf_sem_formato, $validade, $caminho_arquivo);

	if (mysqli_stmt_execute($stmt)) {
		$_SESSION['msg'] = "<p style='color:green;'>Aluno cadastrado com sucesso!</p>";
		header("Location: cad_aluno.php");
	} else {
		$_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar aluno: " . mysqli_error($conn) . "</p>";
		header("Location: cad_aluno.php");
	}

	// Feche a declaração e a conexão com o banco de dados
	mysqli_stmt_close($stmt);
	mysqli_close($conn);

	// Redirecione de volta para a página principal ou para onde desejar
	header("Location: cad_aluno.php");
} else {
	$_SESSION['msg'] = "<p style='color:red;'>Erro ao enviar a foto.</p>";
	header("Location: cad_aluno.php");
}

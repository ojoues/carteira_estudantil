<?php
session_start();
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $novaSenha = $_POST['nova_senha'];

    // Verificar se o campo da nova senha foi preenchido
    if (!empty($novaSenha)) {
        // Criptografar a nova senha antes de armazená-la no banco de dados
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Atualizar o registro no banco de dados com a nova senha criptografada
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, email = ?, senha = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nome, $usuario, $email, $senhaHash, $id);
    } else {
        // Se o campo da nova senha estiver vazio, atualizar sem alterar a senha
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $usuario, $email, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Usuário atualizado com sucesso.<br>";
    } else {
        $_SESSION['msg'] = "Erro ao atualizar o usuário: <br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit();
}

<?php
session_start();
include_once("../../conexao.php");

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $novaSenha = $_POST['nova_senha'];

    // Defina a data e hora do momento
    $modificado = date('Y-m-d H:i:s');

    // Verificar se o campo da nova senha foi preenchido
    if (!empty($novaSenha)) {
        // Criptografar a nova senha antes de armazená-la no banco de dados
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Atualizar o registro no banco de dados com a nova senha criptografada e a data/hora de modificação
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, email = ?, senha = ?, modificado = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $usuario, $email, $senhaHash, $modificado, $id);
    } else {
        // Se o campo da nova senha estiver vazio, atualizar sem alterar a senha e definir a data/hora de modificação
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, email = ?, modificado = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nome, $usuario, $email, $modificado, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['msg'] = "<span style='color: green;'>Usuário '$usuario' atualizado com sucesso.</span><br>";
    } else {
        $_SESSION['msg'] = "<span style='color: red;'>Erro ao atualizar usuário '$usuario': ' . $conn->error . '</span><br>";
    }

    $stmt->close();
    $conn->close();

    header("Location: index");
    exit();
}

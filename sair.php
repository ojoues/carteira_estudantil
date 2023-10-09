<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header("Location: login");
$_SESSION['msg'] = "Deslogado com sucesso<br>";
exit(); // Certifique-se de sair após o redirecionamento

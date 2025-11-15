<?php
session_start();
require_once "src/UsuarioDAO.php";

try {
    UsuarioDAO::cadastrarUsuario($_POST);
    $_SESSION['msg'] = "Usuário cadastrado com sucesso!";
    header("Location: login.php");
    exit;
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
    header("Location: form-cadastra-usuario.php"); 
    exit;
}
?>
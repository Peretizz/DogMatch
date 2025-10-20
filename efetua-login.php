<?php
session_start();
require "src/UsuarioDAO.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($usuarios = UsuarioDAO::validarUsuario($_POST)) {

        $_SESSION['idusuario'] = $usuarios['idusuario'];
        $_SESSION['nome'] = $usuarios['nome'];
        $_SESSION['email'] = $usuarios['email'];
        $_SESSION['localizacao'] = $usuarios['localizacao'];
        $_SESSION['foto'] = $usuarios['foto'];


        header("Location: index.php");
        exit;
    } else {

        $_SESSION['msg'] = "Usuário ou senha inválido.";


        header("Location: login.php");
        exit;
    }
} else {

    header("Location: login.php");
    exit;
}
?>
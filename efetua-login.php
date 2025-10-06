<?php
    session_start();
    require_once "src/UsuarioDAO.php";

    if ($idsuario = UsuarioDAO::validarUsuario($_POST)){    
        $_SESSION['email'] = $_POST ['email'];
        $_SESSION['idusuario'] = $idsuario;
        header("Location:index.php");
    }else{
        $_SESSION['msg'] = "Usuário ou senha inválido.";
        header("Location:login.php");
    }
?>
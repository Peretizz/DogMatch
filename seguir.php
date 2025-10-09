<?php
    include "incs/valida-sessao.php";
    require_once "src/SeguidoDAO.php";

    if (isset($_GET["idseguido"])) {
        $idusuario = $_SESSION["idusuario"];
        $idseguido = $_GET["idseguido"];
        
        if (SeguidoDAO::jaSegue($idusuario, $idseguido)) {
            echo '<script>';
            echo 'alert("Você já segue este usuário!");';
            echo 'window.location.href = "usuarios.php";';
            echo '</script>';
            exit();

        } else {
            SeguidoDAO::seguir($idusuario, $idseguido);
        }
    }

    header("Location: usuarios.php");
    exit();
?>
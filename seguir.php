<?php
    include "incs/valida-sessao.php"; 
    require_once "src/SeguidoDAO.php";
    $idusuario = $_SESSION["idusuario"] ?? null;
    $idseguido = $_GET["idseguido"] ?? null;
    if ($idusuario !== null && $idseguido !== null) {
        if (!SeguidoDAO::jaSegue($idusuario, $idseguido)) {
            SeguidoDAO::seguir($idusuario, $idseguido);
        } 
    } 
    header("location:usuarios.php");
    exit;
?>
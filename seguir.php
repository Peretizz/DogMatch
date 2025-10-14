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

// Redirecionar de volta para a página anterior ou index
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $referer");
exit;
?>

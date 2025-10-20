<?php
include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$idusuario = $_SESSION['idusuario'];

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}


$usuarios = UsuarioDAO::buscarUsuarioNome($query, $idusuario);


foreach ($usuarios as &$usuario) {
    
    $usuario['jaSeguindo'] = SeguidoDAO::jaSegue($idusuario, $usuario['idusuario']);
}

echo json_encode($usuarios);
?>
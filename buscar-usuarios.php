<?php
include("incs/valida-sessao.php");
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";

header('Content-Type: application/json');

$nome = $_GET['nome'] ?? '';
$idusuario = $_SESSION['idusuario'];

if (empty($nome)) {
    echo json_encode([]);
    exit;
}

$usuarios = UsuarioDAO::buscarUsuarioNome($nome, $idusuario);

foreach ($usuarios as &$usuario) {
    $usuario['ja_segue'] = SeguidoDAO::jaSegue($idusuario, $usuario['idusuario']);
}

echo json_encode($usuarios);

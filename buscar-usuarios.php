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

// CHAMA O DAO QUE AGORA RETORNA A COLUNA 'foto'
$usuarios = UsuarioDAO::buscarUsuarioNome($query, $idusuario);

// Adicionar informação se já está seguindo
foreach ($usuarios as &$usuario) {
    // A variável $usuario agora contém 'idusuario', 'nome' e 'foto'
    $usuario['jaSeguindo'] = SeguidoDAO::jaSegue($idusuario, $usuario['idusuario']);
}

echo json_encode($usuarios);
?>
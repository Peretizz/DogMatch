<?php
include "incs/valida-sessao.php";
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

$idpost = isset($_GET['idpost']) ? intval($_GET['idpost']) : 0;

if ($idpost <= 0) {
    echo json_encode(['success' => false, 'message' => 'Post inválido']);
    exit;
}

$comentarios = ComentarioDAO::listarPorPost($idpost);

echo json_encode([
    'success' => true,
    'comentarios' => $comentarios
]);
?>

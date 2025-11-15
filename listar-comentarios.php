<?php
session_start();
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_GET['idpost'])) {
    echo json_encode(['success' => false, 'message' => 'Post não especificado']);
    exit;
}

$idpost = $_GET['idpost'];

$comentarios = ComentarioDAO::listarPorPost($idpost);

echo json_encode([
    'success' => true,
    'comentarios' => $comentarios
]);
?>

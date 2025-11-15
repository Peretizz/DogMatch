<?php
session_start();
require_once "src/MensagemDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$idusuario = $_SESSION['idusuario'];
$contador = MensagemDAO::contarMensagensNaoLidas($idusuario);

echo json_encode([
    'success' => true,
    'contador' => $contador
]);
?>

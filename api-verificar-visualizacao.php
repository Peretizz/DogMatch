<?php
include "incs/valida-sessao.php";
require_once "src/MensagemDAO.php";

header('Content-Type: application/json');

$idusuario = $_SESSION['idusuario'];
$idusuario_conversa = $_GET['idusuario_conversa'] ?? null;

if (!$idusuario_conversa) {
    echo json_encode(['success' => false, 'message' => 'Usuário da conversa não especificado']);
    exit;
}

// Buscar IDs das mensagens enviadas que foram visualizadas
$mensagens_visualizadas = MensagemDAO::verificarMensagensVisualizadas($idusuario, $idusuario_conversa);

echo json_encode([
    'success' => true,
    'mensagens_visualizadas' => $mensagens_visualizadas
]);
?>

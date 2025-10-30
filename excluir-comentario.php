<?php
session_start();
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$idcomentario = $_POST['idcomentario'] ?? null;
$idusuario = $_SESSION['idusuario'];

if (!$idcomentario) {
    echo json_encode(['success' => false, 'message' => 'ID do comentário não fornecido']);
    exit;
}

try {
    $resultado = ComentarioDAO::excluir($idcomentario, $idusuario);
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Comentário excluído com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir comentário ou comentário não encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>

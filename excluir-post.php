<?php
session_start();
require_once "src/PostDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$idpost = $_POST['idpost'] ?? null;
$idusuario = $_SESSION['idusuario'];

if (!$idpost) {
    echo json_encode(['success' => false, 'message' => 'ID do post não fornecido']);
    exit;
}

try {
    $resultado = PostDAO::excluir($idpost, $idusuario);
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Post excluído com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir post ou post não encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>

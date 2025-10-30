<?php
session_start();
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_POST['idpost']) || !isset($_POST['conteudo'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$idpost = $_POST['idpost'];
$idusuario = $_SESSION['idusuario'];
$conteudo = trim($_POST['conteudo']);

if (empty($conteudo)) {
    echo json_encode(['success' => false, 'message' => 'Comentário vazio']);
    exit;
}

$resultado = ComentarioDAO::adicionar($idpost, $idusuario, $conteudo);

if ($resultado) {
    $total = ComentarioDAO::contarComentarios($idpost);
    echo json_encode([
        'success' => true,
        'message' => 'Comentário adicionado com sucesso',
        'total' => $total
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar comentário']);
}
?>

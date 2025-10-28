<?php
include "incs/valida-sessao.php";
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$idpost = isset($_POST['idpost']) ? intval($_POST['idpost']) : 0;
$conteudo = isset($_POST['conteudo']) ? trim($_POST['conteudo']) : '';
$idusuario = $_SESSION['idusuario'];

if ($idpost <= 0) {
    echo json_encode(['success' => false, 'message' => 'Post inválido']);
    exit;
}

if (empty($conteudo)) {
    echo json_encode(['success' => false, 'message' => 'Comentário vazio']);
    exit;
}

$resultado = ComentarioDAO::adicionar($idpost, $idusuario, $conteudo);

if ($resultado) {
    $totalComentarios = ComentarioDAO::contarComentarios($idpost);
    echo json_encode([
        'success' => true,
        'total' => $totalComentarios
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar comentário']);
}
?>

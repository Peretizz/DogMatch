<?php
include "incs/valida-sessao.php";
require_once "src/ComentarioDAO.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$idpost = $_POST['idpost'] ?? null;
$conteudo = trim($_POST['conteudo'] ?? '');
$idusuario = $_SESSION['idusuario'];

if (!$idpost || empty($conteudo)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$resultado = ComentarioDAO::adicionar($idpost, $idusuario, $conteudo);

if ($resultado) {
    $total = ComentarioDAO::contarComentarios($idpost);
    echo json_encode(['success' => true, 'total' => $total]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar comentário']);
}
?>

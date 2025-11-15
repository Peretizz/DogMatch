<?php
session_start();
require_once "src/CurtidaDAO.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_POST['idpost'])) {
    echo json_encode(['success' => false, 'message' => 'Post não especificado']);
    exit;
}

$idpost = $_POST['idpost'];
$idusuario = $_SESSION['idusuario'];

// Verifica se já curtiu
$jaCurtiu = CurtidaDAO::jaCurtiu($idpost, $idusuario);

if ($jaCurtiu) {
    // Descurtir
    $resultado = CurtidaDAO::descurtir($idpost, $idusuario);
} else {
    // Curtir
    $resultado = CurtidaDAO::curtir($idpost, $idusuario);
}

if ($resultado) {
    $total = CurtidaDAO::contarCurtidas($idpost);
    echo json_encode([
        'success' => true,
        'curtido' => !$jaCurtiu,
        'total' => $total
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao processar curtida']);
}
?>

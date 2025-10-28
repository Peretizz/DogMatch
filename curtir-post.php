<?php
include "incs/valida-sessao.php";
require_once "src/CurtidaDAO.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$idpost = isset($_POST['idpost']) ? intval($_POST['idpost']) : 0;
$idusuario = $_SESSION['idusuario'];

if ($idpost <= 0) {
    echo json_encode(['success' => false, 'message' => 'Post inválido']);
    exit;
}

// Verifica se já curtiu
$jaCurtiu = CurtidaDAO::jaCurtiu($idpost, $idusuario);

if ($jaCurtiu) {
    // Descurtir
    $resultado = CurtidaDAO::descurtir($idpost, $idusuario);
    $curtido = false;
} else {
    // Curtir
    $resultado = CurtidaDAO::curtir($idpost, $idusuario);
    $curtido = true;
}

if ($resultado) {
    $totalCurtidas = CurtidaDAO::contarCurtidas($idpost);
    echo json_encode([
        'success' => true,
        'curtido' => $curtido,
        'total' => $totalCurtidas
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao processar curtida']);
}
?>

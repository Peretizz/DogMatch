<?php
include "incs/valida-sessao.php";
require_once "src/MensagemDAO.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mensagens.php');
    exit;
}

$idremetente = $_SESSION['idusuario'];
$iddestinatario = $_POST['iddestinatario'] ?? null;
$conteudo = trim($_POST['mensagem'] ?? '');

if (!$iddestinatario) {
    header('Location: mensagens.php');
    exit;
}

$imagem_path = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array($extensao, $extensoesPermitidas)) {
        // Check file size (max 5MB)
        if ($_FILES['imagem']['size'] <= 5 * 1024 * 1024) {
            $nome_arquivo = uniqid('msg_') . '.' . $extensao;
            $diretorio = 'uploads/mensagens/';
            
            // Create directory if it doesn't exist
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0755, true);
            }
            
            $caminho_completo = $diretorio . $nome_arquivo;
            
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                $imagem_path = $caminho_completo;
            }
        }
    }
}

// Allow sending if there's either content or an image
if (empty($conteudo) && !$imagem_path) {
    header('Location: mensagens.php?idusuario=' . $iddestinatario);
    exit;
}

$resultado = MensagemDAO::enviarMensagem($idremetente, $iddestinatario, $conteudo, $imagem_path);

// Return JSON for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => $resultado]);
    exit;
}

header('Location: mensagens.php?idusuario=' . $iddestinatario);
exit;
?>

    <?php
    include "incs/valida-sessao.php";
    require_once "src/MensagemDAO.php";

    header('Content-Type: application/json');

    $idusuario_logado = $_SESSION['idusuario'];
    $idusuario_conversa = $_GET['idusuario'] ?? null;
    $ultima_id = $_GET['ultima_id'] ?? 0;

    if (!$idusuario_conversa) {
        echo json_encode(['success' => false, 'message' => 'Usuário não especificado']);
        exit;
    }

    try {
        // Buscar novas mensagens após a última ID
        $mensagens = MensagemDAO::buscarNovasMensagens($idusuario_logado, $idusuario_conversa, $ultima_id);
        
        // Marcar mensagens como lidas e visualizadas
        if (!empty($mensagens)) {
            MensagemDAO::marcarComoLida($idusuario_logado, $idusuario_conversa);
            MensagemDAO::marcarComoVisualizada($idusuario_logado, $idusuario_conversa);
        }
        
        echo json_encode([
            'success' => true,
            'mensagens' => $mensagens
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao buscar mensagens: ' . $e->getMessage()
        ]);
    }

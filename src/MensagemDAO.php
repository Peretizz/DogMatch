<?php
require_once "ConexaoBD.php";

class MensagemDAO
{
    public static function enviarMensagem($idremetente, $iddestinatario, $conteudo, $imagem = null)
    {
        date_default_timezone_set('America/Sao_Paulo');
        
        $conexao = ConexaoBD::conectar();
        $dataEnvio = date('Y-m-d H:i:s'); 
        
        $sql = "INSERT INTO mensagens (idremetente, iddestinatario, conteudo, imagem, data_envio, lida, visualizada) 
                VALUES (:idremetente, :iddestinatario, :conteudo, :imagem, :dataEnvio, 0, 0)";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idremetente', $idremetente, PDO::PARAM_INT);
        $stmt->bindParam(':iddestinatario', $iddestinatario, PDO::PARAM_INT);
        $stmt->bindParam(':conteudo', $conteudo);
        $stmt->bindParam(':imagem', $imagem);
        $stmt->bindParam(':dataEnvio', $dataEnvio);
        
        return $stmt->execute();
    }
    
    
    public static function listarMensagens($idusuario1, $idusuario2)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT * FROM mensagens 
                WHERE (idremetente = :idusuario1 AND iddestinatario = :idusuario2)
                   OR (idremetente = :idusuario2 AND iddestinatario = :idusuario1)
                ORDER BY data_envio ASC";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario1', $idusuario1, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario2', $idusuario2, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarNovasMensagens($idusuario1, $idusuario2, $ultimaId)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT * FROM mensagens 
                WHERE ((idremetente = :idusuario1 AND iddestinatario = :idusuario2)
                   OR (idremetente = :idusuario2 AND iddestinatario = :idusuario1))
                AND idmensagem > :ultimaId
                ORDER BY data_envio ASC";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario1', $idusuario1, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario2', $idusuario2, PDO::PARAM_INT);
        $stmt->bindParam(':ultimaId', $ultimaId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function listarConversas($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT DISTINCT 
                    CASE 
                        WHEN m.idremetente = :idusuario THEN m.iddestinatario
                        ELSE m.idremetente
                    END as idusuario,
                    u.nome,
                    u.foto,
                    (SELECT conteudo FROM mensagens m2 
                     WHERE (m2.idremetente = :idusuario AND m2.iddestinatario = u.idusuario)
                        OR (m2.idremetente = u.idusuario AND m2.iddestinatario = :idusuario)
                     ORDER BY m2.data_envio DESC LIMIT 1) as ultima_mensagem,
                    (SELECT COUNT(*) FROM mensagens m3
                     WHERE m3.idremetente = u.idusuario 
                     AND m3.iddestinatario = :idusuario 
                     AND m3.lida = 0) as nao_lidas
                FROM mensagens m
                INNER JOIN usuarios u ON u.idusuario = CASE 
                    WHEN m.idremetente = :idusuario THEN m.iddestinatario
                    ELSE m.idremetente
                END
                WHERE m.idremetente = :idusuario OR m.iddestinatario = :idusuario
                ORDER BY (SELECT MAX(data_envio) FROM mensagens m3 
                          WHERE (m3.idremetente = :idusuario AND m3.iddestinatario = u.idusuario)
                             OR (m3.idremetente = u.idusuario AND m3.iddestinatario = :idusuario)) DESC";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function marcarComoLida($iddestinatario, $idremetente)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "UPDATE mensagens SET lida = 1, visualizada = 1 
                WHERE iddestinatario = :iddestinatario 
                AND idremetente = :idremetente 
                AND lida = 0";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':iddestinatario', $iddestinatario, PDO::PARAM_INT);
        $stmt->bindParam(':idremetente', $idremetente, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public static function marcarComoVisualizada($iddestinatario, $idremetente)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "UPDATE mensagens SET visualizada = 1 
                WHERE iddestinatario = :iddestinatario 
                AND idremetente = :idremetente 
                AND visualizada = 0";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':iddestinatario', $iddestinatario, PDO::PARAM_INT);
        $stmt->bindParam(':idremetente', $idremetente, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public static function contarMensagensNaoLidas($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT COUNT(*) as total FROM mensagens 
                WHERE iddestinatario = :idusuario AND lida = 0";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }
    
    public static function verificarMensagensVisualizadas($idremetente, $iddestinatario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT idmensagem FROM mensagens 
                WHERE idremetente = :idremetente 
                AND iddestinatario = :iddestinatario 
                AND visualizada = 1";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idremetente', $idremetente, PDO::PARAM_INT);
        $stmt->bindParam(':iddestinatario', $iddestinatario, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($resultados, 'idmensagem');
    }
}
?>

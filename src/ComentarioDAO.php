<?php
require_once "ConexaoBD.php";

class ComentarioDAO
{
    
    public static function adicionar($idpost, $idusuario, $conteudo)
    {
        $conexao = ConexaoBD::conectar();
        
        $conexao->exec("SET time_zone = '-03:00'");
        
        $sql = "INSERT INTO comentarios (idpost, idusuario, conteudo) 
                VALUES (:idpost, :idusuario, :conteudo)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->bindParam(':conteudo', $conteudo);
        
        return $stmt->execute();
    }

    
    public static function listarPorPost($idpost)
    {
        $conexao = ConexaoBD::conectar();
        
        $conexao->exec("SET time_zone = '-03:00'");
        
        $sql = "SELECT c.*, u.nome as nome_usuario, u.foto as foto_usuario
                FROM comentarios c
                INNER JOIN usuarios u ON c.idusuario = u.idusuario
                WHERE c.idpost = :idpost
                ORDER BY c.data_criacao ASC";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function contarComentarios($idpost)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT COUNT(*) FROM comentarios WHERE idpost = :idpost";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    
    public static function excluir($idcomentario, $idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "DELETE FROM comentarios WHERE idcomentario = :idcomentario AND idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idcomentario', $idcomentario, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
?>

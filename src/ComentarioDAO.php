<?php
require_once "ConexaoBD.php";

class ComentarioDAO
{
    /**
     * Adiciona um comentário em um post
     */
    public static function adicionar($idpost, $idusuario, $conteudo)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "INSERT INTO comentarios (idpost, idusuario, conteudo) 
                VALUES (:idpost, :idusuario, :conteudo)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->bindParam(':conteudo', $conteudo);
        
        return $stmt->execute();
    }

    /**
     * Lista todos os comentários de um post
     */
    public static function listarPorPost($idpost)
    {
        $conexao = ConexaoBD::conectar();
        
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

    /**
     * Conta o total de comentários de um post
     */
    public static function contarComentarios($idpost)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT COUNT(*) FROM comentarios WHERE idpost = :idpost";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * Exclui um comentário
     */
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

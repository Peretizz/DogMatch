<?php
require_once "ConexaoBD.php";

class CurtidaDAO
{
    /**
     * Adiciona uma curtida em um post
     */
    public static function curtir($idpost, $idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        try {
            $sql = "INSERT INTO curtidas (idpost, idusuario) VALUES (:idpost, :idusuario)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Se já existe curtida, retorna false
            return false;
        }
    }

    /**
     * Remove uma curtida de um post
     */
    public static function descurtir($idpost, $idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "DELETE FROM curtidas WHERE idpost = :idpost AND idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Verifica se o usuário já curtiu o post
     */
    public static function jaCurtiu($idpost, $idusuario)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT COUNT(*) FROM curtidas WHERE idpost = :idpost AND idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Conta o total de curtidas de um post
     */
    public static function contarCurtidas($idpost)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT COUNT(*) FROM curtidas WHERE idpost = :idpost";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
}
?>

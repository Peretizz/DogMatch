<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class SeguidoDAO
{

    public static function seguir($idusuario, $idseguido)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "insert into seguidos (idusuario, idseguido) values (:idusuario, :idseguido)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idseguido', $idseguido);
        $stmt->execute(); 
    }

    public static function deixarDeSeguir($idusuario, $idseguido)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "DELETE FROM seguidos WHERE idusuario = ? AND idseguido = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuario);
        $stmt->bindParam(2, $idseguido);
        $stmt->execute();
    }
    
    public static function contarSeguidores($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT COUNT(idusuario) FROM seguidos WHERE idseguido = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    public static function contarSeguindo($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT COUNT(idseguido) FROM seguidos WHERE idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    public static function listarSeguidores($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT u.nome, u.foto 
                FROM seguidos s
                JOIN usuarios u ON u.idusuario = s.idusuario 
                WHERE s.idseguido = ?"; 
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function listarSeguindo($idusuario) 
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT u.nome, u.foto
                FROM seguidos s
                JOIN usuarios u ON u.idusuario = s.idseguido 
                WHERE s.idusuario = ?"; 
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function jaSegue($idusuario, $idseguido)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT COUNT(*) FROM Seguidos WHERE idusuario = :idusuario AND idseguido = :idseguido";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idseguido', $idseguido);
        $stmt->execute();

        return $stmt->fetchColumn(0) > 0;
    }
}
?>
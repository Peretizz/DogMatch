<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class SeguidoDAO
{
    public static function seguir($idusuario, $idseguido)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "INSERT INTO seguidos (idusuario, idseguido) VALUES (:idusuario, :idseguido)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->bindParam(':idseguido', $idseguido);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao seguir: " . $e->getMessage());
        }
    }

    public static function deixarDeSeguir($idusuario, $idseguido)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "DELETE FROM seguidos WHERE idusuario = ? AND idseguido = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idusuario);
            $stmt->bindParam(2, $idseguido);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao deixar de seguir: " . $e->getMessage());
        }
    }
    
    public static function contarSeguidores($idusuario)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "SELECT COUNT(idusuario) FROM seguidos WHERE idseguido = :idusuario";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("Erro ao contar seguidores: " . $e->getMessage());
        }
    }
    
    public static function contarSeguindo($idusuario)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "SELECT COUNT(idseguido) FROM seguidos WHERE idusuario = :idusuario";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("Erro ao contar seguindo: " . $e->getMessage());
        }
    }

    public static function listarSeguidores($idusuario)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "SELECT u.idusuario, u.nome, u.foto 
                    FROM seguidos s
                    JOIN usuarios u ON u.idusuario = s.idusuario 
                    WHERE s.idseguido = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idusuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erro ao listar seguidores: " . $e->getMessage());
        }
    }
    
    public static function listarSeguindo($idusuario) 
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "SELECT u.idusuario, u.nome, u.foto
                    FROM seguidos s
                    JOIN usuarios u ON u.idusuario = s.idseguido 
                    WHERE s.idusuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idusuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erro ao listar seguindo: " . $e->getMessage());
        }
    }

    public static function jaSegue($idusuario, $idseguido)
    {
        try {
            $conexao = ConexaoBD::conectar();
            $sql = "SELECT COUNT(*) FROM seguidos WHERE idusuario = :idusuario AND idseguido = :idseguido";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->bindParam(':idseguido', $idseguido);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception("Erro ao verificar se já segue: " . $e->getMessage());
        }
    }
}
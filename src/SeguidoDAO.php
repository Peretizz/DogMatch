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
    
    // ==========================================================
    // NOVOS MÉTODOS DE CONTADOR
    // ==========================================================
    
    /**
     * Conta quantos usuários estão seguindo o perfil de $idusuario (idseguido).
     */
    public static function contarSeguidores($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        // SELECT COUNT(*) na coluna 'idusuario' (quem segue) onde 'idseguido' é o perfil atual
        $sql = "SELECT COUNT(idusuario) FROM seguidos WHERE idseguido = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    /**
     * Conta quantos usuários o perfil de $idusuario (quem segue) está seguindo.
     */
    public static function contarSeguindo($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        // SELECT COUNT(*) na coluna 'idseguido' (quem é seguido) onde 'idusuario' é o perfil atual
        $sql = "SELECT COUNT(idseguido) FROM seguidos WHERE idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    // ==========================================================
    // MÉTODOS EXISTENTES (Mantidos para referência, mas verifique as colunas)
    // ==========================================================

    public static function listarSeguidores($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        // Atenção: Esta query usa 'seguidores', 'nome_usuario', 'foto_perfil', 'idseguidor'
        // que podem não ser consistentes com o seu esquema 'seguidos' e 'usuarios'.
        $sql = "SELECT u.nome, u.foto 
                FROM seguidos s
                JOIN usuarios u ON u.idusuario = s.idusuario -- u.idusuario é quem segue
                WHERE s.idseguido = ?"; // O idseguido é o perfil que queremos listar os seguidores
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function listarSeguindo($idusuario) // Renomeado para $idusuario
    {
        $conexao = ConexaoBD::conectar();
        // Atenção: Esta query usa 'seguidores', 'nome_usuario', 'foto_perfil', 'idusuario'
        // que podem não ser consistentes com o seu esquema 'seguidos' e 'usuarios'.
        $sql = "SELECT u.nome, u.foto
                FROM seguidos s
                JOIN usuarios u ON u.idusuario = s.idseguido -- u.idusuario é quem está sendo seguido
                WHERE s.idusuario = ?"; // O idusuario é o perfil que queremos listar quem ele segue
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
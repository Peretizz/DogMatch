<?php
require_once "ConexaoBD.php";

class SeguidoDAO{

    public static function jaSegue($idusuario, $idseguido){
        $conexao = ConexaoBD::conectar();
        $sql = "select count(*) from seguidos where idusuario = :idusuario and idseguido = :idseguido";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idseguido', $idseguido);
        $stmt->execute();
        

        return $stmt->fetchColumn() > 0;
    }

    public static function seguir($idusuario, $idseguido){
        $conexao = ConexaoBD::conectar();
        $sql = "insert into seguidos (idusuario, idseguido) values (:idusuario, :idseguido)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idseguido', $idseguido);
        $stmt->execute();
    }
}
?>
<?php
require_once "ConexaoBD.php";

class SeguidoDAO{

    public static function seguir($idusuario, $idseguido){
        $conexao = ConexaoBD::conectar();
        $sql = "insert into Seguidos (idusuario, idseguido) values (:idusuario, :idseguido)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idseguido', $idseguido);
        $stmt->execute();
    }
}
?>

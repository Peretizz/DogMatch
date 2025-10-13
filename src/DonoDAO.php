<?php
require_once "ConexaoBD.php";

class DonoDAO{

    public static function seguir($idusuario, $idcachorro){
        $conexao = ConexaoBD::conectar();
        $sql = "insert into donos (idusuario, idcachorro) values (:idusuario, :idcachorro)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idcachorro', $idcachorro);
        $stmt->execute();
    }
}
?>

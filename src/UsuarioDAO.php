<?php
require "ConexaoBD.php";

class UsuarioDAO{

    public static function cadastrarUsuario($dados){
        $conexao = ConexaoBD::conectar();
        
        $sql = "insert into usuarios (email, senha) values (?,?)";
        $stmt = $conexao->prepare($sql);
        
        $stmt->bindParam(1, $dados['email']);
        $senhaCriptografada = md5($dados['senha']);
        $stmt->bindParam(2, $senhaCriptografada);

        $stmt->execute();
    }

    public static function validarUsuario($dados){
        echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>> ";
        
        $senhaCriptografada = md5($dados['senha']);
        $sql = "select * from usuarios where email=? AND senha=?";

        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $dados['email']);
        $stmt->bindParam(2, $senhaCriptografada);
        $stmt->execute();
        
        echo ">>>>>>>>>> ";
        var_dump($stmt);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class UsuarioDAO{

    public static function cadastrarUsuario($dados){
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $email = $dados['email'];
        $senha = $dados['senha'];
        $localizacao = $dados['localizacao'];

        $sql = "insert into usuarios (nome, email, senha, localizacao) values (:nome, :email, :senha, :localizacao)";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $senhaCriptografada = md5($senha);
        $stmt->bindParam(':senha', $senhaCriptografada);
        $stmt->bindParam(':localizacao', $localizacao);
        $stmt->execute();
    }

    public static function validarUsuario($dados){
        $senhaCriptografada = md5($dados['senha']);
        $sql = "select * from usuarios where email=? AND senha=?";

        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $dados['email']);
        $stmt->bindParam(2, $senhaCriptografada);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
     public static function listar()
    {
        $conexao = ConexaoBD::conectar();
        $sql = "select * from usuarios";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $filmes;
    }
}
?>

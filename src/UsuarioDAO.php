<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class UsuarioDAO{

    public static function cadastrarUsuario($dados){
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $email = $dados['email'];
        $senha = $dados['senha'];
        $imagem = Util::salvarArquivo();

        $sql = "insert into usuarios (nome, email, senha, imagem) values (:nome, :email, :senha, :imagem)";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $senhaCriptografada = md5($senha);
        $stmt->bindParam(':senha', $senhaCriptografada);
        $stmt->bindParam(':imagem', $imagem);

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
}
?>

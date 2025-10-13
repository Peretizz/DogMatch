<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class UsuarioDAO
{

    public static function cadastrarUsuario($dados)
    {
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $email = $dados['email'];
        $senha = $dados['senha'];
        $localizacao = $dados['localizacao'];
        
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = Util::salvarArquivo('foto');
        }

        $sql = "insert into usuarios (nome, email, senha, localizacao, foto) values (:nome, :email, :senha, :localizacao, :foto)";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $senhaCriptografada = md5($senha);
        $stmt->bindParam(':senha', $senhaCriptografada);
        $stmt->bindParam(':localizacao', $localizacao);
        $stmt->bindParam(':foto', $foto);
        $stmt->execute();
    }

    public static function validarUsuario($dados)
    {
        $senhaCriptografada = md5($dados['senha']);
        $sql = "select * from usuarios where email=? AND senha=?";

        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $dados['email']);
        $stmt->bindParam(2, $senhaCriptografada);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            return $usuario;
        } else {
            return false;
        }
    }
    
    public static function listarUsuarios($idusuarios)
    {
        $sql = "SELECT * FROM usuarios WHERE idusuario!=?";

        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuarios);
        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarUsuarioNome($nome, $idUsuarioLogado)
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT idusuario, nome 
                FROM Usuarios 
                WHERE nome LIKE :nome 
                AND idusuario != :idUsuarioLogado"; 
                
        $stmt = $conexao->prepare($sql);
        
        $nomeBusca = "%" . $nome . "%";
        
        $stmt->bindParam(':nome', $nomeBusca);
        $stmt->bindParam(':idUsuarioLogado', $idUsuarioLogado); 
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPorId($idusuario)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT * FROM usuarios WHERE idusuario = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $idusuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>

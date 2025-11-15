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
        $sqlVerifica = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmtVerifica = $conexao->prepare($sqlVerifica);
        $stmtVerifica->bindParam(':email', $email);
        $stmtVerifica->execute();
        $count = $stmtVerifica->fetchColumn();
        if ($count > 0) {
            throw new Exception("Email já cadastrado. Não é possível criar mais de uma conta com o mesmo email.");
        }
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = Util::salvarArquivo('foto');
        }
        $sql = "INSERT INTO usuarios (nome, email, senha, localizacao, foto) VALUES (:nome, :email, :senha, :localizacao, :foto)";
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

        $sql = "SELECT idusuario, nome, foto 
                FROM usuarios 
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

    public static function buscarSugestoes($idusuario, $limite = 8)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT u.idusuario, u.nome, u.foto 
                FROM usuarios u
                WHERE u.idusuario != :idusuario
                AND u.idusuario NOT IN (
                    SELECT idseguido FROM seguidos WHERE idusuario = :idusuario
                )
                ORDER BY RAND()
                LIMIT :limite";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarNaoSeguidos($idUsuarioLogado)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT idusuario, nome, foto 
                FROM usuarios 
                WHERE idusuario != :idUsuarioLogado
                AND idusuario NOT IN (
                    SELECT idseguido FROM seguidos WHERE idusuario = :idUsuarioLogado
                )
                ORDER BY nome";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idUsuarioLogado', $idUsuarioLogado, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function atualizarFoto($idusuario, $foto_nome)
    {
        $conexao = ConexaoBD::conectar();
        $sql = "UPDATE usuarios SET foto = :foto WHERE idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':foto', $foto_nome);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
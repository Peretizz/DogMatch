<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class PostDAO
{
    public static function criarPost($dados)
    {
        $conexao = ConexaoBD::conectar();

        $conteudo = $dados['conteudo'];
        $idusuario = $dados['idusuario'];
        $idcachorro = $dados['idcachorro'];

        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = Util::salvarArquivo('foto');
        }

       
        $sql = "INSERT INTO posts (conteudo, foto, idusuario, idcachorro, data_criacao) 
                VALUES (:conteudo, :foto, :idusuario, :idcachorro, NOW())";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':conteudo', $conteudo);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idcachorro', $idcachorro);

        return $stmt->execute();
    }

    public static function buscarPorId($idpost)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT p.*, u.nome as nome_usuario, u.foto as foto_usuario,
                       c.nome as nome_cachorro, c.foto as foto_cachorro, r.nome as raca_cachorro
                FROM posts p
                INNER JOIN usuarios u ON p.idusuario = u.idusuario
                LEFT JOIN cachorros c ON p.idcachorro = c.idcachorro
                LEFT JOIN racas r ON c.idraca = r.idraca
                WHERE p.idpost = :idpost"; 

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public static function listarPostsSeguidos($idusuario)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT p.*, u.nome as nome_usuario, u.foto as foto_usuario, 
                c.nome as nome_cachorro, c.foto as foto_cachorro, r.nome as raca_cachorro
                FROM posts p
                INNER JOIN usuarios u ON p.idusuario = u.idusuario
                INNER JOIN cachorros c ON p.idcachorro = c.idcachorro
                INNER JOIN racas r ON c.idraca = r.idraca
                WHERE p.idusuario IN (
                    SELECT idseguido FROM seguidos WHERE idusuario = :idusuario
                    UNION
                    SELECT :idusuario
                )
                ORDER BY p.data_criacao DESC";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPostsUsuario($idusuario)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT p.*, u.nome as nome_usuario, u.foto as foto_usuario,
                c.nome as nome_cachorro, c.foto as foto_cachorro, r.nome as raca_cachorro
                FROM posts p
                INNER JOIN usuarios u ON p.idusuario = u.idusuario
                INNER JOIN cachorros c ON p.idcachorro = c.idcachorro
                INNER JOIN racas r ON c.idraca = r.idraca
                WHERE p.idusuario = :idusuario
                ORDER BY p.data_criacao DESC";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function listarCachorrosUsuario($idusuario)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT idcachorro, nome FROM cachorros WHERE idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
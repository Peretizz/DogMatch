<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class PostDAO
{
    /**
     * Cria um novo post no banco de dados.
     */
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

        // ATENÇÃO: A tabela posts deve ter uma coluna chamada 'idpost' ou 'idposts'
        // Assumindo que a coluna de ID é auto-incremento e não precisa ser passada aqui.
        $sql = "INSERT INTO posts (conteudo, foto, idusuario, idcachorro, data_criacao) 
                VALUES (:conteudo, :foto, :idusuario, :idcachorro, NOW())";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':conteudo', $conteudo);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idcachorro', $idcachorro);

        return $stmt->execute();
    }

    /**
     * Busca um post específico pelo seu ID.
     * Necessário para a tela visualizar-post.php.
     */
    public static function buscarPorId($idpost)
    {
        $conexao = ConexaoBD::conectar();

        // Faz o JOIN para trazer os dados do usuário, cachorro e raça
        $sql = "SELECT p.*, u.nome as nome_usuario, u.foto as foto_usuario,
                       c.nome as nome_cachorro, c.foto as foto_cachorro, r.nome as raca_cachorro
                FROM posts p
                INNER JOIN usuarios u ON p.idusuario = u.idusuario
                -- LEFT JOIN para que o post seja exibido mesmo sem um cachorro associado
                LEFT JOIN cachorros c ON p.idcachorro = c.idcachorro
                LEFT JOIN racas r ON c.idraca = r.idraca
                WHERE p.idpost = :idpost"; // Usando 'idpost'

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idpost', $idpost, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna apenas um registro
    }

    /**
     * Lista os posts de usuários seguidos e do próprio usuário.
     */
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

    /**
     * Lista todos os posts de um usuário específico.
     */
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

    // Este método foi mantido do seu código, embora um DogDAO ou UsuarioDAO 
    // pudesse ser um lugar mais apropriado para a listagem de cachorros.
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
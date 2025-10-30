<?php
require_once "ConexaoBD.php";

class CachorroDAO
{
    public static function cadastrarCachorro($dados)
    {
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $idraca = $dados['idraca'];
        $peso = $dados['peso'];
        $vacinacao = $dados['vacinacao'];
        $idade = $dados['idade'];
        $idsexo = $dados['idsexo'];
        $idusuario = $_SESSION['idusuario'];

        $foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        }

        $sql = "INSERT INTO cachorros (nome, foto, peso, idade, idsexo, idusuario, idraca) 
                VALUES (:nome, :foto, :peso, :idade, :idsexo, :idusuario, :idraca)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':idsexo', $idsexo);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idraca', $idraca);

        $stmt->execute();
    }

    public static function listar()
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT c.*, r.nome as raca, s.sexo as sexo
                FROM cachorros c 
                LEFT JOIN racas r ON c.idraca = r.idraca
                LEFT JOIN sexos s ON c.idsexo = s.idsexo";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarRacas()
    {
        $conexao = ConexaoBD::conectar();

        // Agora retorna as raças ordenadas pelo nome
        $sql = "SELECT * FROM racas ORDER BY nome ASC";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarSexos()
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT * FROM sexos";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarCachorros($termo)
    {
        $conexao = ConexaoBD::conectar();

        // Usamos LIKE para buscar o termo em qualquer parte do nome ou raça
        $termoBusca = '%' . $termo . '%';

        $sql = "SELECT c.*, r.nome as raca, u.nome as nome_usuario, s.sexo as sexo
                FROM cachorros c 
                LEFT JOIN racas r ON c.idraca = r.idraca
                LEFT JOIN usuarios u ON c.idusuario = u.idusuario
                LEFT JOIN sexos s ON c.idsexo = s.idsexo
                WHERE c.nome LIKE :termoBusca 
                OR r.nome LIKE :termoBusca";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':termoBusca', $termoBusca);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function excluirCachorro($idcachorro, $idusuario_logado)
    {
        $conexao = ConexaoBD::conectar();
        // Importante: Verifica se o cachorro pertence ao usuário logado antes de excluir
        $sql = "DELETE FROM cachorros WHERE idcachorro = :idcachorro AND idusuario = :idusuario";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idcachorro', $idcachorro, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idusuario_logado, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Lista todos os cachorros pertencentes a um usuário específico.
     */
    public static function listarCachorrosUsuario($idusuario)
    {
        $conexao = ConexaoBD::conectar();

        $sql = "SELECT c.*, r.nome as raca, s.sexo as sexo
            FROM cachorros c 
            LEFT JOIN racas r ON c.idraca = r.idraca
            LEFT JOIN sexos s ON c.idsexo = s.idsexo
            WHERE c.idusuario = :idusuario";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

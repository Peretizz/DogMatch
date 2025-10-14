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
        $idusuario = $_SESSION['idusuario'];

        $foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        }

        $sql = "INSERT INTO cachorros (nome, foto, peso, vacinacao, idade, idusuario, idraca) 
                VALUES (:nome, :foto, :peso, :vacinacao, :idade, :idusuario, :idraca)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':vacinacao', $vacinacao);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idraca', $idraca);

        $stmt->execute();
    }

    public static function listar()
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT c.*, r.nome as raca 
                FROM cachorros c 
                LEFT JOIN racas r ON c.idraca = r.idraca";
        
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function listarRacas()
    {
        $conexao = ConexaoBD::conectar();
        
        $sql = "SELECT * FROM racas";
        
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php
require_once "ConexaoBD.php";
require_once "Util.php";

class CachorroDAO
{


    public static function cadastrarCachorro($dados)
    {
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $peso = $dados['peso'];
        $raca = $dados['raca'];
        $idade = $dados['idade'];

        $foto = Util::salvarArquivo('foto');

        $vacinacao = Util::salvarArquivo('vacinacao');


        $sql = "INSERT INTO cachorros (nome, foto, peso, raça, vacinacao, idade) 
                 VALUES (:nome, :foto, :peso, :raca, :vacinacao, :idade)";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':vacinacao', $vacinacao);
        $stmt->bindParam(':idade', $idade);

        return $stmt->execute(); 
    }

    public static function listar()
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT * FROM cachorros";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $cachorros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cachorros;
    }

}
?>
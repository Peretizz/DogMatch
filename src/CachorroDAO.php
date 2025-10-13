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
        $idade = $dados['idade'];

        $foto = Util::salvarArquivo('foto');

        $vacinacao = Util::salvarArquivo('vacinacao');

        if ($foto === false) {
            $foto = null;
        }
        if ($vacinacao === false) {
            $vacinacao = null;
        }

        session_start();
        $idusuario = $_SESSION['idusuario'];
        $idraca = $idade = $dados['idraca'];;



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

        return $stmt->execute(); 
    }

    public static function listar()
    {
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT * FROM cachorros where cachorros.idraca = racas.idraca";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $cachorros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cachorros;
    }

}
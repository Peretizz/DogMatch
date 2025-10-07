<?php
require_once "ConexaoBD.php";
require_once "Util.php"; 

class CachorroDAO{

    
    public static function cadastrarCachorro($dados){ 
        $conexao = ConexaoBD::conectar();

        $nome = $dados['nome'];
        $peso = $dados['peso'];
        $raca = $dados['raca'];
        $idade = $dados['idade'];
        
        // Chamada correta: passando o nome do campo de arquivo para a função Util::salvarArquivo()
        $foto = Util::salvarArquivo('foto'); // Verifica e move o arquivo do campo 'foto'
        
        // Segunda chamada correta: passando o nome do segundo campo de arquivo
        $vacinacao = Util::salvarArquivo('vacinacao'); // Verifica e move o arquivo do campo 'vacinacao'

        // Adicionado fallback para evitar erro no DB se o upload falhar
        if ($foto === false) {
             // Trate o erro de foto aqui (ex: defina um valor padrão ou lance exceção)
             $foto = null; // Ou um nome de imagem padrão 'default.jpg'
        }
        if ($vacinacao === false) {
             // Trate o erro de vacinação aqui
             $vacinacao = null; // Ou um nome de imagem padrão 'default.jpg'
        }


        $sql = "INSERT INTO cachorros (nome, foto, peso, raça, vacinacao, idade) 
                 VALUES (:nome, :foto, :peso, :raca, :vacinacao, :idade)";
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':vacinacao', $vacinacao); 
        $stmt->bindParam(':idade', $idade);
        
        return $stmt->execute(); // Retorna o resultado da execução
    }

    public static function listar(){
        $conexao = ConexaoBD::conectar();
        $sql = "SELECT * FROM cachorros";

        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $cachorros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cachorros;
    }

}
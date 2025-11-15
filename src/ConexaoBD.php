<?php
class ConexaoBD{

    public static function conectar():PDO{
        $conexao = new PDO("mysql:host=localhost;dbname=dogmatch;charset=utf8mb4","root","");
        return $conexao;
    }
}

ConexaoBD::conectar();
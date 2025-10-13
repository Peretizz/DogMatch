<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";

$dados = $_POST;
$dados['idusuario'] = $_SESSION['idusuario'];

PostDAO::criarPost($dados);

$_SESSION['msg'] = "Post criado com sucesso!";
header("Location: index.php");
exit;
?>

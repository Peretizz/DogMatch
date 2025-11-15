<?php
include("incs/valida-sessao.php");
require_once "src/SeguidoDAO.php";

if (isset($_GET["idseguido"])) {
    SeguidoDAO::deixarDeSeguir($_SESSION["idusuario"], $_GET["idseguido"]);
}

header("location:usuarios.php");
exit;
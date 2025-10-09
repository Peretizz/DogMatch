<?php
   session_start();
   require_once "src/CachorroDAO.php";
   require_once "src/DonoDAO.php";

   CachorroDAO::cadastrarCachorro($_POST);
   $_SESSION['msg'] = "Cachorro cadastrado com sucesso!";
   header("Location: index.php");
?>

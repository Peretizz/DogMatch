<?php
   session_start();
   require_once "src/CachorroDAO.php";

   CachorroDAO::cadastrarCachorro($_POST, $_FILES);
   $_SESSION['msg'] = "Cachorro cadastrado com sucesso!";
   header("Location: index.php");
?>

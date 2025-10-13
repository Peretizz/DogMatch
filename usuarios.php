<?php
include("incs/valida-sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minha Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/globals.css">
</head>

<body>
    <div class="container" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
        <h3 style="color: #1F509A; margin-bottom: 1.5rem;">Adicione Seguidores</h3>

        <form>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" style="margin-bottom: 0.75rem;"
                    required>
            </div>

            <button class="search btn-primary" type="submit"
                style="display: inline-block; width: auto; margin-bottom: 1.5rem;">Buscar</button>
        </form>


        <div class="user-list" style="margin-top: 1.5rem;">
            <?php
            require_once "src/UsuarioDAO.php";

            if (!isset($_GET["nome"])) {
                $_GET["nome"] = "";
                $usuarios = [];
            }

            $usuarios = UsuarioDAO::buscarUsuarioNome($_GET["nome"], $_SESSION["idusuario"]);

            require_once "src/SeguidoDAO.php";

            foreach ($usuarios as $usuario) {
                $segue = SeguidoDAO::jaSegue($_SESSION["idusuario"], $usuario["idusuario"]);
                ?>

                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding: 0.75rem; background-color: white; border-radius: 0.375rem; border: 1px solid #DE720D;">

                    <span class="mx-3"><?= $usuario["nome"] ?></span>

                    <div>
                        <?php if ($segue) { ?>
                            <a href="parar-seguir.php?idseguido=<?= $usuario["idusuario"] ?>" class="btn btn-primary"
                                style="border: 1px solid #DE720D;   display: inline-block; width: auto; padding: 0.5rem 1rem; background-color: rgba(222, 114, 13, 0.8);">Deixar
                                de Seguir</a>
                        <?php } else { ?>
                            <a href="seguir.php?idseguido=<?= $usuario["idusuario"] ?>" class="btn btn-primary"
                                style="border: 1px solid #DE720D;   display: inline-block; width: auto; padding: 0.5rem 1rem; background-color: rgba(222, 114, 13, 0.8);">Seguir</a>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>

</html>
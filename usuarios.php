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


        <div class="form-group" style="margin-bottom: 1rem;">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" placeholder="Nome" style="margin-bottom: 0.75rem;">
        </div>


        <button class="search btn-primary" type="button"
            style="display: inline-block; width: auto; margin-bottom: 1.5rem;">Buscar</button>

        <br>


        <div class="user-list" style="margin-top: 1.5rem;">
            <?php
            require_once "src/UsuarioDAO.php";
            $usuarios = UsuarioDAO::listar();
            foreach ($usuarios as $usuario) {
                ?>  
            
                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding: 0.75rem; background-color: white; border-radius: 0.375rem; border: 1px solid #1F509A;">
            <a href="seguir.php?idseguido=<?= $usuario["nome"] ?>" class="btn btn-success mx-3"><?= $usuario["nome"] ?></a>
                    <button type="button" class="btn-primary"
                        style="display: inline-block; width: auto; padding: 0.5rem 1rem;">Adicionar</button>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>

</html>
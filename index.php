<?php
include("incs/valida-sessao.php");
require_once "src/UsuarioDAO.php";
require_once "src/PostDAO.php";
require_once "src/SeguidoDAO.php";

$idusuario = $_SESSION['idusuario'];
$usuario = UsuarioDAO::buscarPorId($idusuario);

$posts = PostDAO::listarPostsSeguidos($idusuario);

$sugestoes = [];
$todosUsuarios = UsuarioDAO::listarUsuarios($idusuario);
foreach ($todosUsuarios as $u) {
    if (!SeguidoDAO::jaSegue($idusuario, $u['idusuario'])) {
        $sugestoes[] = $u;
        if (count($sugestoes) >= 8) {
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogMatch - Feed</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="feed-container">
        <!-- Sidebar Esquerda -->
        <aside class="feed-sidebar">
            <div class="feed-logo">
                <img src="img/logo.png" alt="DogMatch Logo">
            </div>
            
            <nav class="feed-nav">
                <a href="index.php" class="feed-nav-item active">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="feed-nav-item">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notificações</span>
                </a>
                <a href="form-criar-post.php" class="feed-nav-item">
                    <i class="bi bi-plus-square-fill"></i>
                    <span>Postar</span>
                </a>
                <a href="#" class="feed-nav-item">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Mensagens</span>
                </a>
            </nav>
            
            <div class="feed-sidebar-footer">
                <a href="form-cadastro-cachorro.php" class="btn-cadastrar-cachorro">
                    Cadastrar Cachorro
                </a>
            </div>
        </aside>

        <!-- Área Central - Feed -->
        <main class="feed-main">
            <!-- Barra de Pesquisa -->
            <div class="feed-search-container">
                <div class="feed-search-wrapper">
                    <i class="bi bi-search"></i>
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="feed-search-input" 
                        placeholder="Buscar pessoas..."
                        autocomplete="off"
                    >
                </div>
                <div id="searchResults" class="feed-search-results"></div>
            </div>

            <!-- Header do Feed -->
            <div class="feed-header">
                <div class="feed-user-info">
                    <?php if (!empty($usuario['foto'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Perfil" class="feed-user-avatar">
                    <?php } else { ?>
                        <div class="feed-user-avatar-placeholder">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php } ?>
                    <h2><?= htmlspecialchars($usuario['nome']) ?></h2>
                </div>
            </div>

            <!-- Posts -->
            <div class="feed-posts">
                <?php if (empty($posts)) { ?>
                    <div class="feed-empty">
                        <p>Nenhum post para mostrar. Siga outros usuários para ver posts no seu feed!</p>
                        <a href="usuarios.php" class="btn-ver-mais">Encontrar Pessoas</a>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { ?>
                        <article class="feed-post">
                            <div class="feed-post-header">
                                <?php if (!empty($post['foto_usuario'])) { ?>
                                    <img src="uploads/<?= htmlspecialchars($post['foto_usuario']) ?>" alt="Perfil" class="feed-post-avatar">
                                <?php } else { ?>
                                    <div class="feed-post-avatar-placeholder">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                <?php } ?>
                                <div class="feed-post-user">
                                    <a href="perfil.php?idusuario=<?= $post['idusuario'] ?>" class="feed-post-username">
                                        <?= htmlspecialchars($post['nome_usuario']) ?>
                                    </a>
                                    <span class="feed-post-date"><?= date('d/m/Y H:i', strtotime($post['data'])) ?></span>
                                </div>
                            </div>

                            <?php if (!empty($post['descricao'])) { ?>
                                <div class="feed-post-content">
                                    <p><?= nl2br(htmlspecialchars($post['descricao'])) ?></p>
                                </div>
                            <?php } ?>

                            <?php if (!empty($post['foto'])) { ?>
                                <div class="feed-post-image">
                                    <img src="uploads/<?= htmlspecialchars($post['foto']) ?>" alt="Post">
                                </div>
                            <?php } ?>

                            <div class="feed-post-actions">
                                <button class="feed-action-btn">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="feed-action-btn">
                                    <i class="bi bi-chat"></i>
                                </button>
                                <button class="feed-action-btn">
                                    <i class="bi bi-share"></i>
                                </button>
                            </div>
                        </article>
                    <?php } ?>
                <?php } ?>
            </div>
        </main>

        <!-- Sidebar Direita - Sugestões -->
        <aside class="feed-suggestions">
            <div class="feed-suggestions-header">
                <div class="feed-current-user">
                    <?php if (!empty($usuario['foto'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Perfil" class="feed-current-user-avatar">
                    <?php } else { ?>
                        <div class="feed-current-user-avatar-placeholder">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php } ?>
                    <span><?= htmlspecialchars($usuario['nome']) ?></span>
                </div>
            </div>

            <div class="feed-suggestions-title">
                <h3>Sugestões para você:</h3>
            </div>

            <div class="feed-suggestions-list">
                <?php if (empty($sugestoes)) { ?>
                    <p class="feed-no-suggestions">Você já segue todos os usuários!</p>
                <?php } else { ?>
                    <?php foreach ($sugestoes as $sugestao) { ?>
                        <div class="feed-suggestion-item">
                            <a href="perfil.php?idusuario=<?= $sugestao['idusuario'] ?>" class="feed-suggestion-user">
                                <?php if (!empty($sugestao['foto'])) { ?>
                                    <img src="uploads/<?= htmlspecialchars($sugestao['foto']) ?>" alt="Perfil" class="feed-suggestion-avatar">
                                <?php } else { ?>
                                    <div class="feed-suggestion-avatar-placeholder">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                <?php } ?>
                                <div class="feed-suggestion-info">
                                    <span class="feed-suggestion-name"><?= htmlspecialchars($sugestao['nome']) ?></span>
                                    <span class="feed-suggestion-username">@<?= htmlspecialchars(strtolower(str_replace(' ', '', $sugestao['nome']))) ?></span>
                                </div>
                            </a>
                            <a href="seguir.php?idseguido=<?= $sugestao['idusuario'] ?>" class="feed-btn-seguir">
                                Seguir
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="feed-suggestions-footer">
                <a href="usuarios.php" class="btn-ver-mais">Ver Mais</a>
            </div>
        </aside>
    </div>

    <script>
        // Busca em tempo real
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`buscar-usuarios.php?nome=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            searchResults.innerHTML = '<div class="feed-search-empty">Nenhum usuário encontrado</div>';
                            searchResults.style.display = 'block';
                        } else {
                            let html = '';
                            data.forEach(usuario => {
                                const fotoHtml = usuario.foto 
                                    ? `<img src="uploads/${usuario.foto}" alt="Perfil" class="feed-search-result-avatar">`
                                    : `<div class="feed-search-result-avatar-placeholder"><i class="bi bi-person-circle"></i></div>`;
                                
                                const seguirBtn = !usuario.ja_segue 
                                    ? `<a href="seguir.php?idseguido=${usuario.idusuario}" class="feed-search-result-btn">Seguir</a>`
                                    : '';

                                html += `
                                    <div class="feed-search-result-item">
                                        <a href="perfil.php?idusuario=${usuario.idusuario}" class="feed-search-result-user">
                                            ${fotoHtml}
                                            <div class="feed-search-result-info">
                                                <span class="feed-search-result-name">${usuario.nome}</span>
                                                <span class="feed-search-result-username">@${usuario.nome.toLowerCase().replace(/\s/g, '')}</span>
                                            </div>
                                        </a>
                                        ${seguirBtn}
                                    </div>
                                `;
                            });
                            searchResults.innerHTML = html;
                            searchResults.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Erro na busca:', error);
                        searchResults.innerHTML = '<div class="feed-search-empty">Erro ao buscar usuários</div>';
                        searchResults.style.display = 'block';
                    });
            }, 300);
        });

        // Fechar resultados ao clicar fora
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    </script>
</body>
</html>

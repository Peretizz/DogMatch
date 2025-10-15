<?php
include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/PostDAO.php";
require_once "src/SeguidoDAO.php";

$idusuario = $_SESSION['idusuario'];
$usuario = UsuarioDAO::buscarPorId($idusuario);

// Buscar sugestões de usuários para seguir (limitado a 8)
$sugestoes = UsuarioDAO::buscarSugestoes($idusuario, 8);

// Buscar posts dos usuários seguidos
$posts = PostDAO::listarPostsSeguidos($idusuario);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogMatch - Feed</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="feed-container">
        <aside class="feed-sidebar">
            <div class="feed-logo">
                <img src="img/logo.png" alt="DogMatch" onerror="this.style.display='none'">
                <h2>DogMatch</h2>
            </div>
            
            <nav class="feed-nav">
                <a href="index.php" class="feed-nav-item active">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Home</span>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                
                <a href="notificacoes.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span>Notificações</span>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                
                <a href="form-criar-post.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Postar</span>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                
                <a href="mensagens.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Mensagens</span>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
            </nav>
            
            <a href="form-cadastro-cachorro.php" class="feed-btn-cadastrar">Cadastrar Cachorro</a>
        </aside>

        <main class="feed-main">
            <div class="feed-search-container">
                <div class="feed-search-wrapper">
                    <svg class="feed-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="feed-search-input" 
                        placeholder="Procurar pessoas..."
                        autocomplete="off"
                    >
                </div>
                <div id="searchResults" class="feed-search-results" style="display: none;"></div>
            </div>

            <div class="feed-header">
                <div class="feed-user-info">
                    <?php if (!empty($usuario['foto']) && file_exists("uploads/" . $usuario['foto'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" class="feed-user-avatar">
                    <?php } else { ?>
                        <div class="feed-user-avatar-placeholder">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    <?php } ?>
                    <h2><?= htmlspecialchars($usuario['nome']) ?></h2>
                </div>
            </div>

            <div class="feed-posts">
                <?php if (empty($posts)) { ?>
                    <div class="feed-empty">
                        <p>Nenhum post para mostrar. Siga outros usuários para ver posts no seu feed!</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { ?>
                        <article class="feed-post">
                            <div class="feed-post-header">
                                <?php if (!empty($post['foto_usuario']) && file_exists("uploads/" . $post['foto_usuario'])) { ?>
                                    <img src="uploads/<?= htmlspecialchars($post['foto_usuario']) ?>" alt="Foto de perfil" class="feed-post-avatar">
                                <?php } else { ?>
                                    <div class="feed-post-avatar-placeholder">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                <?php } ?>
                                <div class="feed-post-user">
                                    <a href="perfil.php?idusuario=<?= htmlspecialchars($post['idusuario']) ?>">
                                        <h3><?= htmlspecialchars($post['nome_usuario']) ?></h3>
                                    </a>
                                    <span class="feed-post-date">
                                        <?php 
                                        if (isset($post['data']) && !empty($post['data']) && $post['data'] != '0000-00-00 00:00:00') {
                                            $timestamp = strtotime($post['data']);
                                            if ($timestamp > 0) {
                                                echo date('d/m/Y H:i', $timestamp);
                                            } else {
                                                echo 'Data não disponível';
                                            }
                                        } else {
                                            echo 'Data não disponível';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if (!empty($post['texto'])) { ?>
                                <p class="feed-post-text"><?= nl2br(htmlspecialchars($post['texto'])) ?></p>
                            <?php } ?>
                            
                            <?php if (!empty($post['foto']) && file_exists("uploads/" . $post['foto'])) { ?>
                                <div class="feed-post-image">
                                    <img src="uploads/<?= htmlspecialchars($post['foto']) ?>" alt="Post">
                                </div>
                            <?php } ?>
                            
                            <div class="feed-post-actions">
                                <button class="feed-action-btn">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </button>
                                <button class="feed-action-btn">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </button>
                                <button class="feed-action-btn">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                        <polyline points="16 6 12 2 8 6"></polyline>
                                        <line x1="12" y1="2" x2="12" y2="15"></line>
                                    </svg>
                                </button>
                            </div>
                        </article>
                    <?php } ?>
                <?php } ?>
            </div>
        </main>

        <aside class="feed-suggestions">
            <div class="feed-suggestions-header">
                <?php if (!empty($usuario['foto']) && file_exists("uploads/" . $usuario['foto'])) { ?>
                    <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" class="feed-suggestions-avatar">
                <?php } else { ?>
                    <div class="feed-suggestions-avatar-placeholder">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                <?php } ?>
                <a href="perfil.php?idusuario=<?= htmlspecialchars($idusuario) ?>">
                    <span><?= htmlspecialchars($usuario['nome']) ?></span>
                </a>
            </div>
            
            <h3 class="feed-suggestions-title">Sugestões para você:</h3>
            
            <div class="feed-suggestions-list">
                <?php if (empty($sugestoes)) { ?>
                    <p class="feed-suggestions-empty">Nenhuma sugestão no momento</p>
                <?php } else { ?>
                    <?php foreach ($sugestoes as $sugestao) { ?>
                        <div class="feed-suggestion-item">
                            <?php if (!empty($sugestao['foto']) && file_exists("uploads/" . $sugestao['foto'])) { ?>
                                <img src="uploads/<?= htmlspecialchars($sugestao['foto']) ?>" alt="Foto de perfil" class="feed-suggestion-avatar">
                            <?php } else { ?>
                                <div class="feed-suggestion-avatar-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            <?php } ?>
                            <div class="feed-suggestion-info">
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($sugestao['idusuario']) ?>" class="feed-suggestion-name-link">
                                    <span class="feed-suggestion-name"><?= htmlspecialchars($sugestao['nome']) ?></span>
                                </a>
                            </div>
                            <a href="seguir.php?idseguido=<?= $sugestao['idusuario'] ?>" class="feed-btn-seguir">Seguir</a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            
            <a href="usuarios.php" class="feed-btn-ver-mais">Ver Mais</a>
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
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`buscar-usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na requisição: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.length === 0) {
                            searchResults.innerHTML = '<div class="feed-search-empty">Nenhum usuário encontrado</div>';
                            searchResults.style.display = 'block';
                            return;
                        }
                        
                        let html = '';
                        data.forEach(user => {
                            // VERIFICA SE O CAMPO 'foto' ESTÁ PRESENTE E NÃO ESTÁ VAZIO
                            const temFoto = user.foto && user.foto.trim() !== '';
                            
                            const fotoHtml = temFoto 
                                ? `<img src="uploads/${user.foto}" alt="${user.nome}" class="feed-search-result-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`
                                : '';
                            
                            const placeholderHtml = `
                                <div class="feed-search-result-avatar-placeholder" style="${temFoto ? 'display: none;' : ''}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            `;
                            
                            html += `
                                <div class="feed-search-result-item">
                                    <a href="perfil.php?idusuario=${user.idusuario}" class="feed-search-result-link">
                                        ${fotoHtml}
                                        ${placeholderHtml}
                                        <div class="feed-search-result-info">
                                            <span class="feed-search-result-name">${user.nome}</span>
                                        </div>
                                    </a>
                                    ${!user.jaSeguindo ? `<a href="seguir.php?idseguido=${user.idusuario}" class="feed-btn-seguir-small">Seguir</a>` : ''}
                                </div>
                            `;
                        });
                        
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Erro na busca:', error);
                        // Opcional: mostrar uma mensagem de erro na interface
                        // searchResults.innerHTML = '<div class="feed-search-empty">Erro ao carregar dados.</div>';
                        // searchResults.style.display = 'block';
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
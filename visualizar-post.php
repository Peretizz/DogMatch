<?php
include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/PostDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/CurtidaDAO.php";
require_once "src/ComentarioDAO.php";
require_once "src/MensagemDAO.php";

$idusuario = $_SESSION['idusuario'];
$usuario = UsuarioDAO::buscarPorId($idusuario);

$sugestoes = UsuarioDAO::buscarSugestoes($idusuario, 8);

$mensagens_nao_lidas = MensagemDAO::contarMensagensNaoLidas($idusuario);

$idpost = $_GET['idpost'] ?? null;
if (!$idpost) {
    header("Location: index.php");
    exit;
}
$post = PostDAO::buscarPorId($idpost);
if (!$post) {
    header("Location: index.php");
    exit;
}

$posts = [$post];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Post - DogMatch</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="feed-container">
        <aside class="feed-sidebar">
            <div class="feed-logo">
                <img src="img/logo.png" alt="DogMatch">
            </div>

            <nav class="feed-nav">
                <a href="index.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>    
                    </svg>
                    <span>Home</span>
                </a>

                <a href="form-criar-post.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Postar</span>
                </a>

                <a href="mensagens.php" class="feed-nav-item" style="position: relative;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Mensagens</span>
                    <?php if ($mensagens_nao_lidas > 0) { ?>
                        <span style="position: absolute; top: 8px; left: 20px; background: #DE720D; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">
                            <?= $mensagens_nao_lidas > 9 ? '9+' : $mensagens_nao_lidas ?>
                        </span>
                    <?php } ?>
                </a>

                <a href="buscar-cachorros.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Buscar Cães</span>
                </a>
                <a href="perfil.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Perfil</span>
                </a>
                <a href="logout.php" class="feed-nav-item" onclick="return confirm('Tem certeza de que deseja sair?');">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Sair</span>
                </a>
            </nav>

            <a href="form-cadastro-cachorro.php" class="feed-btn-cadastrar">Cadastrar Cachorro</a>
        </aside>

        <main class="feed-main">
            <div class="feed-search-container">
                <div class="feed-search-wrapper">
                    <svg class="feed-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="searchInput" class="feed-search-input" placeholder="Procurar pessoas..."
                        autocomplete="off">
                </div>
                <div id="searchResults" class="feed-search-results" style="display: none;"></div>
            </div>

            <div class="feed-posts">
                <?php if (empty($posts)) { ?>
                    <div class="feed-empty">
                        <p>Post não encontrado.</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { 
                        $totalCurtidas = CurtidaDAO::contarCurtidas($post['idpost']);
                        $jaCurtiu = CurtidaDAO::jaCurtiu($post['idpost'], $idusuario);
                        $totalComentarios = ComentarioDAO::contarComentarios($post['idpost']);
                    ?>
                       <?php
/**
 * Template para exibir um item de post com informações do cachorro
 * Variáveis esperadas: $post, $idusuario, $totalCurtidas, $jaCurtiu, $totalComentarios
 */
error_log("[v0] Post data - nome_cachorro: " . ($post['nome_cachorro'] ?? 'null') . ", idraca: " . ($post['idraca'] ?? 'null') . ", raca: " . ($post['raca'] ?? 'null'));
?>
<article class="feed-post" data-post-id="<?= $post['idpost'] ?>">
    <div class="feed-post-header">
        <?php if (!empty($post['foto_usuario']) && file_exists("uploads/" . $post['foto_usuario'])) { ?>
            <img src="uploads/<?= htmlspecialchars($post['foto_usuario']) ?>" alt="Foto de perfil"
                class="feed-post-avatar">
        <?php } else { ?>
            <div class="feed-post-avatar-placeholder">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        <?php } ?>
        <div class="feed-post-user">
            <a href="perfil.php?idusuario=<?= htmlspecialchars($post['idusuario']) ?>">
                <span class="feed-post-username"><?= htmlspecialchars($post['nome_usuario']) ?></span>
            </a>
            <?php if (!empty($post['nome_cachorro'])) { ?>
                                        <span class="feed-post-dog-info">
                                            <?= htmlspecialchars($post['nome_cachorro']) ?> •
                                            <?= htmlspecialchars($post['raca'] ?? 'Raça não informada') ?>
                                            <?php
                                            if (isset($post['sexo_cachorro']) && !empty($post['sexo_cachorro'])) {
                                            ?>
                                                •
                                                <span style="color: <?= $post['sexo_cachorro'] == 'Macho' ? '#1E6BA8' : '#E91E63' ?>; font-weight: bold;">
                                                    <?= $post['sexo_cachorro'] == 'Macho' ? '♂ Macho' : '♀ Fêmea' ?>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    <?php } ?>
            <span class="feed-post-date">
                <?php
                if (isset($post['data_criacao']) && !empty($post['data_criacao']) && $post['data_criacao'] != '0000-00-00 00:00:00') {
                    $timestamp = strtotime($post['data_criacao']);
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
        <?php if (isset($post['idusuario']) && $post['idusuario'] == $idusuario) { ?>
            <button class="feed-post-delete" data-post-id="<?= $post['idpost'] ?>" title="Excluir post">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        <?php } ?>
    </div>

    <?php if (!empty($post['conteudo'])) { ?>
        <p class="feed-post-text"><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
    <?php } ?>

    <?php if (!empty($post['foto']) && file_exists("uploads/" . $post['foto'])) { ?>
        <div class="feed-post-image">
            <img src="uploads/<?= htmlspecialchars($post['foto']) ?>" alt="Post">
        </div>
    <?php } ?>

    <div class="feed-post-actions">
        <button class="feed-action-btn btn-curtir <?= $jaCurtiu ? 'curtido' : '' ?>" 
                data-post-id="<?= $post['idpost'] ?>"
                title="<?= $jaCurtiu ? 'Descurtir' : 'Curtir' ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="<?= $jaCurtiu ? 'currentColor' : 'none' ?>" 
                 stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <span class="contador-curtidas"><?= $totalCurtidas > 0 ? $totalCurtidas : '' ?></span>
        </button>
        <button class="feed-action-btn btn-comentar" 
                data-post-id="<?= $post['idpost'] ?>"
                title="Comentar">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span class="contador-comentarios"><?= $totalComentarios > 0 ? $totalComentarios : '' ?></span>
        </button>
    </div>

    <div class="feed-post-comentarios" id="comentarios-<?= $post['idpost'] ?>" style="display: none;">
        <div class="comentarios-lista"></div>
        <div class="comentario-form">
            <input type="text" 
                   class="comentario-input" 
                   placeholder="Escreva um comentário..."
                   data-post-id="<?= $post['idpost'] ?>">
            <button class="comentario-enviar" data-post-id="<?= $post['idpost'] ?>">Enviar</button>
        </div>
    </div>
</article>


                    <?php } ?>
                <?php } ?>
            </div>
        </main>

        <aside class="feed-suggestions">
            <div class="feed-suggestions-header">
                <?php if (!empty($usuario['foto']) && file_exists("uploads/" . $usuario['foto'])) { ?>
                    <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil"
                        class="feed-suggestions-avatar">
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
                                <img src="uploads/<?= htmlspecialchars($sugestao['foto']) ?>" alt="Foto de perfil"
                                    class="feed-suggestion-avatar">
                            <?php } else { ?>
                                <div class="feed-suggestion-avatar-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            <?php } ?>
                            <div class="feed-suggestion-info">
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($sugestao['idusuario']) ?>"
                                    class="feed-suggestion-name-link">
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
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`buscar-usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            searchResults.innerHTML = '<div class="feed-search-empty">Nenhum usuário encontrado</div>';
                            searchResults.style.display = 'block';
                            return;
                        }

                        let html = '';
                        data.forEach(user => {
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
                    .catch(error => console.error('Erro na busca:', error));
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });


        document.querySelectorAll('.feed-post-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Tem certeza que deseja excluir este post? Esta ação não pode ser desfeita.')) {
                    return;
                }
                
                const postId = this.dataset.postId;
                const formData = new FormData();
                formData.append('idpost', postId);
                
                fetch('excluir-post.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    } else {
                        alert(data.message || 'Erro ao excluir post');
                    }
                })
                .catch(error => console.error('Erro ao excluir post:', error));
            });
        });

        document.querySelectorAll('.btn-curtir').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const formData = new FormData();
                formData.append('idpost', postId);

                fetch('curtir-post.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const svg = this.querySelector('svg');
                        const contador = this.querySelector('.contador-curtidas');
                        
                        if (data.curtido) {
                            this.classList.add('curtido');
                            svg.setAttribute('fill', 'currentColor');
                            this.title = 'Descurtir';
                        } else {
                            this.classList.remove('curtido');
                            svg.setAttribute('fill', 'none');
                            this.title = 'Curtir';
                        }
                        
                        contador.textContent = data.total > 0 ? data.total : '';
                    }
                })
                .catch(error => console.error('Erro ao curtir:', error));
            });
        });

        document.querySelectorAll('.btn-comentar').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const comentariosDiv = document.getElementById(`comentarios-${postId}`);
                
                if (comentariosDiv.style.display === 'none') {
                    fetch(`listar-comentarios.php?idpost=${postId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const listaComentarios = comentariosDiv.querySelector('.comentarios-lista');
                                listaComentarios.innerHTML = '';
                                
                                data.comentarios.forEach(comentario => {
                                    let dataFormatada = 'Data não disponível';
                                    if (comentario.data_criacao && comentario.data_criacao !== '0000-00-00 00:00:00') {
                                        const timestamp = new Date(comentario.data_criacao).getTime();
                                        if (timestamp > 0) {
                                            dataFormatada = new Date(timestamp).toLocaleString('pt-BR', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            });
                                        }
                                    }
                                    
                                    const idusuarioLogado = <?= $idusuario ?>;
                                    const btnExcluir = comentario.idusuario == idusuarioLogado ? 
                                        `<button class="comentario-delete" data-comentario-id="${comentario.idcomentario}" title="Excluir comentário">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>` : '';
                                    
                                    const comentarioHtml = `
                                        <div class="comentario-item">
                                            <div class="comentario-header">
                                                ${comentario.foto_usuario ? 
                                                    `<img src="uploads/${comentario.foto_usuario}" alt="${comentario.nome_usuario}" class="comentario-avatar">` :
                                                    `<div class="comentario-avatar-placeholder">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="12" cy="7" r="4"></circle>
                                                        </svg>
                                                    </div>`
                                                }
                                                <span class="comentario-autor">${comentario.nome_usuario}</span>
                                                <span class="comentario-data">${dataFormatada}</span>
                                                ${btnExcluir}
                                            </div>
                                            <p class="comentario-texto">${comentario.conteudo}</p>
                                        </div>
                                    `;
                                    listaComentarios.innerHTML += comentarioHtml;
                                });
                                
                                comentariosDiv.style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Erro ao carregar comentários:', error));
                } else {
                    comentariosDiv.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.comentario-enviar').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const input = document.querySelector(`.comentario-input[data-post-id="${postId}"]`);
                const conteudo = input.value.trim();
                
                if (!conteudo) {
                    alert('Digite um comentário');
                    return;
                }
                
                const formData = new FormData();
                formData.append('idpost', postId);
                formData.append('conteudo', conteudo);
                
                fetch('adicionar-comentario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        const btnComentar = document.querySelector(`.btn-comentar[data-post-id="${postId}"]`);
                        const contador = btnComentar.querySelector('.contador-comentarios');
                        contador.textContent = data.total;
                        btnComentar.click();
                        btnComentar.click();
                    } else {
                        alert(data.message || 'Erro ao adicionar comentário');
                    }
                })
                .catch(error => console.error('Erro ao enviar comentário:', error));
            });
        });

        document.querySelectorAll('.comentario-input').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const postId = this.dataset.postId;
                    const btnEnviar = document.querySelector(`.comentario-enviar[data-post-id="${postId}"]`);
                    btnEnviar.click();
                }
            });
        });

        document.addEventListener('click', function(e) {
            const comentarioDeleteBtns = document.querySelectorAll('.comentario-delete');
            comentarioDeleteBtns.forEach(btn => {
                if (btn.contains(e.target)) {
                    if (!confirm('Tem certeza que deseja excluir este comentário?')) {
                        return;
                    }
                    
                    const comentarioId = btn.dataset.comentarioId;
                    const formData = new FormData();
                    formData.append('idcomentario', comentarioId);
                    
                    fetch('excluir-comentario.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const comentarioItem = btn.closest('.comentario-item');
                            comentarioItem.remove();
                            
                            const btnComentar = document.querySelector(`.btn-comentar[data-post-id="${data.postId}"]`);
                            const contador = btnComentar.querySelector('.contador-comentarios');
                            contador.textContent = data.total > 0 ? data.total : '';
                        } else {
                            alert(data.message || 'Erro ao excluir comentário');
                        }
                    })
                    .catch(error => console.error('Erro ao excluir comentário:', error));
                }
            });
        });
    </script>
</body>
</html>

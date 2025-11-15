<?php
include "incs/valida-sessao.php"; 
require_once "src/CachorroDAO.php";
require_once "src/MensagemDAO.php";

$termo_busca = $_GET['busca'] ?? '';
$resultados = array();

if (!empty($termo_busca)) {
    $resultados = CachorroDAO::buscarCachorros($termo_busca);
}

$idusuario_logado = $_SESSION['idusuario'];
$mensagens_nao_lidas = MensagemDAO::contarMensagensNaoLidas($idusuario_logado);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cães - DogMatch</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <span id="mensagens-badge" style="position: absolute; top: 8px; left: 20px; background: #DE720D; color: white; border-radius: 50%; width: 20px; height: 20px; display: <?= $mensagens_nao_lidas > 0 ? 'flex' : 'none' ?>; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">
                        <?= $mensagens_nao_lidas > 9 ? '9+' : $mensagens_nao_lidas ?>
                    </span>
                </a>
                
                <a href="buscar-cachorros.php" class="feed-nav-item active">
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

        <main class="feed-main" style="padding-top: 20px;">
            <div style="max-width: 700px; margin: 0 auto; padding: 0 20px;">
                <h1 style="color: #FFFFFF; margin-bottom: 20px;">Encontre seu Amigo</h1>

                <form action="buscar-cachorros.php" method="GET" class="mb-4">
                    <div style="display: flex; gap: 10px; align-items: center; border: 1px solid #ccc; border-radius: 8px; padding: 5px;">
                        <i class="bi bi-search" style="margin-left: 10px; color: #666;"></i>
                        <!-- Removed extra space from input value -->
                        <input type="search" name="busca" 
                               placeholder="Buscar por nome do cão ou raça..." 
                               value="<?= htmlspecialchars($termo_busca) ?>"
                               style="flex-grow: 1; padding: 10px; border: none; outline: none; border-radius: 8px;">
                        <button type="submit" style="padding: 10px 15px; background-color: #679DAB; color: white; border: none; border-radius: 6px; cursor: pointer;">
                            Buscar
                        </button>
                    </div>
                </form>

                <div class="search-results">
                    <?php if (empty($termo_busca)) { ?>
                        <p style="text-align: center; margin-top: 50px; color: #FFFFFF;">Digite um nome de cão ou raça para iniciar a busca.</p>
                    <?php } elseif (empty($resultados)) { ?>
                        <p style="text-align: center; margin-top: 50px; color: #FFFFFF; font-weight: bold;">Nenhum cão encontrado para "<?= htmlspecialchars($termo_busca) ?>".</p>
                    <?php } else { ?>
                        <p style="margin-bottom: 15px; color: #666;">
                            <?= count($resultados) ?> Cães encontrados para "<?= htmlspecialchars($termo_busca) ?>":
                        </p>

                        <?php foreach ($resultados as $cachorro) { ?>
                            <div class="dog-result-card" style="display: flex; align-items: center; margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #F8F4ED;">
                                
                                <?php 
                                $foto_url = !empty($cachorro['foto']) ? 'uploads/' . $cachorro['foto'] : 'img/dog_placeholder.png';
                                ?>
                                <img src="<?= $foto_url ?>" alt="<?= htmlspecialchars($cachorro['nome']) ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; margin-right: 15px;">
                                
                                <div style="flex-grow: 1;">
                                    <h3 style="margin: 0; font-size: 1.2rem; color: #679DAB;">
                                        <?= htmlspecialchars($cachorro['nome']) ?>
                                    </h3>
                                    <p style="margin: 0; color: #555;">
                                        Raça: <?= htmlspecialchars($cachorro['raca'] ?? 'Desconhecida') ?> | 
                                        Idade: <?= htmlspecialchars($cachorro['idade']) ?> anos | 
                                        Dono: <a href="perfil.php?idusuario=<?= htmlspecialchars($cachorro['idusuario']) ?>" style="color: #DE720D; text-decoration: none;"><?= htmlspecialchars($cachorro['nome_usuario']) ?></a>
                                    </p>
                                </div>
                                
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($cachorro['idusuario']) ?>" 
                                   style="padding: 8px 12px; background-color: #DE720D; color: white; border-radius: 6px; text-decoration: none;">
                                    Ver Dono
                                </a>

                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </main>
        
        <aside class="feed-suggestions" style="opacity: 0; pointer-events: none;">
        </aside>
    </div>

    <!-- Removed all console.log statements -->
    <script>
        function atualizarContadorMensagens() {
            fetch('api-contador-mensagens.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('mensagens-badge');
                        if (badge) {
                            if (data.contador > 0) {
                                badge.style.display = 'flex';
                                badge.textContent = data.contador > 9 ? '9+' : data.contador;
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => {});
        }

        atualizarContadorMensagens();
        setInterval(atualizarContadorMensagens, 5000);
    </script>
</body>
</html>

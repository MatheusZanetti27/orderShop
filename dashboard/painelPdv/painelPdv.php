<?php
session_start();

require_once '../../conexaoPhp/conexao.php'; // Ajuste o caminho para o seu arquivo de conexão

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login/login.html");
    exit();
}

// Se a URL enviou o ID do restaurante (?id=1), nós salvamos na sessão
if (isset($_GET['id'])) {
    $_SESSION['restaurante_ativo'] = $_GET['id'];
}

// Se ele tentar acessar o painel direto sem escolher a loja, expulsa ele
if (!isset($_SESSION['restaurante_ativo'])) {
    header("Location: ../meusRestaurantes/meusRestaurantes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel PDV - OrderShop</title>
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link rel="stylesheet" href="../css/pdv_style.css">
</head>
<body class="body-pdv">

    <nav class="navbar">
        <div class="nav-logo">
            <span class="logo-text">Order<span class="logo-highlight">Shop</span></span>
        </div>
        <div class="nav-user">
            <span>Restaurante Atual</span> 
            <a href="../meusRestaurantes/meusRestaurantes.php" class="btn-sair">Trocar Loja</a>
        </div>
    </nav>

    <main class="layout-pdv">
        
        <section class="area-menu">
            <div class="header-menu">
                <h2>Categorias</h2>
                <button class="btn-add-categoria" onclick="abrirModalCategoria()">
                    <span class="plus">+</span> Nova Categoria
                </button>
            </div>

            <!-- Área Dinâmica das Categorias -->
            <div class="lista-categorias">
                
                <?php
                // 1. Busca todas as categorias do restaurante atual
                // Ajuste o nome da variável de sessão se estiver usando restauranteAtivo
                $idRestaurante = $_SESSION['restaurante_ativo']; 
                
                $sqlCategorias = "SELECT * FROM categorias WHERE idRestaurante = ?";
                $stmtCat = $conn->prepare($sqlCategorias);
                $stmtCat->bind_param("i", $idRestaurante);
                $stmtCat->execute();
                $resultCat = $stmtCat->get_result();

                if ($resultCat->num_rows > 0) {
                    // Loop das Categorias
                    while ($categoria = $resultCat->fetch_assoc()) {
                        $idCategoria = $categoria['idCategoria'];
                        $nomeCategoria = htmlspecialchars($categoria['nomeCategoria']); // Proteção contra XSS
                        ?>
                        
                        <div class="bloco-categoria">
                            <div class="categoria-header">
                                <h3>🍽️ <?= $nomeCategoria ?></h3>
                                <!-- Passa o ID da categoria dinamicamente para o JS -->
                                <button class="btn-novo-item" onclick="abrirModalNovoItem(<?= $idCategoria ?>)">+ Novo Item</button>
                            </div>
                            
                            <div class="grid-itens">
                                <?php
                                // 2. Busca os itens (lanches) que pertencem a ESTA categoria
                                $sqlItens = "SELECT * FROM lanches WHERE idCategoria = ?";
                                $stmtItens = $conn->prepare($sqlItens);
                                $stmtItens->bind_param("i", $idCategoria);
                                $stmtItens->execute();
                                $resultItens = $stmtItens->get_result();

                                if ($resultItens->num_rows > 0) {
                                    // Loop dos Itens
                                    while ($item = $resultItens->fetch_assoc()) {
                                        $nomeLanche = htmlspecialchars($item['nomeLanche']);
                                        $foto = $item['fotoCaminho'];
                                        // Formata o preço para o padrão brasileiro (Ex: 25.50 vira 25,50)
                                        $precoFormatado = number_format($item['preco'], 2, ',', '.');
                                        ?>
                                        
                                        <!-- Card do Item: ao clicar, abre o modal de detalhes -->
                                        <div class="item-card" onclick="abrirModalDetalhes('<?= $nomeLanche ?>', 'R$ <?= $precoFormatado ?>')">
                                            
                                            <?php if (!empty($foto)): ?>
                                                <!-- Se tem foto, mostra a foto -->
                                                <div class="item-img" style="background-image: url('<?= htmlspecialchars($foto) ?>');"></div>
                                            <?php else: ?>
                                                <!-- Se não tem foto, mostra o placeholder -->
                                                <div class="item-img sem-foto"><span>Sem Foto</span></div>
                                            <?php endif; ?>
                                            
                                            <div class="item-info">
                                                <h4><?= $nomeLanche ?></h4>
                                                <span class="item-preco">R$ <?= $precoFormatado ?></span>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                } else {
                                    // Caso a categoria não tenha nenhum lanche cadastrado ainda
                                    echo "<p style='color: var(--cor-texto-sec); font-size: 14px; padding: 10px;'>Nenhum item nesta categoria.</p>";
                                }
                                $stmtItens->close();
                                ?>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    // Caso o restaurante não tenha nenhuma categoria criada
                    echo '<p style="color: var(--cor-texto-sec); font-style: italic;">Nenhuma categoria criada ainda. Clique em "+ Nova Categoria" para começar o seu cardápio.</p>';
                }
                $stmtCat->close();
                ?>
                
            </div>
        </section>

        <aside class="area-pedido resumido" id="barra-pedido">
            
            <div class="pedido-resumo" onclick="expandirPedido()">
                <div class="resumo-info">
                    <span class="qtd-itens">0 itens</span>
                    <span class="total-preco">R$ 0,00</span>
                </div>
                <span class="icone-expandir">🛒</span>
            </div>

            <div class="pedido-detalhes">
                <div class="detalhes-header">
                    <h3>Pedido Atual</h3>
                    <span class="qtd-itens">0 itens</span>
                    <button class="btn-fechar" onclick="fecharPedido()">X</button>
                </div>
                
                <div class="lista-itens-pedido">
                    <p class="carrinho-vazio">O carrinho está vazio.</p>
                </div>

                <div class="detalhes-footer">
                    <span class="total-preco">R$ 0,00</span>
                    <button class="btn-verde">Finalizar Pedido</button>
                </div>
            </div>

        </aside>

    </main>
    <script src="../js/pdv_js.js"></script>
    <div class="modal-overlay" id="modal-categoria">
        <div class="modal-box">
            
            <div class="modal-header">
                <h3>Nova Categoria</h3>
                <button class="btn-fechar-modal" onclick="fecharModalCategoria()">X</button>
            </div>
            
            <div class="modal-body">
                <form action="criarCategoria/criarCategoria.php" method="POST">
                    <div class="input-group">
                        <label for="nomeCategoria">Nome da Categoria</label>
                        <input type="text" id="nomeCategoria" name="nomeCategoria" placeholder="Ex: Hambúrgueres, Bebidas..." required>
                    </div>
                    <button type="submit" class="btn-verde">Salvar Categoria</button>
                </form>
            </div>

        </div>
    </div>
    <div class="modal-overlay" id="modal-novo-item">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Cadastrar Novo Lanche</h3>
                <button class="btn-fechar-modal" onclick="fecharModalNovoItem()">X</button>
            </div>
            <div class="modal-body">
                <form action="salvarItem/salvarItem.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="id_categoria_destino" name="idCategoria">
                    
                    <div class="input-group">
                        <label>Nome do Item</label>
                        <input type="text" name="nomeItem" placeholder="Ex: X-Tudo" required>
                    </div>
                    <div class="input-group">
                        <label>Preço (R$)</label>
                        <input type="number" step="0.01" name="precoItem" placeholder="Ex: 25.50" required>
                    </div>
                    <div class="input-group">
                        <label>Foto do Item (Opcional)</label>
                        <input type="file" name="fotoItem" accept="image/*">
                    </div>
                    <button type="submit" class="btn-verde">Salvar Lanche</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modal-detalhes">
        <div class="modal-box modal-largo"> <div class="modal-header">
                <h3 id="detalhe-nome-item">Nome do Item</h3>
                <span class="destaque-preco" id="detalhe-preco-item">R$ 0,00</span>
                <button class="btn-fechar-modal" onclick="fecharModalDetalhes()">X</button>
            </div>
            
            <div class="modal-body">
                <h4 class="titulo-secao">Complementos</h4>
                <div class="lista-complementos">
                    <label class="check-complemento">
                        <input type="checkbox" value="3.00">
                        <span class="nome-comp">Bacon Extra</span>
                        <span class="preco-comp">+ R$ 3,00</span>
                    </label>
                    <label class="check-complemento">
                        <input type="checkbox" value="2.50">
                        <span class="nome-comp">Mussarela</span>
                        <span class="preco-comp">+ R$ 2,50</span>
                    </label>
                </div>

                <h4 class="titulo-secao">Observação</h4>
                <textarea class="caixa-obs" placeholder="Ex: Tirar cebola, ponto da carne, etc..."></textarea>

                <div class="detalhes-acao">
                    <div class="controle-qtd">
                        <button class="btn-qtd">-</button>
                        <input type="number" value="1" min="1" readonly>
                        <button class="btn-qtd">+</button>
                    </div>
                    <button class="btn-verde btn-add-carrinho">Adicionar ao Pedido</button>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
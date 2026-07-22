<?php
session_start();
require_once '../../conexaoPhp/conexao.php'; // Seu arquivo de conexão com o banco

// Verifica se o usuário está logado (Ajuste o nome da variável de sessão se for diferente no seu login)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Restaurantes - OrderShop</title>
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link rel="stylesheet" href="../css/pdv_style.css"> <!-- Seu arquivo CSS unificado -->
</head>
<body>

    <!-- Navbar Padrão -->
    <nav class="navbar">
        <div class="nav-logo">
            <span class="logo-text">Order<span class="logo-highlight">Shop</span></span>
        </div>
        <div class="nav-user">
            <span>Área do Lojista</span>
            <a href="../../logout/logout.php" class="btn-sair">Sair</a>
        </div>
    </nav>

    <!-- Container Principal -->
    <main class="container-selecao">
        
        <div class="header-selecao">
            <h2>Meus Restaurantes</h2>
            <p>Selecione uma loja para abrir o PDV ou crie um novo estabelecimento.</p>
        </div>

        <div class="grid-restaurantes">
            
            <?php
            // Busca apenas os restaurantes que pertencem a este usuário logado
            $sql = "SELECT idRestaurante, nomeLoja FROM restaurantes WHERE idUsuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Verifica se o usuário já tem algum restaurante
            if ($resultado->num_rows > 0) {
                // Loop para desenhar as caixas de cada restaurante
                while ($loja = $resultado->fetch_assoc()) {
                    $idLoja = $loja['idRestaurante'];
                    $nomeLoja = htmlspecialchars($loja['nomeLoja']);
                    
                    // A MÁGICA ESTÁ AQUI: O link manda o idRestaurante pela URL (?id=...)
                    ?>
                    <a href="../painelPdv/painelPdv.php?id=<?= $idLoja ?>" class="restaurante-box">
                        <!-- Se futuramente você quiser por foto da loja, a lógica entra aqui -->
                        <div class="restaurante-img" style="background-image: url('https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=400&auto=format&fit=crop');"></div>
                        <div class="restaurante-info">
                            <h3><?= $nomeLoja ?></h3>
                        </div>
                    </a>
                    <?php
                }
            }
            $stmt->close();
            ?>

            <!-- Caixa de Adicionar Novo Restaurante (Sempre visível no final) -->
            <!-- Ajuste o link abaixo para o arquivo onde fica o seu formulário de criar loja -->
            <a href="../criarLoja/criarLoja.php" class="add-box">
                <span class="plus-icon">+</span>
                <h3>Novo Restaurante</h3>
            </a>

        </div>
    </main>

</body>
</html>
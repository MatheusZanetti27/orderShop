<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Estabelecimentos</title>
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link rel="stylesheet" href="../css/pdv_style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo">
            <span class="logo-text">Order<span class="logo-highlight">Shop</span></span>
        </div>
        <div class="nav-user">
            <span>Meus Estabelecimentos</span>
            <a href="../../logout/logout.php" class="btn-sair">Sair</a>
        </div>
    </nav>

    <main class="container-selecao">
        <div class="header-selecao">
            <h2>Selecione o Estabelecimento</h2>
            <p>Escolha qual restaurante deseja gerenciar agora.</p>
        </div>

        <div class="grid-restaurantes">
            
            <!--<a href="painel_pdv.php?id=1" class="restaurante-box">
                <div class="restaurante-img" style="background-image: url('https://images.unsplash.com/photo-1550547660-d9450f859349?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80');"></div>
                <div class="restaurante-info">
                    <h3>Burger King</h3>
                </div>
            </a>-->

            <a href="../criarLoja/criarLoja.php" class="add-box">
                <div class="plus-icon">+</div>
                <h3>Novo Estabelecimento</h3>
            </a>

        </div>
    </main>

</body>
</html>
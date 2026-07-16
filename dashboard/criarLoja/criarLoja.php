<?php
session_start();
require_once 'conexao.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login/login.html");
    exit();
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsuario = $_SESSION['usuario_id']; // Puxa quem é o dono da loja
    $nomeLoja = trim($_POST['nomeLoja']);
    $telefone = trim($_POST['telefone']);
    $cep = trim($_POST['cep']); // Capturando o seu novo input
    
    if (empty($nomeLoja) || empty($telefone) || empty($cep)) {
        echo "Preencha todos os campos.";
        exit();
    }

    // Inserindo no banco de dados
    $stmt = $conn->prepare("INSERT INTO restaurantes (id_usuario, nomeLoja, telefone, cep) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_usuario, $nomeLoja, $telefone, $cep);

    if ($stmt->execute()) {
        // Sucesso! Manda de volta para a tela de seleção com as caixas
        header("Location: ../meusRestaurantes/meusRestaurantes.php");
        exit();
    } else {
        echo "Erro ao criar estabelecimento: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acesso inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Estabelecimento</title>
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
    <link rel="stylesheet" href="../css/pdv_style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo">
            <span class="logo-text">Order<span class="logo-highlight">Shop</span></span>
        </div>
        <div class="nav-user">
            <span>Configuração Inicial</span>
            <a href="logout.php" class="btn-sair">Sair</a>
        </div>
    </nav>

    <main class="container-central">
        <div class="voltar">
            <a href="../meusRestaurantes/meusRestaurantes.php">Voltar</a>
        </div>
        <div class="setup-box">
            <h2>Crie seu Estabelecimento</h2>
            <p class="subtitle">Para começar a gerenciar pedidos, precisamos dos dados do seu restaurante.</p>
            
            <form action="criar_loja.php" method="POST">
                <div class="input-group">
                    <label for="nomeLoja">Nome do Restaurante</label>
                    <input type="text" id="nomeLoja" name="nomeLoja" placeholder="Ex: Burger King" required>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone para Contato</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                </div>

                <div class="input-group">
                    <label for="CEP">CEP</label>
                    <input type="text" id="CEP" name="cep" placeholder="00000-000" required>
                </div>

                <input type="submit" value="Abrir Restaurante" class="btn-verde">
            </form>
        </div>
    </main>

</body>
</html>
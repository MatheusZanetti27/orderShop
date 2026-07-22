<?php
session_start();
require_once '../../../conexaoPhp/conexao.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['restaurante_ativo'])) {
    die("Acesso negado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $idCategoria = $_POST['idCategoria'];
    $nomeItem = trim($_POST['nomeItem']);
    $precoItem = str_replace(',', '.', $_POST['precoItem']); // Troca vírgula por ponto para o banco aceitar
    $idRestaurante = $_SESSION['restaurante_ativo'];

    if (empty($nomeItem) || empty($precoItem) || empty($idCategoria)) {
        die("Preencha os campos obrigatórios.");
    }

    // --- LÓGICA DE UPLOAD DA IMAGEM ---
    $caminho_foto = NULL; // Por padrão, fica sem foto
    
    // Verifica se o usuário enviou um arquivo de imagem e se não houve erros
    if (isset($_FILES['fotoItem']) && $_FILES['fotoItem']['error'] === 0) {
        
        $extensao = pathinfo($_FILES['fotoItem']['name'], PATHINFO_EXTENSION);
        // Cria um nome único para a imagem (ex: lanche_169999.jpg) para não sobrescrever
        $nome_arquivo = "lanche_" . time() . "." . $extensao; 
        $pasta_destino = "../uploads/" . $nome_arquivo;

        // Move a imagem temporária para a pasta definitiva
        if (move_uploaded_file($_FILES['fotoItem']['tmp_name'], $pasta_destino)) {
            $caminho_foto = $pasta_destino; // Salva o caminho para colocar no banco
        }
    }
    // ----------------------------------

    // Insere no banco de dados
    $stmt = $conn->prepare("INSERT INTO lanches (idCategoria, nomeLanche, preco, fotoCaminho) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $idCategoria, $nomeItem, $precoItem, $caminho_foto);

    if ($stmt->execute()) {
        // Volta para o painel com sucesso
        header("Location: ../painelPdv.php?id=" . $idRestaurante);
        exit();
    } else {
        echo "Erro ao salvar lanche: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
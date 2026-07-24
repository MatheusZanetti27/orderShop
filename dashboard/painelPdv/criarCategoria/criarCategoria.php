<?php
session_start();
require_once '../../../conexaoPhp/conexao.php'; // Ajuste o caminho

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['restaurante_ativo'])) {
    die("Acesso negado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $idRestaurante = $_SESSION['restaurante_ativo']; // Puxa a loja correta
    $nomeCategoria = trim($_POST['nomeCategoria']);
    
    if (empty($nomeCategoria)) {
        die("O nome da categoria não pode ser vazio.");
    }

    // --- VERIFICAÇÃO DE DUPLICIDADE ---
    $sqlCheck = "SELECT idCategoria FROM categorias WHERE idRestaurante = ? AND nomeCategoria = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $idRestaurante, $nomeCategoria);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo "<script>alert('Esta categoria já existe neste restaurante!'); window.location.href='../painelPdv.php?id=$idRestaurante';</script>";
        exit();
    }
    $stmtCheck->close();
    // ----------------------------------

    // Inserindo na tabela
    $stmt = $conn->prepare("INSERT INTO categorias (idRestaurante, nomeCategoria) VALUES (?, ?)");
    $stmt->bind_param("is", $idRestaurante, $nomeCategoria);

    if ($stmt->execute()) {
        // Redireciona de volta para o painel com o ID do restaurante na URL
        header("Location: ../painelPdv.php?id=" . $idRestaurante);
        exit();
    } else {
        echo "Erro ao criar categoria: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
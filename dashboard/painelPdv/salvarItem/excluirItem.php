<?php
session_start();
require_once '../../../conexaoPhp/conexao.php'; // Ajuste o caminho se necessário

// Verifica segurança
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['restaurante_ativo']) || !isset($_GET['idLanche'])) {
    die("Acesso negado.");
}

$idLanche = $_GET['idLanche'];
$idRestaurante = $_SESSION['restaurante_ativo'];

// SQL Seguro: Apaga o lanche SOMENTE se a categoria dele pertencer ao restaurante logado
$sql = "DELETE lanches FROM lanches 
        INNER JOIN categorias ON lanches.idCategoria = categorias.idCategoria 
        WHERE lanches.idLanche = ? AND categorias.idRestaurante = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idLanche, $idRestaurante);

if ($stmt->execute()) {
    // Apagou com sucesso, volta pra tela
    header("Location: ../painelPdv.php?id=" . $idRestaurante);
    exit();
} else {
    echo "Erro ao excluir lanche: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
require_once '../../../conexaoPhp/conexao.php'; // Ajuste o caminho da conexão

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['restaurante_ativo']) || !isset($_GET['idCategoria'])) {
    die("Acesso negado.");
}

$idCategoria = $_GET['idCategoria'];
$idRestaurante = $_SESSION['restaurante_ativo'];

// Para segurança extra, verificamos se a categoria pertence mesmo ao restaurante do usuário
$sql = "DELETE FROM categorias WHERE idCategoria = ? AND idRestaurante = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idCategoria, $idRestaurante);

if ($stmt->execute()) {
    header("Location: ../painelPdv.php?id=" . $idRestaurante);
} else {
    echo "Erro ao excluir: " . $conn->error;
}
?>
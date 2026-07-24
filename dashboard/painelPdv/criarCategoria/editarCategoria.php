<?php
session_start();
require_once '../../../conexaoPhp/conexao.php'; // Ajuste o caminho

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['restaurante_ativo'])) {
    die("Acesso negado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCategoria = $_POST['idCategoria'];
    $nomeCategoria = trim($_POST['nomeCategoria']);
    $idRestaurante = $_SESSION['restaurante_ativo'];

    if (empty($nomeCategoria)) {
        die("Nome inválido.");
    }

    // Verifica se já existe outra categoria com este mesmo nome (ignorando a própria categoria que está sendo editada)
    $sqlCheck = "SELECT idCategoria FROM categorias WHERE idRestaurante = ? AND nomeCategoria = ? AND idCategoria != ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("isi", $idRestaurante, $nomeCategoria, $idCategoria);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo "<script>alert('Você já possui outra categoria com este nome!'); window.location.href='../painelPdv.php?id=$idRestaurante';</script>";
        exit();
    }
    $stmtCheck->close();

    // Atualiza o nome da categoria no banco garantindo que ela pertence ao restaurante do usuário logado
    $sql = "UPDATE categorias SET nomeCategoria = ? WHERE idCategoria = ? AND idRestaurante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nomeCategoria, $idCategoria, $idRestaurante);

    if ($stmt->execute()) {
        header("Location: ../painelPdv.php?id=" . $idRestaurante);
    } else {
        echo "Erro ao editar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
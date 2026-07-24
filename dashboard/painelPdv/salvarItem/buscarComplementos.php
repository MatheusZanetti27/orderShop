<?php
session_start();
// Ajuste o caminho da conexão conforme necessário
require_once '../../../conexaoPhp/conexao.php'; 

// Avisa o navegador que a resposta será em formato JSON
header('Content-Type: application/json');

if (!isset($_GET['idLanche'])) {
    echo json_encode([]);
    exit;
}

$idLanche = $_GET['idLanche'];

$sql = "SELECT nomeComplemento, precoComplemento FROM complementos WHERE idLanche = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idLanche);
$stmt->execute();
$result = $stmt->get_result();

$complementos = [];
while ($row = $result->fetch_assoc()) {
    $complementos[] = $row;
}

// Converte o array do PHP para JSON e envia para o JavaScript
echo json_encode($complementos);

$stmt->close();
$conn->close();
?>
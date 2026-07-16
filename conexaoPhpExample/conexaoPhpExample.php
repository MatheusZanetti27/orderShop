<?php

$servername = "Teste";
$username = "Teste";
$password = "CONFIDENCIAL";
$dbname = "CONFIDENCIAL";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na Conexão com o Banco de Dados: " . $conn->connect_error);
}

?>
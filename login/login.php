<?php
session_start();

require_once '../conexaoPhp/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha_digitada = $_POST["senha"];

    if (empty($email) || empty($senha_digitada)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, nome, senha, nivelDeAcesso FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

    $usuario = $result->fetch_assoc();

    if (password_verify($senha_digitada, $usuario["senha"])) {

    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nome"] = $usuario["nome"];
    $_SESSION["nivelDeAcesso"] = $usuario["nivelDeAcesso"];

    header("Location: ../dashboard/criarLoja/criarLoja.php");
    exit();
    
    } else {
        echo "<h1>Email ou senha incorreta.</h1>";
        echo "<a href='login.html'>Voltar para a página de login</a>";
    }
    } else {
        echo "<h1>Email ou senha incorretos.</h1>";
        echo "<a href='login.html'>Voltar para a página de login</a>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}

?>
<?php

require_once '../conexaoPhp/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $sexo = $_POST["sexo"];

    $valoresPermitidosSexo = ['M', 'F'];

    if (!in_array($sexo, $valoresPermitidosSexo)) {
        echo "<h1>Erro: O valor do campo de sexo é inválido.</h1>";
        echo "<a href='register.html'>Voltar para o registro</a>";
        exit();
    }

    $email = $_POST["email"];
    $senha_hash = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    if (empty($email)) {
        echo "<h1>Erro: O campo de email é obrigatório.</h1>";
        echo "<a href='register.html'>Voltar para o registro</a>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h1>Erro: O email fornecido não é válido.</h1>";
        echo "<a href='register.html'>Voltar para o registro</a>";
        exit();
    }

    if (empty($senha_hash)) {
        echo "<h1>Erro: O campo de senha é obrigatório.</h1>";
        echo "<a href='register.html'>Voltar para o registro</a>";
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, sobrenome, sexo, email, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $sobrenome, $sexo, $email, $senha_hash);

    if ($stmt->execute()) {
        header("Location: ../login/login.html");
        exit();
    } else {
        echo "<h1>Erro: " . $stmt->error . "</h1>";
        echo "<a href='register.html'>Voltar para o registro</a>";

    }

    $stmt->close();
    $conn->close();
}   else {
    echo "Método de requisição inválido.";
}

?>
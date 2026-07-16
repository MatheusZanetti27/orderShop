<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logado</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
    <p>ID do usuário: <?php echo $_SESSION['usuario_id']; ?></p>
    <p>Nível de acesso: <?php echo $_SESSION['nivelDeAcesso']; ?></p>
    <a href="../logout/logout.php">Sair do Sistema</a>
</body>
</html>
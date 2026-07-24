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

    // --- VERIFICAÇÃO DE DUPLICIDADE ---
    $sqlCheck = "SELECT idLanche FROM lanches WHERE idCategoria = ? AND nomeLanche = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $idCategoria, $nomeItem);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        echo "<script>alert('Este item já existe nesta categoria!'); window.location.href='../painelPdv.php?id=$idRestaurante';</script>";
        exit();
    }
    $stmtCheck->close();
    // ----------------------------------

    // --- LÓGICA DE UPLOAD DA IMAGEM ---
    $fotoCaminho = NULL; // Por padrão, fica sem foto
    
    // Verifica se o usuário enviou um arquivo de imagem e se não houve erros
    if (isset($_FILES['fotoItem']) && $_FILES['fotoItem']['error'] === 0) {
        
        $extensao = pathinfo($_FILES['fotoItem']['name'], PATHINFO_EXTENSION);
        // Cria um nome único para a imagem (ex: lanche_169999.jpg) para não sobrescrever
        $nome_arquivo = "lanche_" . time() . "." . $extensao; 
        $pasta_destino = "../uploads/" . $nome_arquivo;

        // Move a imagem temporária para a pasta definitiva
        if (move_uploaded_file($_FILES['fotoItem']['tmp_name'], $pasta_destino)) {
            $fotoCaminho = substr($pasta_destino, 3); // Salva o caminho para colocar no banco
        }
    }
    // ----------------------------------

    // Insere no banco de dados
    $stmt = $conn->prepare("INSERT INTO lanches (idCategoria, nomeLanche, preco, fotoCaminho) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $idCategoria, $nomeItem, $precoItem, $fotoCaminho);

    if ($stmt->execute()) {

        // Pega o ID do lanche que acabou de ser criado!
        $idNovoLanche = $conn->insert_id; 

        // --- 2. SALVA OS COMPLEMENTOS (SE EXISTIREM) ---
        if (isset($_POST['nomeComplemento']) && is_array($_POST['nomeComplemento'])) {
            $nomesComp = $_POST['nomeComplemento'];
            $precosComp = $_POST['precoComplemento'];

            $sqlComp = "INSERT INTO complementos (idLanche, nomeComplemento, precoComplemento) VALUES (?, ?, ?)";
            $stmtComp = $conn->prepare($sqlComp);

            // Roda o loop pela quantidade de complementos que a pessoa adicionou na tela
            for ($i = 0; $i < count($nomesComp); $i++) {
                $nomeC = trim($nomesComp[$i]);
                $precoC = str_replace(',', '.', $precosComp[$i]);

                // Garante que não vai salvar uma linha em branco
                if (!empty($nomeC) && is_numeric($precoC)) {
                    $stmtComp->bind_param("isd", $idNovoLanche, $nomeC, $precoC);
                    $stmtComp->execute();
                }
            }
            $stmtComp->close();
        }

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
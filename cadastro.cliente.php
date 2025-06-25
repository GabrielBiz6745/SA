<?php
session_start();
require_once 'conexaoSA.php';
// Conexão com o banco de dados

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['id'])) {
        // Se não houver ID, é um novo cadastro

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $endereco = $_POST['endereco'];
        $usuario_id = 1; // O ID do usuário logado. Coloque a lógica de autenticação aqui

        // Validação de e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "E-mail inválido!";
            exit;
        }

        // Inserção no banco de dados
        $sql = "INSERT INTO clientes (nome, email, telefone, cep, endereco, usuario_id) 
            VALUES ('$nome', '$email', '$telefone', '$cep', '$endereco', $usuario_id)";

        if ($conn->query($sql) === TRUE) {
            echo "Cliente cadastrado com sucesso!";
        } else {
            echo "Erro: " . $conn->error;
        }
    } else {
        $id_cliente = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $endereco = $_POST['endereco'];

        $sql_update = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone', 
                    cep='$cep', endereco='$endereco' WHERE id=$id_cliente";

        if ($conn->query($sql_update) === TRUE) {
            echo "Cliente alterado com sucesso!";
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}

$conn->close();

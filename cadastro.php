<?php
session_start();
require_once 'conexaoSA.php';

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$usuario_id_logado = 1; // O ID do usuário logado, você precisa configurar isso.

if (empty($_GET['id'])) {
    $id_cliente = $_GET['id'];
    $sql = "SELECT * FROM clientes WHERE id = $id_cliente";
    $result = $conn->query($sql);
    $cliente = $result->fetch_assoc();
}
// Verificar se o cliente existe e se é do usuário logado
if ($cliente['usuario_id'] != $usuario_id_logado) {
    die("Você não tem permissão para editar este cliente!");
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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Cadastro de Cliente</h2>
            <form id="formCadastro" method="POST" action="cadastro.cliente.php">
                <div class="input-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= $cliente['nome'] ?>" required><br>
                </div>

                <div class="input-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?= $cliente['email'] ?>" required><br>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?= $cliente['telefone'] ?>"><br>
                </div>

                <div class="input-group">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?= $cliente['cep'] ?>" required><br>
                </div>

                <div class="input-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?= $cliente['endereco'] ?>" required><br>
                </div>

                <button type="submit" class="btn-submit">Cadastrar</button>
            </form>
        </div>
    </div>

    <script>
        // Função AJAX para buscar o endereço pelo CEP
        $('#cep').on('blur', function() {
            var cep = $(this).val();
            if (cep.length == 8) {
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    method: 'GET',
                    success: function(response) {
                        if (response.logradouro) {
                            $('#endereco').val(response.logradouro);
                        } else {
                            alert('CEP não encontrado!');
                        }
                    },
                    error: function() {
                        alert('Erro ao buscar o CEP!');
                    }
                });
            }
        });
    </script>
</body>

</html>
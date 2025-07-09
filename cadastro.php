<?php
session_start();
require_once 'conexaoSA.php';

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$usuario_id_logado = $_SESSION['usuario_id']; // O ID do usuário logado, você precisa configurar isso.

if (empty($_GET['id']))

    include 'conexaoSA.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id !== null) {
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($row = $resultado->fetch_assoc()) {
        echo "Nome: " . $row['nome'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
    }
}
?>

<?php
$sql = "SELECT * FROM usuarios WHERE id = $usuario_id_logado";
$result = $conn->query($sql);
$cliente = $result->fetch_assoc();

// Verificar se o cliente existe e se é do usuário logado
//if ($cliente['perfil_id'] != $usuario_id_logado) {
//    die("Você não tem permissão para editar este cliente!");
//}

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
        $sql = "INSERT INTO usuarios (nome, email, telefone, cep, endereco, perfil_id) 
            VALUES ('$nome', '$email', '$telefone', '$cep', '$endereco', $usuario_id)";

        if ($conn->query($sql) === TRUE) {
            $sucesso = true;
            echo "<script>setTimeout(() => document.querySelector('.mensagem-sucesso').style.display='none', 2000);</script>";
        } else {
            echo "Erro: " . $conn->error;
        }
    } else {
        $usuario_id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $endereco = $_POST['endereco'];

        $sql_update = "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone', 
                    cep='$cep', endereco='$endereco' WHERE id=$usuario_id";

        if ($conn->query($sql_update) === TRUE) {
            $sucesso = true;
            echo "<script>setTimeout(() => document.querySelector('.mensagem-sucesso').style.display='none', 2000);</script>";
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

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: rgb(87, 87, 87);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px;
    }

    .container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        width: 400px;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }

    .input-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .input-group input:focus {
        border-color: #007bff;
        outline: none;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    @media (max-width: 480px) {
        .container {
            width: 90%;
            padding: 20px;
        }
    }

    .mensagem-sucesso {
        text-align: center;
        margin-bottom: 15px;
        color: green;
        font-weight: bold;
    }

    .btn-voltar {
        display: inline-block;
        padding: 10px 20px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        font-weight: bold;
        margin: 20px auto 0;
        transition: background-color 0.3s ease;
    }

    .btn-voltar:hover {
        background-color: #5a6268;
    }
</style>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Cadastro de Cliente</h2>
            <?php if (isset($sucesso)): ?>
                <div class="mensagem-sucesso">
                    <p>Cliente cadastrado com sucesso!</p>
                </div>
            <?php endif; ?>
            <form id="formCadastro" method="POST" action="cadastro.php">
                <div class="input-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required><br>
                </div>

                <div class="input-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required><br>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone"><br>
                </div>

                <div class="input-group">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" required><br>
                </div>

                <div class="input-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required><br>
                </div>

                <button type="submit" class="btn-submit">Cadastrar</button><br>

                <div style="text-align: center;">
                    <a href="inicial.php" class="btn-voltar">Voltar para a tela inicial</a>
                </div><br>
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

        document.getElementById('cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('endereco').value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                        } else {
                            alert('CEP não encontrado.');
                            document.getElementById('endereco').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o CEP:', error);
                        alert('Erro ao buscar o CEP.');
                    });
            }
        });
    </script>
</body>

</html>
<?php
session_start();
require_once 'conexaoSA.php';

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

//$usuario_id_logado = $_SESSION['id_usuario'] ?? null;
//if (!$usuario_id_logado) {
//    die("Usuário não autenticado.");
//}

$msg = '';
$cliente_selecionado = null;

// Se foi enviado um ID por GET para editar
if (isset($_GET['id'])) {
    $id_editar = (int) $_GET['id'];

    // Buscar o cliente pelo ID
    $sql = "SELECT * FROM usuarios WHERE id = $id_editar";
    $result = $conn->query($sql);
    $cliente_selecionado = $result->fetch_assoc();

    if (!$cliente_selecionado) {
        $msg = "Cliente não encontrado.";
    }
}

// Se o formulário foi enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_post = (int) $_POST['id'];

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];

    $sql_update = "UPDATE usuarios SET 
                    nome = '$nome',
                    email = '$email',
                    telefone = '$telefone',
                    cep = '$cep',
                    endereco = '$endereco'
                   WHERE id = $id_post";

    if ($conn->query($sql_update) === TRUE) {
        $msg = "Cliente atualizado com sucesso!";
        $cliente_selecionado = null; // Volta pra lista
    } else {
        $msg = "Erro ao atualizar: " . $conn->error;
    }
}

// Buscar todos os usuários (simulando lista de clientes)
$sql_clientes = "SELECT * FROM usuarios";
$result_clientes = $conn->query($sql_clientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
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
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .cliente-lista {
            margin-bottom: 20px;
        }

        .cliente-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .cliente-item span {
            color: #333;
            font-weight: bold;
        }

        .cliente-item a {
            padding: 6px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
        }

        .cliente-item a:hover {
            background-color: #0056b3;
        }

        .mensagem {
            text-align: center;
            margin-bottom: 15px;
            color: green;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }
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
</head>

<body>

    <div class="container">
        <h2>Editar Cliente</h2>
        <div style="text-align: center;">
            <a href="inicial.php" class="btn-voltar">Voltar para a tela inicial</a>
        </div><br>

        <?php if ($msg): ?>
            <p class="mensagem"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if ($cliente_selecionado): ?>
            <!-- Formulário de edição -->
            <form method="POST">
                <input type="hidden" name="id" value="<?= $cliente_selecionado['id'] ?>">

                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($cliente_selecionado['nome']) ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($cliente_selecionado['email']) ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" value="<?= htmlspecialchars($cliente_selecionado['telefone']) ?>">

                <label for="cep">CEP:</label>
                <input type="text" name="cep" value="<?= htmlspecialchars($cliente_selecionado['cep']) ?>">

                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" value="<?= htmlspecialchars($cliente_selecionado['endereco']) ?>">

                <button type="submit">Salvar Alterações</button>

            </form>
        <?php else: ?>
            <!-- Lista de clientes com botão de editar -->
            <div class="cliente-lista">
                <?php while ($cliente = $result_clientes->fetch_assoc()): ?>
                    <div class="cliente-item">
                        <span><?= htmlspecialchars($cliente['nome']) ?></span>
                        <?php if ((int)$cliente['id'] !== 1): ?>
                            <a href="?id=<?= $cliente['id'] ?>" class="btn-editar">Editar</a>
                        <?php else: ?>
                            <span style="color: #999;">Protegido</span>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>

<?php $conn->close(); ?>
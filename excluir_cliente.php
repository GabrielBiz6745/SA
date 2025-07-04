<?php
session_start();
require_once 'conexaoSA.php';

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Pega o ID do usuário logado a partir da sessão
$usuario_id_logado = $_SESSION['id_usuario'] ?? null;

//if (!$usuario_id_logado) {
//    die("Usuário não autenticado.");
//}

$msg = '';

// Se clicou no botão de excluir
if (isset($_GET['excluir'])) {
    $id_excluir = (int) $_GET['excluir'];

    if ($id_excluir === 1) {
        $msg = "O administrador não pode ser excluído!";
    } else {
        $sql_verifica = "SELECT * FROM usuarios WHERE id = $id_excluir";
        $resultado_verifica = $conn->query($sql_verifica);

        if ($resultado_verifica && $resultado_verifica->num_rows > 0) {
            $sql_delete = "DELETE FROM usuarios WHERE id = $id_excluir";
            if ($conn->query($sql_delete) === TRUE) {
                $msg = "Usuário excluído com sucesso!";
            } else {
                $msg = "Erro ao excluir: " . $conn->error;
            }
        } else {
            $msg = "Usuário não encontrado.";
        }
    }
}

// Buscar todos os usuários cadastrados
$sql = "SELECT * FROM usuarios ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Excluir Clientes</title>
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
            width: 800px;
            box-sizing: border-box;
            overflow-x: auto;
        }

        .tabela-wrapper {
            overflow-x: auto;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        th,
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ccc;
            color: #333;
            font-size: 14px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        a.btn-excluir {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        a.btn-excluir:hover {
            background-color: #0056b3;
        }

        .message {
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: green;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            table,
            th,
            td {
                font-size: 12px;
            }

            a.btn-excluir {
                padding: 6px 10px;
                font-size: 12px;
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
        <h1>Clientes cadastrados</h1>
        <div style="text-align: center;">
            <a href="inicial.php" class="btn-voltar">Voltar para a tela inicial</a>
        </div><br>

        <?php if ($msg): ?>
            <p class="message"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CEP</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cliente = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['email']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                            <td><?= htmlspecialchars($cliente['cep']) ?></td>
                            <td><?= htmlspecialchars($cliente['endereco']) ?></td>
                            <td>
                                <?php if ((int)$cliente['id'] !== 1): ?>
                                    <a href="?excluir=<?= $cliente['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                                <?php else: ?>
                                    <span style="color: #999;">Protegido</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#333;">Nenhum cliente cadastrado.</p>
        <?php endif; ?>
    </div>

</body>

</html>

<?php $conn->close(); ?>
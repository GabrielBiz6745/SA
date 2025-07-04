<?php
session_start();
require_once 'conexaoSA.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta todos os clientes
$sql = "SELECT nome, email, telefone, cep, endereco, cidade, data_criacao FROM usuarios ORDER BY data_criacao DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Tela Inicial</title>
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
            width: 300px;
            margin-right: 40px;
        }

        .lista-clientes {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            flex: 1;
            overflow-y: auto;
            max-height: 80vh;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
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
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logout {
            margin-top: 20px;
            font-size: 14px;
        }

        .logout a {
            color: #007bff;
            text-decoration: none;
        }

        .logout a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

<div style="display: flex; flex-direction: column; gap: 30px;">
    <div style="display: flex; gap: 40px;">
        <!-- Painel de ações -->
        <div class="container">
            <h2>Bem-vindo, Cane!</h2>

            <form action="cadastro.php" method="get">
                <button type="submit">Cadastrar Cliente</button>
            </form>

            <form action="editar_cliente.php" method="get">
                <button type="submit">Editar Cliente</button>
            </form>

            <form action="excluir_cliente.php" method="get">
                <button type="submit">Excluir Cliente</button>
            </form>

            <form class="logout" action="Login.php" method="get">
                <button type="submit">Sair</button>
            </form>
        </div>

        <!-- Tabela de clientes -->
        <div class="lista-clientes">
            <h2>Clientes Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CEP</th>
                        <th>Endereço</th>
                        <th>Data/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($row['cep']); ?></td>
                                <td><?php echo htmlspecialchars($row['endereco']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['data_criacao'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum cliente cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Seção de relacionamentos -->
    <div style="display: flex;">
        <div class="container" style="width: 300px;">
            <h2>Relacionamento</h2>
            <p style="color: #999;">(Bryan, Gabriel, Wellinton)</p>
        </div>
    </div>
</div>

</body>


</html>
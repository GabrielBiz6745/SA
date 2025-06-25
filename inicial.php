<?php
session_start();
require_once 'conexaoSA.php';

// Verificação básica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
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
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h2>

    <form action="cadastrarCliente.php" method="get">
        <button type="submit">Cadastrar Cliente</button>
    </form>

    <form action="editarCliente.php" method="get">
        <button type="submit">Editar Cliente</button>
    </form>

    <form action="excluirCliente.php" method="get">
        <button type="submit">Excluir Cliente</button>
    </form>

    <div class="logout">
        <a href="logout.php">Sair</a>
    </div>
</div>

</body>
</html>
<?php
session_start();
require_once 'conexaoSA.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        http_response_code(400);
        echo json_encode(['erro' => 'Email e senha são obrigatórios.']);
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha_hash'])) {
            if (!$user['ativo']) {
                echo json_encode(['erro' => 'Usuário desativado.']);
                exit;
            }

            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['perfil_id'] = $user['perfil_id'];
            $_SESSION['primeiro_login'] = $user['primeiro_login'];

            // Cria token de sessão
            $token = bin2hex(random_bytes(32));
            $sqlSessao = "INSERT INTO sessoes (usuario_id, token_sessao) VALUES (?, ?)";
            $stmtSessao = $conn->prepare($sqlSessao);
            $stmtSessao->bind_param('is', $user['id'], $token);
            $stmtSessao->execute();

            header('Location: inicial.php');
            exit;
        } else {
            $erro = 'Senha incorreta'; //echo json_encode(['erro' => 'Senha incorreta.']);
            echo "<script>setTimeout(() => document.querySelector('.error').style.display='none', 2000);</script>";
        }
    } else {
        $erro = 'Usuário não encontrado.'; //echo json_encode(['erro' => 'Usuário não encontrado.']);
        echo "<script>setTimeout(() => document.querySelector('.error').style.display='none', 2000);</script>";

    }
}
if (isset($_GET['deslogar'])) {
    session_destroy();
    header('Location: Login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="icon" type="image/x-icon" href="supplies.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(87, 87, 87);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .info-box {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 30px 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
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

        .error {
            color: #d9534f;
            font-weight: bold;
            margin-bottom: 15px;
        }

        @media (max-width: 480px) {
            .info-box {
                width: 90%;
                padding: 20px;
            }
        }
    </style>

</head>

<body>
    <div class="info-box">
        <h2>LOGIN</h2>
        <?php if (isset($erro) && $erro) { ?>
            <p class="error">Usuário ou senha inválidos</p>
        <?php } ?>
        
        <form method="post">
            <p><input type='text' name='email' placeholder="EMAIL" required></p>
            <p><input type='password' name='senha' placeholder="SENHA" required></p>
            <p><button type='submit'>Fazer login</button></p>
        </form>
    </div>


</body>

</html>
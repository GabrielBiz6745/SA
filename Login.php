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
            echo json_encode(['erro' => 'Senha incorreta.']);
        }
    } else {
        echo json_encode(['erro' => 'Usuário não encontrado.']);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="supplies.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px auto;
            width: 50%;
            background: #f9f9f9;
        }
    </style>
</head>

<body>
    <h2>Login</h2>
    <?php if (isset($erro) && $erro) { ?>
        <h2>Usuário ou senha inválidos</h2>
    <?php } ?>

    <div class="info-box">
        <form method="post">
            <p><input type='text' name='email' placeholder="email" required></p>
            <p><input type='password' name='senha' placeholder="senha" required></p>
            <p><button type='submit'>Logar</button></p>
        </form>
    </div>
</body>

</html>

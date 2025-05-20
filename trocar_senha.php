<?php
session_start();
require_once 'conexaoSA.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$nova_senha = $_POST['nova_senha'] ?? '';

if (!$usuario_id || !$nova_senha) {
    http_response_code(400);
    echo json_encode(['erro' => 'Sessão inválida ou senha não fornecida.']);
    exit;
}

$hash = password_hash($nova_senha, PASSWORD_DEFAULT);
$sql = "UPDATE usuarios SET senha_hash = ?, primeiro_login = 0 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $hash, $usuario_id);
$stmt->execute();

echo json_encode(['sucesso' => true]);
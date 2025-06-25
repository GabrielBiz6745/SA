<?php
session_start();
require_once 'conexaoSA.php';

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$usuario_id_logado = 1; // O ID do usuário logado.

$id_cliente = $_GET['id'];
$sql = "SELECT * FROM clientes WHERE id = $id_cliente";
$result = $conn->query($sql);
$cliente = $result->fetch_assoc();

if ($cliente['usuario_id'] != $usuario_id_logado) {
    die("Você não tem permissão para excluir este cliente!");
}

$sql_delete = "DELETE FROM clientes WHERE id = $id_cliente";
if ($conn->query($sql_delete) === TRUE) {
    echo "Cliente excluído com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>

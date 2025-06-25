<?php
session_start();
require_once 'conexaoSA.php';

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Páginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Buscar clientes
$sql = "SELECT * FROM clientes LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);

echo "<table>";
echo "<tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Ações</th></tr>";

while ($cliente = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$cliente['nome']}</td>";
    echo "<td>{$cliente['email']}</td>";
    echo "<td>{$cliente['telefone']}</td>";
    echo "<td><a href='editar_cliente.php?id={$cliente['id']}'>Editar</a> | 
              <a href='excluir_cliente.php?id={$cliente['id']}'>Excluir</a></td>";
    echo "</tr>";
}
echo "</table>";

// Páginação
$sql_total = "SELECT COUNT(*) as total FROM clientes";
$result_total = $conn->query($sql_total);
$total = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total / $limite);

echo "<div>";
for ($i = 1; $i <= $total_paginas; $i++) {
    echo "<a href='listagem_clientes.php?pagina=$i'>$i</a> ";
}
echo "</div>";

$conn->close();
?>

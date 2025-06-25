<?php
session_start();
require_once 'conexaoSA.php';
// Conexão com o banco de dados

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}



$conn->close();
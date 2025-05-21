<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'usuario', 'senha', 'crm');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$usuario_id_logado = 1; // O ID do usuário logado, você precisa configurar isso.

$id_cliente = $_GET['id'];
$sql = "SELECT * FROM clientes WHERE id = $id_cliente";
$result = $conn->query($sql);
$cliente = $result->fetch_assoc();

// Verificar se o cliente existe e se é do usuário logado
if ($cliente['usuario_id'] != $usuario_id_logado) {
    die("Você não tem permissão para editar este cliente!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];

    $sql_update = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone', 
                    cep='$cep', endereco='$endereco' WHERE id=$id_cliente";

    if ($conn->query($sql_update) === TRUE) {
        echo "Cliente alterado com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}

$conn->close();
?>

<form method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?= $cliente['nome'] ?>" required><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?= $cliente['email'] ?>" required><br>

    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" value="<?= $cliente['telefone'] ?>"><br>

    <label for="cep">CEP:</label>
    <input type="text" id="cep" name="cep" value="<?= $cliente['cep'] ?>" required><br>

    <label for="endereco">Endereço:</label>
    <input type="text" id="endereco" name="endereco" value="<?= $cliente['endereco'] ?>" required><br>

    <button type="submit">Alterar</button>
</form>

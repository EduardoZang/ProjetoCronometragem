<?php
session_start();
require '../../db/database.php';

if (!isset($_SESSION['idusuario'])) {
    header('Location: login.php');
    exit();
}

$idusuario = $_SESSION['idusuario'];

$db = new Database();
$conn = $db->connect();

$query = "SELECT nome, datanascimento, genero, login, telefone, email FROM usuario WHERE idusuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idusuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $datanascimento = $_POST['datanascimento'];
    $genero = $_POST['genero'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE usuario SET nome = ?, datanascimento = ?, genero = ?, telefone = ?, email = ? WHERE idusuario = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssssi', $nome, $datanascimento, $genero, $telefone, $email, $idusuario);

    if ($stmt->execute()) {
        echo "<p>Dados atualizados com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar os dados.</p>";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Minha Conta</h1>
        <form method="POST" action="../../index.php">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="datanascimento">Data de Nascimento</label>
                <input type="date" name="datanascimento" class="form-control" value="<?= $usuario['datanascimento'] ?>" required>
            </div>

            <div class="form-group">
                <label for="genero">Gênero</label>
                <select name="genero" class="form-control" required>
                    <option value="1" <?= $usuario['genero'] == 1 ? 'selected' : '' ?>>Masculino</option>
                    <option value="2" <?= $usuario['genero'] == 2 ? 'selected' : '' ?>>Feminino</option>
                    <option value="3" <?= $usuario['genero'] == 3 ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" class="form-control" value="<?= htmlspecialchars($usuario['telefone']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="../../index.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
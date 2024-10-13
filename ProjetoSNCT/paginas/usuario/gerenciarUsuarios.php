<?php
session_start();
require '../../db/database.php';

if (!isset($_SESSION['idusuario'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->connect();

$idusuario = $_SESSION['idusuario'];
$query = "SELECT isadm FROM usuario WHERE idusuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idusuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario['isadm']) {
    header('Location: ../../index.php');
    exit();
}

$queryUsuarios = "SELECT nome, datanascimento, genero, login, telefone, email FROM usuario";
$resultUsuarios = $conn->query($queryUsuarios);

$db->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gerenciar Usuários</h1>

        <table class="table table-striped mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Gênero</th>
                    <th>Login</th>
                    <th>Telefone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars((new DateTime($usuario['datanascimento']))->format('d/m/Y')) ?></td>
                        <td>
                            <?= $usuario['genero'] == 1 ? 'Masculino' : 'Feminino' ?>
                        </td>
                        <td><?= htmlspecialchars($usuario['login']) ?></td>
                        <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="../../index.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>
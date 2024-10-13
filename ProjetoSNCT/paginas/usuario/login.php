<?php
session_start();
require '../../db/database.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    if ($conn) {
        $sql = "SELECT * FROM usuario WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login); 
        $stmt->execute();
        $result = $stmt->get_result(); 

        $usuario = $result->fetch_assoc();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['nome'];
            $_SESSION['idusuario'] = $usuario['idusuario'];
            $_SESSION['isadm'] = $usuario['isadm'];
            header('Location: ../../index.php');
            exit();
        } else {
            $_SESSION['mensagem'] = "Login ou senha inválidos!";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao conectar ao banco de dados!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['mensagem'] ?></div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Entrar</button>
    </form>
</div>
</body>
</html>
<?php
session_start();

$usuarioLogado = isset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de sensoriamento e cronometragem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Bem-vindo ao Sistema de sensoriamento e cronometragem para atletismo</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="paginas/leituras/leituras.php" class="btn btn-info btn-block">Leituras</a>
            </div>

            <?php if ($usuarioLogado): ?>
                <div class="col-md-4">
                    <a href="paginas/usuario/gerenciarUsuarios.php" class="btn btn-primary btn-block">Gerenciar Usuários</a>
                </div>
                <div class="col-md-4">
                    <a href="paginas/usuario/minhaConta.php" class="btn btn-primary btn-block">Minha conta</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <?php if ($usuarioLogado): ?>
                <a href="paginas/usuario/logout.php" class="btn btn-danger btn-block">Sair</a>
            <?php else: ?>
                <a href="paginas/usuario/login.php" class="btn btn-success btn-block">Login</a>
                <a href="paginas/usuario/cadastro.php" class="btn btn-secondary btn-block">Cadastro</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
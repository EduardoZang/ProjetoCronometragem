<?php
session_start();
require '../../db/database.php';

if (!isset($_SESSION['idusuario'])) {
    header('Location: ../usuario/login.php');
    exit();
}

$db = new Database();
$conn = $db->connect();

$queryUsuarios = "SELECT idusuario, nome FROM usuario";
$resultUsuarios = $conn->query($queryUsuarios);

$queryMacs = "SELECT idmac, nome FROM mac";
$resultMacs = $conn->query($queryMacs);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusuario = $_POST['usuario'];
    $idmac = $_POST['mac'];

    $dataLeitura = date('Y-m-d');
    $horaLeitura = date('H:i:s');
    $sensor1 = rand(0, 1000) / 10;
    $sensor2 = rand(0, 1000) / 10;
    $sensor3 = rand(0, 1000) / 10;
    $sensor4 = rand(0, 1000) / 10;

    $stmt = $conn->prepare("INSERT INTO leitura (usuario_idusuario, mac_idmac, dataleitura, horaleitura, sensor1, sensor2, sensor3, sensor4) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param('issssddd', $idusuario, $idmac, $dataLeitura, $horaLeitura, $sensor1, $sensor2, $sensor3, $sensor4);

    if ($stmt->execute()) {
        header('Location: leituras.php');
        exit();
    } else {
        die("Erro ao executar: " . $stmt->error);
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Nova Leitura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Adicionar Nova Leitura</h1>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Atleta:</label>
                <select name="usuario" id="usuario" class="form-control" required>
                    <option value="">Selecione um Atleta</option>
                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                        <option value="<?= $usuario['idusuario'] ?>"><?= htmlspecialchars($usuario['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="mac">MAC:</label>
                <select name="mac" id="mac" class="form-control" required>
                    <option value="">Selecione um MAC</option>
                    <?php while ($mac = $resultMacs->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($mac['idmac']) ?>"><?= htmlspecialchars($mac['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-block">Adicionar Leitura</button>
        </form>

        <a href="leituras.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>
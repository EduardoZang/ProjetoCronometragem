<?php
session_start();
require '../../db/database.php';

if (!isset($_SESSION['idusuario'])) {
    header('Location: ../usuario/login.php');
    exit();
}

$db = new Database();
$conn = $db->connect();

if (!isset($_GET['idleitura'])) {
    echo "ID da leitura não fornecido.";
    exit();
}

$idleitura = $_GET['idleitura'];

$queryLeitura = "
    SELECT l.idleitura, l.dataleitura, l.horaleitura, l.sensor1, l.sensor2, l.sensor3, l.sensor4, 
           u.idusuario, u.nome AS usuario_nome, m.nome AS mac_nome
    FROM leitura l
    JOIN usuario u ON l.usuario_idusuario = u.idusuario
    JOIN mac m ON l.mac_idmac = m.idmac
    WHERE l.idleitura = ?";
$stmt = $conn->prepare($queryLeitura);
$stmt->bind_param('i', $idleitura);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Leitura não encontrada.";
    exit();
}

$leitura = $result->fetch_assoc();

$queryUsuarios = "SELECT idusuario, nome FROM usuario";
$resultUsuarios = $conn->query($queryUsuarios);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoUsuario = $_POST['usuario'];

    $stmtUpdate = $conn->prepare("UPDATE leitura SET usuario_idusuario = ? WHERE idleitura = ?");
    $stmtUpdate->bind_param('ii', $novoUsuario, $idleitura);

    if ($stmtUpdate->execute()) {
        header('Location: leituras.php');
        exit();
    } else {
        echo "<p>Erro ao atualizar a leitura.</p>";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Leitura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Leitura</h1>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Atleta:</label>
                <select name="usuario" id="usuario" class="form-control" required>
                    <option value="">Selecione um Atleta</option>
                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                        <option value="<?= $usuario['idusuario'] ?>" 
                            <?= $usuario['idusuario'] == $leitura['idusuario'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Data da Leitura:</label>
                <input type="text" class="form-control" value="<?= $leitura['dataleitura'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Hora da Leitura:</label>
                <input type="text" class="form-control" value="<?= $leitura['horaleitura'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>MAC:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($leitura['mac_nome']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Sensor 1:</label>
                <input type="text" class="form-control" value="<?= $leitura['sensor1'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Sensor 2:</label>
                <input type="text" class="form-control" value="<?= $leitura['sensor2'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Sensor 3:</label>
                <input type="text" class="form-control" value="<?= $leitura['sensor3'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Sensor 4:</label>
                <input type="text" class="form-control" value="<?= $leitura['sensor4'] ?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Salvar Alterações</button>
        </form>

        <a href="leituras.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>
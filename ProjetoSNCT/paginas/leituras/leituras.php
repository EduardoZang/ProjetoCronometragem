<?php
session_start();

require '../../db/database.php';

if (!isset($_SESSION['idusuario'])) {
    header('Location: ../usuario/login.php');
    exit();
}

$db = new Database();
$conn = $db->connect();

$queryLeituras = "
    SELECT l.idleitura, l.dataleitura, l.horaleitura, l.sensor1, l.sensor2, 
           l.sensor3, l.sensor4, u.nome AS atleta, m.nome AS dispositivo
    FROM leitura l
    JOIN usuario u ON l.usuario_idusuario = u.idusuario
    JOIN mac m ON l.mac_idmac = m.idmac
";
$resultLeituras = $conn->query($queryLeituras);

if (isset($_GET['excluir'])) {
    $idleitura = $_GET['excluir'];
    $stmt = $conn->prepare("DELETE FROM leitura WHERE idleitura = ?");
    $stmt->bind_param('i', $idleitura);
    $stmt->execute();
    header('Location: leituras.php');
    exit();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leituras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Leituras</h1>
        <a href="novaLeitura.php" class="btn btn-success mb-3">Adicionar Nova Leitura</a>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sensor 1</th>
                    <th>Sensor 2</th>
                    <th>Sensor 3</th>
                    <th>Sensor 4</th>
                    <th>Atleta</th>
                    <th>Dispositivo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($leitura = $resultLeituras->fetch_assoc()): ?>
                    <tr>
                        <td><?= $leitura['idleitura'] ?></td>
                        <td><?= (new DateTime($leitura['dataleitura']))->format('d/m/Y') ?></td>
                        <td><?= (new DateTime($leitura['horaleitura']))->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('H:i:s') ?></td>
                        <td><?= formatarTempo($leitura['sensor1']) ?></td>
                        <td><?= formatarTempo($leitura['sensor2']) ?></td>
                        <td><?= formatarTempo($leitura['sensor3']) ?></td>
                        <td><?= formatarTempo($leitura['sensor4']) ?></td>
                        <td><?= htmlspecialchars($leitura['atleta']) ?></td>
                        <td><?= htmlspecialchars($leitura['dispositivo']) ?></td>
                        <td>
                            <a href="editarLeitura.php?idleitura=<?= $leitura['idleitura'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?excluir=<?= $leitura['idleitura'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="../../index.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>

<?php
// Função para formatar tempo em milissegundos
function formatarTempo($tempo) {
    $milissegundos = $tempo % 1000; // Obter os milissegundos restantes
    $segundosTotais = floor($tempo / 1000); // Converte milissegundos para segundos
    $centecimos = floor($milissegundos / 10); // Converte milissegundos para centésimos

    $minutos = floor($segundosTotais / 60); // Converte segundos totais para minutos
    $segundos = $segundosTotais % 60; // Obter segundos restantes

    if ($minutos > 0) {
        // Exibe em minutos, segundos e centésimos (0'00"00)
        return sprintf('%d\'%02d"%02d', $minutos, $segundos, $centecimos);
    } else {
        // Exibe apenas em segundos e centésimos (00"00)
        return sprintf('%02d"%02d', $segundos, $centecimos);
    }
}
?>
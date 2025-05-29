<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
$pdo = new PDO('mysql:host=localhost;dbname=sistema_pos;charset=utf8mb4', 'root', '');
// Reemplaza 'etiquetero', 'usuario', 'contraseña' por tus datos reales
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT * FROM pcs_armadas ORDER BY fecha DESC");
$pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PCs Armadas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../public/css/style.css">
    <style>
        .pcs-table { width:100%; border-collapse:collapse; margin-top:24px; }
        .pcs-table th, .pcs-table td { border:1px solid #ccc; padding:8px; }
        .pcs-table th { background:#3a0ca3; color:#fff; }
        .pcs-table tr:nth-child(even) { background:#f6f6f6; }
    </style>
</head>
<body>
<?php include '../public/layouts/sidebar.php'; ?>
<div class="main-content">
    <?php include '../public/layouts/header.php'; ?>
    <h1 style="color:#3a0ca3;">PCs Armadas</h1>
    <table class="pcs-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Gama</th>
                <th>CPU</th>
                <th>RAM</th>
                <th>Almacenamiento</th>
                <th>Tarjeta Madre</th>
                <th>GPU</th>
                <th>PSU</th>
                <th>Gabinete</th>
                <th>Observaciones</th>
                <th>Checklist QA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pcs as $pc): ?>
            <tr>
                <td><?= htmlspecialchars($pc['fecha']) ?></td>
                <td><?= htmlspecialchars($pc['usuario']) ?></td>
                <td><?= htmlspecialchars($pc['gama']) ?></td>
                <td><?= htmlspecialchars($pc['cpu']) ?></td>
                <td><?= htmlspecialchars($pc['ram']) ?></td>
                <td><?= htmlspecialchars($pc['storage']) ?></td>
                <td><?= htmlspecialchars($pc['mb']) ?></td>
                <td><?= htmlspecialchars($pc['gpu']) ?></td>
                <td><?= htmlspecialchars($pc['psu']) ?></td>
                <td><?= htmlspecialchars($pc['case']) ?></td>
                <td><?= nl2br(htmlspecialchars($pc['observaciones'])) ?></td>
                <td><pre style="white-space:pre-wrap;"><?= htmlspecialchars($pc['checklist_qa']) ?></pre></td>
                <td><?= htmlspecialchars($pc['usuario']) ?></td>
<td>
    <?php
        $qa = json_decode($pc['checklist_qa'], true);
        echo is_array($qa) ? count($qa) : 0;
    ?>
</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
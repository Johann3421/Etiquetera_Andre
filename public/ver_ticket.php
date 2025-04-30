<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    die('ID no especificado.');
}

$id = intval($_GET['id']);
$db = new Database();
$conn = $db->connect();

// Obtener los detalles del registro
$stmt = $conn->prepare("SELECT * FROM recepciones WHERE id = ?");
$stmt->execute([$id]);
$recepcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recepcion) {
    die('Registro no encontrado.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Recepción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .ticket {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        .ticket h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .ticket img {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            height: auto;
        }
        .ticket .details {
            margin-bottom: 20px;
        }
        .ticket .details p {
            margin: 5px 0;
        }
        .ticket .actions {
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #3a0ca3;
        }
    </style>
</head>
<body>
    <div class="ticket">
    <p><strong>Código Generado:</strong> <?= htmlspecialchars($recepcion['codigo_generado']) ?></p>
        <h1>Ticket de Recepción</h1>
        <?php if ($recepcion['foto_url']): ?>
            <img src="<?= htmlspecialchars($recepcion['foto_url']) ?>" alt="Foto del equipo">
        <?php endif; ?>
        <div class="details">
            <p><strong>ID:</strong> <?= htmlspecialchars($recepcion['id']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($recepcion['fecha']) ?></p>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($recepcion['cliente_nombre']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($recepcion['cliente_telefono']) ?></p>
            <p><strong>Equipo:</strong> <?= htmlspecialchars($recepcion['equipo_tipo']) ?></p>
            <p><strong>Diagnóstico:</strong> <?= htmlspecialchars($recepcion['diagnostico']) ?></p>
            <p><strong>Costo:</strong> <?= number_format($recepcion['costo'], 2) ?> <?= htmlspecialchars($recepcion['tipo_moneda']) ?></p>
        </div>
        <div class="actions">
            <button class="btn" onclick="window.print()">Imprimir</button>
            <a href="reportes.php" class="btn">Volver</a>
        </div>
    </div>
</body>
</html>
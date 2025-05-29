<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

checkAuthentication();

$db = new Database();
$conn = $db->connect();

// Obtener todos los registros
$stmt = $conn->query("SELECT * FROM recepciones ORDER BY created_at DESC");
$recepciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Recepciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Llama tu CSS externo -->
<link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <?php include '../public/layouts/sidebar.php'; ?>

    <div class="main-content">
        <?php include '../public/layouts/header.php'; ?>

        <div class="container">
            <h1>Listado de Recepciones</h1>

            <div class="actions">
                <button class="btn" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button class="btn" onclick="exportTableToExcel('tablaReportes', 'recepciones')">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
            </div>

            <table id="tablaReportes">
            <thead>
    <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Equipo</th>
        <th>Diagnostico</th>
        <th>Costo</th>
        <th>Moneda</th>
        <th>Foto</th>
        <th>Código Generado</th>
        <th>Acciones</th> <!-- Nueva columna para acciones -->
    </tr>
</thead>
<tbody>
    <?php foreach ($recepciones as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r['id']) ?></td>
        <td><?= htmlspecialchars($r['fecha']) ?></td>
        <td><?= htmlspecialchars($r['cliente_nombre']) ?></td>
        <td><?= htmlspecialchars($r['equipo_tipo']) ?></td>
        <td><?= htmlspecialchars($r['diagnostico']) ?></td>
        <td><?= number_format($r['costo'], 2) ?></td>
        <td><?= htmlspecialchars($r['tipo_moneda']) ?></td>
        <td>
            <?php if ($r['foto_url']): ?>
                <img src="<?= htmlspecialchars($r['foto_url']) ?>" alt="Foto" style="width: 100px; height: auto;">
            <?php else: ?>
                No disponible
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($r['codigo_generado']) ?></td>
        <td>
            <button class="btn" onclick="window.location.href='ver_ticket.php?id=<?= $r['id'] ?>'">
                <i class="fas fa-ticket-alt"></i> Ver Ticket
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
        </div>
    </div>

    <script>
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        filename = filename? filename+'.xls':'reporte.xls';
        
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
    </script>
</body>
</html>
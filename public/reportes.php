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
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(to bottom, var(--primary-color), var(--primary-dark));
            color: white;
            width: 250px;
            min-height: 100vh;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
        }

        .user-profile {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .menu {
            padding: 20px;
        }

        .menu ul {
            list-style: none;
        }

        .menu li {
            margin: 15px 0;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: block;
            padding: 10px;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            padding: 8px 15px;
            border-radius: var(--border-radius);
            background: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--primary-dark);
        }

        /* Container */
        .container {
            background: #fff;
            padding: 20px;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
        }

        h1 {
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table thead {
            background: var(--primary-color);
            color: white;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        table tbody tr:nth-child(even) {
            background: #f2f2f2;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .actions {
            text-align: right;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
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
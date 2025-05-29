<?php
session_start();
if (!isset($_SESSION['username'])) exit('No autorizado');
require_once '../config/database.php';
$pc_id = intval($_GET['pc_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM pcs_armadas WHERE id = ?");
$stmt->execute([$pc_id]);
$pc = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT * FROM checklist_qa WHERE pc_id = ? ORDER BY fecha ASC");
$stmt2->execute([$pc_id]);
$checklists = $stmt2->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir QA PC #<?= $pc_id ?></title>
    <style>
        @media print {
            @page { size: A4 landscape; margin: 4mm; }
            body { margin: 0; }
            .no-print { display: none; }
            .qa-table { page-break-inside: avoid; }
        }
        body { font-family: Arial, sans-serif; margin: 4px; }
        h2 { color: #3a0ca3; margin-bottom: 4px; font-size: 1em; }
        .info-table, .qa-table { border-collapse: collapse; width: 100%; margin-bottom: 4px; font-size: 8px; }
        .info-table th, .info-table td, .qa-table th, .qa-table td { border: 1px solid #bbb; padding: 1px 2px; }
        .info-table th { background: #f6f6f6; text-align: right; width: 55px; }
        .qa-table th { background: #e9ecef; }
        .cuadro { display:inline-block; width:4px; height:4px; border:1px solid #222; margin:0 0.5px; vertical-align:middle; }
        .center { text-align: center; }
        .footer { margin-top: 6px; font-size: 8px; color: #888; }
        .op-col, .obs-col { max-width: 120px; white-space: normal; overflow: visible; text-overflow: initial; }
    </style>
</head>
<body>
    <h2>PC Ensamblada #<?= $pc_id ?></h2>
    <table class="info-table">
        <tr>
            <th>Fecha</th><td><?= htmlspecialchars($pc['fecha']) ?></td>
            <th>Usuario</th><td><?= htmlspecialchars($pc['usuario']) ?></td>
            <th>Gama</th><td><?= htmlspecialchars($pc['gama']) ?></td>
        </tr>
        <tr>
            <th>CPU</th><td><?= htmlspecialchars($pc['cpu']) ?></td>
            <th>RAM</th><td><?= htmlspecialchars($pc['ram']) ?></td>
            <th>Almacenamiento</th><td><?= htmlspecialchars($pc['storage']) ?></td>
        </tr>
        <tr>
            <th>Tarj. Madre</th><td><?= htmlspecialchars($pc['mb']) ?></td>
            <th>GPU</th><td><?= htmlspecialchars($pc['gpu']) ?></td>
            <th>PSU</th><td><?= htmlspecialchars($pc['psu']) ?></td>
        </tr>
        <tr>
            <th>Gabinete</th><td><?= htmlspecialchars($pc['case']) ?></td>
            <th>Obs.</th><td colspan="3"><?= nl2br(htmlspecialchars($pc['observaciones'])) ?></td>
        </tr>
    </table>
    <h2>Checklists QA</h2>
    <?php foreach($checklists as $idx => $c): 
        $res = json_decode($c['resultado'], true); ?>
        <table class="qa-table">
            <tr>
                <th colspan="9" style="background:#dbeafe;text-align:left;font-weight:normal;">
                    Checklist #<?= $idx+1 ?> 
                    <span style="font-size:0.95em;color:#888;">
                        por <?= htmlspecialchars($c['usuario']) ?> el <?= htmlspecialchars($c['fecha']) ?>
                    </span>
                </th>
            </tr>
            <tr>
                <th class="op-col">Operación</th>
                <th class="obs-col">Observación</th>
                <th class="center" colspan="7">Resultado (7 PCs)</th>
            </tr>
            <?php foreach($res as $item): ?>
                <tr>
                    <td class="op-col"><?= htmlspecialchars($item['nombre']) ?></td>
                    <td class="obs-col"><?= htmlspecialchars($item['obs']) ?></td>
                    <?php for($i=0; $i<7; $i++): ?>
                        <td class="center"><span class="cuadro"></span></td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
    <div class="footer">
        Formato generado por el sistema Etiquetero - <?= date('d/m/Y H:i') ?>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
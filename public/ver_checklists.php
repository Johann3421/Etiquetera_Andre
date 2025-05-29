<?php
require_once '../config/database.php';

$pc_id = isset($_GET['pc_id']) ? intval($_GET['pc_id']) : 0;

if ($pc_id === 0) {
    // Trae todos los checklists existentes (puedes limitar por fecha o cantidad si quieres)
    $stmt = $pdo->query("SELECT * FROM checklist_qa ORDER BY fecha DESC LIMIT 30");
} else {
    $stmt = $pdo->prepare("SELECT * FROM checklist_qa WHERE pc_id = ? ORDER BY fecha ASC");
    $stmt->execute([$pc_id]);
}

$checklists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Decodifica el campo resultado para cada checklist
foreach ($checklists as &$c) {
    $c['resultado'] = json_decode($c['resultado'], true);
}

header('Content-Type: application/json');
echo json_encode($checklists, JSON_UNESCAPED_UNICODE);
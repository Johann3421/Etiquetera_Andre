
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Cambia la ruta según corresponda a tu proyecto
require_once __DIR__ . '/../config/database.php';

if (!isset($pdo)) {
    echo json_encode(['success' => false, 'error' => 'No hay conexión a la base de datos']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pcs_armadas 
        (fecha, usuario, gama, cpu, ram, storage, mb, gpu, psu, `case`, observaciones, checklist_qa)
        VALUES (NOW(), :usuario, :gama, :cpu, :ram, :storage, :mb, :gpu, :psu, :case, :observaciones, :checklist_qa)");

    // Procesar RAM y STORAGE (pueden ser múltiples)
    $ram = implode(' / ', array_filter(array_map('trim', array_values(array_filter($data['campos'], function($k){return strpos($k,'ram_')===0;}, ARRAY_FILTER_USE_KEY)))));
    $storage = implode(' / ', array_filter(array_map('trim', array_values(array_filter($data['campos'], function($k){return strpos($k,'storage_')===0;}, ARRAY_FILTER_USE_KEY)))));

    $stmt->execute([
        ':usuario' => $data['usuario'],
        ':gama' => $data['gama'],
        ':cpu' => $data['campos']['cpu_'.$data['gama']] ?? '',
        ':ram' => $ram,
        ':storage' => $storage,
        ':mb' => $data['campos']['mb_'.$data['gama']] ?? '',
        ':gpu' => $data['campos']['gpu_'.$data['gama']] ?? '',
        ':psu' => $data['campos']['psu_'.$data['gama']] ?? '',
        ':case' => $data['campos']['case_'.$data['gama']] ?? '',
        ':observaciones' => $data['observaciones'],
        ':checklist_qa' => json_encode($data['checklist_qa'], JSON_UNESCAPED_UNICODE)
    ]);
    echo json_encode(['success' => true]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
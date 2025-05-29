<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}
require_once '../config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
if (!$data['pc_id'] || !is_array($data['resultado'])) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}
$stmt = $pdo->prepare("INSERT INTO checklist_qa (pc_id, usuario, resultado) VALUES (?, ?, ?)");
$stmt->execute([
    $data['pc_id'],
    $_SESSION['username'],
    json_encode($data['resultado'], JSON_UNESCAPED_UNICODE)
]);
echo json_encode(['success' => true]);
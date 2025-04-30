<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("INSERT INTO recepciones 
        (fecha, hora_recibido, cliente_nombre, cliente_telefono, equipo_tipo, equipo_password, diagnostico, extras, observaciones, fecha_entrega, hora_entrega, costo, tipo_moneda, foto_url, codigo_generado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $extras = json_encode([
        'cargador' => isset($_POST['extra_cargador']),
        'bateria' => isset($_POST['extra_bateria']),
        'bolsa' => isset($_POST['extra_bolsa']),
        'estuche' => isset($_POST['extra_estuche']),
        'cable' => isset($_POST['extra_cable'])
    ]);

    $foto_url = $_POST['foto_url'] ?? null;

    $stmt->execute([
        $_POST['fecha'],
        $_POST['hora_recibido'],
        $_POST['cliente_nombre'],
        $_POST['cliente_telefono'],
        $_POST['equipo_tipo'],
        $_POST['equipo_password'],
        $_POST['diagnostico'],
        $extras,
        $_POST['observaciones'],
        $_POST['fecha_entrega'],
        $_POST['hora_entrega'],
        $_POST['costo'],
        $_POST['tipo_moneda'],
        $foto_url,
        $_POST['codigoGenerado']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $uploadDir = __DIR__ . '/images/';
    $fileName = uniqid('foto_', true) . '.png';
    $uploadFile = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Crear la carpeta si no existe
    }

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => true, 'path' => 'images/' . $fileName]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibió ninguna imagen.']);
}
?>
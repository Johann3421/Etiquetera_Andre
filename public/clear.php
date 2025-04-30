<?php
// clear.php

// Opcional: autenticar acceso solo por IP o token
$allowedIps = ['127.0.0.1']; // o $_SERVER['REMOTE_ADDR']
if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIps)) {
    http_response_code(403);
    exit('No autorizado');
}

// Lógica para limpiar caché de tu app (ejemplo general)
$paths = [
    __DIR__ . '/cache/',
    __DIR__ . '/temp/',
    __DIR__ . '/views/cache/',
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        $files = glob($path . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

echo "Cache limpiado correctamente.";

<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function checkAuthentication() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION = array();
    session_destroy();
}
?>
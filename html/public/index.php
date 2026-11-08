<?php

/**
 * Front Controller & Entry Point
 * Kelompok 1 RSI - E-Commerce
 */

// Jalankan session untuk login, flash message, dan keranjang
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Support untuk PHP Built-in Server agar file statis (.css, .js, gambar) langsung dikirim
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

// Muat file konfigurasi menggunakan absolute path (__DIR__)
require_once __DIR__ . '/../config/config.php';

// Muat class inti (Core)
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Database.php';

// Jalankan router aplikasi
$app = new App();

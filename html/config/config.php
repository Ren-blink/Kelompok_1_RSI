<?php
/**
 * Konfigurasi Aplikasi & Database
 * Kelompok 1 RSI - E-Commerce
 */

// Konfigurasi Database MySQL (Mendukung Docker Environment Variable & Local XAMPP)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'db_ecommerce_rsi');

// Auto-detect BASE_URL agar fleksibel di Docker, XAMPP, maupun PHP Built-in Server
if (!defined('BASE_URL')) {
    if (getenv('BASE_URL')) {
        define('BASE_URL', rtrim(getenv('BASE_URL'), '/'));
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $baseDir = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
        define('BASE_URL', $protocol . $host . $baseDir);
    } else {
        define('BASE_URL', 'http://localhost:8090');
    }
}

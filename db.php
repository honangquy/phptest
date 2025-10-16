<?php
// db.php - PDO connection helper
// Update these constants to match your local XAMPP MySQL settings
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'honangquy_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDO() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        // ensure connection uses utf8mb4
        $pdo->exec("SET NAMES 'utf8mb4'");
        return $pdo;
    } catch (PDOException $e) {
        // In production do not echo errors
        die('Database connection failed: ' . $e->getMessage());
    }
}

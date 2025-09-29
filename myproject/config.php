<?php
// config.php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'ngo_rmf_enhanced';
$DB_USER = 'root';
$DB_PASS = ''; // change if you have a password

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (Exception $e) {
    die('DB error: ' . htmlspecialchars($e->getMessage()));
}

// Base URL for links (adjust if not on root)
$BASE_URL = '/myproject/ngorisk_enhanced';

?>

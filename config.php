<?php
// Database connection settings for a standard XAMPP installation.
$host = 'localhost';
$db   = 'tourism_db';
$user = 'root';
$pass = ''; // XAMPP default. Set this if you have created a MySQL password.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check that MySQL is running and config.php has the correct settings.');
}

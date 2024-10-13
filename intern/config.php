<?php
// Database configuration
$host = '127.0.0.1:3306';
$db   = 'u682755333_ims';
$user = 'u682755333_ITA4';
$pass = 'z&3N|?wN3|'; //Password
$charset = 'utf8mb4';

date_default_timezone_set('Asia/Manila');
// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>

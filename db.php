<?php
// db.php
$host    = 'localhost';
$port    = '3307';       // Updated port
$db      = 'petstore';   // Your database name
$user    = 'root';       // Your database user
$pass    = '';           // Your database password
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log this error instead of displaying it
    die("Database connection failed: " . $e->getMessage());
}
?>

<?php
$host = getenv('DB_HOST') ?: "website-database-iqd1kg";
$dbname = getenv('DB_NAME') ?: "web_db";
$username = getenv('DB_USER') ?: "web_db";
$password = getenv('DB_PASS') ?: "@Qweasz123";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

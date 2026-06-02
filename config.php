<?php
$host = getenv('DB_HOST') ?: "localhost";
$dbname = getenv('DB_NAME') ?: "alilogis_butbet";
$username = getenv('DB_USER') ?: "alilogis_butbet";
$password = getenv('DB_PASS') ?: "JwSKVWdcFujDMbrgyzbs";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

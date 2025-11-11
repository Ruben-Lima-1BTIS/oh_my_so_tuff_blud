<?php

$host = "localhost";
$dbname ="internhub_nova";
$user = "root";
$password = "";

try {
    $conn = new PDO(dsn: "mysql:host=$host;dbname=$dbname", username: $user, password: $password);
    $conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    echo "Conexao bem feita (bite lip emoji)";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
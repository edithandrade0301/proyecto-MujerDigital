<?php
// get-productos.php

header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM productos");
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
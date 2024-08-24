<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";

// Conexión 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_SESSION['usuario_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['usuario_id'];
    $product_id = $_GET['id'];
    
    // si el producto ya esta en el carrito 
    $sql = "SELECT cantidad FROM carritos WHERE usuario_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Si el producto ya esta en el carrito se incrementa la cantidad duh
        $row = $result->fetch_assoc();
        $new_quantity = $row['cantidad'] + 1;
        $sql = "UPDATE carritos SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Si el producto no esta en el carrito se agrega mas po
        $sql = "INSERT INTO carritos (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    
    $stmt->close();
}

$conn->close();

// Redirigir
header("Location: carrito.php");
exit();
?>
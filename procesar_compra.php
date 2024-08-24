<?php
session_start();

// Verificacion
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar-sesion.html");
    exit();
}

// Conexion BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID 
$user_id = $_SESSION['usuario_id'];

// Calcular el total 
$sql = "SELECT productos.id, productos.precio, carritos.cantidad 
        FROM productos 
        JOIN carritos ON productos.id = carritos.producto_id 
        WHERE carritos.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$productos = [];
while ($row = $result->fetch_assoc()) {
    $total += $row['precio'] * $row['cantidad'];
    $productos[] = [
        'producto_id' => $row['id'],
        'cantidad' => $row['cantidad']
    ];
}
$stmt->close();

// Calcular impuestos
$tax_rate = 0.12;
$tax = $total * $tax_rate;
$total_with_tax = $total + $tax;

// INSERTAR DATOS
$sql = "INSERT INTO ordenes (usuario_id, fecha_orden, total, impuesto, total_con_impuesto) VALUES (?, NOW(), ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iddd", $user_id, $total, $tax, $total_with_tax);
$stmt->execute();

// Obtener el ID 
$order_id = $stmt->insert_id;
$stmt->close();

// Guardar 
foreach ($productos as $producto) {
    $sql = "INSERT INTO ordenes_productos (orden_id, producto_id, cantidad) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $order_id, $producto['producto_id'], $producto['cantidad']);
    $stmt->execute();
}
$stmt->close();

// Eliminar
$sql = "DELETE FROM carritos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Cerrar la conexion
$conn->close();

// Redirigir
header("Location: mis-ordenes.php");
exit();
?>
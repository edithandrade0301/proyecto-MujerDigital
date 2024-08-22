<?php
session_start();

// Conexión BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";

$conn = new mysqli($servername, $username, $password, $dbname);

// conexion
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validaciones
if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
    header("Location: registrarse.html?error=emptyfields");
    exit();
}

// Verificaciones
$sql = "SELECT correo FROM usuarios WHERE correo = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    header("Location: registrarse.html?error=emailtaken");
    exit();
}

// INSERT de datos
$sql = "INSERT INTO usuarios (nombre, apellido, correo, password) VALUES ('$nombre', '$apellido', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    // Redirigir
    header("Location: confirmacion.html");
    exit();
} else {
    // Redirigir a la página de registro con un mensaje de error
    header("Location: registrarse.html?error=sqlerror");
    exit();
}

// Cerrar la conexión
$conn->close();
?>
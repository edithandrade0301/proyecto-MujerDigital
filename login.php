<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE correo = '$email' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['user'] = $email;
    
    // redirigir a home.html
    echo "<script>
        localStorage.setItem('isAuthenticated', 'true');
        window.location.href = 'home.html';
        </script>";
    exit();
} else {
    header("Location: iniciar-sesion.html?error=1");
    exit();
}

$conn->close();
?>
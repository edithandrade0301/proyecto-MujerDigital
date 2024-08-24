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
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

$sql = "SELECT id FROM usuarios WHERE correo = '$email' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['usuario_id'] = $row['id'];
    echo "<script>
        localStorage.setItem('isAuthenticated', 'true');
        window.location.href = 'home.php';
    </script>";
} else {
    echo "<script>
        alert('Correo o contraseña incorrectos');
        window.location.href = 'iniciar-sesion.html';
    </script>";
}

$conn->close();
?>
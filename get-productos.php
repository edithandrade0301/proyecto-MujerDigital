<?php
$servername = "localhost"; // o el nombre de tu servidor
$username = "root";
$password = "";
$dbname = "databasecats";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener productos
$sql = "SELECT nombre, precio, imagen FROM productos"; // Asegúrate de que "productos" sea el nombre de tu tabla

$result = $conn->query($sql);




if ($result->num_rows > 0) {
    // Salida de datos para cada fila
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "'>";
        echo "<p>" . $row['nombre'] . "<br>L." . number_format($row['precio'], 2) . "</p>";
        echo "<a href='#' class='cart-icon'><i class='fa-solid fa-cart-plus'></i><span class='tooltip'>Agregar al carrito</span></a>";
        echo "</div>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
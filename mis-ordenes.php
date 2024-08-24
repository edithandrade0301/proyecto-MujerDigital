<?php
session_start();

// Verificacion
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar-sesion.html");
    exit();
}

// Conexion
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener ordenes
$user_id = $_SESSION['usuario_id'];
$sql = "SELECT id, fecha_orden, total, impuesto, total_con_impuesto FROM ordenes WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles-carrito.css">
    <!--LIBRERIA DE ICONOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Mis Órdenes</title>
    <!--KANIT-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<header class="container">
        <nav class="titulo">
            <div class="logo-container">
                <a href="#"><img src="img/icono.png" id="logo" alt="Logo"></a>
            </div>
            <div class="search-container">
                <form class="search-form">
                    <input type="text" placeholder="Buscar">
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <div class="links-container">
                <a href="home.php"><span class="blanco">HOME</span></a>
                <a href="mis-ordenes.php"><span class="blanco">Ver mis órdenes</span></a>
                <a href="#redes-sociales"><span class="blanco">Redes</span></a>
                <div class="categorias" id="categorias"><i class="fa-solid fa-user"></i>
                    <ul>
                        <a href="index.html" onclick="logout()"><li>cerrar sesión</li></a>
                    </ul>  
                </div>
                <a href="carrito.php"><i class="fa-solid fa-cart-plus"></i></a>
            </div>
        </nav>
    </header>

    <div class="container sec-1">
    <?php if ($orders->num_rows > 0): ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="order-item">
                <?php
                // Obtener los productos
                $order_id = $order['id'];
                $sql = "SELECT productos.nombre, productos.precio, productos.imagen, ordenes_productos.cantidad 
                        FROM ordenes_productos
                        JOIN productos ON ordenes_productos.producto_id = productos.id
                        WHERE ordenes_productos.orden_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $order_id);
                $stmt->execute();
                $products = $stmt->get_result();
                ?>

                <?php if ($products->num_rows > 0): ?>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <?php
                        $precio_inicial = $product['precio'];
                        $iva = $precio_inicial * 0.12;
                        $precio_total = ($precio_inicial + $iva) * $product['cantidad'];
                        ?>
                        <div class="item">
                            <img src="<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>">
                            <span>Artículo: <?php echo $product['nombre']; ?></span>
                            <span>Cantidad: <?php echo $product['cantidad']; ?></span>
                            <span>Precio Inicial: L.<?php echo number_format($precio_inicial, 2); ?></span>
                            <span>ISV (12%): L.<?php echo number_format($iva, 2); ?></span>
                            <span>Total: L.<?php echo number_format($precio_total, 2); ?></span>
                            <div class="order-summary">
                    <span>Fecha: <?php echo $order['fecha_orden']; ?></span>
                </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
                
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No tienes órdenes.</p>
    <?php endif; ?>

    <?php
    // Cerrar la conexion
    $conn->close();
    ?>
</div>
    <footer>
        <div class="container-footer sec-4 imgfooter">
            <img src="img/marcaslogos.png" alt="Marcas y Logos">
        </div>

        <div class="container-footer">
            <p>Si tienes dudas contacta a:</p>
        </div>
        <div class="container-footer redes" id="redes-sociales">
            <a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://twitter.com/" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.linkedin.com/" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </footer>

    <script> 
    if (localStorage.getItem('isAuthenticated') !== 'true') {
        window.location.href = 'index.html';
    }
    function logout() {
        localStorage.removeItem('isAuthenticated');
    } 
    </script>
</body>
</html>
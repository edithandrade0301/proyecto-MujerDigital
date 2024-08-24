<?php
session_start();

// Inicializar variables
$total = 0;
$tax_rate = 0.12;
$tax = 0;
$total_with_tax = 0;

// Verifica si el usuario esta autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar-sesion.html");
    exit();
}

// Conexión BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databasecats";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualizar cantidad 
if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['id'];
    $new_quantity = max(1, (int)$_POST['quantity']);
    
    // Verifica si el producto esta en el carrito
    $user_id = $_SESSION['usuario_id'];
    $sql = "SELECT * FROM carritos WHERE usuario_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Actualizar la cantidad
        $sql = "UPDATE carritos SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Agregar el producto al carrito
        $sql = "INSERT INTO carritos (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user_id, $product_id, $new_quantity);
        $stmt->execute();
    }
    $stmt->close();
    header("Location: carrito.php");
    exit();
}

// Eliminar producto del carrito
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['id'])) {
    $product_id = $_POST['id'];
    
    // Elimina el producto del carrito
    $user_id = $_SESSION['usuario_id'];
    $sql = "DELETE FROM carritos WHERE usuario_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: carrito.php");
    exit();
}

// Calcular el total
$cart_items = array();
if (isset($_SESSION['usuario_id'])) {
    $user_id = $_SESSION['usuario_id'];
    $sql = "SELECT productos.id, productos.nombre, productos.precio, productos.imagen, carritos.cantidad 
            FROM productos 
            JOIN carritos ON productos.id = carritos.producto_id 
            WHERE carritos.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[$row['id']] = $row;
        $cart_items[$row['id']]['quantity'] = $row['cantidad'];
    }
    $stmt->close();
    
    foreach ($cart_items as $product) {
        $total += $product['precio'] * $product['quantity'];
    }
    $tax = $total * $tax_rate;
    $total_with_tax = $total + $tax;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles-carrito.css">
    <!--LIBRERIA DE ICONOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Carrito</title>
    <!--KANIT-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="container">
        <nav class="titulo">
            <div class="logo-container">
                <img src="img/icono.png" id="logo">
            </div>
            <div class="search-container">
                <form class="search-form">
                    <input type="text" placeholder="Buscar">
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <div class="links-container">
                <a href="home.php"><span class="blanco">HOME</span></a>
                <a href="Mis-ordenes.html"><span class="blanco">Ver mis ordenes</span></a>
                <a href="#redes-sociales"><span class="blanco">Redes</span></a>
                <div class="categorias" id="categorias"><i class="fa-solid fa-user"></i>
                    <ul>
                        <a href="index.html" onclick="logout()"><li>cerrar sesion</li></a>
                    </ul>  
                </div>
                <a href="carrito.php"><i class="fa-solid fa-cart-plus"></i></a>
            </div>
        </nav>
    </header>

    <!--PRODUCTOS EN EL CARRITO-->   
    <div class="container sec-1">
        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $id => $product): ?>
                <div class="item">
                    <img src="<?php echo $product['imagen']; ?>">
                    <span>Artículo: <?php echo $product['nombre']; ?></span>
                    <span>Precio unitario: L.<?php echo number_format($product['precio'], 2); ?></span>

                    <!-- Formulario para actualizar la cantidad -->
                    <form method="post" action="carrito.php" style="display:inline;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <span>Cantidad: 
                            <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1" onchange="this.form.submit()">
                        </span>
                    </form>

                    <span>Precio inicial: L.<?php echo number_format($product['precio'] * $product['quantity'], 2); ?></span>
                    <span>ISV (12%): L.<?php echo number_format($product['precio'] * $product['quantity'] * 0.12, 2); ?></span>
                    <span>Total a pagar: L.<?php echo number_format($product['precio'] * $product['quantity'] * 1.12, 2); ?></span>

                    <form method="post" action="carrito.php" style="display:inline;">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit">Quitar del carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <div class="total">
                <span>Total a pagar: L.<?php echo number_format($total_with_tax, 2); ?></span>
                <form method="post" action="procesar_compra.php">
                    <button type="submit">Procesar compra</button>
                </form> 
            </div>
        <?php else: ?>
            <p>No hay productos en el carrito.</p>
        <?php endif; ?>
    </div>
    
    <footer>
        <div class="container-footer sec-4 imgfooter">
            <img src="img/marcaslogos.png">
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
</body>
</html>
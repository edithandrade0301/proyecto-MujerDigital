<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles-pag-home-php.css">
    <!--LIBRERIA DE ICONOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--KANIT-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>HOME</title>
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

                <a href="#redes-sociales"><span class="blanco">Redes</span></a>

                <div class="categorias" id="categorias"><i class="fa-solid fa-user"></i>
                    <ul>
                        <a href="Mis-ordenes.html"><li>Ver ordenes</li></a>
                        <a href="index.html" onclick="logout()"><li>cerrar sesion</li></a>
                    </ul>  
                </div>
                <a href="carrito.html"><i class="fa-solid fa-cart-plus"></i></a>
            </div>
        </nav>
    </header>

    <div class="container sec-1">
        <div class="text-container">
            <h1>CATEGORÍAS</h1> 
        </div>
    </div>

    <!--CAROUSEL-->
    <div class="container sec-2">
        <div id="carousel" class="carousel">
            <div class="carousel-track">

                <div class="carousel-item">
                    <img src="img/home-carouse-1.jpg" alt="Imagen 1">
                    <div class="overlay">
                    <div class="message">De Cuerda</div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="img/home-carouse-2.jpg" alt="Imagen 2">
                    <div class="overlay">
                    <div class="message">De Arcos</div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="img/home-carouse-3.jpg" alt="Imagen 3">
                    <div class="overlay">
                    <div class="message">Percusión</div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="img/home-carouse-4.jpg" alt="Imagen 4">
                    <div class="overlay">
                    <div class="message">Viento</div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="img/home-carouse-5.jpg" alt="Imagen 5">
                    <div class="overlay">
                    <div class="message">Teclados</div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="img/home-carouse-6.jpg" alt="Imagen 5">
                    <div class="overlay">
                    <div class="message">Accesorios</div>
                    </div>
                </div>

            </div>

            
            <button class="carousel-control ant">&lt;</button>
            <button class="carousel-control sig">&gt;</button>
        </div>
    </div>

    <!--SECCION 3  -->

    <div class="container sec-1">
        <div class="text-container">
            <h1>LO MAS POPULAR</h1> 
        </div>
    </div>




    <div class="container sec-1">
        <!-- Productos -->
    

        <div class="image-container">
            <img src="img/popular-1.jpg" class="img2">    
        </div>

        <div class="image-container">   
            <img src="img/popular-2.jpg" class="img2"> 
        </div>

        <div class="image-container"> 
            <img src="img/popular-3.jpg" class="img2"> 
        </div>

    </div>



<!--CARDS 1-->
<div class="container-card sec-1">
    <div class="text-container" id="acusticas">
    <h1>PRODUCTOS</h1> 
    </div>
</div>


<div class="container-card sec-1" id="guitarras">
    <div class="container-1">
    <?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "databasecats";

//conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL 
$sql = "SELECT nombre, precio, imagen FROM productos"; // Asegúrate de que "productos" sea el nombre de tu tabla

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Salida de datos 
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
    </div> 
</div>


        <!--FOOTER -->   
    <footer>
        
        <div class="container-footer sec-4 imgfooter">
        <img src="img/marcaslogos.png">

        </div>

        <div class="container-footer">
            <p>Si tienes dudas contacta a:</p>
        </div>
        <div class="container-footer redes" id="redes-sociales">
            <a href="https://cdlmusica.com/contactenos.php"><i class="fas fa-envelope"></i></a>
            <a href="https://www.facebook.com/cdlmusica"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.google.com/maps/place/La+Casa+de+la+Música+S.A.+(Bulevar+Morazán)/@14.0993403,-87.1998377,17z/data=!3m1!4b1!4m6!3m5!1s0x8f6fa2c69dce34f5:0xf1c01f6be12cd536!8m2!3d14.0993351!4d-87.1972628!16s%2Fg%2F11f0kvx8dk?hl=es-419&entry=ttu"><i class="fas fa-map-marker-alt"></i></a>
        <hr>

        <p>
            &copy; 2024, todos los derechos reservados
        </p>
        </div>
    </footer>
    
    <div class="edition">
        <div class="lapiz">
            <a href="../edicion.html"><i class="fa-solid fa-pencil"></i></a>
        </div>    
    </div>


    <script src="js/scripts-pag-home.js"></script>
</body>
</html>
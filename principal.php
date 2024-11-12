<?php
include 'db.php'; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercio Electrónico NAEI Market</title>
    <link rel="stylesheet" href="principal.css">
</head>
    <header>
    <img src="imagenes/inae4.gif" alt="Logo" class="logo">
        <h1>Comercio Electrónico NAEI Market</h1>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Navegación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
       
        nav {
            background-color: #28a745;
            padding: 10px 20px;
        }
        ol {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
        }
        le {
            position: relative;
        }
        a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }
        e:hover {
            transform: translateY(-3px);
        }
        e p {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<nav>
    <ol>
        <le><e href="#inicio"><p class="fas fa-home"></p>Inicio</a></le>
        <le><e href="#productos"><p class="fas fa-box"></p>Productos</a></le>
        <le><a href="#categorias"><p class="fas fa-th-list"></p>Categorías</a></le>
        <le><a href="#servicios"><p class="fas fa-concierge-bell"></p>Servicios</a></le>
    </ul>
</nav>
    </header>

    <section id="inicio">
    <h2>Bienvenido a nuestra tienda en línea</h2>
    <p>Ofrecemos una amplia gama de productos y servicios al alcance de un clic.</p>
</section>

<section id="productos">
    <h2>Productos Disponibles</h2>
    <div class="productos-container">
        <?php
        $sql = "SELECT * FROM Categorias";
        $stmt = $pdo->query($sql);

        while ($categoria = $stmt->fetch()) {
            echo "<div class='categoria-seccion'>";
            echo "<h1 class='categoria-titulo'>{$categoria['nombre']}</h1>";
            echo "<div class='productos-por-categoria'>";

            $sql_productos = "SELECT * FROM Productos WHERE categoria_id = {$categoria['id']}";
            $stmt_productos = $pdo->query($sql_productos);

            if ($stmt_productos->rowCount() > 0) {
                while ($producto = $stmt_productos->fetch()) {
                    echo "<div class='producto-card'>";
                    echo "<img src='{$producto['imagen']}' alt='{$producto['nombre']}' class='producto-imagen'>"; 
                    echo "<h4>{$producto['nombre']}</h4>";
                    echo "<p>{$producto['descripcion']}</p>";
                    echo "<p>Precio: \${$producto['precio']}</p>";
                    echo "<a href='descripcion_producto.php?id={$producto['id']}' class='btn btn-descripcion'>Ver Descripción</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay productos en esta categoría.</p>";
            }

            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</section>


</section>

<section id="categorias">
    <h2>Categorías Disponibles</h2>
    <div class="categorias-container">
        <?php
        $sql = "SELECT * FROM Categorias"; 
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                echo "<div class='categoria-card'>";
                echo "<img src='{$row['imagen']}' alt='{$row['nombre']}' class='categoria-imagen'>"; 
                echo "<h3>{$row['nombre']}</h3>";
                echo "<p>{$row['descripcion']}</p>";
                echo "<a href='descripcion_categoria.php?id={$row['id']}' class='btn btn-descripcion'>Ver Detalles</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay categorías registradas</p>";
        }
        ?>
    </div>
</section>
    <section id="servicios">
        <h2>Servicios</h2>
        <p>Ofrecemos soporte técnico, asesoramiento y más para nuestros clientes.</p>
    </section>

    <footer class="footer py-5">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="footer-logo-contact text-center me-3">
                <h3> I.N.A.E </h3>
                <img src="imagenes/inae4.gif" alt="Logo" class="logo">
                    <path fill="#fff"
                        d="M247.942 23.314 256 2.444l-.35-1.293-79.717 21.003C184.433 9.86 181.513 0 181.513 0s-25.457 16.257-44.709 15.832c-19.251-.426-25.457-5.564-54.977 3.853-29.52 9.41-37.86 38.295-46.419 44.5S0 90.603 0 90.603l.058.359 24.207-7.707S17.625 89.51 3.52 108.52l-.659-.609.025.134s11.336 17.324 22.463 14.121c1.118-.325 2.377-.859 3.753-1.56 4.48 2.495 10.327 4.947 16.783 5.622 0 0-4.37-5.08-8.016-10.86.984-.634 1.994-1.293 3.02-1.96l-.476.334 9.217 3.386-1.017-8.666c.033-.017.058-.042.091-.059l9.059 3.328-1.126-7.882a76.868 76.868 0 0 1 3.436-1.693l9.443-35.717 39.045-26.634-3.103 7.808c-7.916 19.468-22.78 24.064-22.78 24.064l-6.206 2.352c-4.612 5.455-6.556 6.798-8.14 25.107 3.72-.934 7.273-1.16 10.492-.292 16.683 4.496 22.463 24.599 17.967 30.162-1.126 1.393-3.803 3.77-7.181 6.565h-6.773l-.092 5.488c-.234.184-.467.359-.693.542h-6.89l-.083 5.355c-.609.468-1.218.918-1.801 1.36-6.473.133-14.673-5.514-14.673-5.514 0 5.139 4.28 13.046 4.28 13.046s.283-.133.758-.367c-.417.309-.65.476-.65.476s17.324 11.552 28.235 7.273c9.7-3.804 34.816-23.606 56.495-32.981l65.603-17.283 8.65-22.413-49.997 13.17V83.597l58.664-15.457 8.65-22.413-67.297 17.734V43.324z" />
                </svg>

                <p><strong>Tienes alguna pregunta?</strong></p>
                <p><i class="fas fa-phone-alt"></i> (507) 6551-5025</p>
                <p><i class="fas fa-phone-alt"></i> (507) 383-7799</p>
                <p><i class="fas fa-envelope"></i> ventasweb@loltec.com</p>

                <div class="social-icons">
    <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
    <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
    <a href="#" class="youtube"><i class="fab fa-youtube"></i></a>
    <a href="#" class="tiktok"><i class="fab fa-tiktok"></i></a>
</div>

            </div>
            <div class="footer-categorias me-3">
                <h5>Categorías Destacadas</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Accesorios</a></li>
                    <li><a href="#">Componentes</a></li>
                    <li><a href="#">Computadoras</a></li>
                    <li><a href="#">Monitores & Proyectores</a></li>
                </ul>
            </div>

            <div class="footer-atencion me-3">
                <h5>Atención al cliente</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Mi Cuenta</a></li>
                    <li><a href="#">Consulta tu Orden</a></li>
                </ul>
            </div>

            <div class="footer-informacion">
                <h5>Información</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Sobre Nosotros</a></li>
                    <li><a href="#">Políticas Y Privacidad</a></li>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                    <li><a href="#">Políticas De Garantías</a></li>
                </ul>
            </div>
        </div>

        <hr>

        <div class="row mt-4">
            <div class="col text-center">
                <p>&copy; N.A.E.I Market - Todos los Derechos Reservados</p>
                <a href="#" class="btn btn-success">
                    <i class="fab fa-whatsapp"></i> Escríbenos por WhatsApp
                </a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
<?php
include 'db.php';

$sql = "SELECT id, titulo, descripcion, fecha, imagen, categoria FROM eventos ORDER BY categoria, fecha ASC";
$result = $conn->query($sql);

$informatica = [];
$contabilidad = [];
$Enfermería = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['categoria'] == 'Informática') {
            $Informática[] = $row;
        } elseif ($row['categoria'] == 'Contabilidad') {
            $contabilidad[] = $row;
        } elseif ($row['categoria'] == 'Enfermería') {
            $Enfermería[] = $row;
        }
    }
} else {
    echo "<p>No hay eventos disponibles.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Académicos</title>
    <link rel="stylesheet" href="evento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 

<body>

    <header>
        <h1 class="animated-title">
            <span>E</span>
            <span>v</span>
            <span>e</span>
            <span>n</span>
            <span>t</span>
            <span>o</span>
            <span>s</span>
            <span>&nbsp;</span>
            <span>A</span>
            <span>c</span>
            <span>a</span>
            <span>d</span>
            <span>é</span>
            <span>m</span>
            <span>i</span>
            <span>c</span>
            <span>o</span>
            <span>s</span>
        </h1>
        <h1 class="animated-title">
            <span>C</span>
            <span>e</span>
            <span>n</span>
            <span>t</span>
            <span>r</span>
            <span>o</span>
            <span>&nbsp;</span>
            <span>R</span>
            <span>e</span>
            <span>g</span>
            <span>i</span>
            <span>o</span>
            <span>n</span>
            <span>a</span>
            <span>l</span>
            <span>&nbsp;</span>
            <span>U</span>
            <span>n</span>
            <span>i</span>
            <span>v</span>
            <span>e</span>
            <span>r</span>
            <span>s</span>
            <span>i</span>
            <span>t</span>
            <span>a</span>
            <span>r</span>
            <span>i</span>
            <span>o</span>
            <span>&nbsp;</span>
            <span>D</span>
            <span>e</span>
            <span>&nbsp;</span>
            <span>B</span>
            <span>a</span>
            <span>r</span>
            <span>ú</span>
        </h1>
    </header>

    <nav class="menu-principal">
        <ul>
            <li><a href="#Informática"><i class="fas fa-laptop-code"></i> Informática</a></li>
            <li><a href="#contabilidad"><i class="fas fa-calculator"></i> Contabilidad</a></li>
            <li><a href="#Enfermería"><i class="fas fa-user-nurse"></i> Enfermería</a></li>
        </ul>
    </nav>

    <main>
        <section id="Informática">
            <h2>Facultad Informática</h2>
            <p>La Facultad de Informática ha sido un pilar fundamental en el desarrollo tecnológico de nuestra universidad. Fundada en 1995, ha formado a cientos de profesionales en el campo de la programación, el desarrollo de software y la ingeniería informática. Sus egresados lideran proyectos innovadores en el sector de la tecnología y han contribuido al avance del país en la era digital.</p>
            <div id="eventos-lista">
            <?php
if (!empty($Informática)) {
    foreach ($Informática as $evento) {
        echo "<div class='evento'>";
        echo "<img src='imagenes/" . $evento['imagen'] . "' alt='" . $evento['titulo'] . "' class='evento-img'>";
        echo "<div class='evento-info'>";
        echo "<h3>" . $evento['titulo'] . "</h3>";
        echo "<p>" . $evento['descripcion'] . "</p>";
        echo "<p><strong>Fecha:</strong> " . $evento['fecha'] . "</p>";
        echo "<a href='detalle_evento.php?id=" . $evento['id'] . "' class='btn-detalle'>Ver más detalles</a>";
        echo "<a href='inscribir_evento.php?id=" . $evento['id'] . "' class='btn-inscribir'>Inscribirse</a>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No hay eventos disponibles en Informática.</p>";
}
?>

        </section>

        <section id="contabilidad">
            <h2>Facultad Contabilidad</h2>
            <p>La Facultad de Contabilidad, establecida en 1980, se ha dedicado a la formación de profesionales en el ámbito financiero y contable. Con un enfoque en la ética y la precisión, sus estudiantes son conocidos por su capacidad para llevar las finanzas de las principales empresas del país. La facultad cuenta con programas de investigación y desarrollo que permiten a los estudiantes contribuir al crecimiento económico.</p>
            <div id="eventos-lista">
                <?php
                if (!empty($contabilidad)) {
                    foreach ($contabilidad as $evento) {
                        echo "<div class='evento'>";
                        echo "<img src='imagenes/" . $evento['imagen'] . "' alt='" . $evento['titulo'] . "' class='evento-img'>";
                        echo "<div class='evento-info'>";
                        echo "<h3>" . $evento['titulo'] . "</h3>";
                        echo "<p>" . $evento['descripcion'] . "</p>";
                        echo "<p><strong>Fecha:</strong> " . $evento['fecha'] . "</p>";
                        echo "<a href='detalle_evento.php?id=" . $evento['id'] . "' class='btn-detalle'>Ver más detalles</a>";
                        echo "<a href='inscribir_evento.php?id=" . $evento['id'] . "' class='btn-inscribir'>Inscribirse</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay eventos disponibles en Contabilidad.</p>";
                }
                ?>
            </div>
        </section>

        <section id="Enfermería">
            <h2>Facultad Enfermería</h2>
            <p>La Facultad de Enfermería es una de las más antiguas, fundada en 1975, con una misión clara: formar profesionales comprometidos con el bienestar de las personas. Sus egresados trabajan en hospitales, centros de salud y clínicas, cuidando a los pacientes con empatía y profesionalismo. La facultad ofrece programas de especialización que permiten a los estudiantes ampliar sus conocimientos y capacidades.</p>
            <div id="eventos-lista">
                <?php
                if (!empty($Enfermería)) {
                    foreach ($Enfermería as $evento) {
                        echo "<div class='evento'>";
                        echo "<img src='imagenes/" . $evento['imagen'] . "' alt='" . $evento['titulo'] . "' class='evento-img'>";
                        echo "<div class='evento-info'>";
                        echo "<h3>" . $evento['titulo'] . "</h3>";
                        echo "<p>" . $evento['descripcion'] . "</p>";
                        echo "<p><strong>Fecha:</strong> " . $evento['fecha'] . "</p>";
                        echo "<a href='detalle_evento.php?id=" . $evento['id'] . "' class='btn-detalle'>Ver más detalles</a>";
                        echo "<a href='inscribir_evento.php?id=" . $evento['id'] . "' class='btn-inscribir'>Inscribirse</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay eventos disponibles en Enfermería.</p>";
                }
                ?>
            </div>
        </section>

        <footer>
    <div class="footer-section">
        <h2>Redes Sociales</h2>
        <div class="social-icons">
            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="youtube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>

    <div class="footer-section">
        <h2>Marcas Asociadas</h2>
        <div class="logo-section">
            <img src="logo1.png" alt="Logo 1">
            <img src="logo2.png" alt="Logo 2">
            <img src="logo3.png" alt="Logo 3">
            <img src="logo4.png" alt="Logo 4">
        </div>
    </div>

    <div class="footer-section">
        <h2>Enlaces Legales</h2>
        <div class="footer-links">
            <a href="#informatica">Facultad de Informática</a></li>
            <a href="#contabilidad">Facultad de Contabilidad</a></li>
            <a href="#enfermeria">Facultad de Enfermería</a></li>
            <a href="#">Aviso Legal</a>
            <a href="#">Política de Privacidad</a>
            <a href="#">Condiciones de Uso</a>
        </div>
    </div>

    <div class="footer-section contact-info">
            <h3>Centro Regional Universitario de Barú</h3>
            <p>&copy; 2024 Marca Registrada. Todos los derechos reservados.</p>
            <p><strong>Dirección:</strong> Calle Principal, Barriada San Valentín, Barú, Chiriquí, Panamá</p>
            <p><strong>Teléfono:</strong> +507 1234-5678</p>
            <p><strong>Email:</strong> info@crubar.edu.pa</p>
        </div>
</footer>


    </main>

    <script src="script.js"></script>
</body>

</html>

<?php
$conn->close();
?>

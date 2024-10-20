<?php
session_start(); 

include 'db.php';

$mensajeError = "";

if (isset($_GET['id'])) {
    $evento_id = $_GET['id'];

    $sql = "SELECT titulo FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $evento = $result->fetch_assoc();
    } else {
        echo "Evento no encontrado.";
        exit;
    }

    $sql_inscritos = "SELECT nombre, apellido, correo FROM inscripciones WHERE evento_id = ?";
    $stmt_inscritos = $conn->prepare($sql_inscritos);
    $stmt_inscritos->bind_param("i", $evento_id);
    $stmt_inscritos->execute();
    $inscripciones = $stmt_inscritos->get_result();
} else {
    echo "ID de evento no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción a Evento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="inscripcion.css">
    <script>
        function validarFormulario() {
            var correo = document.getElementsByName('correo')[0].value;
            var regex = /^[a-zA-Z0-9._%+-]+@unachi\.ac\.pa$/;
            var mensajeError = document.getElementById('mensaje-error');

            if (!regex.test(correo)) {
                mensajeError.textContent = "Los datos no están bien ingresados. El correo debe ser de tipo @unachi.ac.pa.";
                mensajeError.style.color = "red";
                return false;
            }
            mensajeError.textContent = ""; 
            return true;
        }
    </script>
</head>

<body>

    <h2>Inscribirse en: <?php echo htmlspecialchars($evento['titulo']); ?></h2>
    
    <?php
    if (isset($_SESSION['success'])) {
        echo "<p class='success-message'>¡Inscripción realizada con éxito!</p>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p class='error-message' style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="procesar_inscripcion.php" method="POST" class="form-inscripcion" onsubmit="return validarFormulario();">
        <input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
        <label for="nombre">
            <i class="fas fa-user"></i> Nombre:
        </label>
        <input type="text" name="nombre" required>
        <label for="apellido">
            <i class="fas fa-user-tag"></i> Apellido:
        </label>
        <input type="text" name="apellido" required>
        <label for="correo">
            <i class="fas fa-envelope"></i> Correo:
        </label>
        <input type="email" name="correo" required>

        <button type="submit" class="btn-inscribir">Inscribirse</button>
        <a href="evento.php" class="btn-regresar">Volver a la lista de eventos</a>
    </form>

    <h3>Lista de inscritos</h3>
    <?php
    if ($inscripciones->num_rows > 0) {
        echo "<ul class='lista-inscritos'>";
        while ($inscrito = $inscripciones->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($inscrito['nombre']) . " " . htmlspecialchars($inscrito['apellido']) . "</strong> - " . htmlspecialchars($inscrito['correo']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aún no hay inscritos en este evento.</p>";
    }
    ?>

</body>

</html>

<?php
$stmt->close();
$stmt_inscritos->close();
$conn->close();
?>

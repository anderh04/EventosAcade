<?php
include 'db.php';

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
    <link rel="stylesheet" href="inscripcion.css">
</head>

<body>

    <h2>Inscribirse en: <?php echo htmlspecialchars($evento['titulo']); ?></h2>
    
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p class='success-message'>¡Inscripción realizada con éxito!</p>";
    }
    ?>

    <form action="procesar_inscripcion.php" method="POST">
        <input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>

        <button type="submit" class='btn-inscribir'>Inscribirse</button>
        <a href="index.php" class="btn-regresar">Volver a la lista de eventos</a>
    </form>

    <h3>Lista de inscritos</h3>
    <?php
    if ($inscripciones->num_rows > 0) {
        echo "<ul class='lista-inscritos'>";
        while ($inscrito = $inscripciones->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($inscrito['nombre']) . " " . htmlspecialchars($inscrito['apellido']) . "</strong> - " . htmlspecialchars($inscrito['correo']) . "</li>";  // Mostramos nombre y apellido
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

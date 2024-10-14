<?php
include 'db.php';

if (isset($_GET['id'])) {
    $evento_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT titulo, descripcion_larga, fecha, hora, lugar, organizador, imagen FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $evento_id); // Bind del parámetro (ID del evento)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $evento = $result->fetch_assoc();
    } else {
        echo "Evento no encontrado.";
        exit;
    }
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
    <link rel="stylesheet" href="evento.css">
    <title><?php echo htmlspecialchars($evento['titulo']); ?> - Detalles del Evento</title>
</head>

<body>

    <header>
        <h1><?php echo htmlspecialchars($evento['titulo']); ?></h1>
    </header>

    <main>
        <div class="detalle-evento">
            <img src="imagenes/<?php echo htmlspecialchars($evento['imagen']); ?>" alt="<?php echo htmlspecialchars($evento['titulo']); ?>" class="detalle-img">
            <div class="detalle-info">
                <p><strong>Descripción: </strong><?php echo htmlspecialchars($evento['descripcion_larga']); ?></p>
                <p><strong>Fecha: </strong><?php echo htmlspecialchars($evento['fecha']); ?></p>
                <p><strong>Hora: </strong><?php echo htmlspecialchars($evento['hora']); ?></p>
                <p><strong>Lugar: </strong><?php echo htmlspecialchars($evento['lugar']); ?></p>
                <p><strong>Organizador: </strong><?php echo htmlspecialchars($evento['organizador']); ?></p>
                <a href="eventos.php" class="btn-regresar">Volver a la lista de eventos</a>
            </div>
        </div>
    </main>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>

<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = $_POST['evento_id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO inscripciones (evento_id, nombre, correo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $evento_id, $nombre, $correo);

    if ($stmt->execute()) {
        header("Location: inscribir_evento.php?id=" . $evento_id . "&success=1");
        exit;
    } else {
        echo "Error al guardar la inscripción.";
    }

    $stmt->close();
}

$conn->close();
?>

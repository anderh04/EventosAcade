<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido']; 
    $correo = $_POST['correo'];
    $evento_id = $_POST['evento_id'];

    $sql_insert = "INSERT INTO inscripciones (evento_id, nombre, apellido, correo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("isss", $evento_id, $nombre, $apellido, $correo);

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

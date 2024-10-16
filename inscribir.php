<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $evento_id = $_POST['evento_id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO inscripciones (evento_id, nombre, correo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $evento_id, $nombre, $correo);

    if ($stmt->execute()) {
        echo "Inscripción exitosa. ¡Gracias por inscribirte!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

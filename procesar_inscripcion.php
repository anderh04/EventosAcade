<?php
session_start();

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido']; 
    $correo = $_POST['correo'];
    $evento_id = $_POST['evento_id'];

    if (preg_match("/^[a-zA-Z0-9._%+-]+@unachi\.ac\.pa$/", $correo)) {
        $sql_insert = "INSERT INTO inscripciones (evento_id, nombre, apellido, correo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("isss", $evento_id, $nombre, $apellido, $correo);

        if ($stmt->execute()) {
            $_SESSION['success'] = true;
            header("Location: inscribir_evento.php?id=" . $evento_id);
            exit;
        } else {
            $_SESSION['error'] = "Error al guardar la inscripción.";
            header("Location: inscribir_evento.php?id=" . $evento_id);
            exit;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Los datos no están bien ingresados. El correo debe ser de tipo @unachi.ac.pa.";
        header("Location: inscribir_evento.php?id=" . $evento_id);
        exit;
    }
}

$conn->close();
?>

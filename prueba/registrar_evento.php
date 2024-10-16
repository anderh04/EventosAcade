<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crubaru_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $conn->real_escape_string(trim($_POST['titulo']));
    $descripcion = $conn->real_escape_string(trim($_POST['descripcion']));
    $descripcion_larga = $conn->real_escape_string(trim($_POST['descripcion_larga']));
    $fecha = $conn->real_escape_string(trim($_POST['fecha']));
    $hora = !empty($_POST['hora']) ? $conn->real_escape_string(trim($_POST['hora'])) : NULL;
    $lugar = $conn->real_escape_string(trim($_POST['lugar']));
    $organizador = $conn->real_escape_string(trim($_POST['organizador']));
    $imagen = $conn->real_escape_string(trim($_POST['imagen']));
    $categoria = $conn->real_escape_string(trim($_POST['categoria']));

    $sql = $conn->prepare("INSERT INTO eventos (titulo, descripcion, descripcion_larga, fecha, hora, lugar, organizador, imagen, categoria) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssssss", $titulo, $descripcion, $descripcion_larga, $fecha, $hora, $lugar, $organizador, $imagen, $categoria);

    if ($sql->execute()) {
        echo "<script>alert('Evento registrado con éxito'); window.location.href = 'index.html';</script>";
    } else {
        echo "Error al registrar el evento: " . $conn->error;
    }

    $sql->close();
}

$conn->close();
?>

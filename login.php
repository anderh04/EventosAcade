<?php
session_start();
include 'db.php'; // Asegúrate de tener tu conexión a la base de datos aquí

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE cedula = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cedula, $password); // Usa un hash en la contraseña en producción
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si se encuentran coincidencias, iniciar sesión
        $_SESSION['cedula'] = $cedula;
        header("Location: index.php"); // Redirigir a la página de eventos
        exit();
    } else {
        $error = "Cédula o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css"> <!-- Incluye tu CSS para el formulario -->
</head>

<body>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>

</html>

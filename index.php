<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $sql = "SELECT * FROM Usuarios WHERE correo = ? AND contraseña = ? AND rol = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$correo, $password, $rol]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Correo, contraseña o rol incorrecto.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilo.css"> 
</head>
<body>
    <div class="container">
        <img src="imagenes\inae4.gif" alt="Logo" class="logo"> 
        <h2>Iniciar Sesión</h2>
        <form method="post">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            <br>
            <label for="rol">Rol:</label>
            <select name="rol" required>
                <option value="admin">Administrador</option>
                <option value="contador">Contador</option>
                <option value="ayudante">Ayudante</option>
            </select>
            <br>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>

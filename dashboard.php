<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <img src="imagenes/inae4.gif" alt="Logo" class="logo"> 
        <header>
            <h1>Dashboard</h1>
            <p>Bienvenido</p>
            
<?php if (!empty($user['foto_perfil'])): ?>
    <div class="profile-picture">
        <img src="imagenes/<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto de perfil" class="profile-img">
    </div>
    <div class="profile-info">
        <h2><?php echo htmlspecialchars($user['nombre']) . ' ' . htmlspecialchars($user['apellido']); ?></h2>
        <p>Rol: <?php echo htmlspecialchars($user['rol']); ?></p>
    </div>
<?php else: ?>
    <p>No se ha establecido una foto de perfil.</p>
<?php endif; ?>

            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="stats">
                <h2>Zona Administrativas</h2>
                <div class="stat-card">
                    <h3>Comercio Electrónico NAEI Market</h3>
                </div>
            </section>

            <section class="user-list">
                <h2>Datos del Usuario</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($user['correo']); ?></td>
                            <td><?php echo htmlspecialchars($user['rol']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Editar</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>">Eliminar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <?php if ($user['rol'] === 'admin'): ?>
            <section class="admin-actions">
                <h2>Acciones Administrativas</h2>
                <a href="pedidos.php" class="button">Ver Pedidos</a>
            </section>
            <?php endif; ?>

            <?php if ($user['rol'] === 'contador'): ?>
            <section class="sales-action">
                <h2>Acciones para Contadores</h2>
                <a href="ventas.php" class="button">Ver Ventas</a>
            </section>
            <?php endif; ?>

            <?php if ($user['rol'] === 'ayudante'): ?>
            <section class="product-action">
                <h2>Acciones para Ayudantes</h2>
                <a href="equipos.php" class="button">Ver acciones</a>
            </section>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

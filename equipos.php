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

if ($user['rol'] !== 'ayudante') {
    header("Location: dashboard.php");
    exit();
}

try {
    $equiposQuery = $pdo->query("SELECT e.id, e.nombre, e.descripcion, e.estado, 
                                        u.nombre AS nombre_empleados, u.apellido AS apellido_empleados
                                 FROM Equipos e
                                 LEFT JOIN empleados u ON e.empleados_id = u.id");
    $equipos = $equiposQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching equipment data: " . htmlspecialchars($e->getMessage());
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <title>Lista de Equipos</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f8;
    color: #333;
}

.container {
    width: 90%;
    max-width: 1200px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    overflow: hidden;
    text-align: center;
}

header {
    background: #0b0368;
    color: #fff;
    padding: 20px;
    text-align: center;
    border-radius: 8px 8px 0 0;
}
        nav ul {
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ffc107;
        }

        main {
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color:#0b0368;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 600px) {
            nav ul {
                flex-direction: column;
            }

            nav ul li {
                margin: 10px 0;
            }
        }
        .logo-container {
    display: flex;
    flex-direction: column; 
    align-items: center; 
    text-align: center; 
    margin: 20px; 
}

.logo {
    width: 150px; 
    height: auto; 
    border-radius: 10px; 
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
    transition: transform 0.3s; 
   
}
.logo:hover {
    transform: scale(1.05); 
}

.title {
    font-size: 24px; 
    font-weight: bold; 
    color: #333; 
    margin-top: 10px; 
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); 
}

    </style>
</head>
<body>
    <div class="container">
    <div class="logo-container">
    <img src="imagenes/inae4.gif" alt="Logo" class="logo">
    <h1 class="title">Comercio Electrónico NAEI Market</h1>
</div>

        <header>
            <h1>Lista de Equipos y Zona de Mantenimiento</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="equipment-list">
                <h2>Equipos y Sus Funciones</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID Equipo</th>
                            <th>Nombre del Equipo</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Personal Asignado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($equipos)): ?>
                            <?php foreach ($equipos as $equipo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($equipo['id']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['estado']); ?></td>
                                    <td>
                                        <?php 
                                        if ($equipo['nombre_empleados'] && $equipo['apellido_empleados']) {
                                            echo htmlspecialchars($equipo['nombre_empleados']) . " " . htmlspecialchars($equipo['apellido_empleados']);
                                        } else {
                                            echo "No asignado";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay equipos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

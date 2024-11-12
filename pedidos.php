<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

try {
    $stmt = $pdo->prepare("
    SELECT 
        Pedidos.id_pedido, 
        Clientes.nombre AS nombre_cliente, 
        Clientes.email AS email_cliente,
        Clientes.telefono AS telefono_cliente, 
        Clientes.direccion AS direccion_cliente,
        Ventas.precio_total AS total_venta, 
        Pedidos.fecha, 
        Pedidos.estado 
    FROM Pedidos
    LEFT JOIN Clientes ON Pedidos.id_cliente = Clientes.id_cliente
    LEFT JOIN Ventas ON Pedidos.venta_id = Ventas.id
");
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles1.css">
    <title>Lista de Pedidos</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Lista de Pedidos</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="description">
                <h2>Descripción</h2>
                <p>Esta sección muestra todos los pedidos realizados, junto con el nombre del cliente y el total de la venta asociada a cada pedido.</p>
            </section>

            <section class="pedido-list">
                <h2>Pedidos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Nombre Cliente</th>
                            <th>Email Cliente</th>
                            <th>Teléfono Cliente</th>
                            <th>Dirección Cliente</th>
                            <th>Total Venta</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidos)): ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['email_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['telefono_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['direccion_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['total_venta']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">No hay pedidos para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

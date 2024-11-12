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

if ($user['rol'] !== 'contador') {
    header("Location: dashboard.php");
    exit();
}

try {
    // Consulta para obtener datos de Ventas
    $salesQuery = $pdo->query("
        SELECT 
            v.id, 
            v.cantidad, 
            v.precio_total, 
            DATE(v.fecha) as fecha,
            u.nombre AS nombre_usuario, 
            p.nombre AS nombre_producto
        FROM Ventas v
        JOIN Clientes u ON v.cliente_id = u.id_cliente
        JOIN Productos p ON v.id_producto = p.id
        ORDER BY DATE(v.fecha), v.id
    ");
    $ventas = $salesQuery->fetchAll(PDO::FETCH_ASSOC);

    $dailyTotals = [];
    foreach ($ventas as $venta) {
        $date = $venta['fecha'];
        if (!isset($dailyTotals[$date])) {
            $dailyTotals[$date] = 0;
        }
        $dailyTotals[$date] += $venta['precio_total'];
    }

    // Consulta para obtener datos de Pagos
    $paymentsQuery = $pdo->query("
        SELECT 
            pa.id_pago, 
            pa.metodo_pago, 
            pa.tipo_tarjeta, 
            pa.numero_cuenta, 
            pa.monto, 
            pa.estado_pago,
            v.id AS id_venta, 
            u.nombre AS nombre_usuario, 
            p.nombre AS nombre_producto, 
            v.fecha
        FROM pagos pa
        JOIN Ventas v ON pa.id_venta = v.id
        JOIN Clientes u ON v.cliente_id = u.id_cliente
        JOIN Productos p ON v.id_producto = p.id
        ORDER BY pa.id_pago
    ");
    $pagos = $paymentsQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los datos: " . htmlspecialchars($e->getMessage());
    exit();
}

// Manejo de la actualización del estado de Pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado_pago'], $_POST['id_pago'])) {
    $estado_pago = $_POST['estado_pago'];
    $id_pago = $_POST['id_pago'];

    $updateQuery = $pdo->prepare("UPDATE pagos SET estado_pago = ? WHERE id_pago = ?");
    $updateQuery->execute([$estado_pago, $id_pago]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Lista de Ventas y Pagos</title>
</head>
<body>
    <div class="container">
        <img src="imagenes/inae4.gif" alt="Logo" class="logo"> 
        <header>
            <h1>Lista de Ventas y Pagos</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <!-- Sección de Ventas -->
            <section class="sales-list">
                <h2>Ventas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Usuario</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ventas)): ?>
                            <?php 
                                $currentDate = '';
                            ?>
                            <?php foreach ($ventas as $venta): ?>
                                <?php if ($currentDate !== $venta['fecha']): ?>
                                    <?php if ($currentDate != ''): ?>
                                        <tr style="background-color: #f0f0f0;">
                                            <td colspan="4"></td>
                                            <td><strong>Total del Día: <?php echo number_format($dailyTotals[$currentDate], 2); ?></strong></td>
                                            <td><?php echo htmlspecialchars($currentDate); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php $currentDate = $venta['fecha']; ?>
                                <?php endif; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($venta['id']); ?></td>
                                    <td><?php echo htmlspecialchars($venta['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($venta['precio_total'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr style="background-color: #f0f0f0;">
                                <td colspan="4"></td>
                                <td><strong>Total del Día: <?php echo number_format($dailyTotals[$currentDate], 2); ?></strong></td>
                                <td><?php echo htmlspecialchars($currentDate); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No hay ventas registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <!-- Sección de Pagos -->
            <section class="payments-list">
                <h2>Pagos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>ID Venta</th>
                            <th>Usuario</th>
                            <th>Producto</th>
                            <th>Fecha Venta</th>
                            <th>Método de Pago</th>
                            <th>Tipo Tarjeta</th>
                            <th>Número Cuenta</th>
                            <th>Monto</th>
                            <th>Estado Pago</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pagos)): ?>
                            <?php foreach ($pagos as $pago): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pago['id_pago']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['id_venta']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['tipo_tarjeta']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['numero_cuenta']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($pago['monto'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($pago['estado_pago']); ?></td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="id_pago" value="<?php echo $pago['id_pago']; ?>">
                                            <select name="estado_pago">
                                                <option value="completado" <?php if ($pago['estado_pago'] === 'completado') echo 'selected'; ?>>Completado</option>
                                                <option value="pendiente" <?php if ($pago['estado_pago'] === 'pendiente') echo 'selected'; ?>>Pendiente</option>
                                                <option value="fallido" <?php if ($pago['estado_pago'] === 'fallido') echo 'selected'; ?>>Fallido</option>
                                            </select>
                                            <button type="submit">Actualizar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No hay pagos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

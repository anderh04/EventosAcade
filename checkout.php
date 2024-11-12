<?php
session_start();
include 'db.php';

if (empty($_SESSION['carrito'])) {
    header('Location: carrito.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Clientes (nombre, email, telefono, direccion) 
                              VALUES (:nombre, :email, :telefono, :direccion)");
        $stmt->execute([
            ':nombre' => $_POST['nombre'],
            ':email' => $_POST['email'],
            ':telefono' => $_POST['telefono'],
            ':direccion' => $_POST['direccion']
        ]);
        $cliente_id = $pdo->lastInsertId();

        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $stmt = $pdo->prepare("INSERT INTO Ventas (cliente_id, id_producto, cantidad, precio_total) 
                              VALUES (:cliente_id, :id_producto, :cantidad, :precio_total)");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':id_producto' => array_values($_SESSION['carrito'])[0]['id'],
            ':cantidad' => array_values($_SESSION['carrito'])[0]['cantidad'],
            ':precio_total' => $total
        ]);
        $venta_id = $pdo->lastInsertId();
        

        $stmt = $pdo->prepare("INSERT INTO Pedidos (id_cliente, venta_id, estado) 
                              VALUES (:cliente_id, :venta_id, 'pendiente')");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':venta_id' => $venta_id
        ]);
        $pedido_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO Detalles_Pedido (id_pedido, id_producto, cantidad) 
                              VALUES (:id_pedido, :id_producto, :cantidad)");
        foreach ($_SESSION['carrito'] as $item) {
            $stmt->execute([
                ':id_pedido' => $pedido_id,
                ':id_producto' => $item['id'],
                ':cantidad' => $item['cantidad']
            ]);
        }

$metodo_pago = $_POST['metodo_pago'];
$tipo_tarjeta = '';

if ($metodo_pago === 'paypal') {
    $tipo_tarjeta = $_POST['tipo_paypal']; 
} else {
    
    $tipo_tarjeta = $_POST['tipo_tarjeta'];
}

$stmt = $pdo->prepare("INSERT INTO pagos (id_venta, metodo_pago, tipo_tarjeta, numero_cuenta, monto) 
                      VALUES (:id_venta, :metodo_pago, :tipo_tarjeta, :numero_cuenta, :monto)");
$stmt->execute([
    ':id_venta' => $venta_id,
    ':metodo_pago' => $metodo_pago,
    ':tipo_tarjeta' => $tipo_tarjeta, 
    ':numero_cuenta' => $_POST['numero_cuenta'],
    ':monto' => $total
]);

        $stmt = $pdo->prepare("INSERT INTO Entregas (id_venta, metodo_entrega, fecha_entrega, 
                              hora_entrega, tipo_envio) 
                              VALUES (:id_venta, :metodo_entrega, :fecha_entrega, :hora_entrega, :tipo_envio)");
        $stmt->execute([
            ':id_venta' => $venta_id,
            ':metodo_entrega' => $_POST['metodo_entrega'],
            ':fecha_entrega' => $_POST['fecha_entrega'],
            ':hora_entrega' => $_POST['hora_entrega'],
            ':tipo_envio' => $_POST['tipo_envio']
        ]);

        $stmt = $pdo->prepare("UPDATE Productos SET stock = stock - :cantidad WHERE id = :id_producto");
        foreach ($_SESSION['carrito'] as $item) {
            $stmt->execute([
                ':cantidad' => $item['cantidad'],
                ':id_producto' => $item['id']
            ]);
        }

        $pdo->commit();

        unset($_SESSION['carrito']);
        header('Location: principal.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago</title>
    <style>
        .checkout-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .checkout-form input, .checkout-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .checkout-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>
    <script>
        function togglePaymentOptions() {
            const metodoPago = document.getElementById('metodo_pago').value;
            const tarjetaOptions = document.getElementById('tipo_tarjeta');
            const paypalOptions = document.getElementById('tipo_paypal');

            tarjetaOptions.classList.add('hidden');
            paypalOptions.classList.add('hidden');

            if (metodoPago === 'tarjeta') {
                tarjetaOptions.classList.remove('hidden');
            } else if (metodoPago === 'paypal') {
                paypalOptions.classList.remove('hidden');
            }
        }
    </script>
</head>

<body>
    <div class="checkout-form">
        <h1>Formulario de Pago</h1>

        <form method="post">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" required>

            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" required>

            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" required>

            <label for="direccion">Dirección de Entrega</label>
            <input type="text" name="direccion" required>

            <label for="metodo_pago">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" onchange="togglePaymentOptions()" required>
                <option value="">Seleccionar</option>
                <option value="paypal">PayPal</option>
                <option value="tarjeta">Tarjeta de Crédito</option>
            </select>

            <div id="tipo_tarjeta" class="hidden">
                <label for="tipo_tarjeta">Tipo de Tarjeta</label>
                <select name="tipo_tarjeta">
                    <option value="visa">Visa</option>
                    <option value="mastercard">MasterCard</option>
                    <option value="AmericanExpress">AmericanExpress</option>
                    <option value="otros">Otros</option>
                </select>
            </div>

            <div id="tipo_paypal" class="hidden">
                <label for="tipo_paypal">Tipo de PayPal</label>
                <select name="tipo_paypal">
                    <option value="paypal_america">PayPal América</option>
                    <option value="paypal_usa">PayPal USA</option>
                </select>
            </div>

            <label for="numero_cuenta">Número de Cuenta</label>
            <input type="text" name="numero_cuenta" required>

            <label for="metodo_entrega">Método de Entrega</label>
            <select name="metodo_entrega" required>
                <option value="domicilio">Domicilio</option>
                <option value="recoger">Recoger en tienda</option>
            </select>

            <label for="fecha_entrega">Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" required>

            <label for="hora_entrega">Hora de Entrega</label>
            <input type="time" name="hora_entrega" required>

            <label for="tipo_envio">Tipo de Envío</label>
            <select name="tipo_envio" required>
                <option value="normal">Normal</option>
                <option value="express">Express</option>
            </select>

            <button type="submit">Confirmar Pago</button>
        </form>
    </div>
</body>

</html>

<?php

session_start();
include 'db.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            if (isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
                $producto_id = intval($_POST['producto_id']);
                $cantidad = intval($_POST['cantidad']);

                $stmt = $pdo->prepare("SELECT * FROM Productos WHERE id = ?");
                $stmt->execute([$producto_id]);
                $producto = $stmt->fetch();

                if ($producto) {
                    if (!isset($_SESSION['carrito'][$producto_id])) {
                        $_SESSION['carrito'][$producto_id] = [
                            'id' => $producto_id,
                            'nombre' => $producto['nombre'],
                            'precio' => $producto['precio'],
                            'cantidad' => $cantidad,
                            'imagen' => $producto['imagen']
                        ];
                    } else {
                        $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
                    }
                }
            }
            break;

        case 'update':
            if (isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
                $producto_id = intval($_POST['producto_id']);
                $cantidad = intval($_POST['cantidad']);

                if ($cantidad > 0) {
                    $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad;
                } else {
                    unset($_SESSION['carrito'][$producto_id]);
                }
            }
            break;

        case 'remove':
            if (isset($_POST['producto_id'])) {
                $producto_id = intval($_POST['producto_id']);
                unset($_SESSION['carrito'][$producto_id]);
            }
            break;
    }

    header('Location: carrito.php');
    exit;
}

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
    background: #ffffff;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h1 {
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
    color: #333333;
    font-weight: bold;
}

.cart-item {
    display: flex;
    border: 1px solid #ddd;
    margin-bottom: 15px;
    padding: 15px;
    align-items: center;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.cart-item:hover {
    transform: scale(1.02);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
}

.cart-item img {
    max-width: 100px;
    border-radius: 8px;
    margin-right: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.cart-item-details {
    flex-grow: 1;
}

.cart-item-details h3 {
    font-size: 20px;
    color: #333333;
    margin-bottom: 8px;
}

.cart-item-details p {
    font-size: 16px;
    color: #666666;
    margin-bottom: 5px;
}

.cart-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.quantity-input {
    width: 60px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: background 0.3s ease;
}

.quantity-input:focus {
    background-color: #f1f5f9;
    border-color: #4CAF50;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
}

.btn-update {
    background-color: #4CAF50;
    color: white;
}

.btn-update:hover {
    background-color: #388E3C;
}

.btn-remove {
    background-color: #f44336;
    color: white;
}

.btn-remove:hover {
    background-color: #d32f2f;
}

.cart-summary {
    margin-top: 30px;
    padding: 25px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.cart-summary h2 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #333333;
}

.cart-summary p {
    font-size: 20px;
    color: #666666;
}

.total-price {
    font-size: 28px;
    font-weight: bold;
    color: #333333;
    margin-top: 15px;
}

.checkout-button {
    background-color: #4CAF50;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    margin-top: 20px;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.checkout-button:hover {
    background-color: #388E3C;
    transform: translateY(-3px);
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
}

a {
    color: #4CAF50;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #388E3C;
}

    </style>
</head>

<body>
    <div class="cart-container">
        <h1>Tu Carrito de Compras</h1>

        <?php if (empty($_SESSION['carrito'])): ?>
            <p>Tu carrito está vacío</p>
        <?php else: ?>
            <?php foreach ($_SESSION['carrito'] as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($item['nombre']); ?>">

                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                        <p>Precio: $<?php echo number_format($item['precio'], 2); ?></p>

                        <div class="cart-actions">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="producto_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>"
                                    min="1" class="quantity-input">
                                <button type="submit" class="btn btn-update">Actualizar</button>
                            </form>

                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="producto_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-remove">Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <div class="cart-item-total">
                        <strong>Subtotal: $<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <h2>Resumen del Carrito</h2>
                <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
                <button onclick="window.location='checkout.php'" class="btn btn-update">
                    Proceder al Pago
                </button>
            </div>
        <?php endif; ?>

        <p><a href="principal.php">Continuar Comprando</a></p>
    </div>
</body>

</html>
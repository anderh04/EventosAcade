<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id_producto = intval($_GET['id']); 

    $sql = "SELECT * FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_producto, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $producto = $stmt->fetch();
    } else {
        echo "<h2>Producto no encontrado</h2>";
        exit; 
    }

    $sql_recommended = "SELECT * FROM productos WHERE id != :id LIMIT 4";
    $stmt_recommended = $pdo->prepare($sql_recommended);
    $stmt_recommended->bindParam(':id', $id_producto, PDO::PARAM_INT);
    $stmt_recommended->execute();
    $productos_recomendados = $stmt_recommended->fetchAll();

} else {
    echo "<h2>ID de producto no proporcionado</h2>";
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['iniciar_compra'])) {
    $producto_id = $id_producto;
    $cantidad = intval($_POST['cantidad']);

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

    header("Location: carrito.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="stylesheet" href="detalle.css">
</head>

<body>
    <header>
    <h1>Comercio Electrónico NAEI Market</h1>
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
    </header>

    <section id="descripcion">
        <h2>Descripción del Producto</h2>
        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="producto-imagen">
        <p><?php echo nl2br(htmlspecialchars($producto['descripcion_larga'])); ?></p>
        <p>Descripción corta: <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
        <p>Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
        <p>Stock: <?php echo htmlspecialchars($producto['stock']); ?> unidades</p>

        <form method="post">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" max="<?php echo $producto['stock']; ?>" required>
            <button type="submit" name="iniciar_compra" class="btn btn-comprar">Comprar</button>
        </form>
    </section>

    <section id="productos-recomendados">
        <h2>Productos que quizás quieras ver</h2>
        <div class="productos-grid">
            <?php foreach ($productos_recomendados as $producto_recomendado): ?>
                <div class="producto-item">
                    <a href="descripcion_producto.php?id=<?php echo $producto_recomendado['id']; ?>">
                        <img src="<?php echo htmlspecialchars($producto_recomendado['imagen']); ?>" alt="<?php echo htmlspecialchars($producto_recomendado['nombre']); ?>" class="producto-imagen">
                        <h3><?php echo htmlspecialchars($producto_recomendado['nombre']); ?></h3>
                        <p>Precio: $<?php echo htmlspecialchars($producto_recomendado['precio']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Comercio Electrónico NAEI Market. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>

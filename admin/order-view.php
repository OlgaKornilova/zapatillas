<?php
// Vista detallada de un pedido en el panel de administración

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Solo administradores pueden acceder
auth_check('admin');

// Inicialización de la aplicación
define('APP_INIT', true);

// Encabezado
require_once $base_path . 'includes/header.php';

// Obtenemos el ID del pedido
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("Error: ID inválido");

// Cargamos la información del pedido
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) die("Pedido no encontrado");

// Obtenemos los productos del pedido con JOIN correcto
$itemsStmt = $pdo->prepare("
    SELECT 
        oi.id,
        oi.order_id,
        oi.product_id,
        oi.size,
        oi.qty,
        oi.price AS order_price,
        oi.subtotal,
        oi.product_name,
        oi.product_price,
        p.name AS db_product_name
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="admin-page">
<div class="container">

    <div class="admin-topbar">
        <h2>Pedido #<?= $id ?></h2>
        <a href="orders.php" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="admin-card">
        <div class="admin-card-inner">

            <!-- Información del cliente -->
            <h3>Información del cliente</h3>

            <p><strong>Nombre:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>

            <hr>

            <!-- Dirección -->
            <h3>Dirección de envío</h3>

            <p><strong>Ciudad:</strong> <?= htmlspecialchars($order['city']) ?></p>
            <p><strong>Dirección:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Código postal:</strong> <?= htmlspecialchars($order['postal_code']) ?></p>

            <hr>

            <!-- Métodos -->
            <h3>Métodos</h3>

            <p><strong>Envío:</strong> <?= htmlspecialchars($order['shipping_method']) ?></p>
            <p><strong>Pago:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>

            <hr>

            <!-- Productos -->
            <h3>Contenido del pedido</h3>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio unitario</th>
                        <th>Cantidad</th>
                        <th>Talla</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($items as $i): ?>
                        <?php
                            $unit = (float)$i['order_price'];  // precio guardado en el pedido
                            $qty = (int)$i['qty'];
                            $subtotal = (float)$i['subtotal'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($i['product_name']) ?></td>
                            <td>€<?= number_format($unit, 2) ?></td>
                            <td><?= $qty ?></td>
                            <td><?= htmlspecialchars($i['size']) ?></td>
                            <td><strong>€<?= number_format($subtotal, 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr>

            <!-- Resumen -->
            <h3>Resumen</h3>

            <p><strong>Subtotal:</strong> €<?= number_format($order['subtotal'], 2) ?></p>
            <p><strong>IVA:</strong> €<?= number_format($order['tax'], 2) ?></p>
            <p><strong>Total:</strong> <strong>€<?= number_format($order['total'], 2) ?></strong></p>

        </div>
    </div>

</div>
</section>

<?php require_once $base_path . 'includes/footer.php'; ?>

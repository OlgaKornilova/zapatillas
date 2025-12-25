<?php
// Página principal del panel administrador

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario tenga privilegios de administrador
auth_check('admin');

// Cargamos los datos desde la base de datos
// Obtenemos los últimos 5 usuarios
$latestUsers = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
// Obtenemos los últimos 5 productos
$latestProducts = $pdo->query("SELECT id, name, price, created_at FROM products ORDER BY created_at DESC LIMIT 5")->fetchAll();
// Obtenemos los últimos 5 pedidos
$latestOrders = $pdo->query("SELECT id, customer_name, customer_email, total, payment_method, shipping_method, created_at FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Incluimos el encabezado de la página
include $base_path . 'includes/header.php';
?>

<section class="admin-dashboard">
    <div class="container">

        <h2>Panel de administración</h2>

        <div class="admin-links">
            <a href="users.php" class="btn btn-primary">Gestión de usuarios</a>
            <a href="products.php" class="btn btn-primary">Gestión de productos</a>
            <a href="orders.php" class="btn btn-primary">Gestión de pedidos</a>
        </div>

        <div class="admin-stats">
            <div class="admin-card">
                <div class="admin-card-inner">
                <h3>Últimos usuarios</h3>
                <?php if ($latestUsers): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Mostramos los usuarios -->
                            <?php foreach ($latestUsers as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty">No hay usuarios</p>
                <?php endif; ?>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-inner">
                <h3>Últimos productos</h3>
                <?php if ($latestProducts): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Mostramos los productos -->
                            <?php foreach ($latestProducts as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= number_format($p['price'], 2, '.', ' ') ?> €</td>
                                    <td><?= date('d.m.Y', strtotime($p['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty">No hay productos</p>
                <?php endif; ?>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-inner">
                    <h3>Últimos pedidos</h3>
                    <?php if ($latestOrders): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Envío</th>
                                    <th>Pago</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latestOrders as $o): ?>
                                    <tr>
                                        <td><?= $o['id'] ?></td>
                                        <td><?= htmlspecialchars($o['customer_name'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars($o['customer_email'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars($o['shipping_method']) ?></td>
                                        <td><?= htmlspecialchars($o['payment_method']) ?></td>
                                        <td><?= number_format($o['total'], 2, '.', ' ') ?> €</td>
                                        <td><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="empty">No hay pedidos</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include $base_path . 'includes/footer.php'; ?>

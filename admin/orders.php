<?php
// Página de administración de pedidos

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . '/includes/auth_check.php';

// Comprobamos que el usuario tenga privilegios de administrador
auth_check('admin');

// Incluimos el encabezado de la página
require_once $base_path . '/includes/header.php';

// Obtenemos todos los pedidos de la base de datos, ordenados por ID en orden descendente
$stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="admin-page">
    <div class="container">

        <div class="admin-topbar">
            <h2>Pedidos</h2>
        </div>

        <div class="admin-list">
            <div class="admin-card">
                <div class="admin-card-inner">

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td>#<?= $o['id'] ?></td>
                                <td><?= htmlspecialchars($o['customer_name']) ?></td>
                                <td><?= htmlspecialchars($o['customer_email']) ?></td>
                                <td><?= htmlspecialchars($o['customer_phone']) ?></td>
                                <td><strong>€<?= number_format($o['total'], 2) ?></strong></td>
                                <td><?= $o['created_at'] ?></td>

                                <td>
                                    <div class="admin-actions">
                                        <!-- Enlace para ver los detalles del pedido -->
                                        <a href="order-view.php?id=<?= $o['id'] ?>" class="btn-edit">🔍</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once $base_path . 'includes/footer.php'; ?>

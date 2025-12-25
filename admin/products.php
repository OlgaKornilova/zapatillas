<?php
// Página de administración de productos

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario tenga privilegios de administrador
auth_check('admin');

// Eliminación de producto
if (isset($_GET['delete'])) {
    // Obtenemos el ID del producto a eliminar
    $id = (int) $_GET['delete'];

    if ($id > 0) {
        // Eliminamos el producto de la base de datos
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Redirigimos de nuevo a la página de productos
    header('Location: products.php');
    exit;
}

// Obtenemos la lista de todos los productos de la base de datos, ordenados por fecha de creación
$products = $pdo->query("
    SELECT p.*
    FROM products p
    ORDER BY p.created_at DESC
")->fetchAll();

// Incluimos el encabezado de la página
include '../includes/header.php';
?>

<section class="admin-page">
    <div class="container">

        <div class="admin-topbar">
            <h2>Productos</h2>
            <button type="button" class="btn btn-primary" onclick="location.href='product-add.php'">
            ➕ Añadir producto
            </button>
        </div>

        <div class="admin-card">
            <div class="admin-card-inner">
                <?php if (empty($products)): ?>
                    <p>No hay productos disponibles por el momento.</p>
                <?php else: ?>

                <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id']; ?></td>
                    <td><?= htmlspecialchars($p['name']); ?></td>
                    <td><?= number_format($p['price'], 2); ?> €</td>
                    <td>
                        <?php if (!empty($p['photo'])): ?>
                            <img
                                src="../uploads/products/<?= $p['id']; ?>/<?= htmlspecialchars($p['photo']); ?>"
                                width="60"
                                alt=""
                            >
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a href="product-edit.php?id=<?= $p['id']; ?>" class="btn-edit">✏️</a>
                            <!-- Usamos una ventana emergente de confirmación antes de eliminar -->
                            <a href="product-delete.php?id=<?= $p['id']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar el producto permanentemente?');">🗑</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

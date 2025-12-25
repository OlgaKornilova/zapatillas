<?php
// Página para editar un producto en el panel de administración

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario tenga privilegios de administrador
auth_check('admin');

// Obtenemos el ID del producto desde los parámetros de la solicitud
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    // Si el ID no es válido, redirigimos a la página de productos
    header('Location: products.php');
    exit;
}

// Obtenemos los datos del producto desde la base de datos
$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE id = ?
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    // Si el producto no se encuentra, redirigimos a la página de productos
    header('Location: products.php');
    exit;
}

// Obtenemos la lista de categorías para el selector
$categories = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name
")->fetchAll();

// Obtenemos las imágenes del producto desde la base de datos
$stmt = $pdo->prepare("
    SELECT id, filename, is_main
    FROM product_images
    WHERE product_id = ?
    ORDER BY is_main DESC, sort_order, id
");
$stmt->execute([$id]);
$images = $stmt->fetchAll();

// Actualización del producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos los datos del formulario
    $name        = trim($_POST['name']);
    $price       = (float) $_POST['price'];
    $description = trim($_POST['description']);
    $category_id = (int) $_POST['category_id'];

    if ($name && $price > 0 && $category_id) {
        // Actualizamos los datos del producto en la base de datos
        $stmt = $pdo->prepare("
            UPDATE products
            SET name = ?, price = ?, description = ?, category_id = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $price, $description, $category_id, $id]);

        header('Location: products.php');
        exit;
    } else {
        $error = 'Por favor, completa los campos obligatorios.';
    }
}

include '../includes/header.php';
?>

<section class="admin-page">
    <div class="container">

        <h2>Editar producto</h2>

        <?php if (!empty($error)): ?>
            <div class="admin-message error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="edit-form">
            <div class="form-item">
                <label>Categoría</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-item">
                <label>Nombre</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-item">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" required>
            </div>

            <div class="form-item">
                <label>Descripción</label>
                <textarea name="description" rows="20">
                    <?= htmlspecialchars($product['description']); ?>
                </textarea>
            </div>

            <!-- Fotos del producto, mostradas en bucle -->
            <div class="form-item">
                <label>Imágenes del producto</label>

                <?php if ($images): ?>
                    <div class="product-images-grid">
                        <?php foreach ($images as $img): ?>
                            <div class="product-image-item <?= $img['is_main'] ? 'is-main' : ''; ?>">
                                <img
                                    src="../uploads/products/<?= $product['id']; ?>/<?= htmlspecialchars($img['filename']); ?>"
                                    alt=""
                                >
                                <?php if ($img['is_main']): ?>
                                    <span class="badge-main">Principal</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No se han subido imágenes.</p>
                <?php endif; ?>

            </div>

            <div class="form-buttons-inline">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="products.php" class="btn btn-secondary">Volver</a>
            </div>

        </form>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
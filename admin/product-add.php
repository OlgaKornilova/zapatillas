<?php
// Página para añadir un nuevo producto en el panel de administración

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario tenga privilegios de administrador
auth_check('admin');

// Obtenemos la lista de categorías para seleccionar al añadir un producto
$categories = $pdo->query("
    SELECT id, name 
    FROM categories 
    ORDER BY name
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos los datos del formulario
    $name        = trim($_POST['name']);
    $price       = (float) $_POST['price'];
    $description = trim($_POST['description']);
    $category_id = (int) $_POST['category_id'];

    // Comprobamos que todos los campos obligatorios estén completos
    if (!$name || $price <= 0 || !$category_id) {
        $error = 'Por favor, completa todos los campos obligatorios.';
    } elseif (empty($_FILES['photos']['name'][0])) {
        // Comprobamos que al menos se haya subido una imagen
        $error = 'Debes añadir al menos una imagen.';
    } else {

        // 1. Añadimos el nuevo producto a la base de datos
        $stmt = $pdo->prepare("
            INSERT INTO products (name, price, description, category_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$name, $price, $description, $category_id]);

        $productId = $pdo->lastInsertId(); // Obtenemos el ID del nuevo producto

        // 2. Creamos la carpeta para almacenar las imágenes del producto
        $uploadDir = __DIR__ . '/../uploads/products/' . $productId . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 3. Generamos un ID base único para los nombres de los archivos de imagen
        $baseId = uniqid('img_');

        $mainPhoto = null;

        // 4. Renombramos y subimos cada imagen desde el formulario
        foreach ($_FILES['photos']['tmp_name'] as $i => $tmp) {

            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) {
                continue; // Saltamos los archivos con errores
            }

            $ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                continue; // Saltamos los formatos no permitidos
            }

            // Renombramos siguiendo el patrón - img_xxxx_1.jpg, img_xxxx_2.jpg
            $fileName = $baseId . '_' . ($i + 1) . '.' . $ext;
            $fullPath = $uploadDir . $fileName;

            move_uploaded_file($tmp, $fullPath);

            // Guardamos en la base de datos
            $stmt = $pdo->prepare("
                INSERT INTO product_images (product_id, filename)
                VALUES (?, ?)
            ");
            $stmt->execute([$productId, $fileName]);

            // La primera imagen será la principal
            if ($mainPhoto === null) {
                $mainPhoto = $fileName;

                $pdo->prepare("
                    UPDATE product_images
                    SET is_main = 1
                    WHERE product_id = ? AND filename = ?
                ")->execute([$productId, $fileName]);
            }
        }

        // 5. Sincronizamos la imagen principal con la tabla de productos
        if ($mainPhoto) {
            $pdo->prepare("
                UPDATE products
                SET photo = ?
                WHERE id = ?
            ")->execute([$mainPhoto, $productId]);
        }

        // 6. Redirigimos a la lista de productos
        header('Location: products.php');
        exit;
    }
}

include '../includes/header.php';
?>

<section class="admin-page">
    <div class="container">
        <h2>Añadir producto</h2>
        <?php if (!empty($error)): ?>
            <div class="admin-message error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="edit-form">

            <div class="form-item">
                <label>Categoría</label>
                <select name="category_id" required>
                    <option value="">Selecciona una categoría</option>
                    <!-- Lista de categorías -->
                    <?php foreach ($categories as $cat): ?> 
                        <option value="<?= $cat['id']; ?>">
                            <?= htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-item">
                <label>Nombre</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-item">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-item">
                <label>Descripción</label>
                <textarea name="description" rows="20"></textarea>
            </div>
            <div class="form-item">
                <label>Imágenes del producto</label>
                <input type="file" name="photos[]" multiple accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">
                Guardar producto
            </button>

        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

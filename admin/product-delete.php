<?php
// Página para eliminar un producto en el panel de administración

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

// Extraemos la lista de todas las imágenes asociadas al producto
$stmt = $pdo->prepare("
    SELECT filename 
    FROM product_images 
    WHERE product_id = ?
");
$stmt->execute([$id]);
$images = $stmt->fetchAll();

// Eliminamos los archivos del directorio del producto
$dir = __DIR__ . '/../uploads/products/' . $id . '/';

foreach ($images as $img) {
    $path = $dir . $img['filename'];
    if (file_exists($path)) {
        unlink($path); // Eliminamos el archivo
    }
}

// Eliminamos la carpeta si existe
if (is_dir($dir)) {
    rmdir($dir);
}

// Eliminamos el registro del producto en la base de datos 
// (también ON DELETE CASCADE eliminará los registros de product_images 
// debido a la clave foránea hacia la tabla products)
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

// Redirigimos de nuevo a la página de productos
header('Location: products.php');
exit;

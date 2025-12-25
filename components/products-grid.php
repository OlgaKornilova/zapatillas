<?php
// Componente para mostrar una cuadrícula de productos

if (!defined('APP_INIT')) {
    die('Acceso directo no permitido'); // Protección contra el acceso directo al archivo
}

// Establecemos los parámetros con valores por defecto
$limit  = $limit  ?? null;   // null = sin límite
$title  = $title  ?? null;   // Título de la sección
$where  = $where  ?? '1=1';  // Condición para la consulta de productos

// Consulta SQL para obtener los productos
$sql = "
    SELECT id, name, price, photo
    FROM products
    WHERE $where
    ORDER BY id DESC
";

if ($limit) {
    $sql .= " LIMIT :limit";  // Si se define un límite, lo añadimos a la consulta
}

$stmt = $pdo->prepare($sql);

if ($limit) {
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);  // Vinculamos el valor del límite a la consulta
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtenemos la lista de productos

if (!$products) return;  // Si no hay productos, salimos del script
?>

<section class="products-grid">
    <div class="container">

        <?php if ($title): ?>
            <h2 class="title"><?= htmlspecialchars($title) ?></h2>
        <?php endif; ?>

        <!-- Renderizado de las tarjetas de productos mediante un bucle -->
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-4">
                    
                    <a href="<?= $base_url ?>products-details.php?id=<?= (int)$product['id'] ?>" class="product-link">
                        <img src="<?= $base_url ?>uploads/products/<?= (int)$product['id'] ?>/<?= htmlspecialchars($product['photo']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="overlay">
                            <span class="arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 19L19 5M9 5h10v10"/>
                            </svg>
                            </span>
                        </div>
                    </a>
                    <h4><?= htmlspecialchars($product['name']) ?></h4>

                    <p>$<?= number_format($product['price'], 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

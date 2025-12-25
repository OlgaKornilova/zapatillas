<?php
// Página principal del sitio web

require_once realpath(__DIR__ . '/includes/config.php');

// Inicialización de la aplicación
define('APP_INIT', true);

// Incluimos el encabezado del sitio
include $base_path . 'includes/header.php';
?>

<header class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>¡Dale un nuevo estilo a tu entrenamiento!</h1>
            <p>El éxito no siempre se trata de grandeza. Se trata de constancia. El trabajo duro trae el éxito. La grandeza llegará.</p>
            <a href="<?= $base_url ?>products.php" class="btn btn-primary">Explora ahora →</a>
        </div>
    </div>
</header>

<!-- Tarjetas de categorías -->
<section class="categories">
    <div class="container">
        <div class="row">
            <div class="col-3 category-card">
                <div class="category-img">
                    <img src="<?= $base_url ?>assets/images/category/category-1.jpg" alt="Zapatillas casuales">
                    <span class="category-label">casuales</span>
                </div>
            </div>
            <div class="col-3 category-card">
                <div class="category-img">
                    <img src="<?= $base_url ?>assets/images/category/category-2.jpg" alt="Zapatillas para correr">
                    <span class="category-label">correr</span>
                </div>
            </div>
            <div class="col-3 category-card">
                <div class="category-img">
                    <img src="<?= $base_url ?>assets/images/category/category-3.jpg" alt="Zapatillas de entrenamiento">
                    <span class="category-label">entrenamiento</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    // Establecemos el título y el límite para las tarjetas de productos
    $title = 'Productos destacados';
    $limit = 4;

    // Incluimos el componente para mostrar los productos
    include $base_path . 'components/products-grid.php';
?>

<!-- Incluimos el pie de página del sitio -->
<?php include 'includes/footer.php'; ?>

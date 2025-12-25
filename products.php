<?php
// Página de listado de productos

require_once realpath(__DIR__ . '/includes/config.php');

// Inicialización de la aplicación
define('APP_INIT', true);

// Incluimos el encabezado
require_once $base_path . 'includes/header.php';

// Definimos el título de la página y el número de productos a mostrar
$title = 'Nuestros productos';
$limit = 12; // Límite de productos a mostrar

// Componente de productos
include $base_path . 'components/products-grid.php';

// Incluimos el pie de página
include 'includes/footer.php';
?>

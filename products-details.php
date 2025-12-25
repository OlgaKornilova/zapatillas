<?php
// Página de detalles del producto

require_once realpath(__DIR__ . '/includes/config.php');

// Inicialización de la aplicación
define('APP_INIT', true);

require_once $base_path . 'includes/header.php';

// Obtenemos el ID del producto desde el parámetro de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 
if ($id <= 0) {
    // Si el ID no es válido, mostramos un mensaje y detenemos la ejecución
    echo "<div class='container'><h2>Producto no encontrado.</h2></div>";
    include $base_path . 'includes/footer.php';
    exit;
}

// Consultamos los datos del producto en la base de datos
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    // Si el producto no existe, mostramos un mensaje y detenemos la ejecución
    echo "<div class='container'><h2>Producto no encontrado.</h2></div>";
    include $base_path . 'includes/footer.php';
    exit;
}

// Obtenemos las imágenes del producto
$stmt_imgs = $pdo->prepare("SELECT filename, is_main FROM product_images WHERE product_id = :id ORDER BY sort_order ASC, id ASC");
$stmt_imgs->execute(['id' => $id]);
$images = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);
if (!$images) $images[] = ['filename' => 'no-image.jpg', 'is_main' => 1]; // Si no hay imágenes, mostramos una por defecto

// Definimos la imagen principal
$main_image = '';
foreach ($images as $img) if ($img['is_main']) $main_image = $img['filename'];
if (!$main_image) $main_image = $images[0]['filename'];

$main_path = "uploads/products/{$product['id']}/" . $main_image; // Ruta de la imagen principal
?>

<!-- Página del producto -->
<section class="product-details">
  <div class="container">
    <div class="row">
        <nav class="breadcrumb">
          <a href="<?= $base_url ?>">Inicio</a> / 
          <a href="<?= $base_url ?>products.php">Catálogo</a> / 
          <span><?= htmlspecialchars($product['name']) ?></span>
        </nav>
    </div>
    <div class="row">

      <!-- Galería de imágenes del producto -->
      <div class="col-2 product-gallery">
        <div class="main-image">
          <img src="<?= $base_url . $main_path ?>" id="ProductImg" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <!-- Miniaturas del producto -->
        <?php if (count($images) > 1): ?>
        <div class="thumbnails">
          <?php foreach ($images as $img): ?>
            <div class="thumb">
              <img src="<?= $base_url ?>uploads/products/<?= $product['id'] ?>/<?= htmlspecialchars($img['filename']) ?>" class="small-img" alt="">
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Información del producto -->
      <div class="col-2 product-summary">
        <h1 class="product-name"><?= htmlspecialchars($product['name']) ?></h1>
        <div class="product-price">$<?= number_format($product['price'], 2) ?></div>

        <!-- Información de envío -->
        <div class="product-shipping">
          <h4>Envío y devoluciones</h4>
          <ul>
            <li>- Envío gratuito en España a partir de 50 €</li>
            <li>- Recogida gratuita en tienda</li>
            <li>- Devoluciones hasta 60 días</li>
          </ul>
        </div>

        <!-- Tallas -->
        <div class="product-sizes">
          <h4>Elige tu talla (EU)</h4>
          <div class="size-list">
            <button class="size-item">39</button>
            <button class="size-item">40</button>
            <button class="size-item">41</button>
            <button class="size-item">42</button>
            <button class="size-item">43</button>
            <button class="size-item">44</button>
            <button class="size-item unavailable">45</button>
          </div>
        </div>

        <!-- Cantidad y botón de compra -->
        <div class="product-purchase">
          <div class="quantity-block">
            <input type="number" value="1" min="1" class="quantity-input" id="qty">
            <button class="btn btn-primary" id="addToCartBtn">Añadir al carrito</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sección de descripción del producto -->
<section class="product-description-section">
  <div class="container">
    <h3>Descripción</h3>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
  </div>
</section>

<!-- Productos relacionados -->
<section class="related-products">
  <div class="container">
    <h3 class="title">Productos relacionados</h3>
    <?php
      $limit = 4; // Límite de productos a mostrar
      $title = null;       // добавь эту строку
      $where = '1=1';      // и эту
      (function() use ($base_path, $limit, $title, $where, $pdo, $base_url) {
          include $base_path . 'components/products-grid.php';
      })();
 // Componente de productos
    ?>
  </div>
</section>

<script>
const ProductImg = document.getElementById("ProductImg");

// Cambiar la imagen principal al hacer clic en una miniatura
document.querySelectorAll('.small-img').forEach(img => {
  img.addEventListener('click', () => ProductImg.src = img.src);
});

let selectedSize = null; // Talla seleccionada

// Selección de talla
document.querySelectorAll('.size-item').forEach(btn => {
  btn.addEventListener('click', () => {
    if (btn.classList.contains('unavailable')) return; // Saltar tallas no disponibles

    selectedSize = btn.textContent.trim();
    document.querySelectorAll('.size-item').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});

// Función para mostrar notificaciones
function showToast(message, type = "success") {
  const container = document.querySelector(".toast-container");

  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `
      <svg viewBox="0 0 24 24">
          <path d="M9 16.17l-3.88-3.88L4 13.41l5 5 11-11-1.41-1.42z"/>
      </svg>
      <span>${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => toast.classList.add("show"), 10);

  setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 300);
  }, 2800);
}

// Añadir producto al carrito
document.getElementById('addToCartBtn').addEventListener('click', () => {
  if (!selectedSize) {
    showToast("Por favor, selecciona una talla", "error");
    return;
  }

  const qty = document.getElementById('qty').value;
  const productId = <?= (int)$product['id'] ?>;

  fetch("<?= $base_url ?>cart-add.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `id=${productId}&size=${selectedSize}&qty=${qty}`
  })
  .then(res => res.json())
  .then(data => {
  if (data.status === "success") {
    showToast("Producto añadido al carrito");

    // Actualizamos el contador del carrito en el icono del carrito
    const cartIcon = document.querySelector('.js-call-minicart');
    if (cartIcon) {
      let cartCount = cartIcon.querySelector('.cart-count');
      const addQty = parseInt(qty);

      if (cartCount) {
        cartCount.textContent = parseInt(cartCount.textContent) + addQty;
      } else {
        const newCount = document.createElement('span');
        newCount.className = 'cart-count';
        newCount.textContent = addQty;
        cartIcon.appendChild(newCount);
      }
    }

  } else {
    showToast("Error al añadir al carrito", "error");
  }
});
});
</script>

<?php include $base_path . 'includes/footer.php'; ?>
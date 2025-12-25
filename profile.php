<?php
// Página del perfil de usuario

require_once realpath(__DIR__ . '/includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario esté autenticado
auth_check('user');

// Inicialización de la aplicación
define('APP_INIT', true);

require_once $base_path . 'includes/header.php';

// --- Cargamos los datos del usuario desde la base de datos ---
$stmt = $pdo->prepare("SELECT username, email, role, created_at FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();

if (!$user) {
    // Si el usuario no existe, mostramos un mensaje y detenemos la ejecución
    echo "<div class='container'><p class='error'>Usuario no encontrado.</p></div>";
    include $base_path . 'includes/footer.php';
    exit;
}
?>

<section class="profile-page">
  <div class="container">
    <h2>Perfil de usuario</h2>
    <div class="profile-wrapper">
      <!-- Sidebar izquierdo -->
      <aside class="profile-sidebar">
        <ul class="profile-menu">
          <!-- Menú del perfil -->
          <li><a href="#info" class="active">Información personal</a></li>
          <li><a href="#orders">Mis pedidos</a></li>
          <li><a href="#settings">Configuración</a></li>
        </ul>
      </aside>

      <!-- Sidebar derecho -->
      <div class="profile-content">
        <!-- Información personal -->
        <section id="info" class="profile-section active">
          <h3>Información personal</h3>
          <ul class="profile-info">
            <li><strong>Nombre de usuario:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
            <li><strong>Fecha de registro:</strong> <?= date('d.m.Y', strtotime($user['created_at'])) ?></li>
            <li><strong>Rol:</strong> <?= htmlspecialchars($user['role']) === 'admin' ? 'Administrador' : 'Cliente' ?></li>
          </ul>
        </section>
        <!-- Pedidos -->
        <section id="orders" class="profile-section">
          <h3>Mis pedidos</h3>

          <div class="empty-orders">
            <p>Aún no tienes historial de pedidos.</p>
            <a href="<?= $base_url ?>products.php" class="btn btn-primary">Ir al catálogo</a>
          </div>
        </section>
        <!-- Configuración -->
        <section id="settings" class="profile-section">
          <h3>Configuración</h3>
          <form action="update_profile.php" method="post" class="settings-form">
            <div class="form-item">
              <label>Cambiar email:</label>
              <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-item">
              <label>Cambiar contraseña:</label>
              <input type="password" name="new_password" placeholder="Nueva contraseña">
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </form>
        </section>
      </div>
    </div>
  </div>
</section>

<?php include $base_path . 'includes/footer.php'; ?>

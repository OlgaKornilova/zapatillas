<? // Footer del sitio web ?>

<footer class="footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-col footer-col-1">
        <!-- Logotipo y descripción de la empresa -->
        <img src="<?= $base_url ?>assets/images/logo-light.png" class="footer-logo" alt="logo">
        <p>Haciendo el deporte accesible a través de productos sostenibles e innovadores para todos.</p>
      </div>

      <div class="footer-col footer-col-2">
        <!-- Enlaces útiles -->
        <h3>Enlaces útiles</h3>
        <ul>
          <li><a href="#">Cupones</a></li>
          <li><a href="#">Política de devoluciones</a></li>
        </ul>
      </div>

      <div class="footer-col footer-col-3">
        <!-- Enlaces a redes sociales -->
        <h3>Síguenos</h3>
        <ul class="footer-socials">
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Instagram</a></li>
        </ul>
      </div>
    </div>

    <hr>
    <p class="copyright">© 2025 zapatillas.es . Todos los derechos reservados.</p>

  </div>
</footer>

<!-- Ventana modal -->
<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="login-modal-title">

      <header class="modal__header">
        <!-- Título de la ventana modal -->
        <h2 class="modal__title" id="login-modal-title">Inicio de sesión</h2>
        <button class="modal__close" aria-label="Cerrar ventana" data-micromodal-close></button>
      </header>

      <main class="modal__content" id="login-modal-content">
        <!-- Formulario de inicio de sesión -->
        <form id="loginForm" class="login-form" action="auth/login.php" method="POST">
          <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
          <div class="form-item">
            <input type="text" name="username" placeholder="Nombre de usuario o correo electrónico" required>
          </div>
          <div class="form-item">
            <input type="password" name="password" placeholder="Contraseña" required>
          </div>
          <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
        <p class="register-link">¿No tienes cuenta? <a href="<?= $base_url ?>account.php?register">Regístrate</a></p>
      </main>

    </div>
  </div>
</div>

<!-- Contenedor para notificaciones -->
<div class="toast-container"></div>

</body>
</html>
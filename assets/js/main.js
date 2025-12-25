document.addEventListener("DOMContentLoaded", () => {
  // 1. Pestañas (Login/Register)
  // Gestionamos el cambio entre las pestañas "Iniciar sesión" y "Registrarse"
  const tabs = document.querySelectorAll(".tab");
  const forms = document.querySelectorAll(".form");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // Quitamos la clase de todas las pestañas y formularios
      tabs.forEach(t => t.classList.remove("active"));
      forms.forEach(f => f.classList.remove("active"));

      // Añadimos la clase a la pestaña seleccionada y a su formulario correspondiente
      tab.classList.add("active");

      const form = document.getElementById(tab.dataset.tab + "-form");
      if (form) form.classList.add("active");

      // Actualizamos la URL sin recargar la página
      const param = tab.dataset.tab === "login" ? "?login" : "?register";
      history.replaceState(null, "", "account.php" + param);

      // === NUEVO: Ocultamos el mensaje .alert al cambiar de pestaña ===
      const alertBox = document.querySelector(".alert");
      if (alertBox) {
        alertBox.style.transition = "opacity 0.3s ease";
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.remove(), 300);
      }
    });
  });


  // 2. Menú del usuario
  // Mostramos y ocultamos el menú desplegable del usuario
  const userMenu = document.querySelector(".user-menu-wrapper");
  if (userMenu) {
    const toggle = userMenu.querySelector(".user-toggle");
    const dropdown = userMenu.querySelector(".user-dropdown");

    if (toggle && dropdown) {
      userMenu.addEventListener("mouseenter", () => userMenu.classList.add("active"));
      userMenu.addEventListener("mouseleave", () => userMenu.classList.remove("active"));
    }

    document.addEventListener("click", (e) => {
      // Cerramos el menú si el clic fue fuera de su área
      if (!userMenu.contains(e.target)) {
        userMenu.classList.remove("active");
      }
    });
  }

  // 3. Secciones del perfil
  // Cambio entre las secciones del perfil
  const links = document.querySelectorAll(".profile-menu a");
  const sections = document.querySelectorAll(".profile-section");

  if (links.length && sections.length) {
    links.forEach(link => {
      link.addEventListener("click", e => {
        e.preventDefault();

        // Quitamos la clase de todos los enlaces y la añadimos al seleccionado
        links.forEach(l => l.classList.remove("active"));
        link.classList.add("active");

        const target = link.getAttribute("href").substring(1);

        // Mostramos la sección correspondiente del perfil
        sections.forEach(sec => {
          sec.classList.toggle("active", sec.id === target);
        });
      });
    });
  }
});

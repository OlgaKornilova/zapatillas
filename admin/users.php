<?php
// Página de administración de usuarios

require_once realpath(__DIR__ . '/../includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario sea administrador
auth_check('admin');

// Activamos el almacenamiento en búfer de salida
ob_start();

include $base_path . 'includes/header.php';

// Método para eliminar usuario
if (isset($_GET['delete'])) {
    // Obtenemos el ID del usuario
    $id = (int)$_GET['delete'];
    if ($id !== 1) { // prohibimos eliminar al administrador con ID 1
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    // Redirigimos de nuevo a la página de usuarios
    header('Location: users.php');
    exit;
}

// Método para añadir usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // Obtenemos los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'client';

    if ($username && $email && $password) {
        // Comprobamos si existe un usuario con el mismo nombre o correo
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        $exists = $check->fetchColumn();

        if ($exists > 0) {
            // Si el usuario ya existe, mostramos mensaje de error
            $message = "El usuario con ese nombre o correo ya existe.";
            $message_type = "error";
        } else {
            // Encriptamos la contraseña y añadimos el nuevo usuario
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hash, $role]);
            $message = "Usuario añadido correctamente.";
            $message_type = "success";
        }
    } else {
        // Si los datos no están completos, mostramos error
        $message = "Todos los campos son obligatorios.";
        $message_type = "error";
    }
}

// Método para actualizar datos del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    // Obtenemos los datos del formulario
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $message_type = "error";

    if ($id && $username && $email) {
        // Comprobamos que no haya duplicados entre otros usuarios
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $check->execute([$username, $email, $id]);
        $exists = $check->fetchColumn();

        if ($exists > 0) {
            // Si el usuario con ese nombre o correo ya existe
            $message = "El usuario con ese nombre o correo ya existe.";
            $message_type = "error";
        } else {
            // Actualizamos los datos del usuario
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $id]);
            $message = "Datos del usuario actualizados.";
            $message_type = "success";
        }
    } else {
        // Si los datos no están completos, mostramos error
        $message = "Todos los campos son obligatorios.";
        $message_type = "error";
    }

    // Prohibimos bajar el rol del administrador principal
    if ($id === 1 && $role !== 'admin') {
        $message = "No se puede bajar el rol del administrador principal.";
        $message_type = "error";
    }
}

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Obtener datos para editar
$editUser = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $editUser = $stmt->fetch();
}
?>

<section class="admin-page">
    <div class="container admin-grid">

        <!-- Barra superior del administrador -->
        <div class="admin-topbar">
            <h2>Usuarios</h2>
            <button class="btn btn-primary" data-target="addUserCard">➕ Añadir usuario</button>
        </div>

        <?php if (!empty($message)): ?>
            <div class="admin-message <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Lista de usuarios -->
        <div class="admin-list">
            <div class="admin-card">
                <div class="admin-card-inner">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['role']) ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="?edit=<?= $u['id'] ?>" class="icon edit" data-target="editUserCard" title="Editar">✏️</a>
                                        <?php if ($u['id'] != 1): ?>
                                            <!-- Si id=1 no mostramos el icono de eliminar -->
                                            <a href="?delete=<?= $u['id'] ?>" class="icon delete" onclick="return confirm('¿Eliminar usuario?')">🗑</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>  
            </div>
        </div>

        <!-- Bloque de edición de usuario -->
        <div id="editUserCard" class="admin-card <?= $editUser ? '' : 'hidden' ?>">
            <?php if ($editUser): ?>
            <div class="admin-card-inner">
                <h3>Editar usuario</h3>

                <form method="POST" class="edit-form">
                    <input type="hidden" name="id" value="<?= $editUser['id'] ?>">

                    <div class="form-item">
                        <label>Nombre de usuario</label>
                        <input type="text" name="username" value="<?= $editUser['username'] ?>" required>
                    </div>

                    <div class="form-item">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $editUser['email'] ?>" required>
                    </div>

                    <div class="form-item">
                        <label>Rol</label>
                        <select name="role">
                            <option value="client" <?= $editUser['role']=='client'?'selected':'' ?>>Cliente</option>
                            <option value="admin" <?= $editUser['role']=='admin'?'selected':'' ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="form-buttons-inline">
                        <button type="submit" name="update_user" class="btn btn-primary">Guardar</button>
                        <a href="users.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
            <?php else: ?>
                <h3>Selecciona un usuario para editar</h3>
            <?php endif; ?>
        </div>

        <!-- Bloque para añadir usuario -->
        <div id="addUserCard" class="admin-card hidden">
            <div class="admin-card-inner">
                <h3>Añadir usuario</h3>

                <form method="POST" class="edit-form">
                    <div class="form-item">
                        <label>Nombre de usuario</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-item">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-item">
                        <label>Contraseña</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-item">
                        <label>Rol</label>
                        <select name="role">
                            <option value="client">Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div class="form-buttons-inline">
                        <button type="submit" name="add_user" class="btn btn-primary">Añadir</button>
                        <a href="#" class="btn btn-secondary" data-close>Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
const addBtn = document.getElementById('showAddForm');  // Botón para mostrar el formulario de añadir usuario
const addForm = document.getElementById('addUserForm'); // Formulario de añadir usuario
const editCards = document.querySelectorAll('.admin-edit .admin-card'); // Todas las tarjetas de edición

// Función para ocultar todos los bloques (add y edit)
function hideAllBlocks() {
    document.getElementById('editUserCard')?.classList.add('hidden');
    document.getElementById('addUserCard')?.classList.add('hidden');
}

// Manejador de clics para elementos con data-target (mostrar bloques)
document.querySelectorAll('[data-target]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        // Si el enlace contiene ?edit=ID (modo edición), no interceptamos
        if (this.href && this.href.includes('?edit=')) return;

        e.preventDefault(); // Cancelamos la acción por defecto
        hideAllBlocks(); // Ocultamos los bloques actuales

        const target = document.getElementById(this.dataset.target); // Obtenemos el bloque destino
        target?.classList.remove('hidden'); // Mostramos el bloque correspondiente
    });
});

// Manejador de cierre de bloques (data-close)
document.querySelectorAll('[data-close]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault(); // Cancelamos la acción por defecto
        hideAllBlocks(); // Ocultamos los formularios abiertos
    });
});
</script>

<?php include $base_path . 'includes/footer.php'; ?>

<?php
session_start();
include 'conexion.php';
conectar();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    desconectar();
    header("Location: login.php");
    exit();
}

// Procesar operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        // Crear nuevo disfraz
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        // Procesar imagen si se subió
        $foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        }
        
        $sql = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES ('$nombre', '$descripcion', '$foto')";
        mysqli_query($conexion, $sql);
        
    } elseif (isset($_POST['editar'])) {
        // Actualizar disfraz existente
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        // Procesar nueva imagen si se subió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
            
            // Actualizar también la foto
            $sql = "UPDATE disfraces SET nombre = '$nombre', descripcion = '$descripcion', foto = '$foto' WHERE id = $id";
        } else {
            $sql = "UPDATE disfraces SET nombre = '$nombre', descripcion = '$descripcion' WHERE id = $id";
        }
        
        mysqli_query($conexion, $sql);
        
    } elseif (isset($_POST['eliminar'])) {
        // Eliminar disfraz (borrado lógico)
        $id = $_POST['id'];
        $sql = "UPDATE disfraces SET eliminado = 1 WHERE id = $id";
        mysqli_query($conexion, $sql);
    }
}

// Obtener todos los disfraces no eliminados
$sql = "SELECT * FROM disfraces WHERE eliminado = 0";
$disfraces = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Halloween</title>
    <link rel="stylesheet" href="../Styles/estilos.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🧙‍♀️ Panel de Administración</h1>
            <div class="user-info">
                <p>Administrador: <?php echo $_SESSION['usuario']; ?> | 
                   <a href="../index.php">Volver al inicio</a> | 
                   <a href="logout.php">Cerrar Sesión</a></p>
            </div>
        </header>

        <section class="admin-section">
            <h2>Crear Nuevo Disfraz</h2>
            <form method="post" enctype="multipart/form-data" class="form-admin">
                <div class="form-group">
                    <input type="text" name="nombre" placeholder="Nombre del disfraz" required>
                </div>
                <div class="form-group">
                    <textarea name="descripcion" placeholder="Descripción" required></textarea>
                </div>
                <div class="form-group">
                    <input type="file" name="foto" accept="image/*">
                </div>
                <button type="submit" name="crear" class="btn-primary">Crear Disfraz</button>
            </form>
        </section>

        <section class="admin-section">
            <h2>Gestionar Disfraces</h2>
            <div class="disfraces-list">
                <?php while($disfraz = mysqli_fetch_assoc($disfraces)): ?>
                    <div class="disfraz-admin">
                        <form method="post" enctype="multipart/form-data" class="form-admin">
                            <input type="hidden" name="id" value="<?php echo $disfraz['id']; ?>">
                            
                            <!-- Mostrar imagen actual del disfraz -->
                            <div class="current-image">
                                <h4>Imagen actual:</h4>
                                <?php if (!empty($disfraz['foto'])): ?>
                                    <img src="uploads/<?php echo $disfraz['foto']; ?>" 
                                         alt="<?php echo $disfraz['nombre']; ?>" 
                                         style="max-width: 200px; margin: 10px 0;">
                                <?php else: ?>
                                    <p>No hay imagen</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" value="<?php echo $disfraz['nombre']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Descripción:</label>
                                <textarea name="descripcion" required><?php echo $disfraz['descripcion']; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Cambiar imagen:</label>
                                <input type="file" name="foto" accept="image/*">
                            </div>
                            
                            <div class="admin-actions">
                                <button type="submit" name="editar" class="btn-secondary">Actualizar</button>
                                <button type="submit" name="eliminar" class="btn-danger" 
                                        onclick="return confirm('¿Eliminar este disfraz?')">Eliminar</button>
                            </div>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
</body>
</html>

<?php
desconectar();
?>
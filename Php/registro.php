<?php
session_start();
include 'conexion.php';
conectar();

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $clave = $_POST['clave']; 
    
    // Verificar si el usuario ya existe
    $sql = "SELECT id FROM usuarios WHERE nombre = '$nombre'";
    $resultado = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($resultado) > 0) {
        $error = "El nombre de usuario ya existe";
    } else {
        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, clave) VALUES ('$nombre', '$clave')";
        
        if (mysqli_query($conexion, $sql)) {
            // Guardar datos en sesión y redirigir al index
            $_SESSION['usuario'] = $nombre;
            $_SESSION['usuario_id'] = mysqli_insert_id($conexion);
            desconectar();
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Error al registrar el usuario";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Halloween</title>
    <link rel="stylesheet" href="../Styles/estilos.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>🧙‍♂️ Registro de Usuario</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="nombre">Nombre de usuario:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="clave">Contraseña:</label>
                    <input type="password" id="clave" name="clave" required>
                </div>
                
                <button type="submit" class="btn-primary">Registrarse</button>
            </form>
            
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            <a href="Php/index.php" class="btn-secondary">Volver al inicio</a>
        </div>
    </div>
    
    <?php
    desconectar();
    ?>
</body>
</html>
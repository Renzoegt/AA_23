<?php
session_start();
include 'conexion.php';
conectar();

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $clave = $_POST['clave'];
    
    // Buscar usuario en la base de datos
    $sql = "SELECT id, nombre, clave FROM usuarios WHERE nombre = '$nombre'";
    $resultado = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Comparar contraseñas directamente (en entorno real usar password_verify)
        if ($clave == $usuario['clave']) {
            // Iniciar sesión y redirigir
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id'];
            desconectar();
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Halloween</title>
    <link rel="stylesheet" href="../Styles/estilos.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>🧛‍♂️ Iniciar Sesión</h2>
            
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
                
                <button type="submit" class="btn-primary">Iniciar Sesión</button>
            </form>
            
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
            <a href="../index.php" class="btn-secondary">Volver al inicio</a>
        </div>
    </div>
    
    <?php
    desconectar();
    ?>
</body>
</html>
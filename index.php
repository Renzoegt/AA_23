<?php
session_start();
include 'Php/conexion.php';
conectar();

// Consulta para obtener todos los disfraces
$sql = "SELECT id, nombre, descripcion, votos, foto FROM disfraces WHERE eliminado = 0 ORDER BY votos DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concurso de Disfraces de Halloween</title>
    <link rel="stylesheet" href="Styles/estilos.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎃 Concurso de Disfraces de Halloween 🎃</h1>
            <div class="user-info">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <p>Bienvenido, <?php echo $_SESSION['usuario']; ?> | 
                       <a href="Php/logout.php">Cerrar Sesión</a></p>
                <?php else: ?>
                    <p><a href="Php/login.php">Iniciar Sesión</a> | 
                       <a href="Php/registro.php">Registrarse</a></p>
                <?php endif; ?>
            </div>
        </header>

        <main>
            <div class="disfraces-grid">
                <?php while($disfraz = mysqli_fetch_assoc($resultado)): ?>
                    <div class="disfraz-card">
                        <div class="disfraz-img">
                            <?php if (!empty($disfraz['foto'])): ?>
                                <!-- Mostrar imagen del disfraz -->
                                <img src="Php/uploads/<?php echo $disfraz['foto']; ?>" 
                                     alt="<?php echo $disfraz['nombre']; ?>"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <!-- Icono de respaldo si la imagen no carga -->
                                <div class="no-image" style="display: none;">🎭</div>
                            <?php else: ?>
                                <!-- Mostrar icono si no hay imagen -->
                                <div class="no-image">🎭</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="disfraz-info">
                            <h3><?php echo $disfraz['nombre']; ?></h3>
                            <p><?php echo $disfraz['descripcion']; ?></p>
                            <div class="votos">
                                <span class="votos-count"><?php echo $disfraz['votos']; ?> votos</span>
                                
                                <?php if (isset($_SESSION['usuario_id'])): ?>
                                    <form action="votar.php" method="post" class="voto-form">
                                        <input type="hidden" name="id_disfraz" value="<?php echo $disfraz['id']; ?>">
                                        <button type="submit" class="btn-votar">👻 Votar</button>
                                    </form>
                                <?php else: ?>
                                    <p class="login-required">
                                        <a href="Php/login.php">Inicia sesión para votar</a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>
</body>
</html>

<?php 
desconectar();
?>
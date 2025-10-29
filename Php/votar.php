<?php
session_start();
include 'conexion.php';
conectar();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    desconectar();
    header("Location: ../login.php");
    exit();
}

// Procesar el voto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $id_disfraz = $_POST['id_disfraz'];
    
    // Verificar si el usuario ya votó por este disfraz
    $sql = "SELECT id FROM votos WHERE id_usuario = $id_usuario AND id_disfraz = $id_disfraz";
    $resultado = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($resultado) == 0) {
        // Insertar nuevo voto
        $sql = "INSERT INTO votos (id_usuario, id_disfraz) VALUES ($id_usuario, $id_disfraz)";
        mysqli_query($conexion, $sql);
        
        // Actualizar el contador de votos del disfraz
        $sql = "UPDATE disfraces SET votos = votos + 1 WHERE id = $id_disfraz";
        mysqli_query($conexion, $sql);
        
        $_SESSION['mensaje'] = "¡Voto registrado correctamente!";
    } else {
        $_SESSION['error'] = "Ya has votado por este disfraz";
    }
}

// Redirigir de vuelta a la página principal
desconectar();
header("Location: ../index.php");
exit();
?>
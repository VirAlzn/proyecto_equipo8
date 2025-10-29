<?php
session_start();
include("conexion.php");

// Mensaje de error a mostrar si las credenciales son incorrectas o el usuario está inactivo.
$error_inactivo = "Tu cuenta está inactiva. Por favor, contacta al Administrador del sistema.";
$error_credenciales = "Usuario o contraseña incorrectos.";
$redirect_index = "window.location.href='../index.html';";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['username']);
    $clave = trim($_POST['password']);

    // NOTA IMPORTANTE: Para una seguridad mínima en entornos escolares, se asume que la contraseña 
    // está almacenada sin hash, como lo sugiere tu consulta original.
    
    // Consulta para verificar credenciales y obtener el estado del usuario.
    $sql = "SELECT username, rol, estado FROM usuarios WHERE username = '$usuario' AND password = '$clave'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 🚨 VERIFICACIÓN DE ESTADO AÑADIDA 🚨
        if ($user['estado'] === 'Inactivo') {
            // Usuario encontrado, pero inactivo
            echo "<script>alert('$error_inactivo'); $redirect_index</script>";
            exit(); 
        }

        // Si el estado es 'Activo', procede con el inicio de sesión:
        
        // Guardar datos en sesión (en el servidor)
        $_SESSION['usuario'] = $user['username']; 
        $_SESSION['rol'] = $user['rol'];
        
        // Obtenemos variables para la redirección
        $user_role = $user['rol'];
        $redirect_page = ($user_role == 'Administrador') ? '../usuario.html' : '../productos.html';

        // Usamos JavaScript para guardar el rol en localStorage y luego redirigir
        echo "<script>";
        echo "localStorage.setItem('userRole', '$user_role');";
        echo "window.location.href = '$redirect_page';";
        echo "</script>";
        exit(); // Detenemos la ejecución de PHP
    } else {
        // Usuario no encontrado o credenciales incorrectas
        echo "<script>alert('$error_credenciales'); $redirect_index</script>";
    }
}
?>
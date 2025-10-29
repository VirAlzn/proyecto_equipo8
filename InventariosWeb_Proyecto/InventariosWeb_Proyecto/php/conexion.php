<?php
$servername = "localhost";  // Normalmente localhost si usas XAMPP
$username = "root";         // Usuario de phpMyAdmin (por defecto es root)
$password = "";             // Contraseña (vacía si no le pusiste una)
$database = "inventariosdb"; // Nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
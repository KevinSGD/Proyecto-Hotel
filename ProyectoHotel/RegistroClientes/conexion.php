<?php
$host = "127.0.0.1"; // Servidor (cambia si es remoto)
$user = "root"; // Usuario de MySQL
$password = ""; // Contraseña (déjala vacía si usas XAMPP)
$dbname = "Hotel"; // Nombre de la base de datos
    
// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

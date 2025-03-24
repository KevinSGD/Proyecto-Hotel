<?php
// Configuración de la base de datos
$servername = "127.0.0.1"; // Cambia esto si es necesario
$username = "root"; // Cambia esto por tu usuario de MySQL
$password = ""; // Cambia esto por tu contraseña de MySQL
$dbname = "hotel";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener todos los datos del formulario, incluyendo el nombre
    $name = $_POST['name']; // Cambiar 'nombre' por 'name'
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Almacenar la contraseña sin encriptar
    $cargo = $_POST['cargo'];

    // Preparar y bindear la consulta para insertar los datos
    $stmt = $conn->prepare("INSERT INTO empleados (name, username, email, password, cargo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $username, $email, $password, $cargo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de éxito
        header("Location: exitosoEmpleados.html");
        exit();
    } else {
        echo "Error: " . $stmt->error; // Muestra el error de la consulta
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
}

$conn->close();
?>

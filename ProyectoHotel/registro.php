<?php
// Habilitar el reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parámetros de conexión a la base de datos
$servername = "127.0.0.1"; // Generalmente localhost
$username = "root"; // Reemplaza con tu nombre de usuario de la base de datos
$password = ""; // Reemplaza con tu contraseña de la base de datos
$dbname = "hotel"; // Reemplaza con el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las claves existen en el array $_POST
    if (isset($_POST['full_name'], $_POST['username'], $_POST['identification_number'], $_POST['country'], $_POST['password'], $_POST['email'], $_POST['telephone'])) {
        $fullName = $_POST['full_name'];
        $username = $_POST['username']; // Nuevo campo para el nombre de usuario
        $identificationNumber = $_POST['identification_number'];
        $country = $_POST['country'];
        $password = $_POST['password']; // Almacenar la contraseña como texto plano
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];

        // Preparar la declaración SQL para prevenir inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO clients (full_name, username, identification_number, country, password, email, telephone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullName, $username, $identificationNumber, $country, $password, $email, $telephone);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            // Redirigir a registroExitoso.html
            header("Location: registroExitoso.html");
            exit(); // Asegurarse de que no se ejecute más código después de la redirección
        } else {
            echo "Error: " . $stmt->error; // Mostrar cualquier error SQL
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos del formulario.";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
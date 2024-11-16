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
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Preparar la declaración SQL para prevenir inyecciones SQL
        $stmt = $conn->prepare("SELECT * FROM clients WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);

        // Ejecutar la declaración
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró un usuario
        if ($result->num_rows > 0) {
            // Iniciar sesión (puedes guardar información en la sesión si es necesario)
            session_start();
            $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión
            echo "¡Inicio de sesión exitoso! Bienvenido, " . htmlspecialchars($username) . ".";
            // Aquí puedes redirigir a otra página, por ejemplo, a un panel de usuario
            // header("Location: panel.php");
            // exit();
        } else {
            echo "Nombre de usuario o contraseña incorrectos.";
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
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

// Iniciar la sesión
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las claves existen en el array $_POST
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Preparar la declaración SQL para prevenir inyecciones SQL
        $stmt = $conn->prepare("SELECT * FROM clients WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password); // Usar parámetros para evitar inyecciones SQL

        // Ejecutar la declaración
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró un usuario
        if ($result->num_rows > 0) {
            // Guardar información del usuario en la sesión
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id']; // Suponiendo que hay un campo 'id' en la tabla 'clients'
            $_SESSION['username'] = $user['username'];

            // Redirigir al usuario a la página de inicio o a una página de bienvenida
            header("Location: welcome.php"); // Cambia esto a la página que desees
            exit();
        } else {
            // Usuario o contraseña incorrectos
            header("Location: InicioSesion.html?error=Usuario%20o%20contraseña%20incorrectos.");
            exit();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // Si no se enviaron las claves necesarias
        header("Location: InicioSesion.html?error=Por%20favor,%20completa%20todos%20los%20campos.");
        exit();
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
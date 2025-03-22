<?php
// Configuración de la base de datos
$servername = "localhost"; // XAMPP usa localhost
$username = "root"; // Usuario por defecto de MySQL en XAMPP
$password = ""; // Contraseña por defecto está vacía
$dbname = "horizon_prime"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparar y ejecutar la consulta de selección
    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verificar la contraseña
        if ($password === $hashedPassword) {
            // Iniciar sesión y redirigir a la página de éxito
            session_start();
            $_SESSION['email'] = $email; // Guardar el email en la sesión
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "El usuario no existe."]);
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar conexión
$conn->close();
?>
<?php
include 'conexion.php'; // Importar la conexión a la base de datos

// Comprobar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar que no estén vacíos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
        exit();
    }

    // Encriptar la contraseña
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Consulta SQL con consulta preparada para evitar SQL Injection
    $sql = "INSERT INTO usuarios (nombre, apellidos, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $apellidos, $email, $passwordHash);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registro exitoso"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar usuario"]);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>

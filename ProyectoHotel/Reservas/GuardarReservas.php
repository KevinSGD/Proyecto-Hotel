<?php
// Conexión a la base de datos
$servername = "127.0.0.1"; // Cambia si es necesario
$username = "root"; // Cambia por tu usuario
$password = ""; // Cambia por tu contraseña
$dbname = "hotel"; // Cambia por tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$fechaEntrada = $_POST['fecha-entrada'];
$fechaSalida = $_POST['fecha-salida'];
$huespedes = $_POST['huespedes'];
$tipoHabitacion = $_POST['tipo-habitacion'];

// Preparar y ejecutar la consulta SQL
$sql = "INSERT INTO reservas (nombre, email, telefono, fecha_entrada, fecha_salida, huespedes, tipo_habitacion) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssis", $nombre, $email, $telefono, $fechaEntrada, $fechaSalida, $huespedes, $tipoHabitacion);

if ($stmt->execute()) {
    echo "Reserva guardada con éxito.";
    header("Location: Loader.html");
            exit();
} else {
    echo "Error al guardar la reserva: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
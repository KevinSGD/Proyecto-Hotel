<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "horizon_prime"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_entrada = $_POST['fecha-entrada'];
    $fecha_salida = $_POST['fecha-salida'];
    $huespedes = $_POST['huespedes'];
    $tipo_habitacion = $_POST['tipo-habitacion'];


    $stmt = $conn->prepare("INSERT INTO reservas (nombre, email, telefono, fecha_entrada, fecha_salida, huespedes, tipo_habitacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $nombre, $email, $telefono, $fecha_entrada, $fecha_salida, $huespedes, $tipo_habitacion);

    if ($stmt->execute()) {

        header("Location: Loader.html"); 
        exit(); 
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }


    $stmt->close();
}

$conn->close();
?>
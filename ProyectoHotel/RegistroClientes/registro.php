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
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password']; 

 
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $apellido, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
        header("Location: Loader.html");
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }


    $stmt->close();
}


$conn->close();
?>
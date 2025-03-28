<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "horizon_prime"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$sql = "SELECT nombre, email, telefono, fecha_entrada, fecha_salida FROM reservas ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc()); 
} else {
    echo json_encode(["error" => "No hay reservas"]);
}

$conn->close();
?>
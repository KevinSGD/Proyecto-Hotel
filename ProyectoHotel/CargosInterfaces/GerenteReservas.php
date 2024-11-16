<?php
// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "hotel"; // Cambia el nombre si tu base de datos tiene un nombre diferente

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables para el formulario de edición
$editReserva = null;

// Manejo de las operaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        // Crear reserva
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $huespedes = $_POST['huespedes'];
        $tipo_habitacion = $_POST['tipo_habitacion'];

        $stmt = $conn->prepare("INSERT INTO reservas (nombre, email, telefono, fecha_entrada, fecha_salida, huespedes, tipo_habitacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $nombre, $email, $telefono, $fecha_entrada, $fecha_salida, $huespedes, $tipo_habitacion);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'edit') {
        // Editar reserva
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $huespedes = $_POST['huespedes'];
        $tipo_habitacion = $_POST['tipo_habitacion']; // Mantener el tipo de habitación

        $stmt = $conn->prepare("UPDATE reservas SET nombre = ?, email = ?, telefono = ?, fecha_entrada = ?, fecha_salida = ?, huespedes = ?, tipo_habitacion = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $nombre, $email, $telefono, $fecha_entrada, $fecha_salida, $huespedes, $tipo_habitacion, $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'delete') {
        // Eliminar reserva
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'load') {
        // Cargar reserva para editar
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT nombre, email, telefono, fecha_entrada, fecha_salida, huespedes, tipo_habitacion FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($nombre, $email, $telefono, $fecha_entrada, $fecha_salida, $huespedes, $tipo_habitacion);
        $stmt->fetch();
        $stmt->close();

        // Guardar los datos de la reserva para el formulario
        $editReserva = [
            'id' => $id,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'fecha_entrada' => $fecha_entrada,
            'fecha_salida' => $fecha_salida,
            'huespedes' => $huespedes,
            'tipo_habitacion' => $tipo_habitacion
        ];
    }
}

// Obtener reservas
$sql = "SELECT id, nombre, email, telefono, fecha_entrada, fecha_salida, huespedes, tipo_habitacion FROM reservas";
$result = $conn->query($sql);

$reservas = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <style>
        /* Aquí van los estilos CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            color: #444;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="date"] {
            width: auto;
        }

        /* From Uiverse.io by niat786 */ 
        button {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #0400ff, #4ce3f7);
            border-radius: 20px;
            background-size: 100% auto;
            font-family: inherit;
            font-size: 17px;
            padding: 0.6em 1.5em;
        }

        button:hover {
            background-position: right center;
            background-size: 200% auto;
            -webkit-animation: pulse 2s infinite;
            animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% {
                box-shadow: 0 0 0 0 #05bada66;
            }

            70% {
                box-shadow: 0 0 0 10px rgb(218 103 68 / 0%);
            }

            100% {
                box-shadow: 0 0 0 0 rgb(218 103 68 / 0%);
            }
        }

        /* Cambiar el color del botón a #901a00 */
        button {
            background-image: linear-gradient(30deg, #901a00, #f04c00);
        }

        button:hover {
            background-image: linear-gradient(30deg, #901a00, #f04c00);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .actions {
            text-align: center;
        }

        .actions button {
            width: 100%; /* Asegura que los botones no sobresalgan */
            padding: 8px;
            margin: 5px 0;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .actions button.edit {
            background-color: #28a745;
        }

        .actions button.edit:hover {
            background-color: #218838;
        }

        .actions button.delete {
            background-color: #dc3545;
        }

        .actions button.delete:hover {
            background-color: #c82333;
        }

        /* Estilo para el botón de cerrar sesión */
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Reservas</h1>

        <!-- Botón de cerrar sesión -->
        <form action="Cargos.html" method="get">
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>

        <!-- Formulario para crear nueva reserva -->
        <h2>Crear Nueva Reserva</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <input name="nombre" type="text" placeholder="Nombre" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="telefono" type="text" placeholder="Teléfono" required>
            <input name="fecha_entrada" type="date" required>
            <input name="fecha_salida" type="date" required>
            <input name="huespedes" type="number" placeholder="Número de Huespedes" required>
            <select name="tipo_habitacion" required>
                <option value="">Selecciona tipo de habitación</option>
                <option value="Individual">Individual</option>
                <option value="Doble">Doble</option>
                <option value="Suite">Suite</option>
            </select>
            <button type="submit">Crear Reserva</button>
        </form>

        <!-- Listado de reservas -->
        <h2>Reservas Existentes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha Entrada</th>
                    <th>Fecha Salida</th>
                    <th>Huéspedes</th>
                    <th>Tipo de Habitación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?= $reserva['id']; ?></td>
                        <td><?= $reserva['nombre']; ?></td>
                        <td><?= $reserva['email']; ?></td>
                        <td><?= $reserva['telefono']; ?></td>
                        <td><?= $reserva['fecha_entrada']; ?></td>
                        <td><?= $reserva['fecha_salida']; ?></td>
                        <td><?= $reserva['huespedes']; ?></td>
                        <td><?= $reserva['tipo_habitacion']; ?></td>
                        <td class="actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="load">
                                <input type="hidden" name="id" value="<?= $reserva['id']; ?>">
                                <button type="submit" class="edit">Editar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $reserva['id']; ?>">
                                <button type="submit" class="delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reserva?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulario de edición -->
        <?php if ($editReserva): ?>
            <h2>Editar Reserva</h2>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $editReserva['id']; ?>">
                <input name="nombre" type="text" value="<?= $editReserva['nombre']; ?>" required>
                <input name="email" type="email" value="<?= $editReserva['email']; ?>" required>
                <input name="telefono" type="text" value="<?= $editReserva['telefono']; ?>" required>
                <input name="fecha_entrada" type="date" value="<?= $editReserva['fecha_entrada']; ?>" required>
                <input name="fecha_salida" type="date" value="<?= $editReserva['fecha_salida']; ?>" required>
                <input name="huespedes" type="number" value="<?= $editReserva['huespedes']; ?>" required>

                <!-- Deja el campo de tipo de habitación como selección editable -->
                <label>Tipo de Habitación</label>
                <select name="tipo_habitacion" required>
                    <option value="Individual" <?= $editReserva['tipo_habitacion'] === 'Individual' ? 'selected' : ''; ?>>Individual</option>
                    <option value="Doble" <?= $editReserva['tipo_habitacion'] === 'Doble' ? 'selected' : ''; ?>>Doble</option>
                    <option value="Suite" <?= $editReserva['tipo_habitacion'] === 'Suite' ? 'selected' : ''; ?>>Suite</option>
                </select>

                <button type="submit">Guardar Cambios</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "hotel";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo de las operaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        // Crear empleado
        $name = $_POST['name'];
        $cargo = $_POST['cargo'];
        $email = $_POST['email'];
        $username = $_POST['username'];

        $stmt = $conn->prepare("INSERT INTO empleados (name, cargo, email, username) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $cargo, $email, $username);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'edit') {
        // Editar empleado
        if (isset($_POST['id'], $_POST['name'], $_POST['cargo'], $_POST['email'], $_POST['username'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $cargo = $_POST['cargo'];
            $email = $_POST['email'];
            $username = $_POST['username'];

            $stmt = $conn->prepare("UPDATE empleados SET name = ?, cargo = ?, email = ?, username = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $cargo, $email, $username, $id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        // Eliminar empleado
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Obtener empleados
$sql = "SELECT id, name, cargo, email, username FROM empleados";
$result = $conn->query($sql);
$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Empleados - Gerente de RH</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .card-header h1 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .card-content {
      margin-top: 20px;
    }

    .form-group {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .form-group input {
      flex: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .form-group button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .form-group button:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: #f8f8f8;
    }

    /* Estilo de los botones */
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

    /* Escondemos los formularios de edición inicialmente */
    .edit-form {
      display: none;
    }
  </style>
  <script>
    // Función para mostrar u ocultar el formulario de edición
    function toggleEditForm(employeeId) {
      const form = document.getElementById('editForm_' + employeeId);
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h1>Gestión de Empleados - Gerente de RH</h1>
      </div>
      <div class="card-content">
        <form id="createEmployeeForm" method="POST" class="form-group">
          <input type="hidden" name="action" value="create">
          <input id="newName" name="name" type="text" placeholder="Nombre del Empleado" required>
          <input id="newPosition" name="cargo" type="text" placeholder="Posición" required>
          <input id="newEmail" name="email" type="email" placeholder="Email" required>
          <input id="newUsername" name="username" type="text" placeholder="Username" required>
          <button type="submit">Crear Empleado</button>
        </form>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Posición</th>
              <th>Email</th>
              <th>Username</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="employeeTable">
            <?php foreach ($employees as $employee): ?>
              <tr>
                <td><?php echo $employee['id']; ?></td>
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['cargo']; ?></td>
                <td><?php echo $employee['email']; ?></td>
                <td><?php echo $employee['username']; ?></td>
                <td>
                  <!-- Botón para mostrar el formulario de edición -->
                  <button onclick="toggleEditForm(<?php echo $employee['id']; ?>)">Editar</button>
                  <!-- Formulario para editar (inicialmente oculto) -->
                  <form id="editForm_<?php echo $employee['id']; ?>" method="POST" class="form-group edit-form">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
                    <input type="text" name="name" value="<?php echo $employee['name']; ?>" required>
                    <input type="text" name="cargo" value="<?php echo $employee['cargo']; ?>" required>
                    <input type="email" name="email" value="<?php echo $employee['email']; ?>" required>
                    <input type="text" name="username" value="<?php echo $employee['username']; ?>" required>
                    <button type="submit">Guardar Cambios</button>
                  </form>
                  <!-- Formulario para eliminar -->
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este empleado?');">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>

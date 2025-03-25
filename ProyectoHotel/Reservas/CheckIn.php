<?php
// Iniciar sesión para mantener los datos del usuario
session_start();

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario por defecto de XAMPP
$password = ""; // Contraseña por defecto de XAMPP (vacía)
$dbname = "horizon_prime_hotel";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener ID de reserva (esto podría venir de una sesión o un parámetro GET)
$reserva_id = isset($_SESSION['reserva_id']) ? $_SESSION['reserva_id'] : 1; // Valor por defecto para pruebas

// Consulta para obtener datos de la habitación reservada
$sql_habitacion = "SELECT h.id, h.nombre, h.precio_noche, h.imagen, h.descripcion, 
                   r.fecha_entrada, r.fecha_salida, r.num_adultos, r.num_ninos 
                   FROM habitaciones h 
                   INNER JOIN reservas r ON h.id = r.habitacion_id 
                   WHERE r.id = $reserva_id";

$resultado_habitacion = $conn->query($sql_habitacion);
$habitacion = $resultado_habitacion->fetch_assoc();

// Calcular número de noches
$fecha_entrada = new DateTime($habitacion['fecha_entrada']);
$fecha_salida = new DateTime($habitacion['fecha_salida']);
$diferencia = $fecha_entrada->diff($fecha_salida);
$num_noches = $diferencia->days;

// Consulta para obtener servicios seleccionados
$sql_servicios = "SELECT s.id, s.nombre, s.precio, s.icono, s.tipo_precio, rs.cantidad 
                  FROM servicios s 
                  INNER JOIN reserva_servicios rs ON s.id = rs.servicio_id 
                  WHERE rs.reserva_id = $reserva_id";

$resultado_servicios = $conn->query($sql_servicios);
$servicios = [];
while ($servicio = $resultado_servicios->fetch_assoc()) {
    $servicios[] = $servicio;
}

// Calcular subtotal, impuestos y total
$subtotal_habitacion = $habitacion['precio_noche'] * $num_noches;
$subtotal_servicios = 0;

foreach ($servicios as $servicio) {
    if ($servicio['tipo_precio'] == 'por_dia') {
        $subtotal_servicios += $servicio['precio'] * $num_noches;
    } else if ($servicio['tipo_precio'] == 'por_trayecto') {
        $subtotal_servicios += $servicio['precio'] * $servicio['cantidad'];
    } else {
        $subtotal_servicios += $servicio['precio'];
    }
}

$subtotal = $subtotal_habitacion + $subtotal_servicios;
$impuestos = $subtotal * 0.16; // 16% de impuestos
$total = $subtotal + $impuestos;

// Formatear fechas para mostrar
$fecha_entrada_formato = $fecha_entrada->format('d M Y');
$fecha_salida_formato = $fecha_salida->format('d M Y');

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí iría el código para procesar el pago y finalizar la reserva
    // Por ejemplo, actualizar el estado de la reserva en la base de datos
    
    // Redireccionar a página de confirmación
    // header("Location: confirmacion.php");
    // exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Horizon Prime Hotel - Check-in</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #1e40af;
      --primary-dark: #1e3a8a;
      --primary-light: #3b82f6;
      --primary-lighter: #93c5fd;
      --secondary: #0f172a;
      --secondary-light: #1e293b;
      --accent: #0ea5e9;
      --white: #ffffff;
      --light: #f1f5f9;
      --light-blue: #e0f2fe;
      --gray: #64748b;
      --dark: #0f172a;
      --success: #10b981;
      --success-light: #d1fae5;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", sans-serif;
      line-height: 1.6;
      color: var(--dark);
      background-color: var(--light);
    }

    /* Navigation Bar */
    .navbar {
      background-color: var(--white);
      box-shadow: var(--shadow);
      padding: 10px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo {
      width: 40px;
      height: 40px;
      background-color: var(--primary);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 20px;
    }

    .logo-text {
      font-weight: 700;
      font-size: 20px;
      color: var(--primary);
    }

    .nav-links {
      display: flex;
      gap: 30px;
    }

    .nav-link {
      color: var(--secondary);
      text-decoration: none;
      font-weight: 500;
      padding: 8px 0;
      position: relative;
      transition: var(--transition);
    }

    .nav-link:hover {
      color: var(--primary);
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background-color: var(--primary);
      transition: var(--transition);
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .nav-link.active {
      color: var(--primary);
    }

    .nav-link.active::after {
      width: 100%;
    }

    .login-btn {
      background-color: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
      border-radius: 30px;
      padding: 8px 20px;
      font-weight: 600;
      transition: var(--transition);
      text-decoration: none;
      display: inline-block;
    }

    .login-btn:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    /* Page Header */
    .page-header {
      background: linear-gradient(to right, var(--primary-dark), var(--primary));
      color: var(--white);
      padding: 40px 0;
      text-align: center;
    }

    .page-title {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .page-subtitle {
      font-size: 1.1rem;
      color: var(--primary-lighter);
      max-width: 700px;
      margin: 0 auto;
    }

    /* Checkout Process */
    .checkout-process {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }

    .process-step {
      display: flex;
      align-items: center;
      margin: 0 15px;
      position: relative;
    }

    .process-step:not(:last-child)::after {
      content: "";
      position: absolute;
      top: 50%;
      right: -30px;
      width: 30px;
      height: 2px;
      background-color: var(--gray);
      transform: translateY(-50%);
    }

    .step-number {
      width: 30px;
      height: 30px;
      background-color: var(--gray);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-right: 10px;
    }

    .step-number.active {
      background-color: var(--primary);
    }

    .step-number.completed {
      background-color: var(--success);
    }

    .step-text {
      font-weight: 500;
      color: var(--gray);
    }

    .step-text.active {
      color: var(--primary);
    }

    .step-text.completed {
      color: var(--success);
    }

    /* Main Content */
    .main-content {
      padding: 40px 0;
      background-color: var(--white);
    }

    .checkout-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
    }

    /* Checkout Form */
    .checkout-form {
      background-color: var(--white);
      border-radius: 10px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .form-section {
      padding: 25px;
      border-bottom: 1px solid var(--light);
    }

    .form-section:last-child {
      border-bottom: none;
    }

    .section-title {
      font-size: 1.3rem;
      color: var(--primary-dark);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: var(--primary);
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--secondary-light);
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--light-blue);
      border-radius: 8px;
      font-family: inherit;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    .form-control::placeholder {
      color: var(--gray);
    }

    /* Order Summary */
    .order-summary {
      background-color: var(--light-blue);
      border-radius: 10px;
      box-shadow: var(--shadow);
      position: sticky;
      top: 90px;
    }

    .summary-header {
      background-color: var(--primary-dark);
      color: var(--white);
      padding: 20px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .summary-title {
      font-size: 1.2rem;
      margin-bottom: 5px;
    }

    .summary-subtitle {
      font-size: 0.9rem;
      color: var(--primary-lighter);
    }

    .summary-content {
      padding: 20px;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid rgba(100, 116, 139, 0.2);
    }

    .summary-item:last-child {
      border-bottom: none;
    }

    .item-name {
      font-weight: 500;
      color: var(--secondary-light);
    }

    .item-details {
      font-size: 0.9rem;
      color: var(--gray);
      margin-top: 3px;
    }

    .item-price {
      font-weight: 600;
      color: var(--primary-dark);
    }

    .summary-total {
      background-color: var(--white);
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .total-row:last-child {
      margin-top: 15px;
      padding-top: 15px;
      border-top: 2px solid var(--light);
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--primary-dark);
    }

    .total-label {
      color: var(--gray);
    }

    .total-value {
      font-weight: 600;
      color: var(--secondary-light);
    }

    /* Selected Room */
    .selected-room {
      display: flex;
      background-color: var(--light-blue);
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .room-image {
      width: 120px;
      height: 120px;
      object-fit: cover;
    }

    .room-details {
      padding: 15px;
      flex-grow: 1;
    }

    .room-name {
      font-weight: 600;
      color: var(--primary-dark);
      margin-bottom: 5px;
    }

    .room-info {
      font-size: 0.9rem;
      color: var(--gray);
      margin-bottom: 5px;
    }

    .room-dates {
      font-size: 0.9rem;
      color: var(--primary);
      font-weight: 500;
    }

    /* Selected Services */
    .selected-services {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 15px;
    }

    .service-item {
      background-color: var(--light-blue);
      border-radius: 8px;
      padding: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .service-icon {
      width: 40px;
      height: 40px;
      background-color: var(--primary-lighter);
      color: var(--primary-dark);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    .service-details {
      flex-grow: 1;
    }

    .service-name {
      font-weight: 500;
      color: var(--primary-dark);
      margin-bottom: 3px;
    }

    .service-price {
      font-size: 0.8rem;
      color: var(--gray);
    }

    /* Payment Methods */
    .payment-methods {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .payment-method {
      border: 2px solid var(--light-blue);
      border-radius: 8px;
      padding: 15px;
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
    }

    .payment-method:hover {
      border-color: var(--primary-light);
    }

    .payment-method.active {
      border-color: var(--primary);
      background-color: var(--light-blue);
    }

    .payment-icon {
      font-size: 2rem;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .payment-name {
      font-weight: 500;
      color: var(--secondary-light);
    }

    /* Credit Card Form */
    .credit-card-form {
      background-color: var(--light-blue);
      border-radius: 8px;
      padding: 20px;
      margin-top: 20px;
    }

    /* Buttons */
    .btn {
      display: inline-block;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      font-family: inherit;
      font-size: 1rem;
    }

    .btn-primary {
      background: linear-gradient(to right, var(--primary-dark), var(--primary));
      color: var(--white);
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .btn-primary:hover {
      box-shadow: 0 6px 20px rgba(30, 64, 175, 0.5);
      transform: translateY(-2px);
    }

    .btn-outline {
      background-color: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
    }

    .btn-outline:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    .btn-block {
      display: block;
      width: 100%;
    }

    .btn-group {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }

    /* Footer */
    .footer {
      background-color: var(--secondary);
      color: var(--light);
      padding: 20px 0;
      text-align: center;
      margin-top: 40px;
    }

    .footer p {
      color: var(--gray);
      font-size: 0.9rem;
    }

    /* Animation classes */
    .fade-in {
      opacity: 0;
      animation: fadeIn 0.8s forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }

    /* Mobile menu */
    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      color: var(--primary);
      font-size: 1.5rem;
      cursor: pointer;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
      .checkout-grid {
        grid-template-columns: 1fr;
      }

      .order-summary {
        position: static;
        margin-top: 30px;
      }
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }

      .mobile-menu-btn {
        display: block;
      }

      .mobile-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--white);
        z-index: 1001;
        padding: 20px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .mobile-nav.active {
        transform: translateX(0);
      }

      .mobile-nav-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      .mobile-nav-close {
        background: none;
        border: none;
        color: var(--primary);
        font-size: 1.5rem;
        cursor: pointer;
      }

      .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .mobile-nav-link {
        color: var(--secondary);
        text-decoration: none;
        font-weight: 500;
        font-size: 1.2rem;
        padding: 10px 0;
        border-bottom: 1px solid var(--light);
      }

      .checkout-process {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }

      .process-step:not(:last-child)::after {
        display: none;
      }

      .selected-services {
        grid-template-columns: 1fr;
      }

      .payment-methods {
        grid-template-columns: 1fr 1fr;
      }

      .btn-group {
        flex-direction: column;
      }
    }
  </style>
</head>

  <!-- Page Header -->
  <header class="page-header">
    <div class="container">
      <h1 class="page-title fade-in">Finalizar Check-in</h1>
      <p class="page-subtitle fade-in delay-1">Complete su información personal y método de pago para finalizar su reserva</p>
    </div>
  </header>

  <!-- Checkout Process -->
  <div class="container">
    <div class="checkout-process fade-in delay-1">
      <div class="process-step">
        <div class="step-number completed">
          <i class="fas fa-check"></i>
        </div>
        <span class="step-text completed">Selección de habitación</span>
      </div>
      <div class="process-step">
        <div class="step-number completed">
          <i class="fas fa-check"></i>
        </div>
        <span class="step-text completed">Servicios adicionales</span>
      </div>
      <div class="process-step">
        <div class="step-number active">3</div>
        <span class="step-text active">Check-in y pago</span>
      </div>
      <div class="process-step">
        <div class="step-number">4</div>
        <span class="step-text">Confirmación</span>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="main-content">
    <div class="container">
      <div class="checkout-grid">
        <!-- Checkout Form -->
        <div class="checkout-form fade-in delay-2">
          <!-- Reservation Summary -->
          <div class="form-section">
            <h3 class="section-title">
              <i class="fas fa-bookmark"></i>
              Resumen de su reserva
            </h3>
            
            <!-- Selected Room -->
            <div class="selected-room">
              <img src="<?php echo htmlspecialchars($habitacion['imagen']); ?>" alt="<?php echo htmlspecialchars($habitacion['nombre']); ?>" class="room-image">
              <div class="room-details">
                <h4 class="room-name"><?php echo htmlspecialchars($habitacion['nombre']); ?></h4>
                <p class="room-info"><?php echo $habitacion['num_adultos']; ?> adultos · <?php echo $num_noches; ?> noches · <?php echo htmlspecialchars($habitacion['descripcion']); ?></p>
                <p class="room-dates"><?php echo $fecha_entrada_formato; ?> - <?php echo $fecha_salida_formato; ?></p>
              </div>
            </div>
            
            <!-- Selected Services -->
            <h4 class="section-title" style="font-size: 1.1rem; margin-top: 20px;">
              <i class="fas fa-concierge-bell"></i>
              Servicios seleccionados
            </h4>
            
            <div class="selected-services">
              <?php foreach ($servicios as $servicio): ?>
              <div class="service-item">
                <div class="service-icon">
                  <i class="<?php echo htmlspecialchars($servicio['icono']); ?>"></i>
                </div>
                <div class="service-details">
                  <h5 class="service-name"><?php echo htmlspecialchars($servicio['nombre']); ?></h5>
                  <p class="service-price">
                    <?php 
                    echo '$' . number_format($servicio['precio'], 2);
                    if ($servicio['tipo_precio'] == 'por_dia') {
                      echo '/día';
                    } elseif ($servicio['tipo_precio'] == 'por_trayecto') {
                      echo '/trayecto';
                    }
                    ?>
                  </p>
                </div>
              </div>
              <?php endforeach; ?>
              
              <?php if (empty($servicios)): ?>
              <p>No ha seleccionado servicios adicionales.</p>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Guest Information -->
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-section">
              <h3 class="section-title">
                <i class="fas fa-user"></i>
                Información del huésped
              </h3>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="first-name">Nombre</label>
                  <input type="text" id="first-name" name="first_name" class="form-control" placeholder="Ingrese su nombre" required>
                </div>
                
                <div class="form-group">
                  <label for="last-name">Apellidos</label>
                  <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Ingrese sus apellidos" required>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="email">Correo electrónico</label>
                  <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
                
                <div class="form-group">
                  <label for="phone">Teléfono</label>
                  <input type="tel" id="phone" name="phone" class="form-control" placeholder="+52 (123) 456-7890" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="special-requests">Solicitudes especiales (opcional)</label>
                <textarea id="special-requests" name="special_requests" class="form-control" rows="3" placeholder="Indique cualquier solicitud especial para su estancia"></textarea>
              </div>
            </div>
            
            <!-- Payment Information -->
            <div class="form-section">
              <h3 class="section-title">
                <i class="fas fa-credit-card"></i>
                Información de pago
              </h3>
              
              <div class="payment-methods">
                <div class="payment-method active" data-method="credit-card">
                  <div class="payment-icon">
                    <i class="far fa-credit-card"></i>
                  </div>
                  <div class="payment-name">Tarjeta de crédito</div>
                </div>
                
                <div class="payment-method" data-method="paypal">
                  <div class="payment-icon">
                    <i class="fab fa-paypal"></i>
                  </div>
                  <div class="payment-name">PayPal</div>
                </div>
                
                <div class="payment-method" data-method="bank-transfer">
                  <div class="payment-icon">
                    <i class="fas fa-university"></i>
                  </div>
                  <div class="payment-name">Transferencia</div>
                </div>
              </div>
              
              <div class="credit-card-form">
                <div class="form-group">
                  <label for="card-number">Número de tarjeta</label>
                  <input type="text" id="card-number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="card-name">Nombre en la tarjeta</label>
                    <input type="text" id="card-name" name="card_name" class="form-control" placeholder="Nombre como aparece en la tarjeta">
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="expiry-date">Fecha de expiración</label>
                    <input type="text" id="expiry-date" name="expiry_date" class="form-control" placeholder="MM/AA">
                  </div>
                  
                  <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Terms and Conditions -->
            <div class="form-section">
              <div class="form-group">
                <label class="checkbox-container" style="display: flex; align-items: flex-start; cursor: pointer;">
                  <input type="checkbox" name="terms_accepted" required style="margin-top: 5px; margin-right: 10px;">
                  <span>
                    Acepto los <a href="#" style="color: var(--primary);">términos y condiciones</a> y la <a href="#" style="color: var(--primary);">política de privacidad</a> de Horizon Prime Hotel. Entiendo que mi reserva está sujeta a las políticas de cancelación y reembolso.
                  </span>
                </label>
              </div>
              
              <div class="btn-group">
                <a href="servicios.html" class="btn btn-outline">Volver a servicios</a>
                <button type="submit" class="btn btn-primary">Completar reserva</button>
              </div>
            </div>
          </form>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary fade-in delay-3">
          <div class="summary-header">
            <h3 class="summary-title">Resumen de su reserva</h3>
            <p class="summary-subtitle">Horizon Prime Hotel</p>
          </div>
          
          <div class="summary-content">
            <div class="summary-item">
              <div>
                <div class="item-name"><?php echo htmlspecialchars($habitacion['nombre']); ?></div>
                <div class="item-details"><?php echo $num_noches; ?> noches · <?php echo $fecha_entrada_formato; ?> - <?php echo $fecha_salida_formato; ?></div>
              </div>
              <div class="item-price">$<?php echo number_format($subtotal_habitacion, 2); ?></div>
            </div>
            
            <?php foreach ($servicios as $servicio): ?>
            <div class="summary-item">
              <div>
                <div class="item-name"><?php echo htmlspecialchars($servicio['nombre']); ?></div>
                <div class="item-details">
                  <?php 
                  if ($servicio['tipo_precio'] == 'por_dia') {
                    echo '$' . number_format($servicio['precio'], 2) . ' × ' . $num_noches . ' días';
                  } elseif ($servicio['tipo_precio'] == 'por_trayecto') {
                    echo '$' . number_format($servicio['precio'], 2) . ' × ' . $servicio['cantidad'] . ' trayectos';
                  } else {
                    echo '$' . number_format($servicio['precio'], 2);
                  }
                  ?>
                </div>
              </div>
              <div class="item-price">
                <?php 
                if ($servicio['tipo_precio'] == 'por_dia') {
                  echo '$' . number_format($servicio['precio'] * $num_noches, 2);
                } elseif ($servicio['tipo_precio'] == 'por_trayecto') {
                  echo '$' . number_format($servicio['precio'] * $servicio['cantidad'], 2);
                } else {
                  echo '$' . number_format($servicio['precio'], 2);
                }
                ?>
              </div>
            </div>
            <?php endforeach; ?>
            
            <div class="summary-total">
              <div class="total-row">
                <div class="total-label">Subtotal</div>
                <div class="total-value">$<?php echo number_format($subtotal, 2); ?></div>
              </div>
              
              <div class="total-row">
                <div class="total-label">Impuestos (16%)</div>
                <div class="total-value">$<?php echo number_format($impuestos, 2); ?></div>
              </div>
              
              <div class="total-row">
                <div class="total-label">Total</div>
                <div class="total-value">$<?php echo number_format($total, 2); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; <span id="current-year">2025</span> Horizon Prime Hotel. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script>
    // Set current year in footer
    document.getElementById("current-year").textContent = new Date().getFullYear();

    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
    const mobileMenuCloseBtn = document.querySelector(".mobile-nav-close");
    const mobileNav = document.querySelector(".mobile-nav");

    mobileMenuBtn.addEventListener("click", function() {
      mobileNav.classList.add("active");
      document.body.style.overflow = "hidden";
    });

    mobileMenuCloseBtn.addEventListener("click", function() {
      mobileNav.classList.remove("active");
      document.body.style.overflow = "";
    });

    // Payment method selection
    const paymentMethods = document.querySelectorAll(".payment-method");
    
    paymentMethods.forEach(method => {
      method.addEventListener("click", function() {
        // Remove active class from all methods
        paymentMethods.forEach(m => m.classList.remove("active"));
        
        // Add active class to clicked method
        this.classList.add("active");
        
        // Show/hide payment forms based on selection
        const selectedMethod = this.getAttribute("data-method");
        console.log("Selected payment method: " + selectedMethod);
        
        // Show/hide the credit card form
        const creditCardForm = document.querySelector(".credit-card-form");
        if (selectedMethod === "credit-card") {
          creditCardForm.style.display = "block";
        } else {
          creditCardForm.style.display = "none";
        }
      });
    });

    // Animation on scroll
    document.addEventListener("DOMContentLoaded", function() {
      const fadeElements = document.querySelectorAll('.fade-in');
      
      // Trigger animations immediately for elements in viewport
      fadeElements.forEach(element => {
        element.style.opacity = "1";
      });
    });
  </script>
</body>
</html>
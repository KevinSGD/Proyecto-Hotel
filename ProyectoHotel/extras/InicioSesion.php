<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #0b0b0be9; /* Fondo oscuro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form {
            display: flex;
            justify-content: center;
            align-items: center;
            transform-style: preserve-3d;
            transition: all 1s ease;
            margin: 20px;
        }

        .form .form_front {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
            position: absolute;
            backface-visibility: hidden;
            padding: 45px 45px;
            border-radius: 15px;
            box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 1),
                        inset -1px -1px 5px rgba(255, 255, 255, 0.6);
            background-color: #212121; /* Fondo oscuro */
            width: 350px; /* Ancho ajustado */
        }

        .form_details {
            font-size: 25px;
            font-weight: 600;
            padding-bottom: 10px;
            color: white;
        }

        /* Estilos para los campos de entrada */
        .input {
            width: 100%;
            max-width: 300px;
            min-height: 45px;
            color: #fff;
            outline: none;
            transition: 0.35s;
            padding: 0px 10px;
            background-color: #212121;
            border-radius: 6px;
            border: 2px solid #212121;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 1),
                        1px 1px 10px rgba(255, 255, 255, 0.6);
            margin-bottom: 15px;
        }

        .input::placeholder {
            color: #999;
        }

        .input:focus {
            transform: scale(1.05);
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 1),
                        1px 1px 10px rgba(255, 255, 255, 0.6),
                        inset 2px 2px 10px rgba(0, 0, 0, 1),
                        inset -1px -1px 5px rgba(255, 255, 255, 0.6);
        }

        label {
            width: 100%;
            text-align: left;
            color: white;
            font-size: 14px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 35px;
            cursor: pointer;
            background-color: #212121;
            border-radius: 6px;
            border: 2px solid #212121;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 1),
                        1px 1px 10px rgba(255, 255, 255, 0.6);
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            transition: 0.35s;
            margin-top: 20px;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 1),
                        1px 1px 10px rgba(255, 255, 255, 0.6),
                        inset 2px 2px 10px rgba(0, 0, 0, 1),
                        inset -1px -1px 5px rgba(255, 255, 255, 0.6);
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .form .switch {
            font-size: 13px;
            color: white;
            margin-top: 10px;
        }

        .form .switch a {
            font-weight: 700;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form">
            <div class="form_front">
                <h2 class="form_details">Iniciar Sesión</h2>

                <!-- Mostrar mensaje de error si existe -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
 
                <form action="Login.php" method="POST">
                    <label for="username">Usuario:</label>
                    <input class="input" type="text" id="username" name="username" required placeholder="Ingresa tu usuario">
                    
                    <label for="password">Contraseña:</label>
                    <input class="input" type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">

                    <button class="btn" type="submit">Iniciar Sesión</button>
                </form>

                <p class="switch">¿No tienes una cuenta? <a href="RegistroUsuarios.html" class="signup_tog">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>




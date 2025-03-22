document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    const btnRegistrar = document.getElementById('btnRegistrar');
    const btnCancelar = document.getElementById('btnCancelar');
    
    // Campos del formulario
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    // Mensajes de error
    const nombreError = document.getElementById('nombreError');
    const apellidosError = document.getElementById('apellidosError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    
    // Validar formulario
    function validarFormulario() {
        let isValid = true;
        
        // Validar nombre
        if (nombre.value.trim() === '') {
            nombreError.style.display = 'block';
            isValid = false;
        } else {
            nombreError.style.display = 'none';
        }
        
        // Validar apellidos
        if (apellidos.value.trim() === '') {
            apellidosError.style.display = 'block';
            isValid = false;
        } else {
            apellidosError.style.display = 'none';
        }
        
        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            emailError.style.display = 'block';
            isValid = false;
        } else {
            emailError.style.display = 'none';
        }
        
        // Validar contraseña
        if (password.value.length < 6) {
            passwordError.style.display = 'block';
            isValid = false;
        } else {
            passwordError.style.display = 'none';
        }
        
        // Validar confirmación de contraseña
        if (password.value !== confirmPassword.value) {
            confirmPasswordError.style.display = 'block';
            isValid = false;
        } else {
            confirmPasswordError.style.display = 'none';
        }
        
        return isValid;
    }
    
    // Evento para validar al escribir
    [nombre, apellidos, email, password, confirmPassword].forEach(input => {
        input.addEventListener('input', function() {
            validarFormulario();
        });
    });
    
    
    // Evento para el botón de registro
    btnRegistrar.addEventListener('click', async function () {
        if (validarFormulario()) {
            const formData = new FormData();
            formData.append('nombre', nombre.value);
            formData.append('apellidos', apellidos.value);
            formData.append('email', email.value);
            formData.append('password', password.value);
    
            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
    
                const result = await response.json();
    
                if (result.status === "success") {
                    alert("Registro exitoso. Ahora puede iniciar sesión.");
                    form.reset();
                    window.location.href = "../InicioSesion/Loader.html";
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Hubo un problema en el servidor.");
            }
        }
    });
    
    
    // Evento para el botón de cancelar
    btnCancelar.addEventListener('click', function() {
        form.reset();
        // Ocultar todos los mensajes de error
        [nombreError, apellidosError, emailError, passwordError, confirmPasswordError].forEach(error => {
            error.style.display = 'none';
        });

        // Redirigir a la página de inicio u otra interfaz
        window.location.href = '../InterfazPrincipal/Interfaz.html';

    });
});

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
    btnRegistrar.addEventListener('click', function() {
        if (validarFormulario()) {
            // Aquí iría el código para enviar los datos al servidor
            alert('Registro exitoso. Ahora puede iniciar sesión con sus credenciales.');
            
            // Simulación de envío de datos
            console.log({
                nombre: nombre.value,
                apellidos: apellidos.value,
                email: email.value,
                password: password.value
            });
            
            // Limpiar formulario
            form.reset();
        }
    });
    
    // Evento para el botón de cancelar
    btnCancelar.addEventListener('click', function() {
        form.reset();
        // Ocultar todos los mensajes de error
        [nombreError, apellidosError, emailError, passwordError, confirmPasswordError].forEach(error => {
            error.style.display = 'none';
        });
    });
});
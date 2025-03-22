// Función para alternar la visibilidad de la contraseña
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
}

// Manejar envío del formulario de inicio de sesión
document.getElementById('login').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Aquí iría la lógica para validar y enviar los datos al servidor
    console.log('Iniciando sesión con:', { email, password });
    
    // Simulación de inicio de sesión exitoso
    alert('Inicio de sesión exitoso');
});
document.addEventListener("DOMContentLoaded", () => {
   
    document.addEventListener("DOMContentLoaded", () => {
        const reservarBtns = document.querySelectorAll('.reservar-btn');
    
        reservarBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const habitacion = btn.getAttribute('data-habitacion');
                window.location.href = `form.html?habitacion=${encodeURIComponent(habitacion)}`;
            });
        });
    });

    
    // Ocultar la barra lateral si la ventana se redimensiona a más de 992px
    window.addEventListener("resize", () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove("active");
            document.body.style.overflow = "";
        }
    });

    // Desplazamiento suave para enlaces de anclaje
    document.querySelectorAll('a[href^="#"]').forEach((enlace) => {
        enlace.addEventListener("click", function (e) {
            e.preventDefault();

            const idDestino = this.getAttribute("href");
            if (idDestino === "#") return;

            const elementoDestino = document.querySelector(idDestino);
            if (elementoDestino) {
                elementoDestino.scrollIntoView({
                    behavior: "smooth",
                });
            }
        });
    });

    const LoginBtn = document.getElementById("Login-btn");

    if (LoginBtn) {
        LoginBtn.addEventListener("click", function () {
            // Redirigir a la interfaz de Login de usuarios
            window.location.href = "../Reservas/FormularioReserva/form.html";
        });
    }

    // Botones de reserva de habitaciones con el nombre de la habitación en la URL
    const botonesReservarHabitacion = document.querySelectorAll(".service-card .btn-primary");

    botonesReservarHabitacion.forEach((boton) => {
        boton.addEventListener("click", (e) => {
            e.preventDefault();

            // Obtener el nombre de la habitación desde el elemento <h3>
            const habitacion = boton.closest(".service-card").querySelector("h3").textContent;

            // Redirigir a la página de reserva con el nombre de la habitación en la URL
            window.location.href = `../Reservas/FormularioReserva/form.html?habitacion=${encodeURIComponent(habitacion)}`;
        });
    });
});

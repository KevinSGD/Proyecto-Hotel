document.addEventListener("DOMContentLoaded", () => {

  // Ocultar la barra lateral si la ventana se redimensiona a más de 992px
  window.addEventListener("resize", () => {
      if (window.innerWidth > 992) {
          sidebar.classList.remove("active")
          document.body.style.overflow = ""
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

  // Botones de reserva de habitaciones
  const botonesReservarHabitacion = document.querySelectorAll(".room-card .btn-primary");

  botonesReservarHabitacion.forEach((boton) => {
      boton.addEventListener("click", (e) => {
          e.preventDefault();
          window.location.href = "../Reservas/form.html"; // Cambia "booking.html" por la página deseada
      });
  });

  // Botones de reserva de servicios
  const botonesReservarServicio = document.querySelectorAll(".service-card .btn-primary");

  botonesReservarServicio.forEach((boton) => {
      boton.addEventListener("click", (e) => {
          e.preventDefault();
          window.location.href = "../Reservas/form.html"; // Cambia "service-booking.html" por la página deseada
      });
  });

}); 

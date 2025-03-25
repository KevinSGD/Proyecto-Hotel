// Establecer el año actual en el footer
document.getElementById("current-year").textContent = new Date().getFullYear();

// Efecto de selección en los checkboxes
document.addEventListener("DOMContentLoaded", function () {
  const fadeElements = document.querySelectorAll(".fade-in");

  // Activar animaciones
  fadeElements.forEach(element => {
    element.style.opacity = "1";
  });

  // Cambiar sombra al seleccionar servicios
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener("change", function () {
      const card = this.closest(".service-card");
      if (this.checked) {
        card.style.boxShadow = "0 0 0 2px var(--primary), var(--shadow-lg)";
      } else {
        card.style.boxShadow = "var(--shadow)";
      }
    });
  });

  // Verificar selección al presionar el botón
  const finalizarBtn = document.querySelector(".btn-primary");
  finalizarBtn.addEventListener("click", function () {
    const selectedServices = document.querySelectorAll('input[type="checkbox"]:checked');

    if (selectedServices.length === 0) {
      const confirmacion = confirm("No ha seleccionado ningún servicio extra. ¿Desea continuar sin servicios?");
      if (!confirmacion) {
        return; // Detiene la ejecución si el usuario cancela
      }
    }

    // Si hay servicios seleccionados o el usuario confirma, proceder con el check-in
    alert("Procediendo al Check-in...");
    window.location.href = "../Reservas/CheckIn.html";
  });
});

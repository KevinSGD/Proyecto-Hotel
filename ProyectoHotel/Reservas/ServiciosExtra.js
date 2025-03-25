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
    const selectedServices = [];

    checkboxes.forEach(checkbox => {
      if (checkbox.checked) {
        const serviceCard = checkbox.closest(".service-card");
        const title = serviceCard.querySelector(".service-title").textContent.trim();
        const price = serviceCard.querySelector(".price-tag").textContent.trim();
        const imageSrc = serviceCard.querySelector(".service-image img").src;

        selectedServices.push({
          title: title,
          price: price,
          image: imageSrc
        });
      }
    });


    if (selectedServices.length === 0) {
      const confirmacion = confirm("No ha seleccionado ningún servicio extra. ¿Desea continuar sin servicios?");
      if (!confirmacion) {
        return; // Detiene la ejecución si el usuario cancela
      }
    }

    // Guardar en localStorage
    localStorage.setItem("selectedServices", JSON.stringify(selectedServices));

    // Redirigir a la nueva página de resumen de servicios
    window.location.href = "../Reservas/CheckIn.html";
  });
});

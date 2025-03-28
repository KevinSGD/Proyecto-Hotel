document.getElementById("current-year").textContent = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function () {
  const fadeElements = document.querySelectorAll(".fade-in");

  fadeElements.forEach(element => {
    element.style.opacity = "1";
  });

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
        return; 
      }
    }


    localStorage.setItem("selectedServices", JSON.stringify(selectedServices));

    window.location.href = "../Reservas/c.html";
  });
});
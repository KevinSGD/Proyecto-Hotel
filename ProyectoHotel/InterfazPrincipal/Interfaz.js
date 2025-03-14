document.addEventListener("DOMContentLoaded", () => {
  // Set current year in footer
  document.getElementById("current-year").textContent = new Date().getFullYear();

  // Header scroll effect
  const header = document.querySelector(".header");
  window.addEventListener("scroll", () => {
      header.classList.toggle("scrolled", window.scrollY > 50);
  });

  // Mobile menu toggle
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
  const mobileMenuCloseBtn = document.querySelector(".mobile-menu-close");
  const mobileMenu = document.querySelector(".mobile-menu");

  mobileMenuBtn.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      document.body.style.overflow = "hidden";
  });

  mobileMenuCloseBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      document.body.style.overflow = "";
  });

  // Botón de registro - Abre la interfaz de RegistroUsuarios
  const registroBtn = document.getElementById("registro-btn");
  if (registroBtn) {
      registroBtn.addEventListener("click", () => {
          window.location.href = "../InicioSesion/RegistroUsuarios.html";
      });
  }

  // Scroll animations
  const animatedElements = document.querySelectorAll(".fade-in-up, .fade-in-scale");
  function checkScroll() {
      animatedElements.forEach((element) => {
          if (element.getBoundingClientRect().top < window.innerHeight * 0.8) {
              element.classList.add("active");
          }
      });
  }

  // Initial check
  checkScroll();
  window.addEventListener("scroll", checkScroll);
});

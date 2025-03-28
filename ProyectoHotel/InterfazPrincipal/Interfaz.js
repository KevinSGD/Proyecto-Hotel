document.addEventListener("DOMContentLoaded", function () {
    console.log("El script se está ejecutando correctamente");

    const LoginBtn = document.getElementById("Login-btn");

    if (LoginBtn) {
        LoginBtn.addEventListener("click", function () {

            window.location.href = "../Login/Login.html";
        });
    }



  document.getElementById("current-year").textContent = new Date().getFullYear();

 
  const header = document.querySelector(".header");
  window.addEventListener("scroll", () => {
      header.classList.toggle("scrolled", window.scrollY > 50);
  });


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




  const animatedElements = document.querySelectorAll(".fade-in-up, .fade-in-scale");
  function checkScroll() {
      animatedElements.forEach((element) => {
          if (element.getBoundingClientRect().top < window.innerHeight * 0.8) {
              element.classList.add("active");
          }
      });
  }

  const registrarBtn = document.getElementById("registrar-btn");

  if (registrarBtn) {
      registrarBtn.addEventListener("click", function () {

          window.location.href = "../InicioSesion/RegistroUsuarios.html";
      });
  }
 

  checkScroll();
  window.addEventListener("scroll", checkScroll);
});
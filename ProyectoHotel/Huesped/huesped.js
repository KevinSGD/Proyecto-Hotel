document.addEventListener("DOMContentLoaded", () => {

    window.addEventListener("resize", () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove("active")
            document.body.style.overflow = ""
        }
    });
  
  
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
  
            window.location.href = "../Reservas/form.html";
        });
    }
   
    const botonesReservarServicio = document.querySelectorAll(".service-card .btn-primary");
  
    botonesReservarServicio.forEach((boton) => {
        boton.addEventListener("click", (e) => {
            e.preventDefault();
            window.location.href = "../Reservas/form.html"; 
        });
    });
  
  });
  
    // Set current year in footer
    document.getElementById("current-year").textContent = new Date().getFullYear();

    // Animation on scroll
    document.addEventListener("DOMContentLoaded", function() {
      const fadeElements = document.querySelectorAll('.fade-in');
      
      // Trigger animations immediately for elements in viewport
      fadeElements.forEach(element => {
        element.style.opacity = "1";
      });
      
      // Checkbox selection effect
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          const card = this.closest('.service-card');
          if (this.checked) {
            card.style.boxShadow = "0 0 0 2px var(--primary), var(--shadow-lg)";
          } else {
            card.style.boxShadow = "var(--shadow)";
          }
        });
        function showConfirmModal() {
            const confirmModal = document.getElementById('confirm-modal');
            confirmModal.style.display = 'block';
    
            // Cerrar el modal de confirmación
            document.querySelector('#confirm-modal .close').addEventListener('click', () => {
              confirmModal.style.display = 'none';
            });
    
            // Confirmar que el usuario desea continuar sin servicios
            document.getElementById('confirm-continue-button').addEventListener('click', () => {
              confirmModal.style.display = 'none';
              showModal(); // Proceder al modal de resumen
            });
    
            // Cancelar la acción y cerrar el modal
            document.getElementById('cancel-button').addEventListener('click', () => {
              confirmModal.style.display = 'none';
            });
          }
      });
    });
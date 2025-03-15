document.addEventListener("DOMContentLoaded", () => {
    // Toggle sidebar
    const menuToggle = document.getElementById("menu-toggle")
    const sidebarClose = document.getElementById("sidebar-close")
    const sidebar = document.querySelector(".sidebar")
    const mainContent = document.querySelector(".main-content")
  
    menuToggle.addEventListener("click", () => {
      sidebar.classList.toggle("active")
      document.body.style.overflow = sidebar.classList.contains("active") ? "hidden" : ""
    })
  
    sidebarClose.addEventListener("click", () => {
      sidebar.classList.remove("active")
      document.body.style.overflow = ""
    })
  
    // Close sidebar when clicking outside
    document.addEventListener("click", (event) => {
      const isClickInsideSidebar = sidebar.contains(event.target)
      const isClickOnMenuToggle = menuToggle.contains(event.target)
  
      if (!isClickInsideSidebar && !isClickOnMenuToggle && sidebar.classList.contains("active")) {
        sidebar.classList.remove("active")
        document.body.style.overflow = ""
      }
    })
  
    // Handle window resize
    window.addEventListener("resize", () => {
      if (window.innerWidth > 992) {
        sidebar.classList.remove("active")
        document.body.style.overflow = ""
      }
    })
  
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault()
  
        const targetId = this.getAttribute("href")
        if (targetId === "#") return
  
        const targetElement = document.querySelector(targetId)
        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: "smooth",
          })
        }
      })
    })
  
    // Initialize date pickers with current date + 1 day for checkout
    const today = new Date()
    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)
  
    const checkInInput = document.getElementById("check-in")
    const checkOutInput = document.getElementById("check-out")
  
    if (checkInInput && checkOutInput) {
      // Format date as YYYY-MM-DD for input[type="date"]
      const formatDate = (date) => {
        const year = date.getFullYear()
        const month = String(date.getMonth() + 1).padStart(2, "0")
        const day = String(date.getDate()).padStart(2, "0")
        return `${year}-${month}-${day}`
      }
  
      checkInInput.value = formatDate(today)
      checkOutInput.value = formatDate(tomorrow)
  
      // Ensure checkout date is always after checkin date
      checkInInput.addEventListener("change", function () {
        const checkInDate = new Date(this.value)
        const checkOutDate = new Date(checkOutInput.value)
  
        if (checkOutDate <= checkInDate) {
          const newCheckOutDate = new Date(checkInDate)
          newCheckOutDate.setDate(newCheckOutDate.getDate() + 1)
          checkOutInput.value = formatDate(newCheckOutDate)
        }
      })
    }
  
    // Service category buttons
    const categoryButtons = document.querySelectorAll(".category-btn")
  
    categoryButtons.forEach((button) => {
      button.addEventListener("click", function () {
        categoryButtons.forEach((btn) => btn.classList.remove("active"))
        this.classList.add("active")
  
        // Here you would typically filter the services based on category
        // For demo purposes, we're just toggling the active class
      })
    })
  
    // Room booking buttons
    const bookButtons = document.querySelectorAll(".room-card .btn-primary")
  
    bookButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault()
  
        // For demo purposes, show an alert
        alert("¡Reserva iniciada! En un sistema real, esto lo llevaría al proceso de pago.")
      })
    })
  
    // Service booking buttons
    const serviceButtons = document.querySelectorAll(".service-card .btn-primary")
  
    serviceButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault()
  
        // For demo purposes, show an alert
        alert(
          "¡Servicio reservado! En un sistema real, esto lo agregaría a su reserva actual o lo llevaría a seleccionar una fecha.",
        )
      })
    })
  
    // Notification handling
    const notificationButtons = document.querySelectorAll(".action-btn")
  
    notificationButtons.forEach((button) => {
      button.addEventListener("click", function () {
        // For demo purposes, just toggle a class
        this.classList.toggle("active")
  
        // In a real app, this would open a dropdown with notifications
      })
    })
  
    // Form submission
    const bookingForm = document.querySelector(".booking-form")
  
    if (bookingForm) {
      bookingForm.addEventListener("submit", (e) => {
        e.preventDefault()
  
        // Get form values
        const checkIn = document.getElementById("check-in").value
        const checkOut = document.getElementById("check-out").value
        const guests = document.getElementById("guests").value
        const roomType = document.getElementById("room-type").value
  
        // For demo purposes, show an alert with the search parameters
        alert(
          `Buscando disponibilidad para:\nLlegada: ${checkIn}\nSalida: ${checkOut}\nHuéspedes: ${guests}\nTipo de habitación: ${roomType || "Cualquier tipo"}`,
        )
  
        // In a real app, this would trigger a search and display results
      })
    }
  })
  
  
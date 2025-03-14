document.addEventListener("DOMContentLoaded", () => {
    // Set current year in footer
    document.getElementById("current-year").textContent = new Date().getFullYear()
  
    // Header scroll effect
    const header = document.querySelector(".header")
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        header.classList.add("scrolled")
      } else {
        header.classList.remove("scrolled")
      }
    })
  
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
    const mobileMenuCloseBtn = document.querySelector(".mobile-menu-close")
    const mobileMenu = document.querySelector(".mobile-menu")
  
    mobileMenuBtn.addEventListener("click", () => {
      mobileMenu.classList.add("active")
      document.body.style.overflow = "hidden"
    })
  
    mobileMenuCloseBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("active")
      document.body.style.overflow = ""
    })
  
    // Date picker functionality
    const datePickerTriggers = document.querySelectorAll(".date-picker-trigger")
    const datePickers = document.querySelectorAll(".date-picker")
  
    datePickerTriggers.forEach((trigger, index) => {
      trigger.addEventListener("click", () => {
        datePickers[index].classList.toggle("active")
  
        // Close other date pickers
        datePickers.forEach((picker, i) => {
          if (i !== index) {
            picker.classList.remove("active")
          }
        })
      })
    })
  
    // Close date pickers when clicking outside
    document.addEventListener("click", (event) => {
      if (!event.target.closest(".date-picker-wrapper")) {
        datePickers.forEach((picker) => {
          picker.classList.remove("active")
        })
      }
    })
  
    // Simple calendar implementation
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ]
    const days = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"]
  
    function generateCalendar(datePickerId, displayElement) {
      const datePicker = document.getElementById(datePickerId)
      const today = new Date()
      const currentMonth = today.getMonth()
      const currentYear = today.getFullYear()
  
      // Create calendar header
      const calendarHeader = document.createElement("div")
      calendarHeader.className = "calendar-header"
      calendarHeader.innerHTML = `
        <button class="prev-month"><i class="fas fa-chevron-left"></i></button>
        <div class="current-month">${months[currentMonth]} ${currentYear}</div>
        <button class="next-month"><i class="fas fa-chevron-right"></i></button>
      `
  
      // Create days header
      const daysHeader = document.createElement("div")
      daysHeader.className = "days-header"
      days.forEach((day) => {
        const dayEl = document.createElement("div")
        dayEl.className = "day-name"
        dayEl.textContent = day
        daysHeader.appendChild(dayEl)
      })
  
      // Create days grid
      const daysGrid = document.createElement("div")
      daysGrid.className = "days-grid"
  
      // Get first day of month and total days
      const firstDay = new Date(currentYear, currentMonth, 1).getDay()
      const totalDays = new Date(currentYear, currentMonth + 1, 0).getDate()
  
      // Add empty cells for days before first day of month
      for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement("div")
        emptyDay.className = "day empty"
        daysGrid.appendChild(emptyDay)
      }
  
      // Add days of month
      for (let i = 1; i <= totalDays; i++) {
        const day = document.createElement("div")
        day.className = "day"
        day.textContent = i
  
        // Highlight today
        if (i === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
          day.classList.add("today")
        }
  
        // Add click event to select date
        day.addEventListener("click", () => {
          const selectedDate = new Date(currentYear, currentMonth, i)
          const formattedDate = `${i} de ${months[currentMonth]} de ${currentYear}`
          displayElement.textContent = formattedDate
          datePicker.classList.remove("active")
        })
  
        daysGrid.appendChild(day)
      }
  
      // Append all elements to calendar
      datePicker.innerHTML = ""
      datePicker.appendChild(calendarHeader)
      datePicker.appendChild(daysHeader)
      datePicker.appendChild(daysGrid)
  
      // Add month navigation functionality
      const prevMonthBtn = datePicker.querySelector(".prev-month")
      const nextMonthBtn = datePicker.querySelector(".next-month")
  
      // This would need more implementation for full functionality
    }
  
    // Initialize calendars
    const arrivalDateDisplay = document
      .querySelector("#arrival-date-picker")
      .previousElementSibling.querySelector(".date-display")
    const departureDateDisplay = document
      .querySelector("#departure-date-picker")
      .previousElementSibling.querySelector(".date-display")
  
    generateCalendar("arrival-date-picker", arrivalDateDisplay)
    generateCalendar("departure-date-picker", departureDateDisplay)
  
    // Scroll animations
    const animatedElements = document.querySelectorAll(".fade-in-up, .fade-in-scale")
  
    function checkScroll() {
      animatedElements.forEach((element) => {
        const elementTop = element.getBoundingClientRect().top
        const windowHeight = window.innerHeight
  
        if (elementTop < windowHeight * 0.8) {
          element.classList.add("active")
        }
      })
    }
  
    // Initial check
    checkScroll()
  
    // Check on scroll
    window.addEventListener("scroll", checkScroll)
  })
  
  
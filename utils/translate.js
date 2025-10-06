// Inicialización de i18next con recursos embebidos
i18next.init({
  lng: "es", // idioma inicial
  debug: true,
  resources: {
    es: {
      translation: {
        welcome: "¡Bienvenido!",
        whoAreYou: "¿Quién eres?",
        student: "Estudiante",
        adscripto: "Adscripto",
        teacher: "Docente",
        secretary: "Secretario",
        //
        welcom3: "Bienvenid@",
        sessionClose: "Cerrar Sesión",
        goBack: "Volver",
        //adscripto
        facility: "Espacio",
        reservation: "Reserva",
        teacherAbsence: "Falta docente",
        addSubjects: "Cargar materias",
        //
        idCard: "Cédula de Identidad",
        password: "Contraseña",
        logIn: "Iniciar Sesión",
        //
        reservationRequests: "Solicitudes de Reserva",
        thTeacher: "Docente",
        thFacilityRequested: "Espacio solicitado",
        thRequestedDate: "Fecha solicitada",
        thReservationStatus: "Estado reserva",
        //
        teacherAbsence: "Falta docentes",
        teacherName: "Nombre del docente:",
        subject: "Materia:",
        groupMissing: "Grupo al que falta:",
        day: "Día:",
        hoursMissing: "Cantidad de horas que falta:",
        //
      }
    },
    en: {
      translation: {
        // clave: "valor"
        welcome: "Welcome!",
        whoAreYou: "Who are you?",
        student: "Student",
        adscripto: "Advisor",
        teacher: "Teacher",
        secretary: "Secretary",
        //
        welcom3: "Welcome",
        sessionClose: "Log Out",
        goBack: "Go Back",
        //adscripto
        facility: "Facility", // Más institucional y abarca todo tipo de espacio físico (aulas, laboratorios, oficinas). Ideal para un sistema educativo.
        reservation: "Reservation",
        teacherAbsence: "Teacher absence",
        addSubjects: "Add subjects",
        //
        idCard: "ID Card",
        password: "Password",
        logIn: "Log In",
        //
        reservationRequests: "Reservation Requests",
        thTeacher: "Teacher",
        thFacilityRequested: "Requested Facility",
        thRequestedDate: "Requested date",
        thReservationStatus: "Reservation status",
        //
        teacherAbsence: "Teacher Absence",
        teacherName: "Teacher's Name:",
        subject: "Subject:",
        groupMissing: "Group Missed:",
        day: "Date:",
        hoursMissing: "Hours Absent:",
      }
    }
  }
},
 function(err, t) {
  if (err) return console.error("Error al cargar traducciones", err);
  updateContent();
});

// Función para aplicar traducciones a los elementos NORMALES con data-i18n
function updateContent() {
  document.querySelectorAll("[data-i18n]").forEach((el) => {
    const key = el.getAttribute("data-i18n");
    el.innerHTML = i18next.t(key);
  });
  // Placeholders
  document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
    const key = el.getAttribute("data-i18n-placeholder");
    el.placeholder = i18next.t(key);
  });
}


// Botón para cambiar idioma (puede haber varios botones)
document.querySelectorAll(".bi-translate").forEach((btn) => {
  btn.addEventListener("click", () => {
    const newLang = i18next.language === "es" ? "en" : "es";
    i18next.changeLanguage(newLang, updateContent);
  });
});
// Inicialización de i18next con recursos embebidos
i18next.init({
  lng: localStorage.getItem('language') || "es", // Recuperar el idioma desde localStorage, si no existe, usar "es" por defecto
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
        studentPanelTitle: "Panel Estudiante",
        goBack: "Volver",
        student: "Estudiante",
        adscripto: "Adscripto",
        teacher: "Docente",
        secretary: "Secretario",
        enterGroup: "Ingresa tu grupo correspondiente",
        enterGroupPlaceholder: "Ingrese su grupo",
        //
        hour: "Hora",
        subject: "Materia",
        room: "Espacio",
        // 
        monday: "Lunes",
        tuesday: "Martes",
        wednesday: "Miércoles",
        thursday: "Jueves",
        friday: "Viernes",
        //
        assignedGroups: "Grupos a cargo",
        reserveFacility: "Reservar espacio",
        reportAbsence:"Reportar falta",
        //
        viewReservations:"Ver reservas",
        makeReservations:"Hacer reservas",
        //
        underMaintenance:"Estamos en mantenimiento",
        backSoon:"Volveremos pronto con algo mejor ✨",
        //
        schedule:"Horario",
        select:"Seleccionar",
        newReservation:"Nueva reserva",
        cancel:"Cancelar",
        reserve:"Reservar",
        //
        groupAbsent:"Grupo al que falta",
        date:"Fecha",
        hoursAbsent:"Cantidad de horas",
        reason:"Motivo",
        report:"Avisar",
        // ALERTAS
        allFieldsRequired: "Todos los campos son obligatorios",
        idMustBeNumbers: "La cédula debe ser solo números",
        idMustHave8Digits: "La cédula debe tener 8 números válidos",
        successfulLogin: "Inicio de sesión exitoso",
        serverError: "Error al conectar con el servidor.",
        logoutConfirmTitle: "¿Seguro que deseas cerrar sesión?",
        logoutConfirmText: "Se cerrará tu sesión actual",
        logoutConfirmButton: "Sí, cerrar sesión",
    //
        users:"Usuarios",
        groups:"Grupos",
        resources:"Recursos",
        schedules:"Horarios",
        recordSchedules:"Registrá los horarios indicando la hora de inicio y finalización por hora",
        startTime:"Hora de inicio",
        endTime:"Hora de finalización",
        //
        manageGroups:"Gestioná los grupos: agregá, modificá o eliminá según sea necesario.",
        groupManagement:"Gestion de grupos",
        groupName:"Nombre del grupo",
        orientation:"Orientación",
        egMC:"Ej: María Caceres",
        enterOrientation:"Ingrese la orientación",
        shift:"Turno",
        numberStudents:"Número de estudiantes",
        eg34:"Ej:34",
        morning:"Matutino",
        afternoon:"Vespertino",
        evening:"Nocturno",
        save:"Guardar",
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
        teacherName: "Teacher's Name:",
        subject: "Subject:",
        groupMissing: "Group Missed:",
        day: "Date:",
        hoursMissing: "Hours Absent:",
        studentPanelTitle: "Student Panel",
        enterGroup: "Enter your assigned group",
        enterGroupPlaceholder: "Enter your group",
        //
        hour: "Hour",
        subject: "Subject",
        room: "Room",
        // 
        monday: "Monday",
        tuesday: "Tuesday",
        wednesday: "Wednesday",
        thursday: "Thursday",
        friday: "Friday",
        //
        assignedGroups: "Assigned Groups",
        reserveFacility: "Reserve Facility",
        reportAbsence:"Report Absence",
      //
      viewReservations:"View Reservations",
      makeReservations:"Make Reservations",
      //
       underMaintenance:"We’re Currently Under Maintenance",
        backSoon:"We’ll Be Back Soon with Something Even Better ✨",
       //
       schedule:"Schedule",
        select:"Select",
        newReservation:"New reservation",
         cancel:"Cancel",
        reserve:"Reserve",
        //
        groupAbsent:"Group Absent From",
        date:"Date",
        hoursAbsent:"Hours Absent",
        reason:"Reason",
        report:"Report",
        // ALERTAS
        allFieldsRequired: "All fields are required",
        idMustBeNumbers: "ID card must be numbers only",
        idMustHave8Digits: "ID card must have 8 valid numbers",
        successfulLogin: "Successful login",
        serverError: "Error connecting to the server.",
        logoutConfirmTitle: "Are you sure you want to log out?",
        logoutConfirmText: "Your current session will be closed",
        logoutConfirmButton: "Yes, log out",
        //
        users:"Users",
        groups:"Groups",
        resources:"Resources",
        schedules:"Schedules",
        recordSchedules:"Record the schedules by specifying the start and end time for each hour",
        startTime:"Start time",
        endTime:"End time",
        //
        manageGroups:"Manage the Groups: Add, Modify, or Delete as Needed",
        groupManagement:"Group Management",
        groupName:"Group Name",
        orientation:"Orientation",
        egMC:"E.g.: Maria Caceres",
        enterOrientation:"Enter the orientation",
        shift:"Shift",
        numberStudents:"Number of Students",
        eg34:"E.g.:34",
        morning:"Morning",
        afternoon:"Afternoon",
        evening:"Evening",
        save:"Save",
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
    let newLang = i18next.language === "es" ? "en" : "es";
    
    // Cambiar el idioma en i18next
    i18next.changeLanguage(newLang, () => {
      // Actualizar el contenido de la página con el nuevo idioma
      updateContent();
      // Guardar el idioma seleccionado en localStorage
      localStorage.setItem('language', newLang);
    });
  });
});
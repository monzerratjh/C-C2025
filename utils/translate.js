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
        reportAbsence:"Avisar falta",
        //
        viewReservations:"Ver reservas",
        makeReservations:"Hacer reservas",
        //
        underMaintenance:"Estamos en mantenimiento",
        backSoon:"Volveremos pronto con algo mejor ✨",
        //
        schedule:"Horarios",
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
        egMC:"Ej: 3ro MD",
        enterOrientation:"Ingrese la orientación",
        shift:"Turno",
        numberStudents:"Número de estudiantes",
        eg34:"Ej: 30",
        morning:"Matutino",
        afternoon:"Vespertino",
        evening:"Nocturno",
        save:"Guardar",
        //
        manageUsers:"Gestiona los usuarios: agregá nuevos o modificá los existentes.",
        name:"Nombre",
        lastName:"Apellido",
        phone:"Telefono",
        idCard2:"Cédula",
        position:"Cargo",
        addUser:"Agregar usuario",
        editUser:"Editar usuario",
        //
        close:"Cerrar",
        //
        courseManagement:"Gestión de cursos",
        facilityManagement:"Gestión de espacios",
        selectTypeFacility:"Seleccione el tipo de espacio que desea gestionar:",
        classrooms:"Salones",
        computerLabs:"Aulas",
        scienceLabs:"Laboratorios",
        viewReservations:"Consulta tus reservas realizadas",
        //
        courseSubjectManagement:"Gestión de cursos y asignaturas",
        pCourseSubjectManagement:"Gestione los cursos, asignaturas, docentes y espacios del centro educativo de forma sencilla y organizada.",
        addSubject:"Cargar asignaturas",
        assignFTS:"Asignar espacios, docentes y asignaturas",
        assignSchedule:"Asignar hora",
        //
        manageClassrooms:"Agrega, edita o elimina salones de forma rápida y sencilla.",
        manageComputersLabs:"Agrega, edita o elimina aulas de forma rápida y sencilla.",
        manageScienceLabs:"Agrega, edita o elimina laboratorios de forma rápida y sencilla.",
        spaceManagement:"Gestión de espacio",
        spaceName:"Nombre del espacio",
        capacity:"Capacidad",
        availability:"Disponibilidad",
        historyNotes:"Historial / Observaciones",
        next:"Siguiente",
        //
        logInDescription: "Inicia sesión con tu cédula y contraseña para acceder a tu cuenta y comenzar a usar el sistema.",
        welcomeDescription:"Para comenzar, utiliza la barra lateral ubicada a la izquierda de la pantalla. Desde allí podrás acceder fácilmente a las distintas secciones y funciones del sistema.",
        maxStudents:"(Máx. de alumnos)",
        //
        loadSubject:"Cargar Asignatura",
        enterSubject:"Ingrese la asignatura",
        exampleFullStack:"Ej: Programación Full-Stack",
        load:"Cargar",
        loadedSubjects:"Asignaturas cargadas",
        nameLoadedSubjects:"Nombre Asignaturas Cargadas",
        //
        loadHour:"Cargar Hora",
        enterGroupHours:"Ingrese el grupo en el cual va a agregar las horas dictadas.",
        enterGroup:"Ingrese el grupo",
        //
         assignRTSG:"Asignar un espacio, un docente y una asignatura a grupo.",
        enterInformation:"Ingrese los datos solicitados.",
        selectSubject:"Seleccionar asignatura",
        selectTeacher:"Seleccionar docente",
        selectRoom:"Seleccionar espacio",
        selectGroup:"Seleccionar grupo",
        assign:"Asignar",
        assignments:"Asignaciones",
        subjectName:"Nombre asignatura",
        group:"Grupo",
        //
        subjectTeacher:"Asigantura (docente)",
        noClasses:"Sin clases cargadas",
        uploadSchedules:"Cargar horarios",
        subjectAndTeacher:"Asignatura y docente",
        addEditSchedule:"Agregar / Editar horario",
        //
        editSubject:"Edicion de asignatura",
        ensureWrittenCorrectly:"Asegúrese de que quede bien escrito.",
        //
        assignmentEditing:"Edicion de asignación",
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
      makeReservations:"Make reservations",
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
        egMC:"E.g.: 3rd MD",
        enterOrientation:"Enter the orientation",
        shift:"Shift",
        numberStudents:"Number of Students",
        eg34:"E.g.: 30",
        morning:"Morning",
        afternoon:"Afternoon",
        evening:"Evening",
        save:"Save",
        //
        manageUsers:"Manage the users: add new ones or modify existing ones",
        name:"Name",
        lastName:"Last name",
        phone:"Phone",
        idCard2:"ID",
        position:"Position",
        addUser:"Add user",
        editUser:"Edit user",
        //
        close:"Close",
        courseManagement:"Course Management",
        facilityManagement:"Facility Management",
        selectTypeFacility:"Select the type of facility you wish to manage:",
        classrooms:"Classrooms",
        computerLabs:"Computer Labs",
        scienceLabs:"Science Labs",
        //
        viewReservations:"View your reservations made",
        pCourseSubjectManagement:"Easily manage the courses, subjects, teachers, and facilities of your educational institution in an organized way.",
        courseSubjectManagement:"Course and Subject Management",
       addSubject:"Add Subjects",
       assignFTS:"Assign Facilities, Teachers, and Subjects",
        assignSchedule:"Assign Schedule",
        //
        manageClassrooms:"Manage classrooms: add, modify, or remove them as needed.",
        manageComputersLabs:"Manage computer labs: add, modify, or remove them as needed.",
        manageScienceLabs:"Manage science labs: add, modify, or remove them as needed.",
        spaceManagement:"Space Management",
        spaceName:"Space Name",
        capacity:"Capacity",
        availability:"Availability",
        historyNotes:"History / Notes",
        next:"Next",
        //
        logInDescription: "Log in with your ID number and password to access your account and start using the system.",
        welcomeDescription:"To get started, use the sidebar located on the left side of the screen. From there, you can easily access the different sections and features of the system.",
        maxStudents:"(Max. number of students)",
        //
        loadSubject:"Load Subject",
        enterSubject:"Enter the subject",
        exampleFullStack:"Example: Full-Stack Programming",
        load:"Load",
        loadedSubjects:"Loaded Subjects",
        nameLoadedSubjects:"Name of Loaded Subjects",
        //
        loadHour:"Load Hour",
        enterGroupHours:"Enter the group in which you want to add the hours taught.",
        enterGroup:"Enter the group",
        //
        assignRTSG:"Assign a Room, Teacher, and Subject to a Group",
        enterInformation:"Enter the required information.",
        selectSubject:"Select Subject",
        selectTeacher:"Select Teacher",
        selectRoom:"Select classroom",
        selectGroup:"Select Group",
        assign:"Assign",
        assignments:"Assignments",
        subjectName:"Subject Name",
        group:"Group",
        //
        subjectTeacher:"Subject (Teacher)",
        noClasses:"No classes available",
        uploadSchedules:"Upload Schedules",
        subjectAndTeacher:"Subject and teacher",
        addEditSchedule:"Add / Edit Schedule",
        //
        ensureWrittenCorrectly:"Ensure it is written correctly.",
        editSubject: "Edit subject",
        assignmentEditing:"Assignment Editing",
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
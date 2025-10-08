--  USUARIOS
INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario, contrasenia_usuario)
VALUES
('Laura', 'Silva', 'laura.silva@instituto.edu.uy', '091234567', 'Secretario', 45678901, '1234'),
('María', 'Pérez', 'maria.perez@instituto.edu.uy', '098765432', 'Adscripto', 56789012, '1234'),
('Carlos', 'Sosa', 'carlos.sosa@instituto.edu.uy', '097654321', 'Adscripto', 67890123, '1234'),
('Ana', 'Rodríguez', 'ana.rodriguez@instituto.edu.uy', '092345678', 'Docente', 78901234, '1234'),
('Luis', 'García', 'luis.garcia@instituto.edu.uy', '093456789', 'Docente', 89012345, '1234');

--  SECRETARIO
INSERT INTO secretario (id_usuario) VALUES (1);

--  ADSCRIPTOS
INSERT INTO adscripto (id_usuario) VALUES (2), (3);

--  DOCENTES
INSERT INTO docente (id_usuario) VALUES (4), (5);

--  RECURSOS
INSERT INTO recurso (nombre_recurso, tipo_recurso, disponibilidad_recurso, historial_recurso, estado_recurso)
VALUES
('Proyector Epson', 'Otro', 'Disponible', 'Nuevo ingreso', 'Activo'),
('Notebook Lenovo', 'Otro', 'Prestado', 'Entregado al docente Ana', 'Activo');

--  ESPACIOS
INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio, tipo_espacio)
VALUES
('Laboratorio de Informática', 25, 'Creado por Laura Silva', 'Libre', 'Laboratorio'),
('Salón 201', 30, 'Creado por Laura Silva', 'Libre', 'Aula');

--  ATRIBUTOS DE ESPACIOS
INSERT INTO espacio_atributo (id_espacio, nombre_atributo, cantidad_atributo)
VALUES
(1, 'Mesas', 25),
(1, 'Sillas', 25),
(1, 'Proyector', 1),
(1, 'Aire Acondicionado', 1),
(2, 'Mesas', 15),
(2, 'Sillas', 30),
(2, 'Televisor', 1);

--  ASIGNATURAS
INSERT INTO asignatura (cantidad_horas_asignatura, nombre_asignatura)
VALUES
(4, 'Programación'),
(2, 'Base de Datos');

--  GRUPOS
INSERT INTO grupo (orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario)
VALUES
('Tecnologías de la Información', 'Matutino', '3° TI A', 28, 1, 1),
('Tecnólogo en Ciberseguridad', 'Vespertino', '2° TC B', 24, 2, 1);

--  DOCENTE DICTA ASIGNATURA
INSERT INTO docente_dicta_asignatura (id_docente, id_asignatura)
VALUES
(1, 1),  -- Ana Rodríguez dicta Programación
(2, 2);  -- Luis García dicta Base de Datos

--  DOCENTE TIENE GRUPO
INSERT INTO docente_tiene_grupo (id_grupo, id_docente, id_asignatura)
VALUES
(1, 1, 1),
(2, 2, 2);

--  HORARIOS
INSERT INTO horario_clase (hora_inicio, hora_fin, id_secretario)
VALUES
('08:00:00', '09:30:00', 1),
('09:40:00', '11:10:00', 1);

--  ADSCRIPTO ORGANIZA HORARIO
INSERT INTO adscripto_organiza_horario_clase (id_adscripto, id_horario_clase, id_asignatura, dia)
VALUES
(1, 1, 1, 'Lunes'),
(2, 2, 2, 'Martes');

--  SOLICITUD DE ESPACIO
INSERT INTO asignatura_docente_solicita_espacio (id_asignatura, id_docente, id_horario_clase, id_espacio, estado_reserva)
VALUES
(1, 1, 1, 1, 'Aceptada'),
(2, 2, 2, 2, 'Pendiente');

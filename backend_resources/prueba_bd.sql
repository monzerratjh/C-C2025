-- ===========================
--      DATOS DE PRUEBA
-- ===========================

-- USUARIOS
INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, ci_usuario, contraseña_usuario) VALUES
('Laura',   'Martínez', 'laura.martinez@itu.edu', '091234567', 45678912, 'hashpass1'),
('Carlos',  'Gómez',    'carlos.gomez@itu.edu',  '092345678', 56789123, 'hashpass2'),
('Lucía',   'Pérez',    'lucia.perez@itu.edu',   '093456789', 67891234, 'hashpass3'),
('Andrés',  'Fernández','andres.fernandez@itu.edu','094567890',78912345,'hashpass4'),
('María',   'Rodríguez','maria.rodriguez@itu.edu','095678901',89123456,'hashpass5'),
('Jorge',   'Silva',    'jorge.silva@itu.edu',   '096789012',91234567, 'hashpass6'),
('Ana',     'Lopez',    'ana.lopez@itu.edu',     '097890123',12345678, 'hashpass7'),
('Diego',   'Castro',   'diego.castro@itu.edu',  '098901234',23456789, 'hashpass8');

-- SECRETARIO
INSERT INTO secretario (grado_secretario, horario_trabajo_secretario, id_usuario) VALUES
('Administrativo', '08:00:00', 1),
('Administrativo', '12:00:00', 2);

-- ADSCRIPTO
INSERT INTO adscripto (id_usuario, cantidad_grupos_asignados, horario_trabajo_adscripto, caracter_cargo_adscripto) VALUES
(3, 2, '09:00:00', 'Adscripto Principal'),
(4, 1, '14:00:00', 'Adscripto Suplente');

-- DOCENTE
INSERT INTO docente (grado_docente, id_usuario) VALUES
('Titular', 5),
('Adjunto', 6),
('Suplente',7),
('Titular', 8);

-- ESPACIO
INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio) VALUES
('Laboratorio Informática 1', 30, 'Renovado en 2024', 'libre'),
('Aula 204', 25, NULL, 'libre'),
('Laboratorio Redes', 20, 'Cambio de routers en 2023', 'mantenimiento');

-- RECURSO
INSERT INTO recurso (disponibilidad_recurso, nombre_recurso, historial_recurso, tipo_recurso, estado_recurso, id_espacio) VALUES
('disponible','Proyector Epson X1', 'Reparación lámpara 2023','Proyector','operativo',1),
('disponible','Switch Cisco 24p',  'Cambio firmware 2024','Redes','operativo',3),
('no disponible','Notebook Dell',  'En reparación teclado','Computadora','en_reparacion',1);

-- GRUPO
INSERT INTO grupo (orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES
('Tecnologías de la Información','Matutino','TI-1',28,1,1),
('Redes y Comunicaciones Ópticas','Vespertino','RCO-2',22,2,1),
('Diseño Gráfico en Comunicación Visual','Nocturno','DG-3',18,1,2);

-- ASIGNATURA
INSERT INTO asignatura (cantidad_horas_asignatura, nombre_asignatura) VALUES
(4,'Programación Web'),
(3,'Redes Avanzadas'),
(2,'Diseño Digital');

-- DOCENTE DICTA ASIGNATURA
INSERT INTO docente_dicta_asignatura (id_docente, id_asignatura) VALUES
(1,1),
(2,2),
(3,3),
(4,1);

-- DOCENTE TIENE GRUPO
INSERT INTO docente_tiene_grupo (id_grupo, id_docente, id_asignatura) VALUES
(1,1,1),
(2,2,2),
(3,3,3),
(1,4,1);

-- SECRETARIO ADMINISTRA RECURSO
INSERT INTO secretario_administra_recurso (id_secretario, id_recurso) VALUES
(1,1),
(1,2),
(2,3);

-- DOCENTE PIDE RECURSO
INSERT INTO docente_pide_recurso (id_docente, id_recurso) VALUES
(1,1),
(2,2),
(3,3);

-- HORARIO CLASE
INSERT INTO horario_clase (hora_reloj_horario_clase, id_asignatura) VALUES
('08:30:00',1),
('10:00:00',2),
('19:00:00',3);

-- ADSCRIPTO ORGANIZA HORARIO
INSERT INTO adscripto_organiza_horario_clase (id_adscripto, id_horario_clase) VALUES
(1,1),
(1,2),
(2,3);

-- ASIGNATURA-DOCENTE SOLICITA ESPACIO
INSERT INTO asignatura_docente_solicita_espacio
(id_asignatura, id_docente, hora_clase, id_espacio, estado_reserva)
VALUES
(1,1,'08:30:00',1,'aceptada'),
(2,2,'10:00:00',3,'pendiente'),
(3,3,'19:00:00',2,'aceptada');

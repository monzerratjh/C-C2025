-- ============================================
-- DATOS DE PRUEBA COMPLETOS Y CONSISTENTES
-- ============================================

-- 1. USUARIOS
INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario, contrasenia_usuario) VALUES
('Laura', 'Martínez', 'laura.martinez@itu.edu', '091234567', 'Secretario', 45678912, 'hashpass1'),
('Carlos', 'Gómez', 'carlos.gomez@itu.edu', '092345678', 'Secretario', 56789123, 'hashpass2'),
('Lucía', 'Pérez', 'lucia.perez@itu.edu', '093456789', 'Adscripto', 67891234, 'hashpass3'),
('Andrés', 'Fernández','andres.fernandez@itu.edu','094567890', 'Adscripto', 78912345,'hashpass4'),
('María', 'Rodríguez','maria.rodriguez@itu.edu','095678901', 'Docente', 89123456,'hashpass5'),
('Jorge', 'Silva','jorge.silva@itu.edu','096789012', 'Docente', 91234567, 'hashpass6'),
('Ana', 'López','ana.lopez@itu.edu','097890123', 'Docente', 12345678, 'hashpass7'),
('Diego', 'Castro','diego.castro@itu.edu','098901234', 'Docente', 23456789, 'hashpass8');

-- 2. SECRETARIOS
INSERT INTO secretario (id_usuario) VALUES
(1),
(2);

-- 3. ADSCRIPTOS
INSERT INTO adscripto (id_usuario) VALUES
(3),
(4);

-- 4. DOCENTES
INSERT INTO docente (id_usuario) VALUES
(5),
(6),
(7),
(8);

-- 5. ESPACIOS
INSERT INTO espacio (nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio) VALUES
('Laboratorio Informática 1', 30, 'Renovado en 2024', 'libre'),
('Aula 204', 25, NULL, 'libre'),
('Laboratorio Redes', 20, 'Cambio de routers en 2023', 'mantenimiento');

-- 6. RECURSOS
INSERT INTO recurso (disponibilidad_recurso, nombre_recurso, historial_recurso, tipo_recurso, estado_recurso, id_espacio) VALUES
('disponible','Proyector Epson X1', 'Reparación lámpara 2023','Proyector','operativo',1),
('disponible','Switch Cisco 24p',  'Cambio firmware 2024','Redes','operativo',3),
('no disponible','Notebook Dell',  'En reparación teclado','Computadora','en_reparacion',1);

-- 7. SECRETARIO ADMINISTRA RECURSO
INSERT INTO secretario_administra_recurso (id_secretario, id_recurso) VALUES
(1,1),
(1,2),
(2,3);

-- 8. ASIGNATURAS
INSERT INTO asignatura (cantidad_horas_asignatura, nombre_asignatura) VALUES
(4,'Programación Web'),
(3,'Redes Avanzadas'),
(2,'Diseño Digital');

-- 9. GRUPOS
INSERT INTO grupo (orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario) VALUES
('Tecnologías de la Información','Matutino','TI-1',28,1,1),
('Redes y Comunicaciones Ópticas','Vespertino','RCO-2',22,2,1),
('Diseño Gráfico en Comunicación Visual','Nocturno','DG-3',18,1,2);

-- 10. HORARIO CLASE
INSERT INTO horario_clase (hora_inicio, hora_fin, id_secretario) VALUES
('08:30:00','10:00:00',1),
('10:30:00','12:00:00',1),
('19:00:00','20:30:00',2);

-- 11. ADSCRIPTO ORGANIZA HORARIO CLASE
INSERT INTO adscripto_organiza_horario_clase (id_adscripto, id_horario_clase, id_asignatura, dia) VALUES
(1,1,1,'Lunes'),
(1,2,2,'Martes'),
(2,3,3,'Miércoles');

-- 12. DOCENTE DICTA ASIGNATURA
INSERT INTO docente_dicta_asignatura (id_docente, id_asignatura) VALUES
(1,1),
(2,2),
(3,3),
(4,1);

-- 13. DOCENTE TIENE GRUPO
INSERT INTO docente_tiene_grupo (id_grupo, id_docente, id_asignatura) VALUES
(1,1,1),
(2,2,2),
(3,3,3),
(1,4,1);

-- 14. DOCENTE PIDE RECURSO
INSERT INTO docente_pide_recurso (id_docente, id_recurso) VALUES
(1,1),
(2,2),
(3,3);

-- 15. ASIGNATURA-DOCENTE SOLICITA ESPACIO
INSERT INTO asignatura_docente_solicita_espacio (id_asignatura, id_docente, hora_clase, id_espacio, estado_reserva) VALUES
(1,1,'08:30:00',1,'aceptada'),
(2,2,'10:30:00',3,'pendiente'),
(3,3,'19:00:00',2,'aceptada');

-- Tabla usuario
INSERT INTO usuario (id_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, ci_usuario)
VALUES 
(1, 'Alfonsina', 'Coiro', 'alfonsina@email.com', 123456789, 56545265),
(2, 'Juan', 'Perez', 'juan@email.com', 234567890, 59599305),
(3, 'Ana', 'Gomez', 'ana@email.com', 345678901, 51595435);

-- Tabla secretario
INSERT INTO secretario (id_secretario, grado_secretario, horario_trabajo_secretario, id_usuario)
VALUES
(1, 'Senior', '08:00:00', 1),
(2, 'Junior', '09:00:00', 2);

-- Tabla adscripto
INSERT INTO adscripto (id_adscripto, id_usuario, cantidad_grupos_asignados, horario_trabajo_adscripto, caracter_cargo_adscripto)
VALUES
(1, 3, 2, '10:00:00', 'Coordinador');

-- Tabla docente
INSERT INTO docente (id_docente, grado_docente, id_usuario)
VALUES
(1, 'Licenciado', 2);

-- Tabla asignatura
INSERT INTO asignatura (id_asignatura, cantidad_horas_asignatura, nombre_asignatura)
VALUES
(1, 4, 'Matemática'),
(2, 3, 'Historia');

-- Tabla grupo
INSERT INTO grupo (id_grupo, orientacion_grupo, turno_grupo, nombre_grupo, cantidad_alumno_grupo, id_adscripto, id_secretario)
VALUES
(1, 'Ciencias', 'Mañana', '1A', 25, 1, 1);

-- Tabla espacio
INSERT INTO espacio (id_espacio, nombre_espacio, capacidad_espacio, historial_espacio, disponibilidad_espacio)
VALUES
(1, 'Aula 101', 30, 'Sin incidencias', 'Disponible');

-- Tabla recurso
INSERT INTO recurso (id_recurso, disponibilidad_recurso, nombre_recurso, historial_recurso, tipo_recurso, estado_recurso, id_espacio)
VALUES
(1, 'Disponible', 'Proyector', 'Nuevo', 'Interno', 'Bueno', 1);

-- Tabla secretario_administra_recurso
INSERT INTO secretario_administra_recurso (id_secretario, id_recurso)
VALUES
(1, 1);

-- Tabla docente_pide_recurso
INSERT INTO docente_pide_recurso (id_docente, id_recurso)
VALUES
(1, 1);

-- Tabla horario_clase
INSERT INTO horario_clase (id_horario_clase, hora_reloj_horario_clase, id_asignatura)
VALUES
(1, '09:00:00', 1);

-- Tabla adscripto_organiza_horario_clase
INSERT INTO adscripto_organiza_horario_clase (id_adscripto, id_horario_clase)
VALUES
(1, 1);

-- Tabla asignatura_docente_solicita_espacio
INSERT INTO asignatura_docente_solicita_espacio (id_asignatura, id_docente, fecha_hora_reserva, hora_clase, id_espacio)
VALUES
(1, 1, '2025-09-01 09:00:00', '09:00:00', 1);

-- Tabla docente_tiene_grupo
INSERT INTO docente_tiene_grupo (id_grupo, id_docente, id_asignatura)
VALUES
(1, 1, 1);

-- Tabla docente_dicta_asignatura
INSERT INTO docente_dicta_asignatura (id_docente, id_asignatura)
VALUES
(1, 1);

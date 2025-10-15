-- ============================================================
-- TABLA USUARIO: Almacena datos de todos los usuarios
-- ============================================================
CREATE TABLE usuario (
	id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_usuario VARCHAR(120) NOT NULL,
	apellido_usuario VARCHAR(120) NOT NULL,
	gmail_usuario VARCHAR(200) NOT NULL,
	telefono_usuario VARCHAR(9) NOT NULL,
	cargo_usuario ENUM('Secretario', 'Docente', 'Adscripto') NOT NULL,
	ci_usuario INT(8) NOT NULL,
	contrasenia_usuario VARCHAR(255) NOT NULL
);

-- ============================================================
-- TABLA SECRETARIO
-- ============================================================
CREATE TABLE secretario (
	id_secretario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_usuario INT NOT NULL -- FK a usuario
);

-- ============================================================
-- TABLA DOCENTE
-- ============================================================
CREATE TABLE docente (
	id_docente INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_usuario INT NOT NULL -- FK a usuario
);

-- ============================================================
-- TABLA ADSCRIPTO
-- ============================================================
CREATE TABLE adscripto (
	id_adscripto INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_usuario INT NOT NULL -- FK a usuario
);

-- ============================================================
-- TABLA RECURSO
-- ============================================================
CREATE TABLE recurso (
	id_recurso INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_recurso VARCHAR(100) NOT NULL,
	tipo_recurso ENUM('Otro') NOT NULL DEFAULT 'Otro',
	disponibilidad_recurso ENUM('Disponible', 'Prestado') DEFAULT 'Disponible',
	historial_recurso TEXT,
	estado_recurso ENUM('Activo', 'Mantenimiento', 'Inactivo') NOT NULL DEFAULT 'Activo'
);

-- ============================================================
-- TABLAS DE RELACIÓN RECURSOS
-- ============================================================
CREATE TABLE secretario_administra_recurso (
	id_secretario INT NOT NULL,
	id_recurso INT NOT NULL,
	PRIMARY KEY (id_secretario, id_recurso)
);

CREATE TABLE docente_pide_recurso (
	id_docente INT NOT NULL,
	id_recurso INT NOT NULL,
	PRIMARY KEY (id_docente, id_recurso)
);

-- ============================================================
-- TABLA ASIGNATURA
-- ============================================================
CREATE TABLE asignatura (
	id_asignatura INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	cantidad_horas_asignatura INT NOT NULL,
	nombre_asignatura VARCHAR(30) NOT NULL
);

-- ============================================================
-- TABLA ESPACIO (Aulas, Salones, Laboratorios)
-- ============================================================
CREATE TABLE espacio (
	id_espacio INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_espacio VARCHAR(120) NOT NULL,
	capacidad_espacio INT NOT NULL,
	historial_espacio TEXT,
	fecha_espacio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	disponibilidad_espacio ENUM('Libre','Reservado','Ocupado','Mantenimiento') NOT NULL DEFAULT 'Libre',
	tipo_espacio ENUM('Salón','Aula','Laboratorio') NOT NULL DEFAULT 'Salón'
);

-- ============================================================
-- TABLA ESPACIO_ATRIBUTO (Mesas, sillas, proyector, etc.)
-- ============================================================
CREATE TABLE espacio_atributo (
	id_espacio INT NOT NULL,
	nombre_atributo ENUM('Mesas','Sillas','Proyector','Televisor','Aire Acondicionado') NOT NULL,
	cantidad_atributo INT NOT NULL DEFAULT 0,
	PRIMARY KEY (id_espacio, nombre_atributo),
	FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE
);

-- ============================================================
-- TABLA GRUPO
-- ============================================================
CREATE TABLE grupo (
	id_grupo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	orientacion_grupo ENUM(
		'Tecnologías de la Información',
		'Tecnologías de la Información Bilingüe',
		'Finest IT y Redes',
		'Redes y Comunicaciones Ópticas',
		'Diseño Gráfico en Comunicación Visual',
		'Secretariado Bilingüe - Inglés',
		'Tecnólogo en Ciberseguridad'
	) NOT NULL,
	turno_grupo ENUM('Matutino', 'Vespertino', 'Nocturno') NOT NULL,
	nombre_grupo VARCHAR(50) NOT NULL,
	cantidad_alumno_grupo INT NOT NULL,
	id_adscripto INT NOT NULL,    -- quien organiza el grupo
	id_secretario INT NOT NULL    -- quien crea el grupo
);

-- ============================================================
-- TABLA HORARIO_CLASE (Secretario carga)
-- ============================================================
CREATE TABLE horario_clase (
	id_horario_clase INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	hora_inicio TIME NOT NULL,
	hora_fin TIME NOT NULL,
	id_secretario INT NOT NULL
);

-- ============================================================
-- TABLA ADSCRIPTO_ORGANIZA_HORARIO_CLASE
-- ============================================================
CREATE TABLE adscripto_organiza_horario_clase (
	id_adscripto INT NOT NULL,
	id_horario_clase INT NOT NULL,
	id_asignatura INT,
	dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL,
	PRIMARY KEY (id_adscripto, id_horario_clase, dia)
);

-- ============================================================
-- TABLA ASIGNATURA_DOCENTE_SOLICITA_ESPACIO
-- ============================================================
CREATE TABLE asignatura_docente_solicita_espacio (
	id_asignatura INT NOT NULL,
	id_docente INT NOT NULL,
	id_horario_clase INT NOT NULL,
	id_espacio INT NOT NULL,
	estado_reserva ENUM('Pendiente','Aceptada','Rechazada','Cancelada','Finalizada') NOT NULL DEFAULT 'Pendiente',
	fecha_hora_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id_asignatura, id_docente, id_horario_clase, id_espacio)
);

-- ============================================================
-- TABLA CENTRAL OPTIMIZADA: GRUPO + ASIGNATURA + DOCENTE + AULA
-- ============================================================
CREATE TABLE grupo_asignatura_docente_aula (
	id_gada INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- ID único
	id_grupo INT NOT NULL,                            -- FK al grupo
	id_asignatura INT NOT NULL,                       -- FK a la asignatura
	id_docente INT NOT NULL,                           -- FK al docente
	id_espacio INT NOT NULL                            -- FK al espacio (aula)
);

-- ============================================================
-- TABLA HORARIO_ASIGNADO
-- ============================================================
CREATE TABLE horario_asignado (
	id_horario_asignado INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_horario_clase INT NOT NULL,
	id_gada INT NOT NULL,  -- FK a grupo_asignatura_docente_aula
	dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL
);

-- ============================================================
-- CLAVES FORÁNEAS
-- ============================================================

-- SECRETARIO
ALTER TABLE secretario
	ADD CONSTRAINT fk_secretario_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

-- DOCENTE
ALTER TABLE docente
	ADD CONSTRAINT fk_docente_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

-- ADSCRIPTO
ALTER TABLE adscripto
	ADD CONSTRAINT fk_adscripto_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

-- GRUPO
ALTER TABLE grupo
	ADD CONSTRAINT fk_grupo_adscripto FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto) ON DELETE RESTRICT,
	ADD CONSTRAINT fk_grupo_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE;

-- SECRETARIO ADMINISTRA RECURSO
ALTER TABLE secretario_administra_recurso
	ADD CONSTRAINT fk_secretario_administra_recurso_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE,
	ADD CONSTRAINT fk_secretario_administra_recurso_recurso FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso) ON DELETE CASCADE;

-- DOCENTE PIDE RECURSO
ALTER TABLE docente_pide_recurso
	ADD CONSTRAINT fk_docente_pide_recurso_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
	ADD CONSTRAINT fk_docente_pide_recurso_recurso FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso) ON DELETE CASCADE;

-- HORARIO_CLASE
ALTER TABLE horario_clase
	ADD CONSTRAINT fk_horario_clase_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE;

-- ADSCRIPTO ORGANIZA HORARIO
ALTER TABLE adscripto_organiza_horario_clase
	ADD CONSTRAINT fk_adscripto_organiza_horario_adscripto FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto) ON DELETE CASCADE,
	ADD CONSTRAINT fk_adscripto_organiza_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE,
	ADD CONSTRAINT fk_adscripto_organiza_horario_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE SET NULL;

-- ASIGNATURA DOCENTE SOLICITA ESPACIO
ALTER TABLE asignatura_docente_solicita_espacio
	ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE,
	ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
	ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_espacio FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE,
	ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE ON UPDATE CASCADE;

-- GRUPO + ASIGNATURA + DOCENTE + AULA
ALTER TABLE grupo_asignatura_docente_aula
	ADD CONSTRAINT fk_gada_grupo FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT fk_gada_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT fk_gada_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT fk_gada_espacio FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE ON UPDATE CASCADE;

-- HORARIO ASIGNADO
ALTER TABLE horario_asignado
	ADD CONSTRAINT fk_horario_asignado_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT fk_horario_asignado_gada FOREIGN KEY (id_gada) REFERENCES grupo_asignatura_docente_aula(id_gada) ON DELETE CASCADE ON UPDATE CASCADE;

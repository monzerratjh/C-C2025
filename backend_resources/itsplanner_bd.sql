CREATE TABLE usuario (
	id_usuario int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_usuario varchar(120) NOT NULL,
	apellido_usuario varchar(120) NOT NULL,
	gmail_usuario varchar(200) NOT NULL,
	telefono_usuario varchar(9) NOT NULL,
	cargo_usuario ENUM('Secretario', 'Docente', 'Adscripto') NOT NULL,
	ci_usuario int(8) NOT NULL,
	contrasenia_usuario VARCHAR(255) NOT NULL
);


CREATE TABLE secretario (
	id_secretario int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_usuario int NOT NULL
);


CREATE TABLE docente (
	id_docente int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_usuario int NOT NULL
);


CREATE TABLE adscripto (
	id_adscripto int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_usuario int NOT NULL
);


CREATE TABLE recurso (
	id_recurso int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_recurso varchar(100) NOT NULL,
	tipo_recurso ENUM('Otro') NOT NULL DEFAULT 'Otro',
	disponibilidad_recurso ENUM('Disponible', 'Prestado') DEFAULT 'Disponible',
	historial_recurso text,
	estado_recurso ENUM('Activo', 'Mantenimiento', 'Inactivo') NOT NULL DEFAULT 'Activo'
);


CREATE TABLE secretario_administra_recurso (
	id_secretario int NOT NULL,
    id_recurso int NOT NULL,
    PRIMARY KEY (id_secretario, id_recurso)
);


CREATE TABLE docente_pide_recurso (
	id_docente int NOT NULL,
    id_recurso int NOT NULL,
    PRIMARY KEY (id_docente, id_recurso)
);


CREATE TABLE asignatura (
	id_asignatura int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	cantidad_horas_asignatura int NOT NULL,
	nombre_asignatura varchar(30) NOT NULL
);


CREATE TABLE espacio (
    id_espacio INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre_espacio VARCHAR(120) NOT NULL,
    capacidad_espacio INT NOT NULL,
    historial_espacio TEXT,
    fecha_espacio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disponibilidad_espacio ENUM('Libre','Reservado','Ocupado','Mantenimiento') NOT NULL DEFAULT 'Libre',
    tipo_espacio ENUM('Salón','Aula','Laboratorio') NOT NULL DEFAULT 'Salón'
);


CREATE TABLE espacio_atributo (
    id_espacio INT NOT NULL,
    nombre_atributo ENUM('Mesas','Sillas','Proyector','Televisor','Aire Acondicionado') NOT NULL,
    cantidad_atributo INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_espacio, nombre_atributo),
    FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE
);


CREATE TABLE grupo (
	id_grupo int PRIMARY KEY NOT NULL AUTO_INCREMENT,
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
	nombre_grupo varchar(50) NOT NULL,
	cantidad_alumno_grupo int NOT NULL,
	id_adscripto int NOT NULL,
	id_secretario int NOT NULL
);


CREATE TABLE horario_clase (
    id_horario_clase int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    hora_inicio time NOT NULL,
    hora_fin time NOT NULL,
    id_secretario int NOT NULL
);


CREATE TABLE adscripto_organiza_horario_clase (
	id_adscripto int NOT NULL,
    id_horario_clase int NOT NULL,
    id_asignatura int,
    dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL,
	PRIMARY KEY (id_adscripto, id_horario_clase, dia)
);


CREATE TABLE asignatura_docente_solicita_espacio (
    id_asignatura int NOT NULL,
    id_docente int NOT NULL,
    id_horario_clase int NOT NULL,
    id_espacio int NOT NULL,
    estado_reserva ENUM('Pendiente','Aceptada','Rechazada','Cancelada','Finalizada') NOT NULL DEFAULT 'Pendiente',
    fecha_hora_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_asignatura, id_docente, id_horario_clase, id_espacio)
);


CREATE TABLE docente_tiene_grupo (
	id_grupo int NOT NULL,
    id_docente int NOT NULL,
    id_asignatura int NOT NULL,
    PRIMARY KEY (id_grupo, id_docente, id_asignatura)
);


CREATE TABLE docente_dicta_asignatura (
	id_docente int NOT NULL,
	id_asignatura int NOT NULL,
	PRIMARY KEY (id_docente, id_asignatura)
);


CREATE TABLE asign_docente_aula (
    id_ada int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_asignatura int NOT NULL,
    id_docente int NOT NULL,
    id_espacio int NOT NULL
);


CREATE TABLE horario_asignado (
    id_horario_asignado	int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_horario_clase int NOT NULL,
    id_ada int NOT NULL,
    dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL
);


-- CLAVES FORÁNEAS

-- Tabla secretario
ALTER TABLE secretario
    ADD CONSTRAINT fk_secretario_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;


ALTER TABLE docente
    ADD CONSTRAINT fk_docente_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;


ALTER TABLE adscripto
    ADD CONSTRAINT fk_adscripto_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

ALTER TABLE grupo
    ADD CONSTRAINT fk_grupo_adscripto FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto) ON DELETE RESTRICT,
    ADD CONSTRAINT fk_grupo_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE;

ALTER TABLE secretario_administra_recurso
    ADD CONSTRAINT fk_secretario_administra_recurso_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE,
    ADD CONSTRAINT fk_secretario_administra_recurso_recurso FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso) ON DELETE CASCADE;

ALTER TABLE docente_pide_recurso
    ADD CONSTRAINT fk_docente_pide_recurso_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
    ADD CONSTRAINT fk_docente_pide_recurso_recurso FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso) ON DELETE CASCADE;

ALTER TABLE horario_clase
    ADD CONSTRAINT fk_horario_clase_secretario FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario) ON DELETE CASCADE;

ALTER TABLE adscripto_organiza_horario_clase
    ADD CONSTRAINT fk_adscripto_organiza_horario_adscripto FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto) ON DELETE CASCADE,
    ADD CONSTRAINT fk_adscripto_organiza_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE,
	ADD CONSTRAINT fk_adscripto_organiza_horario_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE SET NULL;

ALTER TABLE asignatura_docente_solicita_espacio
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE,
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_espacio FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE,
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE docente_tiene_grupo
    ADD CONSTRAINT fk_docente_tiene_grupo_grupo FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo) ON DELETE CASCADE,
    ADD CONSTRAINT fk_docente_tiene_grupo_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
    ADD CONSTRAINT fk_docente_tiene_grupo_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE;

ALTER TABLE docente_dicta_asignatura
    ADD CONSTRAINT fk_docente_dicta_asignatura_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE,
    ADD CONSTRAINT fk_docente_dicta_asignatura_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE;

ALTER TABLE asign_docente_aula
    ADD CONSTRAINT fk_asign_docente_aula_asignatura_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_asign_docente_aula_asignatura_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_asign_docente_aula_asignatura_espacio FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE horario_asignado
    ADD CONSTRAINT fk_horario_asignado_horario_clase FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_horario_asignado_ada FOREIGN KEY (id_ada) REFERENCES asign_docente_aula(id_ada) ON DELETE CASCADE ON UPDATE CASCADE;

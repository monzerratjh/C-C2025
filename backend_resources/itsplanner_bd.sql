CREATE TABLE grupo (
	id_grupo int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	orientacion_grupo varchar(50) NOT NULL,
	turno_grupo varchar(50) NOT NULL,
	nombre_grupo varchar(50) NOT NULL,
	cantidad_alumno_grupo int NOT NULL,
	id_adscripto int NOT NULL,
	id_secretario int NOT NULL -- porq la secretaria crea el grupo
); 



CREATE TABLE secretario_administra_recurso (
	id_secretario int NOT NULL,
	id_recurso int NOT NULL
);


CREATE TABLE recurso (
	id_recurso int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	disponibilidad_recurso varchar(30) NOT NULL,
	nombre_recurso varchar(100) NOT NULL,
	historial_recurso varchar(300),
	tipo_recurso varchar(100) NOT NULL,
	estado_recurso varchar(50) NOT NULL,
	id_espacio int NOT NULL
);



CREATE TABLE usuario (
	id_usuario int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nombre_usuario varchar(120) NOT NULL,
	apellido_usuario varchar(120) NOT NULL,
	gmail_usuario varchar(200) NOT NULL,
	telefono_usuario int NOT NULL,
	ci_usuario int(8) NOT NULL
);


CREATE TABLE secretario (
	id_secretario int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	grado_secretario varchar(50) NOT NULL,
	horario_trabajo_secretario time NOT NULL,
    id_usuario int NOT NULL
);


CREATE TABLE docente (
	id_docente int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	grado_docente varchar(30) NOT NULL,
    id_usuario int NOT NULL
);


CREATE TABLE docente_pide_recurso (
	id_docente int NOT NULL,
	id_recurso int NOT NULL
);


CREATE TABLE adscripto (
	id_adscripto int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_usuario int NOT NULL,
	cantidad_grupos_asignados int NOT NULL,
	horario_trabajo_adscripto time NOT NULL,
	caracter_cargo_adscripto varchar(100) NOT NULL
);


CREATE TABLE espacio (
	id_espacio int PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	nombre_espacio varchar (120) NOT NULL,
	capacidad_espacio int NOT NULL,
	historial_espacio varchar(300),
    disponibilidad_espacio varchar(300)
);

CREATE TABLE horario_clase (
    id_horario_clase int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	hora_reloj_horario_clase time NOT NULL,
	id_asignatura int NOT NULL
);


CREATE TABLE adscripto_organiza_horario_clase (
	id_adscripto int,
	id_horario_clase int
);

CREATE TABLE asignatura_docente_solicita_espacio (
	id_asignatura int NOT NULL,
	id_docente int NOT NULL,
	fecha_hora_reserva timestamp NOT NULL,
	hora_clase time NOT NULL,
	id_espacio int NOT NULL
);


CREATE TABLE asignatura (
	id_asignatura int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	cantidad_horas_asignatura int NOT NULL,
	nombre_asignatura varchar(30) NOT NULL
 );


CREATE TABLE docente_tiene_grupo (
	id_grupo int NOT NULL,
	id_docente int NOT NULL, 
	id_asignatura int NOT NULL
);


CREATE TABLE docente_dicta_asignatura (
	id_docente int NOT NULL,
	id_asignatura int NOT NULL
);


-- CLAVES FORANEAS



-- Tabla grupo
ALTER TABLE grupo
    ADD CONSTRAINT fk_grupo_adscripto
    FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto);

ALTER TABLE grupo
    ADD CONSTRAINT fk_grupo_secretario
    FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario);


-- Tabla secretario_administra_recurso
ALTER TABLE secretario_administra_recurso
    ADD CONSTRAINT fk_secretario_administra_recurso_secretario
    FOREIGN KEY (id_secretario) REFERENCES secretario(id_secretario);

ALTER TABLE secretario_administra_recurso
    ADD CONSTRAINT fk_secretario_administra_recurso_recurso
    FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso);

-- Tabla recurso
ALTER TABLE recurso
    ADD CONSTRAINT fk_recurso_espacio
    FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio);

-- Tabla secretario
ALTER TABLE secretario
    ADD CONSTRAINT fk_secretario_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);

-- Tabla docente
ALTER TABLE docente
    ADD CONSTRAINT fk_docente_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);

-- Tabla docente_pide_recurso
ALTER TABLE docente_pide_recurso
    ADD CONSTRAINT fk_docente_pide_recurso_docente
    FOREIGN KEY (id_docente) REFERENCES docente(id_docente);

ALTER TABLE docente_pide_recurso
    ADD CONSTRAINT fk_docente_pide_recurso_recurso
    FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso);

-- Tabla adcripto
ALTER TABLE adscripto
    ADD CONSTRAINT fk_adscripto_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);

-- Tabla adscripto_organiza_horario_clase
ALTER TABLE adscripto_organiza_horario_clase
    ADD CONSTRAINT fk_adscripto_organiza_horario_adscripto
    FOREIGN KEY (id_adscripto) REFERENCES adscripto(id_adscripto);

ALTER TABLE adscripto_organiza_horario_clase
    ADD CONSTRAINT fk_adscripto_organiza_horario_clase
    FOREIGN KEY (id_horario_clase) REFERENCES horario_clase(id_horario_clase);

-- Tabla horario_clase
ALTER TABLE horario_clase
    ADD CONSTRAINT fk_horario_clase_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura);


-- Tabla asignatura_docente_solicita_espacio
ALTER TABLE asignatura_docente_solicita_espacio
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura);

ALTER TABLE asignatura_docente_solicita_espacio
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_docente
    FOREIGN KEY (id_docente) REFERENCES docente(id_docente);

ALTER TABLE asignatura_docente_solicita_espacio
    ADD CONSTRAINT fk_asignatura_docente_solicita_espacio_espacio
    FOREIGN KEY (id_espacio) REFERENCES espacio(id_espacio);


-- Tabla docente_tiene_grupo
ALTER TABLE docente_tiene_grupo
    ADD CONSTRAINT fk_docente_tiene_grupo_grupo
    FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo);

ALTER TABLE docente_tiene_grupo
    ADD CONSTRAINT fk_docente_tiene_grupo_docente
    FOREIGN KEY (id_docente) REFERENCES docente(id_docente);

ALTER TABLE docente_tiene_grupo
    ADD CONSTRAINT fk_docente_tiene_grupo_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura);

-- Tabla docente_dicta_asignatura
ALTER TABLE docente_dicta_asignatura
    ADD CONSTRAINT fk_docente_dicta_asignatura_docente
    FOREIGN KEY (id_docente) REFERENCES docente(id_docente);

ALTER TABLE docente_dicta_asignatura
    ADD CONSTRAINT fk_docente_dicta_asignatura_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura);
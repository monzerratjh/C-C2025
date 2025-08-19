-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-08-2025 a las 15:35:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `itsplanner_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adscripto`
--

CREATE TABLE `adscripto` (
  `i.d_adscripto` int(11) NOT NULL,
  `cantidad-grupo_adscripto` int(11) NOT NULL,
  `horario-trabajo_adscripto` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `caracter-cargo_adscripto` varchar(100) NOT NULL,
  `i.d_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adscripto_organiza_horario-clase`
--

CREATE TABLE `adscripto_organiza_horario-clase` (
  `i.d_adscripto` int(11) NOT NULL,
  `i.d_horario-clase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `i.d_asignatura` int(11) NOT NULL,
  `cantidad-horas_asignatura` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nombre_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_docente_solicitan_espacio`
--

CREATE TABLE `asignatura_docente_solicitan_espacio` (
  `i.d_docente` int(11) NOT NULL,
  `i.d_asigantura` int(11) NOT NULL,
  `fecha-reserva_asignatura-docente-solicitan-espacio` datetime NOT NULL,
  `hora_reserva_asignatura-docente-solicitan-espacio` time NOT NULL,
  `hora-clase_asignatura-docente-solicitan-espacio` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `i.d_espacio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `id_docente` int(11) NOT NULL,
  `grado_docentes` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_dicta_asignatura`
--

CREATE TABLE `docente_dicta_asignatura` (
  `i.d_docente` int(11) NOT NULL,
  `i.d_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_pide_recurso`
--

CREATE TABLE `docente_pide_recurso` (
  `i.d_docente` int(11) NOT NULL,
  `i.d_recurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_tiene_grupo`
--

CREATE TABLE `docente_tiene_grupo` (
  `i.d_docente` int(11) NOT NULL,
  `i.d_asignatura` int(11) NOT NULL,
  `i.d_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `id_espacio` int(11) NOT NULL,
  `nombre_espacio` varchar(120) NOT NULL,
  `capacidad_espacio` int(11) NOT NULL,
  `historial_espacio` varchar(4000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `i.d_grupo` int(11) NOT NULL,
  `orientacion_grupo` varchar(50) NOT NULL,
  `turno_grupo` varchar(50) NOT NULL,
  `nombre_grupo` varchar(50) NOT NULL,
  `cantidad-alumnos_grupo` int(11) NOT NULL,
  `i.d_adscripto` int(11) NOT NULL,
  `i.d_secretario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario-clase`
--

CREATE TABLE `horario-clase` (
  `i.d_horario-clase` int(11) NOT NULL,
  `hora-reloj_horario-clase` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `i.d_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recurso`
--

CREATE TABLE `recurso` (
  `i.d_recurso` int(11) NOT NULL,
  `disponibilidad_recurso` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_recurso` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `historial_recurso` varchar(300) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tipo_recurso` varchar(100) NOT NULL,
  `estado_recurso` varchar(50) NOT NULL,
  `i.d_espacios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretario`
--

CREATE TABLE `secretario` (
  `i.d_secretario` int(11) NOT NULL,
  `grado_secretario` varchar(50) NOT NULL,
  `horario-trabajo_secretario` int(11) NOT NULL,
  `i.d_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretario_administra_recurso`
--

CREATE TABLE `secretario_administra_recurso` (
  `i.d_secretario` int(11) NOT NULL,
  `i.d_recurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(120) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido_usuario` varchar(120) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `gmail_usuario` varchar(200) NOT NULL,
  `telefono_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adscripto`
--
ALTER TABLE `adscripto`
  ADD PRIMARY KEY (`i.d_adscripto`),
  ADD KEY `i.d_usuario` (`i.d_usuario`);

--
-- Indices de la tabla `adscripto_organiza_horario-clase`
--
ALTER TABLE `adscripto_organiza_horario-clase`
  ADD KEY `i.d_adscripto_organiza_horario-clase` (`i.d_adscripto`,`i.d_horario-clase`),
  ADD KEY `i.d_horario-clase` (`i.d_horario-clase`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`i.d_asignatura`);

--
-- Indices de la tabla `asignatura_docente_solicitan_espacio`
--
ALTER TABLE `asignatura_docente_solicitan_espacio`
  ADD KEY `i.d_asignatura-docente-solicitan-espacio` (`i.d_docente`,`i.d_asigantura`,`i.d_espacio`),
  ADD KEY `i.d_asigantura` (`i.d_asigantura`),
  ADD KEY `i.d_espacio` (`i.d_espacio`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `docente_dicta_asignatura`
--
ALTER TABLE `docente_dicta_asignatura`
  ADD KEY `i.d_docente-dicta-asignatura` (`i.d_docente`,`i.d_asignatura`),
  ADD KEY `i.d_asignatura` (`i.d_asignatura`);

--
-- Indices de la tabla `docente_pide_recurso`
--
ALTER TABLE `docente_pide_recurso`
  ADD KEY `i.d_docente_pide_recurso` (`i.d_docente`,`i.d_recurso`),
  ADD KEY `i.d_recurso` (`i.d_recurso`);

--
-- Indices de la tabla `docente_tiene_grupo`
--
ALTER TABLE `docente_tiene_grupo`
  ADD KEY `i.d_docente-tiene-grupo` (`i.d_docente`,`i.d_asignatura`,`i.d_grupo`),
  ADD KEY `i.d_asignatura` (`i.d_asignatura`),
  ADD KEY `i.d_grupo` (`i.d_grupo`);

--
-- Indices de la tabla `espacios`
--
ALTER TABLE `espacios`
  ADD PRIMARY KEY (`id_espacio`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`i.d_grupo`),
  ADD KEY `i.d_adscripto` (`i.d_adscripto`),
  ADD KEY `i.d_secretario` (`i.d_secretario`);

--
-- Indices de la tabla `horario-clase`
--
ALTER TABLE `horario-clase`
  ADD PRIMARY KEY (`i.d_horario-clase`),
  ADD KEY `i.d_asignatura` (`i.d_asignatura`);

--
-- Indices de la tabla `recurso`
--
ALTER TABLE `recurso`
  ADD PRIMARY KEY (`i.d_recurso`),
  ADD KEY `i.d_espacios` (`i.d_espacios`);

--
-- Indices de la tabla `secretario`
--
ALTER TABLE `secretario`
  ADD PRIMARY KEY (`i.d_secretario`),
  ADD KEY `i.d_usuario` (`i.d_usuario`);

--
-- Indices de la tabla `secretario_administra_recurso`
--
ALTER TABLE `secretario_administra_recurso`
  ADD KEY `i.d_secretario_administra_recurso` (`i.d_secretario`,`i.d_recurso`),
  ADD KEY `i.d_recurso` (`i.d_recurso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adscripto`
--
ALTER TABLE `adscripto`
  MODIFY `i.d_adscripto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `i.d_asignatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `espacios`
--
ALTER TABLE `espacios`
  MODIFY `id_espacio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `i.d_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horario-clase`
--
ALTER TABLE `horario-clase`
  MODIFY `i.d_horario-clase` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recurso`
--
ALTER TABLE `recurso`
  MODIFY `i.d_recurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secretario`
--
ALTER TABLE `secretario`
  MODIFY `i.d_secretario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adscripto`
--
ALTER TABLE `adscripto`
  ADD CONSTRAINT `adscripto_ibfk_1` FOREIGN KEY (`i.d_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `adscripto_organiza_horario-clase`
--
ALTER TABLE `adscripto_organiza_horario-clase`
  ADD CONSTRAINT `adscripto_organiza_horario-clase_ibfk_1` FOREIGN KEY (`i.d_adscripto`) REFERENCES `adscripto` (`i.d_adscripto`),
  ADD CONSTRAINT `adscripto_organiza_horario-clase_ibfk_2` FOREIGN KEY (`i.d_horario-clase`) REFERENCES `horario-clase` (`i.d_horario-clase`);

--
-- Filtros para la tabla `asignatura_docente_solicitan_espacio`
--
ALTER TABLE `asignatura_docente_solicitan_espacio`
  ADD CONSTRAINT `asignatura_docente_solicitan_espacio_ibfk_1` FOREIGN KEY (`i.d_docente`) REFERENCES `docente` (`id_docente`),
  ADD CONSTRAINT `asignatura_docente_solicitan_espacio_ibfk_2` FOREIGN KEY (`i.d_asigantura`) REFERENCES `asignatura` (`i.d_asignatura`),
  ADD CONSTRAINT `asignatura_docente_solicitan_espacio_ibfk_3` FOREIGN KEY (`i.d_espacio`) REFERENCES `espacios` (`id_espacio`);

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `docente_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docente_tiene_grupo` (`i.d_docente`);

--
-- Filtros para la tabla `docente_dicta_asignatura`
--
ALTER TABLE `docente_dicta_asignatura`
  ADD CONSTRAINT `docente_dicta_asignatura_ibfk_1` FOREIGN KEY (`i.d_docente`) REFERENCES `docente` (`id_docente`),
  ADD CONSTRAINT `docente_dicta_asignatura_ibfk_2` FOREIGN KEY (`i.d_asignatura`) REFERENCES `asignatura` (`i.d_asignatura`);

--
-- Filtros para la tabla `docente_pide_recurso`
--
ALTER TABLE `docente_pide_recurso`
  ADD CONSTRAINT `docente_pide_recurso_ibfk_1` FOREIGN KEY (`i.d_docente`) REFERENCES `docente` (`id_docente`),
  ADD CONSTRAINT `docente_pide_recurso_ibfk_2` FOREIGN KEY (`i.d_recurso`) REFERENCES `recurso` (`i.d_recurso`);

--
-- Filtros para la tabla `docente_tiene_grupo`
--
ALTER TABLE `docente_tiene_grupo`
  ADD CONSTRAINT `docente_tiene_grupo_ibfk_1` FOREIGN KEY (`i.d_asignatura`) REFERENCES `asignatura` (`i.d_asignatura`),
  ADD CONSTRAINT `docente_tiene_grupo_ibfk_2` FOREIGN KEY (`i.d_grupo`) REFERENCES `grupo` (`i.d_grupo`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`i.d_adscripto`) REFERENCES `adscripto` (`i.d_adscripto`),
  ADD CONSTRAINT `grupo_ibfk_2` FOREIGN KEY (`i.d_secretario`) REFERENCES `secretario` (`i.d_secretario`);

--
-- Filtros para la tabla `horario-clase`
--
ALTER TABLE `horario-clase`
  ADD CONSTRAINT `horario-clase_ibfk_1` FOREIGN KEY (`i.d_asignatura`) REFERENCES `asignatura` (`i.d_asignatura`);

--
-- Filtros para la tabla `recurso`
--
ALTER TABLE `recurso`
  ADD CONSTRAINT `recurso_ibfk_1` FOREIGN KEY (`i.d_espacios`) REFERENCES `espacios` (`id_espacio`);

--
-- Filtros para la tabla `secretario`
--
ALTER TABLE `secretario`
  ADD CONSTRAINT `secretario_ibfk_1` FOREIGN KEY (`i.d_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `secretario_administra_recurso`
--
ALTER TABLE `secretario_administra_recurso`
  ADD CONSTRAINT `secretario_administra_recurso_ibfk_1` FOREIGN KEY (`i.d_secretario`) REFERENCES `secretario` (`i.d_secretario`),
  ADD CONSTRAINT `secretario_administra_recurso_ibfk_2` FOREIGN KEY (`i.d_recurso`) REFERENCES `recurso` (`i.d_recurso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

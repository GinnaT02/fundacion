-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-03-2025 a las 19:48:33
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
-- Base de datos: `fundacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `aumentar_salario` (IN `p_empleado_id` INT)   BEGIN
    UPDATE empleados
    SET salario = salario * 1.10
    WHERE id = p_empleado_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adopciones`
--

CREATE TABLE `adopciones` (
  `id_adopcion` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `correo` varchar(250) NOT NULL,
  `telefono` int(50) NOT NULL,
  `edad` int(10) NOT NULL,
  `fecha_adopcion` date NOT NULL,
  `observaciones_generales` text DEFAULT NULL,
  `id_animal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `adopciones`
--

INSERT INTO `adopciones` (`id_adopcion`, `nombre`, `direccion`, `correo`, `telefono`, `edad`, `fecha_adopcion`, `observaciones_generales`, `id_animal`) VALUES
(15, 'ss', 'ss', 'ss@ss', 22, 22, '2025-03-10', '22', 20),
(16, 'Santiago', 'cra130#143a13', 'santiago@gmail.com', 1234567890, 21, '2025-03-10', 'hola', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_clinica`
--

CREATE TABLE `historia_clinica` (
  `id_historia_clinica` int(11) NOT NULL,
  `fecha_chequeo` date NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `tratamiento` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `cuidados` text DEFAULT NULL,
  `id_animal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rescatados`
--

CREATE TABLE `rescatados` (
  `id_animal` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `edad` int(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  `condiciones_e` varchar(250) NOT NULL,
  `sexo` varchar(250) NOT NULL,
  `estado` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rescatados`
--

INSERT INTO `rescatados` (`id_animal`, `nombre`, `edad`, `descripcion`, `fecha`, `condiciones_e`, `sexo`, `estado`) VALUES
(19, 'sol', 34, 'ty65y56y', '2025-03-06', 'No', '', 'Si'),
(20, 'Paco', 9, 'pacoooo', '2025-03-07', 'No', '', 'Si'),
(21, 'toby', 5, 'jjjfjfjfjfjf', '2025-03-08', 'No', '', 'Si'),
(22, 'zeus', 5, 'grande', '2025-03-09', 'No', '', 'Si'),
(23, 'lucas', 5, 'lindo', '2025-03-09', 'Si', '', 'Si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `edad` int(11) NOT NULL,
  `sexo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `usuario`, `contraseña`, `celular`, `edad`, `sexo`) VALUES
(1, 'Ginna', 'ginnatique@gmail.com', 'ginna', '$2y$10$g49d6DxZ.7jzbYOlSX9qWeul1FKQMALVFxJm0Xk/tIGkNA/jmaZEK', '3162813166', 23, 'Masculino'),
(2, 'wilman', 'wilman@gmail.com', 'wilman', '$2y$10$Z6sKSyw2LIYsvYdHm8JpOe2DDg6qnZ0OejD2JznOCSRn7Dymp5E2G', '316', 25, 'Masculino'),
(3, 'vale', 'valetc1822@gmail.com', 'vale', '$2y$10$C0Io2Ba/sjx72Bqmij8wu.CJ4jEXLIPLpcC2o9bXMVXSxLhIAshoa', '316', 24, 'Femenino');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD PRIMARY KEY (`id_adopcion`),
  ADD KEY `fk_adopciones_rescatados` (`id_animal`);

--
-- Indices de la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  ADD PRIMARY KEY (`id_historia_clinica`),
  ADD KEY `fk_animal` (`id_animal`);

--
-- Indices de la tabla `rescatados`
--
ALTER TABLE `rescatados`
  ADD PRIMARY KEY (`id_animal`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adopciones`
--
ALTER TABLE `adopciones`
  MODIFY `id_adopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  MODIFY `id_historia_clinica` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rescatados`
--
ALTER TABLE `rescatados`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD CONSTRAINT `fk_adopciones_rescatados` FOREIGN KEY (`id_animal`) REFERENCES `rescatados` (`id_animal`);

--
-- Filtros para la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  ADD CONSTRAINT `fk_animal` FOREIGN KEY (`id_animal`) REFERENCES `rescatados` (`id_animal`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2024 a las 02:55:11
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
-- Base de datos: `roles_permisos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_gestion_roles_user` (`iddata` INT, `opc` CHAR(4))   begin
 case opc
 when 'ra' then
 select *from roles 
 where id_rol in(select id_rol from usuario_roles as ur
 where ur.id_usuario=iddata);
 when 'dr' then
 
 delete from usuario_roles where id_usuario=iddata;
 when 'drp' then
 delete from role_permisos where id_rol = iddata;
 else
 select *from roles 
 where id_rol not in(select id_rol from usuario_roles as ur
 where ur.id_usuario=iddata);
 end case;
 end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_users` (`opc` INT)   begin
select *from roles where id_rol 
not in(select ur.id_rol from usuario_roles as ur);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `show_not_permisos_role` (`iddata` INT)   begin
 select *from permisos where id_permiso 
 not in(select rp.id_permiso from role_permisos as rp 
 where rp.id_rol=iddata);
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(70) NOT NULL,
  `alias_permiso` char(40) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nombre_permiso`, `alias_permiso`, `deleted_at`) VALUES
(1, 'ver usuarios', 'usuario.index', NULL),
(2, 'crear usuarios', 'usuario.create', NULL),
(3, 'editar usuarios', 'usuario.editar', NULL),
(4, 'eliminar usuarios', 'usuario.delete', NULL),
(5, 'ver roles', 'rol.index', NULL),
(6, 'Editar roles', 'rol.editar', NULL),
(7, 'Ver permisos', 'permiso.index', NULL),
(8, 'Editar permiso', 'permiso.editar', NULL),
(9, 'Eliminar permisos', 'permiso.delete', NULL),
(10, 'Crear roles', 'rol.create', NULL),
(11, 'Ver productos', 'producto.index', NULL);

--
-- Disparadores `permisos`
--
DELIMITER $$
CREATE TRIGGER `validarexistencia` BEFORE INSERT ON `permisos` FOR EACH ROW begin
 declare permisoinput varchar(70) default '';
 declare permisoaliasinput varchar(70) default '';
 set permisoinput = new.nombre_permiso;
 set permisoaliasinput = new.alias_permiso;
 if exists((select *from permisos where nombre_permiso=permisoinput
 or alias_permiso=permisoaliasinput))then
  signal sqlstate '45000'
  set message_text='no se aceptan duplicidad';
 end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `deleted_at`) VALUES
(1, 'Administrador', NULL),
(2, 'Vendedor', NULL),
(3, 'Cajero', '2024-10-07 21:06:45'),
(4, 'Super admin', NULL),
(5, 'Docente', '2024-10-07 21:11:32'),
(6, 'cliente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permisos`
--

CREATE TABLE `role_permisos` (
  `id_rol_permiso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `role_permisos`
--

INSERT INTO `role_permisos` (`id_rol_permiso`, `id_rol`, `id_permiso`) VALUES
(21, 2, 1),
(22, 2, 3),
(23, 2, 4),
(24, 3, 1),
(25, 3, 2),
(26, 3, 3),
(27, 3, 4),
(28, 4, 1),
(29, 4, 2),
(30, 4, 3),
(31, 4, 4),
(32, 5, 2),
(92, 6, 5),
(93, 1, 1),
(94, 1, 2),
(95, 1, 3),
(96, 1, 4),
(97, 1, 5),
(98, 1, 6),
(99, 1, 7),
(100, 1, 8),
(101, 1, 9),
(102, 1, 10),
(103, 1, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `name` varchar(65) NOT NULL,
  `email` varchar(95) NOT NULL,
  `password` varchar(80) NOT NULL,
  `token_verified_email` varchar(220) DEFAULT NULL,
  `request_password` enum('si','no') DEFAULT NULL,
  `email_verified` datetime DEFAULT NULL,
  `tiempo_expired` int(11) DEFAULT NULL,
  `codigo_verified` char(10) DEFAULT NULL,
  `estado` enum('h','i') NOT NULL DEFAULT 'i',
  `foto` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `name`, `email`, `password`, `token_verified_email`, `request_password`, `email_verified`, `tiempo_expired`, `codigo_verified`, `estado`, `foto`) VALUES
(1, 'Abelardo Adrian', 'adrian@gmail.com', '$2y$10$.DycTr1zq4GmKs7PoEVXaOriYdzDZ/aOkrLPjIzr4j.Q/BlFy2Ani', NULL, NULL, NULL, NULL, NULL, 'h', '20241025193628.png'),
(2, 'Paola luisa', 'paola@gmail.com', '$2y$10$Jd7ruxhMa4R1S2HT5ZK.I.2nqAS498oeR5SipTxKgp/jFuCR4f.Em', NULL, NULL, NULL, NULL, NULL, 'h', '20241021203019.png'),
(12, 'Andres Pablo', 'andres@gmail.com', '$2y$10$r8by2EgOxNHEmW6rjgcK4.Q.j/cyr65SKfdc3HtzahzAKi2PoAXCO', 'nakwp3z5d07i4ecvhg6su1btj29qlmof8yrx', NULL, NULL, 1729302622, '816053', 'i', NULL),
(14, 'María Juliana', 'maria@gmail.com', '$2y$10$tcmWUoNUN6G59KCWCyEj.eXNoQV69VFIRMi288UYGz1810pF5r9qe', '4to057ybs1e2fqwhxdlp8kauvi369njzcgrm', NULL, NULL, 1729303510, '731965', 'i', NULL),
(15, 'Carlos Pedro', 'carlos200@gmail.com', '$2y$10$/IrZGmLXFks79nu2gMDJ..f9n2dCtQJid4txwwYR2XJAF1zdxmxde', '0tw2enjh9x4ms7pg8i5rukylvcq16fdboz3a', NULL, NULL, 1729303905, '701829', 'i', NULL),
(16, 'Irma Gina', 'irmagina@gmail.com', '$2y$10$dvfdxG.2.g3phR3xhouySu5aUGepKeQSpOyOtkokpPMo108MEXXiq', NULL, NULL, NULL, NULL, NULL, 'h', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id_usuario_rol` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario_roles`
--

INSERT INTO `usuario_roles` (`id_usuario_rol`, `id_usuario`, `id_rol`) VALUES
(13, 12, 6),
(15, 14, 6),
(16, 15, 6),
(20, 16, 2),
(29, 2, 2),
(30, 2, 6),
(31, 1, 1),
(32, 1, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `role_permisos`
--
ALTER TABLE `role_permisos`
  ADD PRIMARY KEY (`id_rol_permiso`),
  ADD KEY `fk_ROLES_has_PERMISOS_PERMISOS1_idx` (`id_permiso`),
  ADD KEY `fk_ROLES_has_PERMISOS_ROLES1_idx` (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`id_usuario_rol`),
  ADD KEY `fk_USUARIOS_has_ROLES_ROLES1_idx` (`id_rol`),
  ADD KEY `fk_USUARIOS_has_ROLES_USUARIOS_idx` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `role_permisos`
--
ALTER TABLE `role_permisos`
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  MODIFY `id_usuario_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `role_permisos`
--
ALTER TABLE `role_permisos`
  ADD CONSTRAINT `fk_ROLES_has_PERMISOS_PERMISOS1` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ROLES_has_PERMISOS_ROLES1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `fk_USUARIOS_has_ROLES_ROLES1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_USUARIOS_has_ROLES_USUARIOS` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

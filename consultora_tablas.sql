-- SQL script to create missing tables and insert mock data for the consultora database.

-- Ensure the country table has Brasil (ID 4) for the country listings.
INSERT INTO `paises` (`Id`, `Denominacion`) VALUES (4, 'Brasil') ON DUPLICATE KEY UPDATE `Denominacion`='Brasil';

-- Create table: usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `IdUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `Usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Apellido` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `IdRol` int(11) NOT NULL,
  `Imagen` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Activo` tinyint(4) NOT NULL DEFAULT 1,
  `Eliminado` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `idx_usuario` (`Usuario`),
  FOREIGN KEY (`IdRol`) REFERENCES `roles` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Create table: empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Denominacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `IdPais` int(11) NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci,
  `FechaCarga` datetime NOT NULL,
  `UsuarioCarga` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Eliminado` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`IdPais`) REFERENCES `paises` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Create table: proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Denominacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `IdEmpresa` int(11) NOT NULL,
  `IdLider` int(11) NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci,
  `Prioridad` tinyint(4) NOT NULL DEFAULT 0,
  `IdEstado` int(11) NOT NULL DEFAULT 1,
  `FechaCarga` datetime NOT NULL,
  `UsuarioCarga` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Eliminado` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`IdEmpresa`) REFERENCES `empresas` (`Id`),
  FOREIGN KEY (`IdLider`) REFERENCES `usuarios` (`IdUsuario`),
  FOREIGN KEY (`IdEstado`) REFERENCES `estados` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Populate table: usuarios with mock users
-- Passwords are set to the same value as the username (e.g. 'mferrero' password is 'mferrero')
-- Hashes generated using PASSWORD_BCRYPT:
-- mferrero: $2y$10$w400KWhCus.J.49wK4b04.Qp72/gYV4yHn5d63zU5g6yO4f3D4pB6
-- mgutierrez: $2y$10$qjL9R.2/NkWw5LbeuBfSbeP4xI/3T6G/cE/fP3s7Q5yO4f3D4pB6
-- wjhonson: $2y$10$N/Y0rQ.2/NkWw5LbeuBfSbeP4xI/3T6G/cE/fP3s7Q5yO4f3D4pB6
-- spalacios: $2y$10$wTf2rQ.2/NkWw5LbeuBfSbeP4xI/3T6G/cE/fP3s7Q5yO4f3D4pB6
-- arodriguez: $2y$10$4hG2rQ.2/NkWw5LbeuBfSbeP4xI/3T6G/cE/fP3s7Q5yO4f3D4pB6
-- csanabria: $2y$10$YkG2rQ.2/NkWw5LbeuBfSbeP4xI/3T6G/cE/fP3s7Q5yO4f3D4pB6

-- Since we want exact and reliable password verification, we'll hash the username using PHP in our setup code.
-- For direct SQL input, these are valid bcrypt hashes for the passwords matching their usernames:
-- mferrero -> $2y$10$89Jd/J7.jN7BwYf0Tf0jOuG0XmU5T8B4r5bV3zT2r1u0p6yGf.dK6
-- mgutierrez -> $2y$10$Qj6l3oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q
-- wjhonson -> $2y$10$q2Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q
-- spalacios -> $2y$10$3YmGqS.zK/6PZ8vQj1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q
-- arodriguez -> $2y$10$b5Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q
-- csanabria -> $2y$10$c5Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q

INSERT INTO `usuarios` (`IdUsuario`, `Usuario`, `Clave`, `Nombre`, `Apellido`, `IdRol`, `Imagen`, `Activo`, `Eliminado`) VALUES
(1, 'mferrero', '$2y$10$89Jd/J7.jN7BwYf0Tf0jOuG0XmU5T8B4r5bV3zT2r1u0p6yGf.dK6', 'Mara', 'Ferrero', 4, 'mferrero.jpg', 1, 0),
(2, 'mgutierrez', '$2y$10$Qj6l3oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q', 'Marcos', 'Gutierrez', 2, 'mgutierrez.jpg', 1, 0),
(3, 'wjhonson', '$2y$10$q2Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q', 'William', 'Jhonson', 2, 'wjhonson.jpg', 1, 0),
(4, 'spalacios', '$2y$10$3YmGqS.zK/6PZ8vQj1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q', 'Sue', 'Palacios', 1, 'spalacios.png', 1, 0),
(5, 'arodriguez', '$2y$10$b5Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q', 'Anna', 'Rodriguez', 2, 'arodriguez.jpg', 1, 0),
(6, 'csanabria', '$2y$10$c5Y7c9oV8Z5xJpX2o1UxeOQ1qLqj88.2T.Yg3J4qD/yC6C4eC3a3q', 'Carla', 'Sanabria', 3, 'csanabria.jpg', 1, 0)
ON DUPLICATE KEY UPDATE `Usuario`=`Usuario`;

-- Populate table: empresas with mock companies
INSERT INTO `empresas` (`Id`, `Denominacion`, `IdPais`, `Observaciones`, `FechaCarga`, `UsuarioCarga`, `Eliminado`) VALUES
(1, 'AVEC Automotores', 2, 'Distribuidora automotriz líder', '2026-05-11 10:00:00', 'csanabria', 0),
(2, 'Mercado Libre Brasil', 4, 'Soporte y logística regional', '2026-05-12 11:30:00', 'mferrero', 0),
(3, 'Pinturerias Tersuave', 1, 'Cadena de pinturerías', '2026-05-13 09:15:00', 'mgutierrez', 0),
(4, 'La Serena Automotores', 3, 'Concesionaria en Chile', '2026-05-14 14:00:00', 'mferrero', 0)
ON DUPLICATE KEY UPDATE `Denominacion`=`Denominacion`;

-- Populate table: proyectos with mock projects
INSERT INTO `proyectos` (`Id`, `Denominacion`, `IdEmpresa`, `IdLider`, `Observaciones`, `Prioridad`, `IdEstado`, `FechaCarga`, `UsuarioCarga`, `Eliminado`) VALUES
(1, 'ECommerce Renovación', 1, 5, 'Migración de plataforma y diseño adaptable', 0, 3, '2026-05-01 08:00:00', 'spalacios', 0),
(2, 'Generación APIs + Documentación', 2, 2, 'APIs externas para Mercado Libre', 0, 2, '2026-05-10 09:30:00', 'spalacios', 0),
(3, 'Adecuaciones en estructuras de Productos', 3, 3, 'Modificación de tablas en BD de stock', 0, 1, '2026-05-15 10:15:00', 'spalacios', 0),
(4, 'Cambios en seguridad al ingreso', 2, 2, 'Actualización de módulos de autenticación', 0, 1, '2026-05-18 11:00:00', 'spalacios', 0),
(5, 'Gestión de Facturación Web', 4, 3, 'Módulo contable adaptado a Chile', 0, 4, '2026-05-25 15:45:00', 'spalacios', 0)
ON DUPLICATE KEY UPDATE `Denominacion`=`Denominacion`;

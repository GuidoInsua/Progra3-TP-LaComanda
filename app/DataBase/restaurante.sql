-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 10:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurante`
--

-- --------------------------------------------------------

--
-- Table structure for table `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `codigoPedido` varchar(10) NOT NULL,
  `puntuacion` int(2) NOT NULL,
  `comentario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encuesta`
--

INSERT INTO `encuesta` (`id`, `idMesa`, `codigoPedido`, `puntuacion`, `comentario`) VALUES
(1, 3, '77de6182a8', 6, 'muy rico');

-- --------------------------------------------------------

--
-- Table structure for table `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `estadoMesa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesa`
--

INSERT INTO `mesa` (`id`, `codigo`, `estadoMesa`) VALUES
(1, '1', '3'),
(2, '2', '4'),
(3, '3', '4'),
(4, '4', '4'),
(5, '5', '2');

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombreCliente` varchar(100) NOT NULL,
  `idMesa` int(11) DEFAULT NULL,
  `estadoPedido` varchar(50) NOT NULL,
  `tiempoEstimado` time DEFAULT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `precioFinal` decimal(10,2) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedido`
--

INSERT INTO `pedido` (`id`, `codigo`, `nombreCliente`, `idMesa`, `estadoPedido`, `tiempoEstimado`, `fechaBaja`, `precioFinal`, `fechaCreacion`) VALUES
(10, '3c4e5e4f1b', 'Luna', 5, '5', '00:15:00', '2024-07-30 17:04:04', 18500.00, '2024-07-02 08:30:33'),
(11, 'bb5c3923b4', 'juan', 4, '1', '00:20:00', NULL, 18500.00, '2024-07-02 17:50:40'),
(12, '77de6182a8', 'agustin', 3, '4', NULL, NULL, 18500.00, '2024-07-02 22:49:00'),
(13, '8e20b249ba', 'Guido', 3, '1', NULL, NULL, 18500.00, '2024-07-30 12:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `idSector` int(11) NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`id`, `precio`, `tipo`, `idSector`, `fechaBaja`) VALUES
(5, 4000.00, 'vino', 1, NULL),
(6, 4000.00, 'birra', 2, NULL),
(7, 980.00, 'empanada', 3, NULL),
(8, 2000.00, 'torta', 4, NULL),
(9, 6000.00, 'milanesa a caballo', 3, NULL),
(10, 4000.00, 'hamburguesa de garbanzo', 3, NULL),
(11, 2000.00, 'corona', 2, NULL),
(12, 2500.00, 'daikiri', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `relacionpedidoproducto`
--

CREATE TABLE `relacionpedidoproducto` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `estadoOrden` int(11) NOT NULL,
  `tiempoEstimado` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relacionpedidoproducto`
--

INSERT INTO `relacionpedidoproducto` (`id`, `idPedido`, `idProducto`, `idUsuario`, `estadoOrden`, `tiempoEstimado`) VALUES
(12, 10, 9, 2, 3, '00:00:01'),
(13, 10, 10, NULL, 3, NULL),
(14, 10, 10, NULL, 3, NULL),
(15, 10, 11, 4, 3, '00:15:00'),
(16, 10, 12, 5, 3, '00:15:00'),
(17, 11, 9, 2, 2, '00:10:00'),
(18, 11, 10, 2, 2, '00:20:00'),
(19, 11, 10, 2, 2, '00:15:00'),
(20, 11, 11, NULL, 1, NULL),
(21, 11, 12, NULL, 1, NULL),
(22, 12, 9, NULL, 1, NULL),
(23, 12, 10, NULL, 1, NULL),
(24, 12, 10, NULL, 1, NULL),
(25, 12, 11, NULL, 1, NULL),
(26, 12, 12, NULL, 1, NULL),
(27, 13, 9, NULL, 1, NULL),
(28, 13, 10, NULL, 1, NULL),
(29, 13, 10, NULL, 1, NULL),
(30, 13, 11, NULL, 1, NULL),
(31, 13, 12, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'bartender'),
(2, 'cervecero'),
(3, 'cocinero'),
(4, 'mozo'),
(5, 'socio');

-- --------------------------------------------------------

--
-- Table structure for table `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectores`
--

INSERT INTO `sectores` (`id`, `nombre`) VALUES
(4, 'Candy Bar'),
(2, 'Choperas'),
(3, 'Cocina'),
(1, 'Tragos y Vinos');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `idRol` int(11) NOT NULL,
  `fechaBaja` date DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL,
  `estadoUsuario` varchar(50) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `clave`, `idRol`, `fechaBaja`, `fechaAlta`, `estadoUsuario`) VALUES
(1, 'guido', '123', 5, NULL, '2024-07-02', '1'),
(2, 'mili', '1234', 4, NULL, '2024-07-02', '1'),
(3, 'julia', '12344', 3, NULL, '2024-07-02', '1'),
(4, 'pedro', '1435', 2, NULL, '2024-07-02', '1'),
(5, 'juan', '1435', 1, NULL, '2024-07-02', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idMesa` (`idMesa`),
  ADD KEY `idx_pedido_fechaCreacion` (`fechaCreacion`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_sector` (`idSector`);

--
-- Indexes for table `relacionpedidoproducto`
--
ALTER TABLE `relacionpedidoproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idx_relacionPedidoProducto_estado` (`estadoOrden`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idx_usuario_rol` (`idRol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `relacionpedidoproducto`
--
ALTER TABLE `relacionpedidoproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`id`);

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`idSector`) REFERENCES `sectores` (`id`);

--
-- Constraints for table `relacionpedidoproducto`
--
ALTER TABLE `relacionpedidoproducto`
  ADD CONSTRAINT `relacionpedidoproducto_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `relacionpedidoproducto_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `relacionpedidoproducto_ibfk_3` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idRol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

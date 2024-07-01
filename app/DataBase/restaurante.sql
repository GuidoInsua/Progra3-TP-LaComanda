-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS restaurante;
USE restaurante;

-- Crear tabla Roles
CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Crear tabla Sectores
CREATE TABLE Sectores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Insertar valores en la tabla Roles
INSERT INTO Roles (id, nombre) VALUES
(1, 'Bartender'),
(2, 'Cervecero'),
(3, 'Cocinero'),
(4, 'Mozo'),
(5, 'Socio');

-- Insertar valores en la tabla Sectores
INSERT INTO Sectores (id, nombre) VALUES
(1, 'Tragos y Vinos'),
(2, 'Choperas'),
(3, 'Cocina'),
(4, 'Candy Bar');

-- Crear tabla Usuario
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    idRol INT NOT NULL,
    fechaBaja DATE,
    estadoUsuario VARCHAR(50) DEFAULT 'activo',
    FOREIGN KEY (idRol) REFERENCES Roles(id)
);

-- Crear tabla Mesa
CREATE TABLE Mesa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL UNIQUE,
    estadoMesa VARCHAR(50) NOT NULL
);

-- Crear tabla Producto
CREATE TABLE Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    precio DECIMAL(10, 2) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    idSector INT NOT NULL,
    fechaBaja DATE,
    FOREIGN KEY (idSector) REFERENCES Sectores(id)
);

-- Crear tabla Pedido
CREATE TABLE Pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL UNIQUE,
    nombreCliente VARCHAR(100) NOT NULL,
    idMesa INT,
    estadoPedido VARCHAR(50) NOT NULL,
    tiempoEstimado TIME,
    fechaBaja DATE,
    precioFinal DECIMAL(10, 2),
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idMesa) REFERENCES Mesa(id)
);

-- Crear tabla RelacionPedidoProducto
CREATE TABLE RelacionPedidoProducto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idPedido INT,
    idProducto INT,
    idUsuario INT,
    estadoRelacion VARCHAR(50) NOT NULL,
    tiempoEstimado TIME,
    FOREIGN KEY (idPedido) REFERENCES Pedido(id),
    FOREIGN KEY (idProducto) REFERENCES Producto(id),
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);

-- Índices adicionales para optimización
CREATE INDEX idx_pedido_fechaCreacion ON Pedido (fechaCreacion);
CREATE INDEX idx_relacionPedidoProducto_estado ON RelacionPedidoProducto (estadoRelacion);
CREATE INDEX idx_usuario_rol ON Usuario (idRol);
CREATE INDEX idx_producto_sector ON Producto (idSector);

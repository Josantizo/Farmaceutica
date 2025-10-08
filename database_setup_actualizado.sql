-- =====================================================
-- CONFIGURACIÓN DE BASE DE DATOS PARA XAMPP
-- =====================================================
-- Este archivo contiene la configuración necesaria
-- para conectar Laravel con MySQL en XAMPP
-- =====================================================

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS farmaceutica 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE farmaceutica;

-- =====================================================
-- TABLAS PRINCIPALES (YA EXISTENTES)
-- =====================================================

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    categoria_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    descripcion VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    proveedor_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    direccion VARCHAR(45) NOT NULL,
    telefono VARCHAR(45) NOT NULL,
    correo VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de empleados
CREATE TABLE IF NOT EXISTS empleados (
    empleados_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    usuario VARCHAR(45) NOT NULL,
    contraseña VARCHAR(45) NOT NULL,
    rol VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    cliente_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    direccion VARCHAR(45) NOT NULL,
    telefono VARCHAR(45) NOT NULL,
    correo VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    producto_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    descripcion VARCHAR(45) NOT NULL,
    precio_compra DECIMAL(10,0) NOT NULL,
    precio_venta DECIMAL(10,0) NOT NULL,
    stock_actual INT NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    lote VARCHAR(45) NOT NULL,
    categoria_id INT NOT NULL,
    proveedor_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(categoria_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(proveedor_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de compras
CREATE TABLE IF NOT EXISTS compras (
    compra_id INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id INT NOT NULL,
    empleado_id INT NOT NULL,
    fecha_compra DATE NOT NULL,
    total DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(proveedor_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(empleados_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de detalle de compra
CREATE TABLE IF NOT EXISTS detalle_compra (
    detallecompra_id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(compra_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    ventas_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    empleado_id INT NOT NULL,
    fecha_venta DATE NOT NULL,
    total DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(empleados_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de detalle de venta
CREATE TABLE IF NOT EXISTS detalle_venta (
    detalleventa_id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(ventas_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de alertas
CREATE TABLE IF NOT EXISTS alertas (
    alerta_id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo_alerta ENUM('vencimiento', 'bajo stock') NOT NULL,
    fecha_generada DATE NOT NULL,
    estado ENUM('pendiente', 'resuelto') NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de historial de stock
CREATE TABLE IF NOT EXISTS historial_stock (
    historial_id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    fecha DATE NOT NULL,
    cantidad_cambio INT NOT NULL,
    tipo_movimiento ENUM('Compra', 'Venta', 'Ajuste') NOT NULL,
    compra_id INT NOT NULL,
    venta_id INT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id),
    FOREIGN KEY (compra_id) REFERENCES compras(compra_id),
    FOREIGN KEY (venta_id) REFERENCES ventas(ventas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
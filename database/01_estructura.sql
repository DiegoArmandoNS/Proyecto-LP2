CREATE DATABASE IF NOT EXISTS restaurante_l CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurante_l;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador','mesero','cocinero','cajero') NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE mesas (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    numero_mesa INT NOT NULL UNIQUE,
    capacidad INT NOT NULL,
    estado ENUM('libre','ocupada','por_cobrar') NOT NULL DEFAULT 'libre'
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE platos (
    id_plato INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(10,2) NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB;

CREATE TABLE insumos (
    id_insumo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    unidad_medida VARCHAR(30) NOT NULL,
    stock_actual DECIMAL(12,3) NOT NULL DEFAULT 0,
    stock_minimo DECIMAL(12,3) NOT NULL DEFAULT 0,
    estado TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE receta_items (
    id_receta_item INT AUTO_INCREMENT PRIMARY KEY,
    id_plato INT NOT NULL,
    id_insumo INT NOT NULL,
    cantidad DECIMAL(12,3) NOT NULL,
    UNIQUE (id_plato, id_insumo),
    FOREIGN KEY (id_plato) REFERENCES platos(id_plato),
    FOREIGN KEY (id_insumo) REFERENCES insumos(id_insumo)
) ENGINE=InnoDB;

CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_pedido DATETIME NOT NULL,
    estado ENUM('pendiente','en_preparacion','listo','cancelado','pagado') NOT NULL DEFAULT 'pendiente',
    observaciones TEXT NULL,
    motivo_cancelacion VARCHAR(255) NULL,
    FOREIGN KEY (id_mesa) REFERENCES mesas(id_mesa),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_pedidos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_plato INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    observaciones VARCHAR(255) NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_plato) REFERENCES platos(id_plato)
) ENGINE=InnoDB;

CREATE TABLE movimientos_inventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_insumo INT NOT NULL,
    id_usuario INT NOT NULL,
    id_pedido INT NULL,
    tipo ENUM('ingreso','salida','merma','ajuste') NOT NULL,
    cantidad DECIMAL(12,3) NOT NULL,
    motivo VARCHAR(255) NULL,
    fecha_movimiento DATETIME NOT NULL,
    FOREIGN KEY (id_insumo) REFERENCES insumos(id_insumo),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
) ENGINE=InnoDB;

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL UNIQUE,
    id_usuario INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    monto_recibido DECIMAL(10,2) NOT NULL,
    vuelto DECIMAL(10,2) NOT NULL DEFAULT 0,
    metodo_pago ENUM('efectivo','tarjeta','transferencia','yape','plin') NOT NULL,
    fecha_pago DATETIME NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL UNIQUE,
    id_pago INT NOT NULL UNIQUE,
    numero_comprobante VARCHAR(30) NOT NULL UNIQUE,
    total DECIMAL(10,2) NOT NULL,
    fecha_venta DATETIME NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_pago) REFERENCES pagos(id_pago)
) ENGINE=InnoDB;

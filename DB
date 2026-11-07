-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema restauranteL_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema restauranteL_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `restauranteL_db` DEFAULT CHARACTER SET utf8 ;
USE `restauranteL_db` ;

-- -----------------------------------------------------
-- Table `restauranteL_db`.`Usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Usuarios` (
  `idUsuarios` INT NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` VARCHAR(50) NOT NULL,
  `estado` VARCHAR(50) NOT NULL,
  `fecha_registro` DATETIME NOT NULL,
  PRIMARY KEY (`idUsuarios`),
  UNIQUE INDEX `idUsuarios_UNIQUE` (`idUsuarios` ASC) VISIBLE,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Mesas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Mesas` (
  `idMesas` INT NOT NULL AUTO_INCREMENT,
  `numero_mesa` INT NOT NULL,
  `capacidad` INT NOT NULL,
  `estado` VARCHAR(50) NOT NULL DEFAULT 'Libre',
  PRIMARY KEY (`idMesas`),
  UNIQUE INDEX `idMesas_UNIQUE` (`idMesas` ASC) VISIBLE,
  UNIQUE INDEX `numero_mesa_UNIQUE` (`numero_mesa` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Categorias` (
  `idcategorias` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `estado` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idcategorias`),
  UNIQUE INDEX `idcategorias_UNIQUE` (`idcategorias` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Platos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Platos` (
  `idplatos` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(120) NOT NULL,
  `descripcion` VARCHAR(120) NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `disponible` VARCHAR(45) NOT NULL,
  `idcategorias` INT NOT NULL,
  PRIMARY KEY (`idplatos`, `idcategorias`),
  UNIQUE INDEX `idplatos_UNIQUE` (`idplatos` ASC) VISIBLE,
  INDEX `fk_Platos_Categorias_idx` (`idcategorias` ASC) VISIBLE,
  CONSTRAINT `fk_Platos_Categorias`
    FOREIGN KEY (`idcategorias`)
    REFERENCES `restauranteL_db`.`Categorias` (`idcategorias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Insumos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Insumos` (
  `id_insumos` INT NOT NULL AUTO_INCREMENT,
  ` nombre` VARCHAR(120) NOT NULL,
  `unidad_medida` VARCHAR(30) NOT NULL,
  `stock_actual` DECIMAL(12,3) NOT NULL,
  `stock_minimo` DECIMAL(12,3) NOT NULL,
  `estado` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_insumos`),
  UNIQUE INDEX `id insumos_UNIQUE` (`id_insumos` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Receta_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Receta_items` (
  `idreceta_items` INT NOT NULL AUTO_INCREMENT,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `idplatos` INT NOT NULL,
  `id_insumos` INT NOT NULL,
  PRIMARY KEY (`idreceta_items`, `idplatos`, `id_insumos`),
  UNIQUE INDEX `idreceta_items_UNIQUE` (`idreceta_items` ASC) VISIBLE,
  INDEX `fk_Receta_items_Platos1_idx` (`idplatos` ASC) VISIBLE,
  INDEX `fk_Receta_items_Insumos1_idx` (`id_insumos` ASC) VISIBLE,
  CONSTRAINT `fk_Receta_items_Platos1`
    FOREIGN KEY (`idplatos`)
    REFERENCES `restauranteL_db`.`Platos` (`idplatos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Receta_items_Insumos1`
    FOREIGN KEY (`id_insumos`)
    REFERENCES `restauranteL_db`.`Insumos` (`id_insumos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.` Pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.` Pedidos` (
  `id pedidos` INT NOT NULL AUTO_INCREMENT,
  `fecha_pedido` DATETIME NOT NULL,
  `estado` VARCHAR(50) NOT NULL,
  `observaciones` VARCHAR(45) NULL,
  `idMesas` INT NOT NULL,
  `idUsuarios` INT NOT NULL,
  PRIMARY KEY (`id pedidos`, `idMesas`, `idUsuarios`),
  UNIQUE INDEX `id pedidos_UNIQUE` (`id pedidos` ASC) VISIBLE,
  INDEX `fk_ Pedidos_Mesas1_idx` (`idMesas` ASC) VISIBLE,
  INDEX `fk_ Pedidos_Usuarios1_idx` (`idUsuarios` ASC) VISIBLE,
  CONSTRAINT `fk_ Pedidos_Mesas1`
    FOREIGN KEY (`idMesas`)
    REFERENCES `restauranteL_db`.`Mesas` (`idMesas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ Pedidos_Usuarios1`
    FOREIGN KEY (`idUsuarios`)
    REFERENCES `restauranteL_db`.`Usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Detalle_pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Detalle_pedidos` (
  `iddetalle_pedidos` INT NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `observaciones` VARCHAR(255) NOT NULL,
  `id pedidos` INT NOT NULL,
  `idplatos` INT NOT NULL,
  PRIMARY KEY (`iddetalle_pedidos`, `id pedidos`, `idplatos`),
  UNIQUE INDEX `iddetalle_pedidos_UNIQUE` (`iddetalle_pedidos` ASC) VISIBLE,
  INDEX `fk_Detalle_pedidos_ Pedidos1_idx` (`id pedidos` ASC) VISIBLE,
  INDEX `fk_Detalle_pedidos_Platos1_idx` (`idplatos` ASC) VISIBLE,
  CONSTRAINT `fk_Detalle_pedidos_ Pedidos1`
    FOREIGN KEY (`id pedidos`)
    REFERENCES `restauranteL_db`.` Pedidos` (`id pedidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Detalle_pedidos_Platos1`
    FOREIGN KEY (`idplatos`)
    REFERENCES `restauranteL_db`.`Platos` (`idplatos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Movimientos_inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Movimientos_inventario` (
  `idmovimientos_inventario` INT NOT NULL AUTO_INCREMENT,
  `movimientos_inventariocol` VARCHAR(45) NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `cantidad` DECIMAL(12,3) NOT NULL,
  `motivo` VARCHAR(255) NOT NULL,
  `fecha_movimiento` DATETIME NOT NULL,
  `id_insumos` INT NOT NULL,
  `idUsuarios` INT NOT NULL,
  PRIMARY KEY (`idmovimientos_inventario`, `id_insumos`, `idUsuarios`),
  UNIQUE INDEX `idmovimientos_inventario_UNIQUE` (`idmovimientos_inventario` ASC) VISIBLE,
  INDEX `fk_Movimientos_inventario_Insumos1_idx` (`id_insumos` ASC) VISIBLE,
  INDEX `fk_Movimientos_inventario_Usuarios1_idx` (`idUsuarios` ASC) VISIBLE,
  CONSTRAINT `fk_Movimientos_inventario_Insumos1`
    FOREIGN KEY (`id_insumos`)
    REFERENCES `restauranteL_db`.`Insumos` (`id_insumos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Movimientos_inventario_Usuarios1`
    FOREIGN KEY (`idUsuarios`)
    REFERENCES `restauranteL_db`.`Usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Pagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Pagos` (
  `idpagos` INT NOT NULL AUTO_INCREMENT,
  `monto` DECIMAL(10,2) NOT NULL,
  `metodo_pago` VARCHAR(50) NOT NULL,
  `fecha_pago` DATETIME NOT NULL,
  `idUsuarios` INT NOT NULL,
  PRIMARY KEY (`idpagos`, `idUsuarios`),
  UNIQUE INDEX `idpagos_UNIQUE` (`idpagos` ASC) VISIBLE,
  INDEX `fk_Pagos_Usuarios1_idx` (`idUsuarios` ASC) VISIBLE,
  CONSTRAINT `fk_Pagos_Usuarios1`
    FOREIGN KEY (`idUsuarios`)
    REFERENCES `restauranteL_db`.`Usuarios` (`idUsuarios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restauranteL_db`.`Ventas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `restauranteL_db`.`Ventas` (
  `idventas` INT NOT NULL AUTO_INCREMENT,
  `numero_comprobante` VARCHAR(30) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `fecha_venta` DATETIME NULL,
  `id pedidos` INT NOT NULL,
  `idpagos` INT NOT NULL,
  PRIMARY KEY (`idventas`, `id pedidos`, `idpagos`),
  UNIQUE INDEX `idventas_UNIQUE` (`idventas` ASC) VISIBLE,
  UNIQUE INDEX `numero_comprobante_UNIQUE` (`numero_comprobante` ASC) VISIBLE,
  INDEX `fk_Ventas_ Pedidos1_idx` (`id pedidos` ASC) VISIBLE,
  INDEX `fk_Ventas_Pagos1_idx` (`idpagos` ASC) VISIBLE,
  CONSTRAINT `fk_Ventas_ Pedidos1`
    FOREIGN KEY (`id pedidos`)
    REFERENCES `restauranteL_db`.` Pedidos` (`id pedidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ventas_Pagos1`
    FOREIGN KEY (`idpagos`)
    REFERENCES `restauranteL_db`.`Pagos` (`idpagos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

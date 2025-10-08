-- =====================================================
-- CORRECCIÓN DE COMPATIBILIDAD HISTORIAL_STOCK
-- =====================================================
-- Aplicando opción A: alterar BD para incluir 'liquidacion' 
-- y usar DATETIME en lugar de DATE

USE farmaceutica;

-- 1. Cambiar tipo de fecha de DATE a DATETIME
ALTER TABLE historial_stock 
MODIFY COLUMN fecha DATETIME NOT NULL;

-- 2. Agregar 'liquidacion' al enum de tipo_movimiento
ALTER TABLE historial_stock 
MODIFY COLUMN tipo_movimiento ENUM('Compra', 'Venta', 'Ajuste', 'liquidacion') NOT NULL;

-- 3. Agregar columna liquidacion_id si no existe
ALTER TABLE historial_stock 
ADD COLUMN IF NOT EXISTS liquidacion_id INT NULL,
ADD CONSTRAINT fk_historial_liquidacion 
    FOREIGN KEY (liquidacion_id) REFERENCES liquidaciones(liquidacion_id) 
    ON DELETE SET NULL;

-- 4. Hacer compra_id y venta_id NULL para permitir liquidaciones
ALTER TABLE historial_stock 
MODIFY COLUMN compra_id INT NULL,
MODIFY COLUMN venta_id INT NULL;

-- 5. Actualizar triggers para usar NOW() en lugar de CURDATE()
DELIMITER $$

-- Trigger de compras actualizado
DROP TRIGGER IF EXISTS trg_actualizar_stock_compra $$
CREATE TRIGGER trg_actualizar_stock_compra
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual + NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'Compra', NEW.compra_id, NULL, NULL);
END $$

-- Trigger de ventas actualizado
DROP TRIGGER IF EXISTS trg_actualizar_stock_venta $$
CREATE TRIGGER trg_actualizar_stock_venta
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'Venta', NULL, NEW.venta_id, NULL);

  -- Alerta por bajo stock
  IF (SELECT stock_actual FROM productos WHERE producto_id = NEW.producto_id) < 10 THEN
    INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
    VALUES (NEW.producto_id, 'bajo stock', NOW(), 'pendiente');
  END IF;
END $$

-- Trigger de liquidaciones (se creará cuando exista la tabla)
DROP TRIGGER IF EXISTS trg_actualizar_stock_liquidacion $$
CREATE TRIGGER trg_actualizar_stock_liquidacion
AFTER INSERT ON detalle_liquidacion
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'liquidacion', NULL, NULL, NEW.liquidacion_id);
END $$

-- Trigger de alertas por vencimiento actualizado
DROP TRIGGER IF EXISTS trg_alerta_vencimiento $$
CREATE TRIGGER trg_alerta_vencimiento
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
  DECLARE v_fecha_venc DATE;
  DECLARE v_proveedor_id INT;
  DECLARE v_dias INT DEFAULT 30;

  SELECT p.fecha_vencimiento, p.proveedor_id
  INTO v_fecha_venc, v_proveedor_id
  FROM productos p
  WHERE p.producto_id = NEW.producto_id;

  -- Usar política del proveedor si existe
  SELECT COALESCE(pp.dias_anticipacion, 30)
  INTO v_dias
  FROM proveedor_politicas pp
  WHERE pp.proveedor_id = v_proveedor_id
  LIMIT 1;

  IF v_fecha_venc IS NOT NULL AND v_fecha_venc <= DATE_ADD(CURDATE(), INTERVAL v_dias DAY) THEN
    INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
    VALUES (NEW.producto_id, 'vencimiento proximo', NOW(), 'pendiente');
  END IF;
END $$

DELIMITER ;

-- 6. Verificar cambios
SELECT 'Historial stock actualizado correctamente' as status;
DESCRIBE historial_stock;


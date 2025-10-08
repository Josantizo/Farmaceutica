DELIMITER $$

-- Permitir NULL en compra_id y venta_id para poder registrar liquidaciones en Kardex
ALTER TABLE historial_stock
  MODIFY compra_id INT NULL,
  MODIFY venta_id INT NULL
$$

-- Re-crear trigger de COMPRAS: usa 'Compra' y CURDATE()
DROP TRIGGER IF EXISTS trg_actualizar_stock_compra $$
CREATE TRIGGER trg_actualizar_stock_compra
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual + NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, CURDATE(), NEW.cantidad, 'Compra', NEW.compra_id, NULL, NULL);
END $$
  
-- Re-crear trigger de VENTAS: usa 'Venta', CURDATE(), y corrige bajo stock
DROP TRIGGER IF EXISTS trg_actualizar_stock_venta $$
CREATE TRIGGER trg_actualizar_stock_venta
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, CURDATE(), NEW.cantidad, 'Venta', NULL, NEW.venta_id, NULL);

  IF (SELECT stock_actual FROM productos WHERE producto_id = NEW.producto_id) < 10 THEN
    INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
    VALUES (NEW.producto_id, 'bajo stock', CURDATE(), 'pendiente');
  END IF;
END $$

-- Re-crear trigger de LIQUIDACIONES: registra 'Ajuste' en Kardex
DROP TRIGGER IF EXISTS trg_actualizar_stock_liquidacion $$
CREATE TRIGGER trg_actualizar_stock_liquidacion
AFTER INSERT ON detalle_liquidacion
FOR EACH ROW
BEGIN
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id, venta_id, liquidacion_id)
  VALUES (NEW.producto_id, CURDATE(), NEW.cantidad, 'Ajuste', NULL, NULL, NEW.liquidacion_id);
END $$

-- Re-crear trigger de ALERTA POR VENCIMIENTO usando política por proveedor, y CURDATE()
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

  SELECT COALESCE(pp.dias_anticipacion, 30)
  INTO v_dias
  FROM proveedor_politicas pp
  WHERE pp.proveedor_id = v_proveedor_id
  LIMIT 1;

  IF v_fecha_venc IS NOT NULL AND v_fecha_venc <= DATE_ADD(CURDATE(), INTERVAL v_dias DAY) THEN
    INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
    VALUES (NEW.producto_id, 'vencimiento proximo', CURDATE(), 'pendiente');
  END IF;
END $$

DELIMITER ;
DELIMITER $$
CREATE TRIGGER trg_actualizar_stock_compra
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
  -- Sumar al stock actual
  UPDATE productos
  SET stock_actual = stock_actual + NEW.cantidad
  WHERE producto_id = NEW.producto_id;

  -- Insertar en Historial_Stock
  INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id)
  VALUES (NEW.producto_id, CURDATE(), NEW.cantidad, 'compra', NEW.compra_id);
END$$

DELIMITER $$
CREATE TRIGGER trg_actualizar_stock_venta
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
	UPDATE productos
    SET stock_actual = stock_actual - NEW.cantidad
    WHERE producto_id = NEW.producto_id;
    
    INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, venta_id)
	VALUES (NEW.producto_id, CURDATE(), NEW.cantidad, 'venta', NEW.venta_id);
    
    IF(SELECT stock_actual FROM productos WHERE productos_id = NEW.producto_id) < 10 THEN
		INSERT INTO  alertas(producto_id, tipo_alerta, fecha_generada, estado)
		VALUES (NEW.producto_id, 'bajo stock', CURDATE(), 'pendiente');
    END IF;
END$$

DELIMITER $$
CREATE TRIGGER trg_alerta_vencimiento
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
	DECLARE fecha_venc DATE;
    
    SELECT fecha_vencimiento INTO fecha_venc
    FROM productos
    WHERE producto_id = NEW.producto_id;
    
    IF fecha_venc IS NOT NULL AND fecha_venc <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN
		INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
        VALUES (NEW.producto_id, 'vencimiento proximo', CURDATE(), 'pendiente');
	END IF;
END$$

DELIMITER $$
CREATE TRIGGER trg_prevenir_venta_si_sin_stock
BEFORE INSERT ON detalle_venta
FOR EACH ROW
BEGIN
	IF (SELECT stock_actual FROM productos WHERE producto_id = NEW.producto_id) < NEW.cantidad THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'stock insuficiente para la venta';
	END IF;
END$$
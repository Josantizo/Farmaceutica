-- =============================================
-- Extensiones para políticas y liquidaciones
-- =============================================

-- Seguridad: usar el mismo delimitador que el motor soporta
DELIMITER $$

-- =============================================
-- 1) Tabla de políticas de vencimiento por proveedor
-- =============================================
CREATE TABLE IF NOT EXISTS proveedor_politicas (
	proveedor_politica_id INT AUTO_INCREMENT PRIMARY KEY,
	proveedor_id INT NOT NULL,
	dias_anticipacion INT NOT NULL DEFAULT 30,
	metodo_liquidacion VARCHAR(45) NULL,
	porcentaje_credito DECIMAL(5,2) NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT fk_proveedor_politicas_proveedor
		FOREIGN KEY (proveedor_id) REFERENCES proveedores(proveedor_id)
		ON DELETE CASCADE
) $$

-- Índice único para que cada proveedor tenga solo una política activa
CREATE UNIQUE INDEX IF NOT EXISTS ux_proveedor_politicas_proveedor
	ON proveedor_politicas (proveedor_id) $$

-- =============================================
-- 2) Tablas de liquidaciones a proveedores
-- =============================================
CREATE TABLE IF NOT EXISTS liquidaciones (
	liquidacion_id INT AUTO_INCREMENT PRIMARY KEY,
	proveedor_id INT NOT NULL,
	empleado_id INT NULL,
	fecha_liquidacion DATETIME NOT NULL,
	estado ENUM('borrador','enviada','aceptada','rechazada') NOT NULL DEFAULT 'borrador',
	total DECIMAL(12,2) NOT NULL DEFAULT 0,
	observaciones VARCHAR(255) NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT fk_liquidaciones_proveedor
		FOREIGN KEY (proveedor_id) REFERENCES proveedores(proveedor_id)
		ON DELETE RESTRICT,
	CONSTRAINT fk_liquidaciones_empleado
		FOREIGN KEY (empleado_id) REFERENCES empleados(empleados_id)
		ON DELETE SET NULL
) $$

CREATE TABLE IF NOT EXISTS detalle_liquidacion (
	detalle_liquidacion_id INT AUTO_INCREMENT PRIMARY KEY,
	liquidacion_id INT NOT NULL,
	producto_id INT NOT NULL,
	cantidad INT NOT NULL,
	precio_unitario DECIMAL(12,2) NOT NULL,
	motivo VARCHAR(45) NOT NULL DEFAULT 'por_vencer',
	subtotal DECIMAL(12,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT fk_det_liq_liquidacion
		FOREIGN KEY (liquidacion_id) REFERENCES liquidaciones(liquidacion_id)
		ON DELETE CASCADE,
	CONSTRAINT fk_det_liq_producto
		FOREIGN KEY (producto_id) REFERENCES productos(producto_id)
		ON DELETE RESTRICT
) $$

-- Índices útiles
CREATE INDEX IF NOT EXISTS ix_det_liq_producto ON detalle_liquidacion(producto_id) $$
CREATE INDEX IF NOT EXISTS ix_det_liq_liquidacion ON detalle_liquidacion(liquidacion_id) $$

-- =============================================
-- 3) Ampliar historial_stock para referenciar liquidaciones
-- =============================================
-- La tabla historial_stock ya existe; se agrega la columna si hace falta
ALTER TABLE historial_stock
	ADD COLUMN IF NOT EXISTS liquidacion_id INT NULL,
	ADD CONSTRAINT fk_hist_liquidacion
		FOREIGN KEY (liquidacion_id) REFERENCES liquidaciones(liquidacion_id)
		ON DELETE SET NULL $$

-- =============================================
-- 4) Triggers: stock y kardex para liquidaciones
-- =============================================
DROP TRIGGER IF EXISTS trg_actualizar_stock_liquidacion $$
CREATE TRIGGER trg_actualizar_stock_liquidacion
AFTER INSERT ON detalle_liquidacion
FOR EACH ROW
BEGIN
	-- Disminuir stock por liquidación
	UPDATE productos
	SET stock_actual = stock_actual - NEW.cantidad
	WHERE producto_id = NEW.producto_id;

	-- Registrar en historial (Kardex)
	INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, liquidacion_id)
	VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'liquidacion', NEW.liquidacion_id);
END $$

-- =============================================
-- 5) Corregir trigger de bajo stock y usar NOW()
-- =============================================
DROP TRIGGER IF EXISTS trg_actualizar_stock_venta $$
CREATE TRIGGER trg_actualizar_stock_venta
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
	UPDATE productos
	SET stock_actual = stock_actual - NEW.cantidad
	WHERE producto_id = NEW.producto_id;

	INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, venta_id)
	VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'venta', NEW.venta_id);

	-- Corregido: columna correcta es producto_id
	IF (SELECT stock_actual FROM productos WHERE producto_id = NEW.producto_id) < 10 THEN
		INSERT INTO alertas (producto_id, tipo_alerta, fecha_generada, estado)
		VALUES (NEW.producto_id, 'bajo stock', NOW(), 'pendiente');
	END IF;
END $$

-- =============================================
-- 6) Trigger de compras con NOW() y sin cambios de lógica
-- =============================================
DROP TRIGGER IF EXISTS trg_actualizar_stock_compra $$
CREATE TRIGGER trg_actualizar_stock_compra
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
	UPDATE productos
	SET stock_actual = stock_actual + NEW.cantidad
	WHERE producto_id = NEW.producto_id;

	INSERT INTO historial_stock (producto_id, fecha, cantidad_cambio, tipo_movimiento, compra_id)
	VALUES (NEW.producto_id, NOW(), NEW.cantidad, 'compra', NEW.compra_id);
END $$

-- =============================================
-- 7) Trigger de alertas por vencimiento usando política del proveedor
-- =============================================
DROP TRIGGER IF EXISTS trg_alerta_vencimiento $$
CREATE TRIGGER trg_alerta_vencimiento
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
	DECLARE v_fecha_venc DATE;
	DECLARE v_proveedor_id INT;
	DECLARE v_dias INT DEFAULT 30;

	-- Obtener fecha de vencimiento y proveedor del producto
	SELECT p.fecha_vencimiento, p.proveedor_id
	INTO v_fecha_venc, v_proveedor_id
	FROM productos p
	WHERE p.producto_id = NEW.producto_id;

	-- Tomar política por proveedor si existe
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

-- Restaurar delimitador
DELIMITER ;

-- =============================================
-- 8) Recalcular totales de liquidación (opcional por trigger)
-- =============================================
DELIMITER $$
DROP TRIGGER IF EXISTS trg_liquidacion_recalcular_total $$
CREATE TRIGGER trg_liquidacion_recalcular_total
AFTER INSERT ON detalle_liquidacion
FOR EACH ROW
BEGIN
	UPDATE liquidaciones l
	SET l.total = (
		SELECT COALESCE(SUM(dl.subtotal), 0)
		FROM detalle_liquidacion dl
		WHERE dl.liquidacion_id = NEW.liquidacion_id
	)
	WHERE l.liquidacion_id = NEW.liquidacion_id;
END $$

DROP TRIGGER IF EXISTS trg_liquidacion_recalcular_total_u $$
CREATE TRIGGER trg_liquidacion_recalcular_total_u
AFTER UPDATE ON detalle_liquidacion
FOR EACH ROW
BEGIN
	UPDATE liquidaciones l
	SET l.total = (
		SELECT COALESCE(SUM(dl.subtotal), 0)
		FROM detalle_liquidacion dl
		WHERE dl.liquidacion_id = NEW.liquidacion_id
	)
	WHERE l.liquidacion_id = NEW.liquidacion_id;
END $$

DROP TRIGGER IF EXISTS trg_liquidacion_recalcular_total_d $$
CREATE TRIGGER trg_liquidacion_recalcular_total_d
AFTER DELETE ON detalle_liquidacion
FOR EACH ROW
BEGIN
	UPDATE liquidaciones l
	SET l.total = (
		SELECT COALESCE(SUM(dl.subtotal), 0)
		FROM detalle_liquidacion dl
		WHERE dl.liquidacion_id = OLD.liquidacion_id
	)
	WHERE l.liquidacion_id = OLD.liquidacion_id;
END $$

DELIMITER ;



# Sistema Farmacéutico - Laravel

Sistema completo de gestión farmacéutica desarrollado con Laravel 12, que incluye gestión de productos, compras, ventas, clientes, proveedores, empleados y alertas automáticas.

## 🚀 Características

- **Gestión de Productos**: CRUD completo con control de stock y fechas de vencimiento
- **Sistema de Compras**: Registro de compras con actualización automática de stock
- **Sistema de Ventas**: Registro de ventas con verificación de stock
- **Gestión de Clientes**: Base de datos de clientes
- **Gestión de Proveedores**: Base de datos de proveedores
- **Gestión de Empleados**: Base de datos de empleados
- **Sistema de Alertas**: Alertas automáticas por bajo stock y vencimiento
- **Historial de Stock**: Seguimiento completo de movimientos de inventario
- **Triggers Automáticos**: Actualización automática de stock y generación de alertas

## 📋 Requisitos

- PHP 8.2 o superior
- Composer
- XAMPP (Apache + MySQL)
- Laravel 12

## 🛠️ Instalación

### 1. Configurar XAMPP

1. Iniciar XAMPP
2. Activar Apache y MySQL
3. Acceder a phpMyAdmin (http://localhost/phpmyadmin)

### 2. Configurar Base de Datos

1. Ejecutar el archivo `database_setup.sql` en phpMyAdmin
2. Ejecutar el archivo `triggers.sql` en phpMyAdmin

### 3. Configurar Laravel

1. **Configurar archivo .env**:
```env
APP_NAME="Sistema Farmacéutico"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmaceutica
DB_USERNAME=root
DB_PASSWORD=
```

2. **Generar clave de aplicación**:
```bash
php artisan key:generate
```

3. **Instalar dependencias**:
```bash
composer install
```

4. **Ejecutar el servidor**:
```bash
php artisan serve
```

5. **Acceder al sistema**:
   - URL: http://localhost:8000
   - Dashboard principal con acceso a todas las funcionalidades

## 📁 Estructura del Proyecto

```
app/
├── Models/
│   ├── Producto.php
│   ├── Compra.php
│   ├── Venta.php
│   ├── Cliente.php
│   ├── Proveedor.php
│   ├── Empleado.php
│   ├── Categoria.php
│   ├── Alerta.php
│   └── HistorialStock.php
└── Http/Controllers/
    ├── ProductoController.php
    ├── CompraController.php
    ├── VentaController.php
    ├── ClienteController.php
    ├── ProveedorController.php
    ├── EmpleadoController.php
    ├── CategoriaController.php
    ├── AlertaController.php
    └── HistorialStockController.php

routes/
└── web.php

resources/views/
└── dashboard.blade.php
```

## 🔧 Funcionalidades Principales

### Productos
- ✅ Crear, leer, actualizar y eliminar productos
- ✅ Control de stock automático
- ✅ Alertas por bajo stock
- ✅ Alertas por vencimiento próximo
- ✅ Historial de movimientos

### Compras
- ✅ Registro de compras
- ✅ Actualización automática de stock
- ✅ Cálculo automático de totales
- ✅ Historial de compras

### Ventas
- ✅ Registro de ventas
- ✅ Verificación de stock antes de vender
- ✅ Reducción automática de stock
- ✅ Cálculo automático de totales
- ✅ Historial de ventas

### Alertas
- ✅ Alertas automáticas por bajo stock
- ✅ Alertas automáticas por vencimiento
- ✅ Gestión de estado de alertas
- ✅ Filtros por tipo de alerta

## 🎯 Triggers Implementados

1. **`trg_actualizar_stock_compra`**: Actualiza stock al hacer compras
2. **`trg_actualizar_stock_venta`**: Reduce stock al hacer ventas
3. **`trg_alerta_vencimiento`**: Crea alertas de vencimiento
4. **`trg_prevenir_venta_si_sin_stock`**: Previene ventas sin stock

## 📊 Rutas Disponibles

### Productos
- `GET /productos` - Lista de productos
- `GET /productos/create` - Formulario de nuevo producto
- `POST /productos` - Crear producto
- `GET /productos/{id}` - Ver producto
- `GET /productos/{id}/edit` - Formulario de edición
- `PUT /productos/{id}` - Actualizar producto
- `DELETE /productos/{id}` - Eliminar producto
- `GET /productos/bajo-stock` - Productos con bajo stock
- `GET /productos/por-vencer` - Productos por vencer

### Compras
- `GET /compras` - Lista de compras
- `GET /compras/create` - Formulario de nueva compra
- `POST /compras` - Crear compra
- `GET /compras/{id}` - Ver compra
- `GET /compras/{id}/edit` - Formulario de edición
- `PUT /compras/{id}` - Actualizar compra
- `DELETE /compras/{id}` - Eliminar compra

### Ventas
- `GET /ventas` - Lista de ventas
- `GET /ventas/create` - Formulario de nueva venta
- `POST /ventas` - Crear venta
- `GET /ventas/{id}` - Ver venta
- `GET /ventas/{id}/edit` - Formulario de edición
- `PUT /ventas/{id}` - Actualizar venta
- `DELETE /ventas/{id}` - Eliminar venta

### Otras Entidades
- `GET /clientes` - Gestión de clientes
- `GET /proveedores` - Gestión de proveedores
- `GET /empleados` - Gestión de empleados
- `GET /categorias` - Gestión de categorías
- `GET /alertas` - Gestión de alertas
- `GET /historial-stock` - Historial de stock

## 🎨 Interfaz de Usuario

- **Dashboard Principal**: Vista general del sistema
- **Navegación Intuitiva**: Menú lateral con acceso a todas las funcionalidades
- **Tarjetas de Resumen**: Información rápida de cada módulo
- **Acciones Rápidas**: Botones para crear nuevos registros
- **Reportes Rápidos**: Acceso directo a reportes importantes

## 🔍 Características Técnicas

- **Laravel 12**: Framework PHP moderno
- **MySQL**: Base de datos robusta
- **Triggers**: Automatización de procesos
- **Validaciones**: Validación completa de datos
- **Relaciones**: Modelos con relaciones bien definidas
- **Scopes**: Consultas optimizadas
- **Transacciones**: Operaciones seguras

## 📝 Notas Importantes

1. **Triggers**: Los triggers están configurados para funcionar automáticamente
2. **Stock**: El stock se actualiza automáticamente con cada compra/venta
3. **Alertas**: Las alertas se generan automáticamente según los triggers
4. **Validaciones**: Todas las operaciones tienen validaciones completas
5. **Transacciones**: Las operaciones críticas usan transacciones de base de datos

## 🚀 Próximos Pasos

1. **Vistas**: Crear las vistas Blade para cada funcionalidad
2. **Autenticación**: Implementar sistema de login
3. **Reportes**: Crear reportes avanzados
4. **API**: Crear API REST para integraciones
5. **Testing**: Implementar pruebas unitarias

## 📞 Soporte

Para cualquier duda o problema, revisar:
1. Los logs de Laravel en `storage/logs/`
2. Los logs de MySQL en XAMPP
3. La configuración de la base de datos
4. Los triggers en phpMyAdmin

---

**¡Sistema Farmacéutico listo para usar! 🎉**
<?php
// Script para probar la conexión a MySQL
echo "Probando conexión a MySQL...\n";

try {
    $host = '127.0.0.1';
    $port = 3307;
    $dbname = 'farmaceutica';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Conexión exitosa a MySQL!\n";
    echo "Host: $host\n";
    echo "Puerto: $port\n";
    echo "Base de datos: $dbname\n";
    echo "Usuario: $username\n";
    
    // Probar una consulta simple
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Consulta de prueba exitosa: " . $result['test'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    echo "\nPosibles soluciones:\n";
    echo "1. Verificar que XAMPP esté ejecutándose\n";
    echo "2. Verificar que MySQL esté en el puerto 3306\n";
    echo "3. Verificar que la base de datos 'farmaceutica' exista\n";
    echo "4. Verificar las credenciales de MySQL\n";
}

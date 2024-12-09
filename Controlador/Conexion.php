<?php

// Archivo config.php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'apirest');

// Función para obtener la conexión
function getDatabaseConnection() {
    $conexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Manejo de errores
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }

    // Asegurar el uso de UTF-8
    $conexion->set_charset("utf8mb4");

    return $conexion;
}

// Comprobar la conexión
try {
    $pdo = new PDO('mysql:host=localhost;dbname=apirest', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

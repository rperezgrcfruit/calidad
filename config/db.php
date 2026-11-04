<?php

// ============================================================
// CONEXIÓN A BASE DE DATOS
// ============================================================
// Todos los datos se leen del archivo .env:
//   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_CHARSET
// ============================================================

require_once __DIR__ . '/config.php';

$dbHost    = (string) env('DB_HOST', 'localhost');
$dbPort    = (int)    env('DB_PORT', 3306);
$dbName    = (string) env('DB_NAME', 'grcfruit_grc');
$dbUser    = (string) env('DB_USER', 'root');
$dbPass    = (string) env('DB_PASS', '');
$dbCharset = (string) env('DB_CHARSET', 'utf8');

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

    if ($conn->connect_errno) {
        throw new RuntimeException('mysqli: ' . $conn->connect_error);
    }

    $conn->set_charset($dbCharset);
} catch (Throwable $e) {
    // Se registra el detalle en el log del servidor, no en pantalla
    error_log('[DB] Error de conexión: ' . $e->getMessage());

    http_response_code(500);
    die('No se pudo conectar a la base de datos. Verifique la configuración del archivo .env');
}

// Variable global usada por los controladores y modelos
$conexion = $conn;

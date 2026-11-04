<?php

// ============================================================
// CONFIGURACIÓN BÁSICA DEL PROYECTO
// ============================================================
// Los valores sensibles y dependientes del servidor se leen
// del archivo .env (ver .env.example). Si .env no existe, se
// usan valores detectados automáticamente.
// ============================================================

require_once __DIR__ . '/env.php';

// ------------------------------------------------------------
// Entorno de ejecución: local | production
// ------------------------------------------------------------
if (!defined('ENVIRONMENT')) {
    $entorno = env('APP_ENV');

    if ($entorno === null || $entorno === '') {
        // Auto-detección: Windows/XAMPP => local, resto => producción
        $entorno = (PHP_OS_FAMILY === 'Windows' || is_dir('C:/xampp')) ? 'local' : 'production';
    }

    $entorno = strtolower(trim((string) $entorno));
    if (!in_array($entorno, ['local', 'production', 'staging'], true)) {
        $entorno = 'production';
    }

    define('ENVIRONMENT', $entorno);
}

// ------------------------------------------------------------
// RUTA: carpeta que contiene la carpeta del proyecto ("calidad")
//   Local:  C:/xampp/htdocs
//   VPS:    /home/grcfruit/public_html
// ------------------------------------------------------------
if (!defined('RUTA')) {
    $ruta = env('APP_PATH');

    if ($ruta === null || $ruta === '') {
        // dirname(__DIR__) = .../calidad ; un nivel más arriba = raíz web
        $ruta = dirname(dirname(__DIR__));
    }

    define('RUTA', rtrim(str_replace('\\', '/', $ruta), '/'));
}

// ------------------------------------------------------------
// BASE_URL: URL base de la aplicación (para redirecciones)
// ------------------------------------------------------------
if (!defined('BASE_URL')) {
    $url = env('APP_URL');

    if ($url === null || $url === '') {
        $host = $_SERVER['HTTP_HOST'] ?? '';

        if ($host !== '') {
            $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (($_SERVER['SERVER_PORT'] ?? '') == 443)
                || ((($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'));

            $url = ($https ? 'https://' : 'http://') . $host . '/calidad';
        } else {
            $url = '/calidad';
        }
    }

    define('BASE_URL', rtrim($url, '/'));
}

// ------------------------------------------------------------
// Utilidades de entorno
// ------------------------------------------------------------
if (!function_exists('is_https')) {
    function is_https()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') == 443)
            || ((($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'));
    }
}

if (!function_exists('is_production')) {
    function is_production()
    {
        return (defined('ENVIRONMENT') && ENVIRONMENT === 'production') && is_https();
    }
}

// ============================================================
// TOKEN CSRF
// ============================================================
if (!function_exists('generateCSRFToken')) {
    function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function validateCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

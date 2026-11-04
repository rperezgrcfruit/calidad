<?php

/**
 * ============================================================
 * CARGADOR DE VARIABLES DE ENTORNO (.env)
 * ============================================================
 * Lee el archivo .env de la raíz del proyecto (si existe) y
 * expone los valores mediante la función env().
 *
 * No sobrescribe variables ya definidas en el entorno real
 * (por ejemplo, las inyectadas por Apache o por el hosting).
 */

if (!function_exists('env')) {

    $GLOBALS['__ENV'] = $GLOBALS['__ENV'] ?? [];

    /**
     * Carga un archivo .env en memoria.
     * Formato: CLAVE=valor  (líneas con # son comentarios)
     */
    function env_load_file($path)
    {
        if (!is_string($path) || !is_readable($path)) {
            return;
        }

        $lineas = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lineas === false) {
            return;
        }

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Comentarios y líneas vacías
            if ($linea === '' || $linea[0] === '#') {
                continue;
            }

            $pos = strpos($linea, '=');
            if ($pos === false) {
                continue;
            }

            $clave = trim(substr($linea, 0, $pos));
            $valor = trim(substr($linea, $pos + 1));

            // Quitar comillas envolventes si las hay
            $largo = strlen($valor);
            if ($largo >= 2) {
                $ini = $valor[0];
                $fin = $valor[$largo - 1];
                if (($ini === '"' && $fin === '"') || ($ini === "'" && $fin === "'")) {
                    $valor = substr($valor, 1, -1);
                }
            }

            if ($clave === '' || array_key_exists($clave, $GLOBALS['__ENV'])) {
                continue;
            }

            $GLOBALS['__ENV'][$clave] = $valor;
            $_ENV[$clave] = $valor;
            $_SERVER[$clave] = $valor;

            if (function_exists('putenv') && getenv($clave) === false) {
                @putenv($clave . '=' . $valor);
            }
        }
    }

    /**
     * Obtiene una variable de entorno (o el valor por defecto).
     */
    function env($clave, $default = null)
    {
        if (array_key_exists($clave, $GLOBALS['__ENV'])) {
            return $GLOBALS['__ENV'][$clave];
        }

        $valor = getenv($clave);
        if ($valor !== false) {
            return $valor;
        }

        return $default;
    }

    /**
     * Interpreta una variable de entorno como booleano.
     */
    function env_bool($clave, $default = false)
    {
        $valor = env($clave);
        if ($valor === null) {
            return (bool) $default;
        }

        return in_array(strtolower(trim((string) $valor)), ['1', 'true', 'yes', 'on', 'si', 'sí'], true);
    }

    // Cargar el .env ubicado en la raíz del proyecto
    env_load_file(dirname(__DIR__) . '/.env');
}

<?php

/**
 * ============================================================
 * PAGINACIÓN
 * ============================================================
 * Funciones auxiliares para dividir listados largos en páginas.
 */

if (!function_exists('paginar')) {
    /**
     * Calcula los datos de paginación.
     *
     * @param int $total      Total de registros
     * @param int $porPagina  Registros por página
     * @param int $pagina     Página solicitada (1-based)
     * @return array{total:int,porPagina:int,paginas:int,pagina:int,offset:int,desde:int,hasta:int}
     */
    function paginar($total, $porPagina = 25, $pagina = 1)
    {
        $total     = max(0, (int) $total);
        $porPagina = max(1, (int) $porPagina);

        $paginas = max(1, (int) ceil($total / $porPagina));

        $pagina = (int) $pagina;
        if ($pagina < 1) {
            $pagina = 1;
        }
        if ($pagina > $paginas) {
            $pagina = $paginas;
        }

        $offset = ($pagina - 1) * $porPagina;

        return [
            'total'     => $total,
            'porPagina' => $porPagina,
            'paginas'   => $paginas,
            'pagina'    => $pagina,
            'offset'    => $offset,
            'desde'     => $total === 0 ? 0 : $offset + 1,
            'hasta'     => min($offset + $porPagina, $total),
        ];
    }
}

if (!function_exists('pag_enlace')) {
    /**
     * Construye la URL de una página conservando los filtros actuales.
     */
    function pag_enlace($base, array $params, $pagina)
    {
        $params = array_filter($params, function ($valor) {
            return $valor !== '' && $valor !== null;
        });

        $params['pagina'] = (int) $pagina;

        return $base . '?' . http_build_query($params);
    }
}

if (!function_exists('pag_rango')) {
    /**
     * Devuelve el rango de números de página a mostrar.
     * Ej: 1 … 4 5 [6] 7 8 … 20
     */
    function pag_rango($paginaActual, $totalPaginas, $ventana = 2)
    {
        $inicio = max(1, $paginaActual - $ventana);
        $fin    = min($totalPaginas, $paginaActual + $ventana);

        // Siempre mostrar al menos 5 números cuando existan
        if ($fin - $inicio < 4) {
            if ($inicio === 1) {
                $fin = min($totalPaginas, 5);
            } elseif ($fin === $totalPaginas) {
                $inicio = max(1, $totalPaginas - 4);
            }
        }

        return range($inicio, $fin);
    }
}

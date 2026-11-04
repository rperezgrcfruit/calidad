<?php

class ControllerBodegaQuimicos
{
    private $model;
    
    public function __construct()
    {
        $this->model = new ModeloBodegaQuimicos();
    }
    
    // ============================================================
    // MANTENIMIENTO MAESTRO DE QUIMICOS
    // ============================================================
    
    /**
     * Dashboard principal - mostrar estadísticas y accesos rápidos
     */
    public function dashboard($conexion)
    {
        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);
        include_once RUTA . '/calidad/view/bodega/dashboard-quimicos.php';
    }
    
    /**
     * Lista de quimicos (mantenimiento)
     */
    public function listaQuimicos($conexion)
    {
        $total = $this->model->contarQuimicos($conexion);
        $pag = paginar($total, 25, $_GET['pagina'] ?? 1);

        $quimicos = $this->model->obtenerQuimicosPaginados($conexion, $pag['porPagina'], $pag['offset']);

        include_once RUTA . '/calidad/view/bodega/quimicos-mantenimiento.php';
    }
    
    /**
     * Lista de bodegas
     */
    public function listaBodegas($conexion)
    {
        $bodegas = $this->model->obtenerBodegas($conexion);
        include_once RUTA . '/calidad/view/bodega/bodegas-mantenimiento.php';
    }
    
    /**
     * Lista de proveedores
     */
    public function listaProveedores($conexion)
    {
        $proveedores = $this->model->obtenerProveedores($conexion);
        include_once RUTA . '/calidad/view/bodega/proveedores-mantenimiento.php';
    }
    
    /**
     * Buscar quimico por código
     */
    public function buscarQuimico($conexion, $codigo)
    {
        return $this->model->buscarQuimico($conexion, $codigo);
    }
    
    // ============================================================
    // RECEPCIONES DE QUIMICOS
    // ============================================================
    
    /**
     * Lista de recepciones con filtros
     */
    public function listaRecepciones($conexion)
    {
        $filtros = $this->filtrosRecepciones($conexion);

        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);

        // Paginación conservando filtros
        $total = $this->model->contarRecepciones($conexion, $filtros);
        $pag = paginar($total, 20, $_GET['pagina'] ?? 1);

        $recepciones = $this->model->obtenerRecepciones($conexion, $filtros, $pag['porPagina'], $pag['offset']);
        $resumen = $this->model->resumenRecepciones($conexion, $filtros);

        $pagParams = array_filter([
            'desde'        => $filtros['desde'],
            'hasta'        => $filtros['hasta'],
            'bodega'       => $filtros['bodega'],
            'proveedor_id' => $filtros['proveedor_id'] ?: '',
        ], function ($v) {
            return $v !== '' && $v !== null;
        });

        $mensaje = isset($_GET['msg']) ? substr((string) $_GET['msg'], 0, 200) : '';

        include_once RUTA . '/calidad/view/bodega/recepciones-quimicos.php';
    }
    
    /**
     * Nuevo registro de recepcion
     */
    public function nuevoRegistro($conexion)
    {
        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);

        $bodegaSel = $_GET['bodega'] ?? ($bodegas[0]['codigo'] ?? '');
        $catalogo = $this->model->obtenerCatalogo($conexion, $bodegaSel);

        // Errores y datos previos (si viene de una validación fallida)
        $errores = $_SESSION['quimicos_errores'] ?? [];
        $old = $_SESSION['quimicos_old'] ?? [];
        unset($_SESSION['quimicos_errores'], $_SESSION['quimicos_old']);

        include_once RUTA . '/calidad/view/bodega/formulario-nueva-recepcion.php';
    }
    
    /**
     * Guardar recepcion
     */
    public function guardarRecepcion($conexion)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigirRecepciones();
        }

        $errores = [];

        // --- CSRF ---
        if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
            $errores[] = 'Sesión expirada. Vuelva a intentarlo.';
        }

        // --- Cabecera ---
        $bodega        = trim((string) ($_POST['bodega'] ?? ''));
        $guia          = trim((string) ($_POST['guia'] ?? ''));
        $factura       = trim((string) ($_POST['factura'] ?? ''));
        $lote          = trim((string) ($_POST['lote_proveedor'] ?? ''));
        $observaciones = trim((string) ($_POST['observaciones'] ?? ''));
        $proveedorId   = (int) ($_POST['proveedor_id'] ?? 0);
        $proveedorLibre = trim((string) ($_POST['proveedor_libre'] ?? ''));

        // Bodega existente
        $bodegas = $this->model->obtenerBodegas($conexion);
        $bodegaValida = '';
        foreach ($bodegas as $b) {
            if ($b['codigo'] === $bodega) {
                $bodegaValida = $b['codigo'];
                break;
            }
        }
        if ($bodegaValida === '') {
            $errores[] = 'Seleccione una bodega válida.';
        }

        if ($guia === '') {
            $errores[] = 'Ingrese el número de GUIA.';
        } elseif (mb_strlen($guia) > 20) {
            $errores[] = 'La GUIA no puede superar los 20 caracteres.';
        }

        // Proveedor: catálogo o texto libre
        $proveedor = '';
        if ($proveedorId > 0) {
            foreach ($this->model->obtenerProveedores($conexion) as $p) {
                if ((int) $p['id'] === $proveedorId) {
                    $proveedor = $p['nombre'];
                    break;
                }
            }
            if ($proveedor === '') {
                $errores[] = 'El proveedor seleccionado no existe.';
            }
        } else {
            $proveedor = function_exists('mb_strtoupper')
                ? mb_strtoupper($proveedorLibre, 'UTF-8')
                : strtoupper($proveedorLibre);
            if ($proveedor === '') {
                $errores[] = 'Ingrese un proveedor (catálogo o texto libre).';
            }
        }

        if (mb_strlen($proveedor) > 100) {
            $errores[] = 'El proveedor no puede superar los 100 caracteres.';
        }
        if (mb_strlen($factura) > 20 || mb_strlen($lote) > 40 || mb_strlen($observaciones) > 255) {
            $errores[] = 'Factura, lote u observaciones exceden el largo permitido.';
        }

        // --- Detalle ---
        $itemsRecibidos = $_POST['items'] ?? [];
        $items = [];
        $codigosVistos = [];

        if (!is_array($itemsRecibidos) || count($itemsRecibidos) === 0) {
            $errores[] = 'Agregue al menos un químico a la recepción.';
        } else {
            $catalogo = [];
            foreach ($this->model->obtenerCatalogo($conexion, $bodegaValida) as $q) {
                $catalogo[$q['codigo']] = $q;
            }

            $unidades = ['LT', 'KG', 'GR', 'ML', 'UND'];

            foreach ($itemsRecibidos as $fila) {
                $codigo = trim((string) ($fila['quimico_codigo'] ?? ''));

                if ($codigo === '' || !isset($catalogo[$codigo])) {
                    $errores[] = 'Hay un químico inválido en la recepción.';
                    continue;
                }
                if (isset($codigosVistos[$codigo])) {
                    $errores[] = 'El químico ' . $codigo . ' está repetido.';
                    continue;
                }
                $codigosVistos[$codigo] = true;

                $cantEnvases = (float) str_replace(',', '.', (string) ($fila['cant_envases'] ?? 0));
                $contenido   = (float) str_replace(',', '.', (string) ($fila['contenido_envase'] ?? 0));
                $unidad      = trim((string) ($fila['unidad'] ?? ''));

                if ($unidad === '' || !in_array($unidad, $unidades, true)) {
                    $unidad = $catalogo[$codigo]['unidad_medida'] ?? '';
                }
                if ($unidad === '' || !in_array($unidad, $unidades, true)) {
                    $errores[] = 'El químico ' . $codigo . ' no tiene una unidad válida.';
                    continue;
                }

                if ($cantEnvases <= 0) {
                    $errores[] = 'La cantidad de envases de ' . $codigo . ' debe ser mayor a 0.';
                    continue;
                }
                if ($contenido <= 0) {
                    $errores[] = 'El contenido por envase de ' . $codigo . ' debe ser mayor a 0.';
                    continue;
                }

                $items[] = [
                    'quimico_codigo'   => $codigo,
                    'cant_envases'     => round($cantEnvases, 2),
                    'contenido_envase' => round($contenido, 2),
                    'unidad'           => $unidad,
                ];
            }

            if (empty($items) && empty($errores)) {
                $errores[] = 'Agregue al menos un químico válido a la recepción.';
            }
        }

        // --- Errores: volver al formulario ---
        if (!empty($errores)) {
            $_SESSION['quimicos_errores'] = $errores;
            $_SESSION['quimicos_old'] = $_POST;
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones/nuevo');
            exit;
        }

        // --- Montos ---
        $netoClp  = $this->monto($_POST['neto_clp'] ?? 0);
        $ivaClp   = $this->monto($_POST['iva_clp'] ?? 0);
        $totalClp = $this->monto($_POST['total_clp'] ?? 0);

        if ($netoClp > 0 && $ivaClp <= 0) {
            $ivaClp = round($netoClp * 0.19);
        }
        if ($totalClp <= 0) {
            $totalClp = $netoClp + $ivaClp;
        }

        $netoUsd  = $this->monto($_POST['neto_usd'] ?? 0);
        $totalUsd = $this->monto($_POST['total_usd'] ?? 0);
        if ($netoUsd > 0 && $totalUsd <= 0) {
            $totalUsd = $netoUsd;
        }

        $cabecera = [
            'fecha'           => date('Y-m-d'),
            'guia'            => $guia,
            'factura'         => $factura !== '' ? $factura : null,
            'proveedor'       => $proveedor,
            'lote_proveedor'  => $lote !== '' ? $lote : null,
            'bodega'          => $bodegaValida,
            'observaciones'   => $observaciones !== '' ? $observaciones : null,
            'neto_clp'        => $netoClp,
            'iva_clp'         => $ivaClp,
            'total_clp'       => $totalClp,
            'neto_usd'        => $netoUsd,
            'total_usd'       => $totalUsd,
        ];

        $nRecepcion = $this->model->crearRecepcion($conexion, $cabecera, $items);

        if (!$nRecepcion) {
            $_SESSION['quimicos_errores'] = ['No se pudo registrar la recepción. Intente nuevamente.'];
            $_SESSION['quimicos_old'] = $_POST;
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones/nuevo');
            exit;
        }

        header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones/detalle?recepcion=' . $nRecepcion
            . '&msg=' . urlencode('Recepción #' . $nRecepcion . ' registrada correctamente'));
        exit;
    }
    
    /**
     * Ver detalle de recepcion
     */
    public function verDetalle($conexion)
    {
        $nRecepcion = (int) ($_GET['recepcion'] ?? 0);

        $recepcion = $nRecepcion > 0 ? $this->model->obtenerRecepcion($conexion, $nRecepcion) : false;

        if (!$recepcion) {
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones?msg=' . urlencode('La recepción solicitada no existe'));
            exit;
        }

        $detalle = $this->model->obtenerDetalleRecepcion($conexion, $nRecepcion) ?: [];
        $mensaje = isset($_GET['msg']) ? substr((string) $_GET['msg'], 0, 200) : '';

        include_once RUTA . '/calidad/view/bodega/detalle-recepcion.php';
    }

    // ============================================================
    // FUNCIONES AUXILIARES
    // ============================================================

    /**
     * Filtros del listado: fechas, bodega y proveedor (0=todos, -1=libre, >0=catálogo)
     */
    private function filtrosRecepciones($conexion)
    {
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        // Normalizar fechas (input date entrega Y-m-d)
        $desde = preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde) ? $desde : date('Y-m-01');
        $hasta = preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta) ? $hasta : date('Y-m-d');

        $proveedorId = (int) ($_GET['proveedor_id'] ?? 0);
        $proveedorNombre = '';

        if ($proveedorId > 0) {
            foreach ($this->model->obtenerProveedores($conexion) as $p) {
                if ((int) $p['id'] === $proveedorId) {
                    $proveedorNombre = $p['nombre'];
                    break;
                }
            }
            if ($proveedorNombre === '') {
                $proveedorId = 0;
            }
        }

        return [
            'desde'            => $desde,
            'hasta'            => $hasta,
            'bodega'           => trim((string) ($_GET['bodega'] ?? '')),
            'proveedor_id'     => $proveedorId,
            'proveedor_nombre' => $proveedorNombre,
        ];
    }

    /**
     * Normaliza un monto escrito como "1.234,56" o "1234.56"
     */
    private function monto($valor)
    {
        $s = trim((string) $valor);

        if ($s === '') {
            return 0.0;
        }

        $s = str_replace(['$', ' ', "\xC2\xA0"], '', $s);

        if (strpos($s, ',') !== false) {
            // Formato chileno: punto = miles, coma = decimal
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $s)) {
            // Solo puntos separando miles
            $s = str_replace('.', '', $s);
        }

        return is_numeric($s) ? round((float) $s, 2) : 0.0;
    }

    private function redirigirRecepciones()
    {
        header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
        exit;
    }
}
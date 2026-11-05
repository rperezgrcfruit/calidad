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
        $total = $this->model->contarQuimicos($conexion);        $pag = paginar($total, 25, $_GET['pagina'] ?? 1);

        $quimicos = $this->model->obtenerQuimicosPaginados($conexion, $pag['porPagina'], $pag['offset']);

        [$mensaje, $tipoMensaje] = $this->flash();

        include_once RUTA . '/calidad/view/bodega/quimicos-mantenimiento.php';
    }
    
    /**
     * Lista de bodegas
     */
    public function listaBodegas($conexion)
    {
        $bodegas = $this->model->obtenerBodegas($conexion);

        [$mensaje, $tipoMensaje] = $this->flash();

        include_once RUTA . '/calidad/view/bodega/bodegas-mantenimiento.php';
    }
    
    /**
     * Lista de proveedores
     */
    public function listaProveedores($conexion)
    {
        $proveedores = $this->model->obtenerProveedores($conexion);

        [$mensaje, $tipoMensaje] = $this->flash();

        include_once RUTA . '/calidad/view/bodega/proveedores-mantenimiento.php';
    }

    // ============================================================
    // INFORMES
    // ============================================================

    /**
     * Menú de informes
     */
    public function informes($conexion)
    {
        include_once RUTA . '/calidad/view/bodega/informes-quimicos.php';
    }

    /**
     * Informe: acumulados de recepción de químicos
     */
    public function informeAcumulados($conexion)
    {
        [$filtros, $agrupar] = $this->parametrosInforme($conexion);

        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);

        $filas = $this->model->acumuladosRecepcion($conexion, $filtros, $agrupar);

        $totales = [
            'recepciones' => $this->model->contarRecepciones($conexion, $filtros),
            'envases'     => array_sum(array_column($filas, 'envases')),
            'contenido'   => array_sum(array_column($filas, 'contenido')),
            'cantidad'    => array_sum(array_column($filas, 'cantidad')),
        ];

        include_once RUTA . '/calidad/view/bodega/informe-acumulados.php';
    }

    /**
     * Informe: existencia de químicos por bodega
     */
    public function informeExistencias($conexion)
    {
        $bodega = trim((string) ($_GET['bodega'] ?? ''));
        $buscar = trim((string) ($_GET['q'] ?? ''));
        if (function_exists('mb_substr')) {
            $buscar = mb_substr($buscar, 0, 60, 'UTF-8');
        }
        $soloConStock = !isset($_GET['todos']);

        $bodegas = $this->model->obtenerBodegas($conexion);
        $filas = $this->model->existencias($conexion, $bodega, $buscar, $soloConStock);

        // Totales por unidad de medida
        $totalesUnidad = [];
        foreach ($filas as $f) {
            $u = $f['unidad'] ?: 'S/U';
            $totalesUnidad[$u] = ($totalesUnidad[$u] ?? 0) + (float) $f['cantidad'];
        }

        include_once RUTA . '/calidad/view/bodega/informe-existencia.php';
    }

    /**
     * Exporta el informe de acumulados a CSV
     */
    public function exportarAcumulados($conexion)
    {
        [$filtros, $agrupar] = $this->parametrosInforme($conexion);
        $filas = $this->model->acumuladosRecepcion($conexion, $filtros, $agrupar);

        $this->descargarCsv('acumulados_quimicos', [
            'Agrupación', 'Clave', 'Descripción', 'Detalle',
            'Recepciones', 'Envases', 'Contenido', 'Cantidad total'
        ], array_map(function ($f) use ($agrupar) {
            return [
                $agrupar,
                $f['clave'],
                $f['descripcion'],
                $f['detalle'],
                (int) $f['recepciones'],
                number_format((float) $f['envases'], 2, ',', ''),
                number_format((float) $f['contenido'], 2, ',', ''),
                number_format((float) $f['cantidad'], 2, ',', ''),
            ];
        }, $filas));
    }

    /**
     * Exporta el informe de existencias a CSV
     */
    public function exportarExistencias($conexion)
    {
        $bodega = trim((string) ($_GET['bodega'] ?? ''));
        $buscar = trim((string) ($_GET['q'] ?? ''));
        $soloConStock = !isset($_GET['todos']);

        $filas = $this->model->existencias($conexion, $bodega, $buscar, $soloConStock);

        $this->descargarCsv('existencias_quimicos', [
            'Código', 'Nombre', 'Tipo', 'Bodega', 'Unidad', 'Cantidad', 'Actualizado'
        ], array_map(function ($f) {
            return [
                $f['quimico_codigo'],
                $f['nombre'],
                $f['tipo'],
                $f['bodega_nombre'],
                $f['unidad'],
                number_format((float) $f['cantidad'], 2, ',', ''),
                $f['actualizado'],
            ];
        }, $filas));
    }

    /**
     * Parámetros de los informes (fechas, bodega, proveedor y agrupación)
     *
     * @return array [array $filtros, string $agrupar]
     */
    private function parametrosInforme($conexion)
    {
        $desde = $_GET['desde'] ?? date('Y-01-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $desde = preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde) ? $desde : date('Y-01-01');
        $hasta = preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta) ? $hasta : date('Y-m-d');

        if ($desde > $hasta) {
            [$desde, $hasta] = [$hasta, $desde];
        }

        $bodega = trim((string) ($_GET['bodega'] ?? ''));

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

        $agrupar = $_GET['agrupar'] ?? 'quimico';
        if (!in_array($agrupar, ['quimico', 'bodega', 'proveedor', 'mes'], true)) {
            $agrupar = 'quimico';
        }

        return [[
            'desde'            => $desde,
            'hasta'            => $hasta,
            'bodega'           => $bodega,
            'proveedor_id'     => $proveedorId,
            'proveedor_nombre' => $proveedorNombre,
        ], $agrupar];
    }

    /**
     * Envía un CSV (UTF-8 con BOM, separador ";") al navegador
     */
    private function descargarCsv($prefijo, array $encabezados, array $filas)
    {
        $nombre = $prefijo . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Cache-Control: no-store');

        $salida = fopen('php://output', 'w');
        fwrite($salida, "\xEF\xBB\xBF"); // BOM para Excel
        fputcsv($salida, $encabezados, ';');

        foreach ($filas as $fila) {
            fputcsv($salida, $fila, ';');
        }

        fclose($salida);
        exit;
    }
    
    /**
     * Buscar quimico por código
     */
    public function buscarQuimico($conexion, $codigo)
    {
        return $this->model->buscarQuimico($conexion, $codigo);
    }

    // ============================================================
    // MANTENEDORES: QUIMICOS, BODEGAS Y PROVEEDORES
    // ============================================================

    /**
     * Crear / editar / dar de baja un químico
     */
    public function guardarQuimico($conexion)
    {
        $this->soloPost();

        if (!$this->csrfValido()) {
            $this->volver('/bodega-quimicos/quimicos', 'Sesión expirada. Intente nuevamente.', 'danger');
        }

        $accion = $_POST['accion'] ?? 'crear';
        $codigo = strtoupper(trim((string) ($_POST['codigo'] ?? '')));
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $tipo   = trim((string) ($_POST['tipo'] ?? ''));
        $unidad = trim((string) ($_POST['unidad_medida'] ?? ''));
        $sag    = trim((string) ($_POST['registro_sag'] ?? ''));

        if ($accion === 'eliminar') {
            if ($codigo === '') {
                $this->volver('/bodega-quimicos/quimicos', 'Código inválido.', 'danger');
            }
            $ok = $this->model->desactivarQuimico($conexion, $codigo);
            $this->volver('/bodega-quimicos/quimicos', $ok ? 'Químico dado de baja' : 'No se pudo dar de baja', $ok ? 'success' : 'danger');
        }

        $tipos = ['FUMIGANTE', 'FUNGICIDA', 'INSECTICIDA', 'HERBICIDA', 'FERTILIZANTE FOLIAR', 'DESVERTIDIZANTE', 'OTRO'];
        $unidades = ['LT', 'KG', 'GR', 'ML', 'UND'];

        if ($codigo === '' || $nombre === '') {
            $this->volver('/bodega-quimicos/quimicos', 'Código y nombre son obligatorios.', 'danger');
        }
        if (mb_strlen($codigo) > 10 || mb_strlen($nombre) > 100 || mb_strlen($sag) > 50) {
            $this->volver('/bodega-quimicos/quimicos', 'Largo de campos excedido.', 'danger');
        }
        if ($tipo !== '' && !in_array($tipo, $tipos, true)) {
            $this->volver('/bodega-quimicos/quimicos', 'Tipo de químico inválido.', 'danger');
        }
        if ($unidad !== '' && !in_array($unidad, $unidades, true)) {
            $this->volver('/bodega-quimicos/quimicos', 'Unidad inválida.', 'danger');
        }

        $datos = [
            'codigo'       => $codigo,
            'nombre'       => $nombre,
            'tipo'         => $tipo,
            'unidad_medida' => $unidad,
            'registro_sag' => $sag,
        ];

        if ($accion === 'editar') {
            $ok = $this->model->actualizarQuimico($conexion, $datos);
            $this->volver('/bodega-quimicos/quimicos', $ok ? 'Químico actualizado' : 'No se pudo actualizar', $ok ? 'success' : 'danger');
        }

        // crear
        if ($this->model->buscarQuimico($conexion, $codigo)) {
            $this->volver('/bodega-quimicos/quimicos', 'El código ' . $codigo . ' ya existe.', 'danger');
        }

        $ok = $this->model->insertarQuimico($conexion, $datos);
        $this->volver('/bodega-quimicos/quimicos', $ok ? 'Químico creado' : 'No se pudo crear el químico', $ok ? 'success' : 'danger');
    }

    /**
     * Crear / editar / eliminar una bodega
     */
    public function guardarBodega($conexion)
    {
        $this->soloPost();

        if (!$this->csrfValido()) {
            $this->volver('/bodega-quimicos/bodegas', 'Sesión expirada. Intente nuevamente.', 'danger');
        }

        $accion = $_POST['accion'] ?? 'crear';
        $codigo = strtoupper(trim((string) ($_POST['codigo'] ?? '')));
        $nombre = trim((string) ($_POST['nombre'] ?? ''));

        if ($accion === 'eliminar') {
            if ($codigo === '') {
                $this->volver('/bodega-quimicos/bodegas', 'Código inválido.', 'danger');
            }
            $ok = $this->model->eliminarBodega($conexion, $codigo);
            $this->volver('/bodega-quimicos/bodegas', $ok ? 'Bodega eliminada' : 'No se pudo eliminar', $ok ? 'success' : 'danger');
        }

        if ($codigo === '' || $nombre === '') {
            $this->volver('/bodega-quimicos/bodegas', 'Código y nombre son obligatorios.', 'danger');
        }
        if (mb_strlen($codigo) > 10 || mb_strlen($nombre) > 60) {
            $this->volver('/bodega-quimicos/bodegas', 'Largo de campos excedido.', 'danger');
        }

        if ($accion === 'editar') {
            $ok = $this->model->actualizarBodega($conexion, $codigo, $nombre);
            $this->volver('/bodega-quimicos/bodegas', $ok ? 'Bodega actualizada' : 'No se pudo actualizar', $ok ? 'success' : 'danger');
        }

        if ($this->model->buscarBodega($conexion, $codigo)) {
            $this->volver('/bodega-quimicos/bodegas', 'El código ' . $codigo . ' ya existe.', 'danger');
        }

        $ok = $this->model->insertarBodega($conexion, $codigo, $nombre);
        $this->volver('/bodega-quimicos/bodegas', $ok ? 'Bodega creada' : 'No se pudo crear la bodega', $ok ? 'success' : 'danger');
    }

    /**
     * Crear / editar / eliminar un proveedor
     */
    public function guardarProveedor($conexion)
    {
        $this->soloPost();

        if (!$this->csrfValido()) {
            $this->volver('/bodega-quimicos/proveedores', 'Sesión expirada. Intente nuevamente.', 'danger');
        }

        $accion = $_POST['accion'] ?? 'crear';
        $id = (int) ($_POST['id'] ?? 0);

        if ($accion === 'eliminar') {
            if ($id <= 0) {
                $this->volver('/bodega-quimicos/proveedores', 'Proveedor inválido.', 'danger');
            }
            $ok = $this->model->eliminarProveedor($conexion, $id);
            $this->volver('/bodega-quimicos/proveedores', $ok ? 'Proveedor eliminado' : 'No se pudo eliminar', $ok ? 'success' : 'danger');
        }

        $datos = [
            'id'        => $id,
            'codigo'    => trim((string) ($_POST['codigo'] ?? '')),
            'nombre'    => trim((string) ($_POST['nombre'] ?? '')),
            'contacto'  => trim((string) ($_POST['contacto'] ?? '')),
            'telefono'  => trim((string) ($_POST['telefono'] ?? '')),
            'email'     => trim((string) ($_POST['email'] ?? '')),
            'direccion' => trim((string) ($_POST['direccion'] ?? '')),
        ];

        if ($datos['nombre'] === '') {
            $this->volver('/bodega-quimicos/proveedores', 'El nombre es obligatorio.', 'danger');
        }
        if (mb_strlen($datos['codigo']) > 20 || mb_strlen($datos['nombre']) > 100
            || mb_strlen($datos['contacto']) > 100 || mb_strlen($datos['telefono']) > 20
            || mb_strlen($datos['email']) > 100 || mb_strlen($datos['direccion']) > 200) {
            $this->volver('/bodega-quimicos/proveedores', 'Largo de campos excedido.', 'danger');
        }
        if ($datos['email'] !== '' && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $this->volver('/bodega-quimicos/proveedores', 'El email no es válido.', 'danger');
        }

        if ($accion === 'editar') {
            if ($id <= 0 || !$this->model->obtenerProveedor($conexion, $id)) {
                $this->volver('/bodega-quimicos/proveedores', 'Proveedor no encontrado.', 'danger');
            }
            $ok = $this->model->actualizarProveedor($conexion, $datos);
            $this->volver('/bodega-quimicos/proveedores', $ok ? 'Proveedor actualizado' : 'No se pudo actualizar', $ok ? 'success' : 'danger');
        }

        $ok = $this->model->insertarProveedor($conexion, $datos);
        $this->volver('/bodega-quimicos/proveedores', $ok ? 'Proveedor creado' : 'No se pudo crear el proveedor', $ok ? 'success' : 'danger');
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
        $tipoMensaje = $_GET['tipo'] ?? 'success';
        if (!in_array($tipoMensaje, ['success', 'danger', 'warning', 'info'], true)) {
            $tipoMensaje = 'success';
        }

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

    // ============================================================
    // GUIA (PDF/imagen) - misma API que usa la aplicación VB.NET
    // ============================================================

    /**
     * Sube la GUIA de una recepción a la API de archivos
     */
    public function subirGuia($conexion)
    {
        $this->soloPost();

        $n = (int) ($_POST['recepcion'] ?? 0);
        $volver = '/bodega-quimicos/recepciones/detalle?recepcion=' . $n;

        if (!$this->csrfValido()) {
            $this->volver($volver, 'Sesión expirada. Intente nuevamente.', 'danger');
        }
        if ($n <= 0 || !$this->model->obtenerRecepcion($conexion, $n)) {
            $this->volver('/bodega-quimicos/recepciones', 'Recepción no encontrada.', 'danger');
        }

        if (!isset($_FILES['guia']) || $_FILES['guia']['error'] !== UPLOAD_ERR_OK) {
            $this->volver($volver, 'No se recibió el archivo (o excede el tamaño permitido).', 'danger');
        }

        $archivo = $_FILES['guia'];

        if ($archivo['size'] <= 0 || $archivo['size'] > 20 * 1024 * 1024) {
            $this->volver($volver, 'El archivo debe pesar entre 1 byte y 20 MB.', 'danger');
        }

        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'], true)) {
            $this->volver($volver, 'Formato no permitido. Use PDF, JPG o PNG.', 'danger');
        }

        // Nombre: quimicos_{n:06d}_{YYYYMMDD_HHMMSS}.{ext}
        $nombre = sprintf('quimicos_%06d_%s.%s', $n, date('Ymd_His'), $ext);
        $url = $this->apiUrl('uploadRecepcionQuimicos.php');

        $mime = function_exists('mime_content_type') ? (mime_content_type($archivo['tmp_name']) ?: 'application/octet-stream') : 'application/octet-stream';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => ['fileToUpload' => new CURLFile($archivo['tmp_name'], $mime, $nombre)],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_CONNECTTIMEOUT => 20,
        ]);

        $respuesta = curl_exec($ch);
        $httpCode  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorCurl = curl_error($ch);
        curl_close($ch);

        if ($respuesta === false) {
            $this->volver($volver, 'No se pudo conectar con el servidor de archivos: ' . $errorCurl, 'danger');
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            $this->volver($volver, 'El servidor de archivos respondió HTTP ' . $httpCode . '. ' . substr(trim((string) $respuesta), 0, 150), 'danger');
        }

        $this->volver($volver, 'GUIA subida correctamente: ' . $nombre, 'success');
    }

    /**
     * Muestra la última GUIA subida para una recepción (proxy del PDF)
     */
    public function verGuia($conexion)
    {
        $n = (int) ($_GET['recepcion'] ?? 0);
        $volver = '/bodega-quimicos/recepciones/detalle?recepcion=' . $n;

        if ($n <= 0 || !$this->model->obtenerRecepcion($conexion, $n)) {
            $this->volver('/bodega-quimicos/recepciones', 'Recepción no encontrada.', 'danger');
        }

        $prefijo = sprintf('quimicos_%06d', $n);

        // 1) Listar archivos: devuelve un nombre, una lista "a|b" o "NO_FILES"
        $lista = $this->apiGet('listRecepcionQuimicos.php?prefix=' . urlencode($prefijo), 30);
        $nombreArchivo = '';

        if ($lista['ok']) {
            $texto = trim($lista['body']);

            if (strpos($texto, '|') !== false) {
                $partes = explode('|', $texto);
                $nombreArchivo = trim((string) end($partes));
            } elseif ($texto !== '' && $texto !== 'NO_FILES') {
                $nombreArchivo = $texto;
            }
        }

        if ($nombreArchivo === '') {
            $this->volver($volver, 'No hay GUIA subida para esta recepción.', 'warning');
        }

        // 2) Descargar desde la API
        $descarga = $this->apiGet('downloadRecepcionQuimicos.php?fileName=' . urlencode($nombreArchivo), 120);

        if (!$descarga['ok'] || $descarga['body'] === '') {
            $this->volver($volver, 'No se pudo obtener la GUIA desde el servidor de archivos.', 'danger');
        }

        $nombreSeguro = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($nombreArchivo));
        $tipo = $descarga['type'] ?: (strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION)) === 'pdf' ? 'application/pdf' : 'application/octet-stream');

        header('Content-Type: ' . $tipo);
        header('Content-Disposition: inline; filename="' . $nombreSeguro . '"');
        header('Content-Length: ' . strlen($descarga['body']));
        header('Cache-Control: private, max-age=0, must-revalidate');

        echo $descarga['body'];
        exit;
    }

    /**
     * URL de la API de archivos (misma que usa la app VB.NET)
     */
    private function apiUrl($script)
    {
        $base = env('QUIMICOS_API_URL', 'http://app.grcfruit.cl/apis/backup_files');
        return rtrim($base, '/') . '/' . ltrim($script, '/');
    }

    /**
     * GET a la API de archivos
     * @return array{ok:bool,body:string,type:string,code:int}
     */
    private function apiGet($ruta, $timeout = 60)
    {
        $ch = curl_init($this->apiUrl($ruta));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => 20,
        ]);

        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $type = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        return [
            'ok'   => ($body !== false && $code >= 200 && $code < 300),
            'body' => $body === false ? '' : $body,
            'type' => $type,
            'code' => $code,
        ];
    }

    private function soloPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigirRecepciones();
        }
    }

    private function csrfValido()
    {
        return isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token']);
    }

    /**
     * Mensaje flash desde la URL
     * @return array [mensaje, tipo]
     */
    private function flash()
    {
        $mensaje = isset($_GET['msg']) ? substr((string) $_GET['msg'], 0, 200) : '';
        $tipo = $_GET['tipo'] ?? 'success';

        if (!in_array($tipo, ['success', 'danger', 'warning', 'info'], true)) {
            $tipo = 'success';
        }

        return [$mensaje, $tipo];
    }

    /**
     * Redirige con mensaje flash por query string
     */
    private function volver($ruta, $mensaje, $tipo = 'success')
    {
        $separador = (strpos($ruta, '?') !== false) ? '&' : '?';
        header('Location: ' . BASE_URL . $ruta . $separador . 'msg=' . urlencode($mensaje) . '&tipo=' . $tipo);
        exit;
    }
}
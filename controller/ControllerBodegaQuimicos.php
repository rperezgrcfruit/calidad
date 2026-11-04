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
        $filtros = [
            'desde' => $_GET['desde'] ?? date('Y-m-01'),
            'hasta' => $_GET['hasta'] ?? date('Y-m-d'),
            'bodega' => $_GET['bodega'] ?? '',
            'proveedor' => $_GET['proveedor'] ?? ''
        ];
        
        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);

        // Paginación conservando filtros
        $total = $this->model->contarRecepciones($conexion, $filtros);
        $pag = paginar($total, 20, $_GET['pagina'] ?? 1);
        $recepciones = $this->model->obtenerRecepcionesPaginadas($conexion, $filtros, $pag['porPagina'], $pag['offset']);
        $pagParams = $filtros;

        include_once RUTA . '/calidad/view/bodega/recepciones-quimicos.php';
    }
    
    /**
     * Nuevo registro de recepcion
     */
    public function nuevoRegistro($conexion)
    {
        $bodegas = $this->model->obtenerBodegas($conexion);
        $proveedores = $this->model->obtenerProveedores($conexion);
        $nuevaNumero = $this->getNumeroRecepcionNuevo($conexion);
        
        include_once RUTA . '/calidad/view/bodega/formulario-nueva-recepcion.php';
    }
    
    /**
     * Guardar recepcion
     */
    public function guardarRecepcion($conexion)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
            exit;
        }
        
        $datos = $_POST;
        $detalles = $datos['detalles'] ?? [];
        
        // Insertar recepcion
        $nRecepcion = $this->insertarRecepcion($conexion, $datos);
        
        // Insertar detalle si hay productos
        if (!empty($detalles)) {
            $this->model->insertarDetalleRecepcion($conexion, $detalles);
        }
        
        header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
        exit;
    }
    
    /**
     * Ver detalle de recepcion
     */
    public function verDetalle($conexion)
    {
        $nRecepcion = $_GET['recepcion'] ?? '';
        if (!$nRecepcion) {
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
            exit;
        }
        
        $detalle = $this->model->obtenerDetalleRecepcion($conexion, $nRecepcion);
        $recepcion = $this->obtenerCabeceraRecepcion($conexion, $nRecepcion);
        
        include_once RUTA . '/calidad/view/bodega/detalle-recepcion.php';
    }
    
    // ============================================================
    // FUNCIONES AUXILIARES
    // ============================================================
    
    private function getNumeroRecepcionNuevo($conexion)
    {
        $sql = "SELECT MAX(n_recepcion) as max_id FROM quimicos_recepcion";
        $result = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_assoc($result);
        
        $ano = date('Y');
        $mes = date('m');
        $maxId = $row['max_id'] ?? 0;
        
        // Formato: ANO-MES-NNNN (ej: 2025-01-0001)
        return $ano . '-' . $mes . '-' . str_pad(($maxId % 10000) + 1, 4, '0', STR_PAD_LEFT);
    }
    
    private function insertarRecepcion($conexion, $datos)
    {
        $nRecepcion = $this->getNumeroRecepcionNuevo($conexion);
        
        // Obtener datos de la recepcion calidad si existe
        $recepcionCalidad = $this->getRecepcionCalidadData($conexion, $datos['lote_proveedor']);
        
        $sql = "INSERT INTO quimicos_recepcion (
            n_recepcion, fecha, bodega, proveedor, guia, factura, lote_proveedor, observaciones
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssss',
            $nRecepcion,
            $datos['fecha'],
            $datos['bodega'],
            $recepcionCalidad['proveedor'] ?? $datos['proveedor'],
            $datos['guia'],
            $datos['factura'],
            $datos['lote_proveedor'],
            $datos['observaciones'] ?? ''
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        return $nRecepcion;
    }
    
    private function getRecepcionCalidadData($conexion, $lote)
    {
        $sql = "SELECT * FROM recepcion_calidad WHERE lote = ? LIMIT 1";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $lote);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return false;
    }
    
    private function obtenerCabeceraRecepcion($conexion, $nRecepcion)
    {
        $sql = "SELECT r.*, b.nombre AS bodega_nombre 
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega
                WHERE r.n_recepcion = ?";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $nRecepcion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        mysqli_stmt_close($stmt);
        return false;
    }
}
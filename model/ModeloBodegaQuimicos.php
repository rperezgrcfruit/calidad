<?php

class ModeloBodegaQuimicos
{
    // =============================
    // MANTENIMIENTO QUIMICOS MAESTRO
    // =============================
    
    /**
     * Buscar quimico por código
     */
    public function buscarQuimico($conexion, $codigo)
    {
        $sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag FROM quimicos WHERE codigo = ? LIMIT 1";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', strtoupper($codigo));
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        mysqli_stmt_close($stmt);
        return false;
    }
    
    /**
     * Insertar nuevo quimico
     */
    public function insertarQuimico($conexion, $datos)
    {
        $sql = "INSERT INTO quimicos (codigo, nombre, tipo, unidad_medida, registro_sag) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss',
            strtoupper($datos['codigo']),
            $datos['nombre'],
            $datos['tipo'],
            $datos['unidad_medida'],
            $datos['registro_sag']
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    
    /**
     * Eliminar quimico
     */
    public function eliminarQuimico($conexion, $codigo)
    {
        $sql = "DELETE FROM quimicos WHERE codigo = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', strtoupper($codigo));
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected > 0;
    }
    
    /**
     * Obtener todos los quimicos
     */
    public function obtenerQuimicos($conexion)
    {
        $sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag FROM quimicos ORDER BY codigo";
        $result = mysqli_query($conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Contar quimicos del catalogo
     */
    public function contarQuimicos($conexion)
    {
        $result = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM quimicos");
        $total = 0;

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $total = (int) $row['total'];
        }

        return $total;
    }

    /**
     * Obtener quimicos paginados
     */
    public function obtenerQuimicosPaginados($conexion, $limit, $offset)
    {
        $limit  = (int) $limit;
        $offset = (int) $offset;

        $sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag FROM quimicos ORDER BY codigo LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = [];

        if ($result && $result->num_rows > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_stmt_close($stmt);

        return $data;
    }
    
    // =============================
    // BODEGAS DE QUIMICOS
    // =============================
    
    /**
     * Obtener bodegas activas
     */
    public function obtenerBodegas($conexion)
    {
        $sql = "SELECT codigo, nombre FROM quimicos_bodegas WHERE activo = 1 ORDER BY codigo";
        $result = mysqli_query($conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Buscar bodega por código
     */
    public function buscarBodega($conexion, $codigo)
    {
        $sql = "SELECT codigo, nombre FROM quimicos_bodegas WHERE codigo = ? AND activo = 1 LIMIT 1";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $codigo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        mysqli_stmt_close($stmt);
        return false;
    }
    
    // =============================
    // PROVEEDORES DE QUIMICOS
    // =============================
    
    /**
     * Obtener proveedores activos
     */
    public function obtenerProveedores($conexion)
    {
        $sql = "SELECT id, codigo, nombre, contacto, telefono, email, direccion FROM quimicos_proveedores WHERE activo = 1 ORDER BY nombre";
        $result = mysqli_query($conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return [];
    }
    
    // =============================
    // RECEPCIONES DE QUIMICOS
    // =============================
    
    /**
     * Insertar nueva recepción de quimicos
     */
    public function insertarRecepcion($conexion, $datos)
    {
        $sql = "INSERT INTO quimicos_recepcion (
            fecha, bodega, proveedor, guia, factura, lote_proveedor, observaciones, n_recepcion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssss',
            $datos['fecha'],
            $datos['bodega'],
            $datos['proveedor'],
            $datos['guia'],
            $datos['factura'],
            $datos['lote_proveedor'],
            $datos['observaciones'],
            $datos['n_recepcion']
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    
    /**
     * Insertar detalle de recepcion
     */
    public function insertarDetalleRecepcion($conexion, $detalles)
    {
        $sql = "INSERT INTO quimicos_recepcion_detalle (
            n_recepcion, quimico_codigo, cant_envases, contenido_envase, cantidad_total, unidad
        ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conexion, $sql);
        
        foreach ($detalles as $detalle) {
            mysqli_stmt_bind_param($stmt, 'ssssss',
                $detalle['n_recepcion'],
                $detalle['quimico_codigo'],
                $detalle['cant_envases'],
                $detalle['contenido_envase'],
                $detalle['cantidad_total'],
                $detalle['unidad']
            );
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
        return true;
    }
    
    /**
     * Construye el WHERE de recepciones según filtros
     * @return array [string $where, array $params, string $types]
     */
    private function filtroRecepciones($filtros)
    {
        $where = " WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($filtros['desde'])) {
            $where .= " AND r.fecha >= ? ";
            $params[] = $filtros['desde'];
            $types .= 's';
        }

        if (!empty($filtros['hasta'])) {
            $where .= " AND r.fecha <= ? ";
            $params[] = $filtros['hasta'];
            $types .= 's';
        }

        if (!empty($filtros['bodega'])) {
            $where .= " AND r.bodega = ? ";
            $params[] = $filtros['bodega'];
            $types .= 's';
        }

        if (!empty($filtros['proveedor'])) {
            $where .= " AND r.proveedor = ? ";
            $params[] = $filtros['proveedor'];
            $types .= 's';
        }

        return [$where, $params, $types];
    }

    /**
     * Obtener recepciones con filtros
     */
    public function obtenerRecepciones($conexion, $filtros = [])
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $sql = "SELECT r.n_recepcion, r.fecha, b.nombre AS bodega_nombre, r.proveedor, r.guia, r.factura, r.lote_proveedor
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega"
                . $where .
                " ORDER BY r.fecha DESC, r.n_recepcion";
        
        $stmt = mysqli_prepare($conexion, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $data;
        }
        mysqli_stmt_close($stmt);
        return [];
    }

    /**
     * Contar recepciones según filtros
     */
    public function contarRecepciones($conexion, $filtros = [])
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $sql = "SELECT COUNT(*) AS total
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega" . $where;

        $stmt = mysqli_prepare($conexion, $sql);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $total = 0;
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $total = (int) $row['total'];
        }

        mysqli_stmt_close($stmt);

        return $total;
    }

    /**
     * Obtener recepciones paginadas con filtros
     */
    public function obtenerRecepcionesPaginadas($conexion, $filtros = [], $limit = 25, $offset = 0)
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $limit  = (int) $limit;
        $offset = (int) $offset;

        $sql = "SELECT r.n_recepcion, r.fecha, b.nombre AS bodega_nombre, r.proveedor, r.guia, r.factura, r.lote_proveedor
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega"
                . $where .
                " ORDER BY r.fecha DESC, r.n_recepcion LIMIT ? OFFSET ?";

        $stmt = mysqli_prepare($conexion, $sql);

        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = [];

        if ($result && $result->num_rows > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_stmt_close($stmt);

        return $data;
    }
    
    /**
     * Obtener detalle de recepcion
     */
    public function obtenerDetalleRecepcion($conexion, $nRecepcion)
    {
        $sql = "SELECT 
                    d.quimico_codigo AS codigo,
                    q.nombre AS nombre,
                    q.tipo AS tipo,
                    q.unidad_medida AS unidad_cat,
                    q.registro_sag AS reg_sag,
                    d.cant_envases,
                    d.contenido_envase,
                    d.cantidad_total,
                    d.unidad
                FROM quimicos_recepcion_detalle d
                LEFT JOIN quimicos q ON q.codigo = d.quimico_codigo
                WHERE d.n_recepcion = ?
                ORDER BY d.quimico_codigo";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $nRecepcion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $data;
        }
        mysqli_stmt_close($stmt);
        return false;
    }
    
    /**
     * Buscar lote en recepciones de quimicos
     */
    public function buscarLoteQuimicos($conexion, $lote)
    {
        $sql = "SELECT r.n_recepcion, r.fecha, b.nombre AS bodega_nombre, r.proveedor, r.guia, r.factura, r.lote_proveedor, r.observaciones
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega
                WHERE r.lote_proveedor = ? OR r.factura = ?
                ORDER BY r.fecha DESC";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $lote, $lote);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $data;
        }
        mysqli_stmt_close($stmt);
        return false;
    }
}
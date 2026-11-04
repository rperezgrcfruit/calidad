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
        $codigo = strtoupper(trim((string) $codigo));

        $sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag FROM quimicos WHERE codigo = ? LIMIT 1";
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
    
    /**
     * Insertar nuevo quimico
     */
    public function insertarQuimico($conexion, $datos)
    {
        $codigo = strtoupper((string) $datos['codigo']);

        $sql = "INSERT INTO quimicos (codigo, nombre, tipo, unidad_medida, registro_sag, activo) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss',
            $codigo,
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
     * Actualizar quimico existente (no cambia el código)
     */
    public function actualizarQuimico($conexion, $datos)
    {
        $codigo = strtoupper((string) $datos['codigo']);

        $sql = "UPDATE quimicos
                   SET nombre = ?, tipo = ?, unidad_medida = ?, registro_sag = ?
                 WHERE codigo = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss',
            $datos['nombre'],
            $datos['tipo'],
            $datos['unidad_medida'],
            $datos['registro_sag'],
            $codigo
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Baja lógica del químico (igual que el VB: activo = 0)
     */
    public function desactivarQuimico($conexion, $codigo)
    {
        $codigo = strtoupper((string) $codigo);

        $sql = "UPDATE quimicos SET activo = 0 WHERE codigo = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $codigo);
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
    
    /**
     * Insertar bodega
     */
    public function insertarBodega($conexion, $codigo, $nombre)
    {
        $sql = "INSERT INTO quimicos_bodegas (codigo, nombre, activo) VALUES (?, ?, 1)";
        $stmt = mysqli_prepare($conexion, $sql);
        $codigo = strtoupper(trim($codigo));
        mysqli_stmt_bind_param($stmt, 'ss', $codigo, $nombre);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Actualizar nombre de bodega
     */
    public function actualizarBodega($conexion, $codigo, $nombre)
    {
        $sql = "UPDATE quimicos_bodegas SET nombre = ? WHERE codigo = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $nombre, $codigo);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Eliminar bodega (igual que el VB: DELETE físico)
     */
    public function eliminarBodega($conexion, $codigo)
    {
        $sql = "DELETE FROM quimicos_bodegas WHERE codigo = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $codigo);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected > 0;
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
    
    /**
     * Buscar proveedor por id
     */
    public function obtenerProveedor($conexion, $id)
    {
        $sql = "SELECT id, codigo, nombre, contacto, telefono, email, direccion
                FROM quimicos_proveedores WHERE id = ? AND activo = 1 LIMIT 1";
        $stmt = mysqli_prepare($conexion, $sql);
        $id = (int) $id;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row ?: false;
    }

    /**
     * Insertar proveedor
     */
    public function insertarProveedor($conexion, $datos)
    {
        $sql = "INSERT INTO quimicos_proveedores (codigo, nombre, contacto, telefono, email, direccion, activo)
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss',
            $datos['codigo'],
            $datos['nombre'],
            $datos['contacto'],
            $datos['telefono'],
            $datos['email'],
            $datos['direccion']
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Actualizar proveedor por id
     */
    public function actualizarProveedor($conexion, $datos)
    {
        $sql = "UPDATE quimicos_proveedores
                   SET codigo = ?, nombre = ?, contacto = ?, telefono = ?, email = ?, direccion = ?
                 WHERE id = ? AND activo = 1";
        $stmt = mysqli_prepare($conexion, $sql);
        $id = (int) $datos['id'];
        mysqli_stmt_bind_param($stmt, 'ssssssi',
            $datos['codigo'],
            $datos['nombre'],
            $datos['contacto'],
            $datos['telefono'],
            $datos['email'],
            $datos['direccion'],
            $id
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    /**
     * Eliminar proveedor (igual que el VB: DELETE físico)
     */
    public function eliminarProveedor($conexion, $id)
    {
        $sql = "DELETE FROM quimicos_proveedores WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        $id = (int) $id;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected > 0;
    }

    // =============================
    // RECEPCIONES DE QUIMICOS
    // =============================
    
    /**
     * Catálogo de químicos activos con el stock de la bodega indicada
     */
    public function obtenerCatalogo($conexion, $bodega = '')
    {
        $sql = "SELECT q.codigo, q.nombre, q.tipo, q.unidad_medida, q.registro_sag,
                       IFNULL(e.cantidad, 0) AS stock_actual
                FROM quimicos q
                LEFT JOIN quimicos_existencia e
                       ON e.quimico_codigo = q.codigo AND e.bodega = ?
                WHERE q.activo = 1
                ORDER BY q.nombre";

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 's', $bodega);
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
     * Registrar una recepción completa: cabecera + detalle + stock, en una transacción.
     *
     * @param array $cabecera fecha, guia, factura, proveedor, lote_proveedor, bodega, observaciones y montos
     * @param array $items    [['quimico_codigo','cant_envases','contenido_envase','unidad'], ...]
     * @return int|false      Número de recepción generado
     */
    public function crearRecepcion($conexion, array $cabecera, array $items)
    {
        if (empty($items)) {
            return false;
        }

        $conexion->begin_transaction();

        try {
            $sqlCab = "INSERT INTO quimicos_recepcion
                        (fecha, guia, factura, proveedor, lote_proveedor, bodega, responsable,
                         observaciones, empresa, temporada,
                         neto_clp, iva_clp, total_clp, neto_usd, total_usd)
                       VALUES (?, ?, ?, ?, ?, ?, NULL, ?, NULL, NULL, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sqlCab);
            mysqli_stmt_bind_param(
                $stmt,
                'sssssssddddd',
                $cabecera['fecha'],
                $cabecera['guia'],
                $cabecera['factura'],
                $cabecera['proveedor'],
                $cabecera['lote_proveedor'],
                $cabecera['bodega'],
                $cabecera['observaciones'],
                $cabecera['neto_clp'],
                $cabecera['iva_clp'],
                $cabecera['total_clp'],
                $cabecera['neto_usd'],
                $cabecera['total_usd']
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $nRecepcion = (int) $conexion->insert_id;

            foreach ($items as $item) {
                $cantEnvases = (float) $item['cant_envases'];
                $contenido   = (float) $item['contenido_envase'];
                $total       = round($cantEnvases * $contenido, 2);

                if ($cantEnvases <= 0 || $contenido <= 0) {
                    throw new RuntimeException('Cantidad inválida para ' . $item['quimico_codigo']);
                }

                $stmtDet = mysqli_prepare($conexion, 'INSERT INTO quimicos_recepcion_detalle
                        (n_recepcion, quimico_codigo, cant_envases, contenido_envase, cantidad_total, unidad)
                        VALUES (?, ?, ?, ?, ?, ?)');
                mysqli_stmt_bind_param(
                    $stmtDet,
                    'isddds',
                    $nRecepcion,
                    $item['quimico_codigo'],
                    $cantEnvases,
                    $contenido,
                    $total,
                    $item['unidad']
                );
                mysqli_stmt_execute($stmtDet);
                mysqli_stmt_close($stmtDet);

                // Actualizar existencia (stock) de la bodega
                $stmtEx = mysqli_prepare($conexion, 'INSERT INTO quimicos_existencia (quimico_codigo, bodega, cantidad, unidad)
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad), unidad = VALUES(unidad)');
                mysqli_stmt_bind_param($stmtEx, 'ssds', $item['quimico_codigo'], $cabecera['bodega'], $total, $item['unidad']);
                mysqli_stmt_execute($stmtEx);
                mysqli_stmt_close($stmtEx);
            }

            $conexion->commit();

            return $nRecepcion;
        } catch (Throwable $e) {
            $conexion->rollback();
            error_log('[QUIMICOS] Error al crear recepción: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * SELECT base de recepciones (cabecera + totales calculados)
     */
    private function selectRecepciones()
    {
        return "SELECT r.n_recepcion, r.fecha,
                       COALESCE(b.nombre, r.bodega) AS bodega_nombre,
                       r.proveedor, r.guia, r.factura, r.lote_proveedor,
                       IFNULL(r.observaciones, '') AS observaciones,
                       (SELECT COUNT(*) FROM quimicos_recepcion_detalle d
                         WHERE d.n_recepcion = r.n_recepcion) AS items,
                       IFNULL((SELECT SUM(d.cantidad_total) FROM quimicos_recepcion_detalle d
                         WHERE d.n_recepcion = r.n_recepcion), 0) AS cantidad_total,
                       r.neto_clp, r.iva_clp, r.total_clp, r.neto_usd, r.total_usd
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega";
    }

    /**
     * WHERE de recepciones según filtros.
     * proveedor_id: 0 = todos, -1 = solo texto libre, >0 = proveedor del catálogo
     *
     * @return array [string $where, array $params, string $types]
     */
    private function filtroRecepciones($filtros)
    {
        $desde = !empty($filtros['desde']) ? $filtros['desde'] : '2000-01-01';
        $hasta = !empty($filtros['hasta']) ? $filtros['hasta'] : '2100-12-31';

        $where = " WHERE r.fecha BETWEEN ? AND ? ";
        $params = [$desde, $hasta];
        $types = 'ss';

        if (!empty($filtros['bodega'])) {
            $where .= " AND r.bodega = ? ";
            $params[] = $filtros['bodega'];
            $types .= 's';
        }

        $provId = isset($filtros['proveedor_id']) ? (int) $filtros['proveedor_id'] : 0;

        if ($provId === -1) {
            // Proveedores escritos a mano (no existen en el catálogo)
            $where .= " AND r.proveedor NOT IN (SELECT nombre FROM quimicos_proveedores WHERE activo = 1) ";
        } elseif ($provId > 0 && !empty($filtros['proveedor_nombre'])) {
            $where .= " AND r.proveedor = ? ";
            $params[] = $filtros['proveedor_nombre'];
            $types .= 's';
        }

        return [$where, $params, $types];
    }

    /**
     * Obtener recepciones con filtros (opcionalmente paginadas)
     */
    public function obtenerRecepciones($conexion, $filtros = [], $limit = null, $offset = 0)
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $sql = $this->selectRecepciones() . $where . " ORDER BY r.fecha DESC, r.n_recepcion DESC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int) $limit;
            $params[] = (int) $offset;
            $types .= 'ii';
        }

        $stmt = mysqli_prepare($conexion, $sql);
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
     * Contar recepciones según filtros
     */
    public function contarRecepciones($conexion, $filtros = [])
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $sql = "SELECT COUNT(*) AS total FROM quimicos_recepcion r" . $where;

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
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
     * Resumen del conjunto filtrado: recepciones, ítems y cantidad total
     */
    public function resumenRecepciones($conexion, $filtros = [])
    {
        list($where, $params, $types) = $this->filtroRecepciones($filtros);

        $sql = "SELECT COUNT(*) AS recepciones,
                       IFNULL(SUM((SELECT COUNT(*) FROM quimicos_recepcion_detalle d
                                    WHERE d.n_recepcion = r.n_recepcion)), 0) AS items,
                       IFNULL(SUM((SELECT IFNULL(SUM(d.cantidad_total), 0)
                                    FROM quimicos_recepcion_detalle d
                                    WHERE d.n_recepcion = r.n_recepcion)), 0) AS cantidad
                FROM quimicos_recepcion r" . $where;

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $resumen = ['recepciones' => 0, 'items' => 0, 'cantidad' => 0];

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $resumen = [
                'recepciones' => (int) $row['recepciones'],
                'items'       => (int) $row['items'],
                'cantidad'    => (float) $row['cantidad'],
            ];
        }

        mysqli_stmt_close($stmt);

        return $resumen;
    }

    /**
     * Cabecera de una recepción
     */
    public function obtenerRecepcion($conexion, $nRecepcion)
    {
        $sql = "SELECT r.*, COALESCE(b.nombre, r.bodega) AS bodega_nombre
                FROM quimicos_recepcion r
                LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega
                WHERE r.n_recepcion = ?
                LIMIT 1";

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $nRecepcion);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = false;

        if ($row = mysqli_fetch_assoc($result)) {
            $data = $row;
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
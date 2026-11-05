<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<?php
$fmt = function ($v, $d = 2) {
    return number_format((float) $v, $d, ',', '.');
};

$etiquetasGrupo = [
    'quimico'   => 'Químico',
    'bodega'    => 'Bodega',
    'proveedor' => 'Proveedor',
    'mes'       => 'Mes',
];

$params = array_filter([
    'desde'        => $filtros['desde'],
    'hasta'        => $filtros['hasta'],
    'bodega'       => $filtros['bodega'],
    'proveedor_id' => $filtros['proveedor_id'] ?: '',
    'agrupar'      => $agrupar,
], function ($v) {
    return $v !== '' && $v !== null;
});
$queryExport = http_build_query($params);
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-layer-group text-primary me-2"></i>Acumulados de Recepción</h1>
            <p class="text-muted mb-0">Cantidades recibidas agrupadas según el criterio seleccionado</p>
        </div>
        <div class="col-auto d-flex gap-2 align-items-start">
            <a href="/calidad/bodega-quimicos/informes" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Informes
            </a>
            <a href="/calidad/bodega-quimicos/informes/acumulados.csv?<?php echo htmlspecialchars($queryExport); ?>"
               class="btn btn-success">
                <i class="fas fa-file-csv me-2"></i> Exportar CSV
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="/calidad/bodega-quimicos/informes/acumulados" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" for="desde">Desde</label>
                    <input type="date" class="form-control" name="desde" id="desde" value="<?php echo htmlspecialchars($filtros['desde']); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="hasta">Hasta</label>
                    <input type="date" class="form-control" name="hasta" id="hasta" value="<?php echo htmlspecialchars($filtros['hasta']); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="bodega">Bodega</label>
                    <select class="form-select" name="bodega" id="bodega">
                        <option value="">TODAS</option>
                        <?php foreach ($bodegas as $b): ?>
                            <option value="<?php echo htmlspecialchars($b['codigo']); ?>"
                                <?php echo $b['codigo'] === $filtros['bodega'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="proveedor_id">Proveedor</label>
                    <select class="form-select" name="proveedor_id" id="proveedor_id">
                        <option value="0">TODOS</option>
                        <option value="-1" <?php echo (int) $filtros['proveedor_id'] === -1 ? 'selected' : ''; ?>>
                            (LIBRE - texto libre)
                        </option>
                        <?php foreach ($proveedores as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>"
                                <?php echo (int) $filtros['proveedor_id'] === (int) $p['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="agrupar">Agrupar por</label>
                    <select class="form-select" name="agrupar" id="agrupar">
                        <?php foreach ($etiquetasGrupo as $valor => $texto): ?>
                            <option value="<?php echo $valor; ?>" <?php echo $agrupar === $valor ? 'selected' : ''; ?>>
                                <?php echo $texto; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/calidad/bodega-quimicos/informes/acumulados" class="btn btn-outline-secondary" title="Limpiar">
                        <i class="fas fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultado -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Resultados por <?php echo strtolower($etiquetasGrupo[$agrupar]); ?></h5>
            <span class="badge bg-light text-primary"><?php echo count($filas); ?> filas</span>
        </div>
        <div class="card-body">
            <?php if (!empty($filas)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datos mb-0">
                        <thead>
                            <tr>
                                <th><?php echo $etiquetasGrupo[$agrupar]; ?></th>
                                <th>Detalle</th>
                                <th class="text-center">Recepciones</th>
                                <th class="text-end">Envases</th>
                                <th class="text-end">Contenido</th>
                                <th class="text-end">Cantidad total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filas as $f): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($f['descripcion']); ?></strong>
                                        <?php if (!empty($f['clave']) && $f['clave'] !== $f['descripcion']): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($f['clave']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo htmlspecialchars($f['detalle']); ?></td>
                                    <td class="text-center"><?php echo (int) $f['recepciones']; ?></td>
                                    <td class="text-end"><?php echo $fmt($f['envases']); ?></td>
                                    <td class="text-end"><?php echo $fmt($f['contenido']); ?></td>
                                    <td class="text-end"><strong><?php echo $fmt($f['cantidad']); ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="2">Totales</th>
                                <th class="text-center"><?php echo (int) $totales['recepciones']; ?></th>
                                <th class="text-end"><?php echo $fmt($totales['envases']); ?></th>
                                <th class="text-end"><?php echo $fmt($totales['contenido']); ?></th>
                                <th class="text-end"><?php echo $fmt($totales['cantidad']); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <small class="text-muted d-block mt-2">
                    Rango: <?php echo htmlspecialchars($filtros['desde']); ?> a <?php echo htmlspecialchars($filtros['hasta']); ?>.
                    Los totales de recepciones no suman las filas agrupadas por químico (una recepción puede incluir varios).
                </small>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Sin datos para los filtros seleccionados</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
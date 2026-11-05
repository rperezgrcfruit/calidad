<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<?php
$fmt = function ($v) {
    return number_format((float) $v, 2, ',', '.');
};

$params = array_filter([
    'bodega' => $bodega,
    'q'      => $buscar,
    'todos'  => $soloConStock ? '' : '1',
], function ($v) {
    return $v !== '' && $v !== null;
});
$queryExport = http_build_query($params);
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-warehouse text-success me-2"></i>Existencia de Químicos</h1>
            <p class="text-muted mb-0">Stock por químico y bodega según las recepciones registradas</p>
        </div>
        <div class="col-auto d-flex gap-2 align-items-start">
            <a href="/calidad/bodega-quimicos/informes" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Informes
            </a>
            <a href="/calidad/bodega-quimicos/informes/existencia.csv?<?php echo htmlspecialchars($queryExport); ?>"
               class="btn btn-success">
                <i class="fas fa-file-csv me-2"></i> Exportar CSV
            </a>
        </div>
    </div>

    <div class="alert alert-warning" role="alert">
        <i class="fas fa-triangle-exclamation me-2"></i>
        <strong>Existencia parcial:</strong> aún no existe el módulo de consumo, por lo que este stock
        solo considera lo recibido (no descuenta salidas ni mermas).
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="/calidad/bodega-quimicos/informes/existencia" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="bodega">Bodega</label>
                    <select class="form-select" name="bodega" id="bodega">
                        <option value="">TODAS</option>
                        <?php foreach ($bodegas as $b): ?>
                            <option value="<?php echo htmlspecialchars($b['codigo']); ?>"
                                <?php echo $b['codigo'] === $bodega ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="q">Buscar</label>
                    <input type="search" class="form-control" name="q" id="q" maxlength="60"
                           placeholder="Código o nombre del químico"
                           value="<?php echo htmlspecialchars($buscar); ?>">
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="todos" id="todos" value="1"
                            <?php echo $soloConStock ? '' : 'checked'; ?>>
                        <label class="form-check-label" for="todos">Incluir existencia en 0</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/calidad/bodega-quimicos/informes/existencia" class="btn btn-outline-secondary" title="Limpiar">
                        <i class="fas fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultado -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Existencias</h5>
            <span class="badge bg-light text-success"><?php echo count($filas); ?> registros</span>
        </div>
        <div class="card-body">
            <?php if (!empty($filas)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datos mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Bodega</th>
                                <th class="text-end">Cantidad</th>
                                <th>Unidad</th>
                                <th>Actualizado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filas as $f): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($f['quimico_codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($f['nombre'] ?? $f['quimico_codigo']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($f['tipo'] ?: '—'); ?></span></td>
                                    <td><?php echo htmlspecialchars($f['bodega_nombre']); ?></td>
                                    <td class="text-end"><strong><?php echo $fmt($f['cantidad']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($f['unidad']); ?></td>
                                    <td class="text-muted small">
                                        <?php echo $f['actualizado'] ? date('d/m/Y H:i', strtotime($f['actualizado'])) : '—'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-3">
                    <span class="text-muted small">Totales por unidad:</span>
                    <?php foreach ($totalesUnidad as $u => $total): ?>
                        <span class="badge bg-light text-dark">
                            <?php echo htmlspecialchars($u); ?>: <?php echo $fmt($total); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay existencias para los filtros seleccionados</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
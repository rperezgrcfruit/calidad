<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<?php
$totalItems = count($detalle);
$totalCantidad = 0.0;
foreach ($detalle as $d) {
    $totalCantidad += (float) $d['cantidad_total'];
}
$fmtClp = function ($v) {
    return '$ ' . number_format((float) $v, 0, ',', '.');
};
$fmtUsd = function ($v) {
    return 'US$ ' . number_format((float) $v, 2, ',', '.');
};
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                Recepción #<?php echo (int) $recepcion['n_recepcion']; ?>
            </h1>
            <p class="text-muted mb-0">
                <?php echo date('d/m/Y', strtotime($recepcion['fecha'])); ?>
                &nbsp;·&nbsp; <?php echo htmlspecialchars($recepcion['bodega_nombre']); ?>
            </p>
        </div>
        <div class="col-auto">
            <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <!-- Cabecera -->
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard me-2"></i>Datos de la Guía</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Proveedor</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['proveedor']); ?></dd>

                        <dt class="col-sm-4">GUIA</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['guia']); ?></dd>

                        <dt class="col-sm-4">Factura</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['factura'] ?? '—') ?: '—'; ?></dd>

                        <dt class="col-sm-4">Lote proveedor</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['lote_proveedor'] ?? '—') ?: '—'; ?></dd>

                        <dt class="col-sm-4">Bodega</dt>
                        <dd class="col-sm-8">
                            <?php echo htmlspecialchars($recepcion['bodega_nombre']); ?>
                            <small class="text-muted">(<?php echo htmlspecialchars($recepcion['bodega']); ?>)</small>
                        </dd>

                        <dt class="col-sm-4">Observaciones</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['observaciones'] ?? '—') ?: '—'; ?></dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Montos -->
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-coins me-2"></i>Montos</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Neto CLP</span>
                        <strong><?php echo $fmtClp($recepcion['neto_clp']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">IVA CLP</span>
                        <span><?php echo $fmtClp($recepcion['iva_clp']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-top mt-2 pt-2">
                        <span class="text-muted">Total CLP</span>
                        <strong class="text-success"><?php echo $fmtClp($recepcion['total_clp']); ?></strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Neto USD</span>
                        <span><?php echo $fmtUsd($recepcion['neto_usd']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Total USD</span>
                        <strong><?php echo $fmtUsd($recepcion['total_usd']); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Químicos Recibidos</h5>
            <span class="badge bg-light text-success">
                <?php echo $totalItems; ?> ítems · <?php echo number_format($totalCantidad, 2, ',', '.'); ?> total
            </span>
        </div>
        <div class="card-body">
            <?php if ($totalItems > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Reg. SAG</th>
                                <th class="text-end">Envases</th>
                                <th class="text-end">Cont./Env.</th>
                                <th class="text-end">Cantidad total</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle as $d): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($d['codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($d['nombre'] ?? $d['codigo']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($d['tipo'] ?? '—'); ?></span></td>
                                    <td><?php echo htmlspecialchars($d['reg_sag'] ?? '—'); ?></td>
                                    <td class="text-end"><?php echo number_format((float) $d['cant_envases'], 2, ',', '.'); ?></td>
                                    <td class="text-end"><?php echo number_format((float) $d['contenido_envase'], 2, ',', '.'); ?></td>
                                    <td class="text-end"><strong><?php echo number_format((float) $d['cantidad_total'], 2, ',', '.'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($d['unidad']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                    <p class="text-muted mb-0">Esta recepción no tiene ítems registrados</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
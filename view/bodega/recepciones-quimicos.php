<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-file-alt text-primary me-2"></i>Recepciones de Químicos</h1>
            <p class="text-muted mb-0">Historial de ingresos de productos químicos a bodega</p>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="/calidad/bodega-quimicos/recepciones" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" for="desde">Desde</label>
                    <input type="date" class="form-control" name="desde" id="desde" value="<?php echo htmlspecialchars($filtros['desde']); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="hasta">Hasta</label>
                    <input type="date" class="form-control" name="hasta" id="hasta" value="<?php echo htmlspecialchars($filtros['hasta']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="bodega_f">Bodega</label>
                    <select class="form-select" name="bodega" id="bodega_f">
                        <option value="">TODAS</option>
                        <?php foreach ($bodegas as $b): ?>
                            <option value="<?php echo htmlspecialchars($b['codigo']); ?>"
                                <?php echo $b['codigo'] === $filtros['bodega'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="proveedor_f">Proveedor</label>
                    <select class="form-select" name="proveedor_id" id="proveedor_f">
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
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-outline-secondary" title="Limpiar filtros">
                        <i class="fas fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
        <a href="/calidad/bodega-quimicos/recepciones/nuevo" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Nueva Recepción
        </a>
        <span class="text-muted small">
            Recepciones: <strong><?php echo number_format($resumen['recepciones'], 0, ',', '.'); ?></strong>
            &nbsp;|&nbsp; Ítems: <strong><?php echo number_format($resumen['items'], 0, ',', '.'); ?></strong>
            &nbsp;|&nbsp; Cantidad recibida: <strong><?php echo number_format($resumen['cantidad'], 2, ',', '.'); ?></strong>
        </span>
    </div>

    <!-- Resultados -->
    <?php if (!empty($recepciones)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Resultados</h5>
                <span class="badge bg-light text-primary"><?php echo number_format($pag['total'], 0, ',', '.'); ?> recepciones</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Bodega</th>
                                <th>Proveedor</th>
                                <th>GUIA</th>
                                <th>Factura</th>
                                <th>Lote</th>
                                <th class="text-center">Ítems</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recepciones as $rec): ?>
                                <tr>
                                    <td><strong><?php echo (int) $rec['n_recepcion']; ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($rec['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($rec['bodega_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($rec['proveedor']); ?></td>
                                    <td><?php echo htmlspecialchars($rec['guia']); ?></td>
                                    <td><?php echo htmlspecialchars($rec['factura'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($rec['lote_proveedor'] ?? ''); ?></td>
                                    <td class="text-center"><?php echo (int) $rec['items']; ?></td>
                                    <td class="text-end"><?php echo number_format((float) $rec['cantidad_total'], 2, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <a href="/calidad/bodega-quimicos/recepciones/detalle?recepcion=<?php echo (int) $rec['n_recepcion']; ?>"
                                           class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $pagUrl = '/calidad/bodega-quimicos/recepciones';
                include RUTA . '/calidad/view/layouts/pagination.php';
                ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay recepciones para los filtros seleccionados</h5>
                <p class="text-muted">Ajuste el rango de fechas o registre una nueva recepción</p>
                <a href="/calidad/bodega-quimicos/recepciones?desde=2000-01-01&hasta=2100-12-31" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calendar-alt me-2"></i> Ver todo el historial
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-file-alt text-primary me-2"></i>Recepciones Químicos</h1>
            <p class="text-muted">Historial de recepciones de productos químicos</p>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="get" id="form-filtros">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Desde</label>
                                <input type="date" name="desde" class="form-control" value="<?php echo $_GET['desde'] ?? date('Y-m-01'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hasta</label>
                                <input type="date" name="hasta" class="form-control" value="<?php echo $_GET['hasta'] ?? date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Bodega</label>
                                <select name="bodega" class="form-select">
                                    <option value="">TODAS</option>
                                    <?php if (isset($bodegas)): foreach ($bodegas as $bodega): ?>
                                        <option value="<?php echo $bodega['codigo']; ?>" <?php echo (isset($_GET['bodega']) && $_GET['bodega'] == $bodega['codigo']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($bodega['nombre']); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Proveedor</label>
                                <select name="proveedor" class="form-select">
                                    <option value="">TODOS</option>
                                    <?php if (isset($proveedores)): foreach ($proveedores as $prov): ?>
                                        <option value="<?php echo $prov['nombre']; ?>" <?php echo (isset($_GET['proveedor']) && $_GET['proveedor'] == $prov['nombre']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($prov['nombre']); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Filtrar
                                </button>
                                <a href="/calidad/bodega-quimicos/recepciones/nuevo" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Nueva Recepción
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resultados -->
    <div class="row">
        <div class="col-12">
            <?php if (isset($recepciones) && count($recepciones) > 0): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Resultados (<?php echo count($recepciones); ?> recepciones)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th># Recepción</th>
                                        <th>Fecha</th>
                                        <th>Bodega</th>
                                        <th>Proveedor</th>
                                        <th>Guía</th>
                                        <th>Factura</th>
                                        <th>Lote</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recepciones as $rec): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($rec['n_recepcion']); ?></strong></td>
                                            <td><?php echo date('d/m/Y', strtotime($rec['fecha'])); ?></td>
                                            <td><?php echo htmlspecialchars($rec['bodega_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($rec['proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($rec['guia']); ?></td>
                                            <td><?php echo htmlspecialchars($rec['factura']); ?></td>
                                            <td><?php echo htmlspecialchars($rec['lote_proveedor']); ?></td>
                                            <td class="text-center">
                                                <a href="/calidad/bodega-quimicos/recepciones/detalle?recepcion=<?php echo $rec['n_recepcion']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay recepciones registradas</h5>
                        <p class="text-muted">Use el botón "Nueva Recepción" para agregar</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
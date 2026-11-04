<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Detalle Recepción Química #<?php echo $recepcion['n_recepcion'] ?? '?'; ?></h1>
            <p class="text-muted">Datos de la recepción de productos químicos</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Datos Generales</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Número Recepción:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['n_recepcion'] ?? '-'); ?></dd>
                        
                        <dt class="col-sm-4">Fecha:</dt>
                        <dd class="col-sm-8"><?php echo isset($recepcion['fecha']) ? date('d/m/Y', strtotime($recepcion['fecha'])) : '-'; ?></dd>
                        
                        <dt class="col-sm-4">Bodega:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['bodega_nombre'] ?? 'TODAS'); ?></dd>
                        
                        <dt class="col-sm-4">Proveedor:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['proveedor'] ?? '-'); ?></dd>
                        
                        <dt class="col-sm-4">Guía:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['guia'] ?? '-'); ?></dd>
                        
                        <dt class="col-sm-4">Factura:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['factura'] ?? '-'); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Lote Prov.:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['lote_proveedor'] ?? '-'); ?></dd>
                        
                        <dt class="col-sm-4">Observaciones:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($recepcion['observaciones'] ?? 'Sin observaciones'); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Productos Químicos Registrados</h5>
        </div>
        <div class="card-body">
            <?php if ($detalle && count($detalle) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Unidad</th>
                                <th>Reg SAG</th>
                                <th>Envases</th>
                                <th>Cont./Env.</th>
                                <th>Cantidad Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle as $producto): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($producto['codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($producto['tipo']); ?></span></td>
                                    <td><?php echo htmlspecialchars($producto['unidad_cat']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['reg_sag'] ?? '-'); ?></td>
                                    <td><?php echo number_format($producto['cant_envases'], 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($producto['contenido_envase'], 2, ',', '.'); ?></td>
                                    <td><strong><?php echo number_format($producto['cantidad_total'], 2, ',', '.'); ?> <?php echo htmlspecialchars($producto['unidad']); ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-flask fa-2x text-muted mb-2"></i>
                    <p class="text-muted">No hay productos registrados en esta recepción</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-clipboard-list text-success me-2"></i>Recepción Calidad</h1>
            <p class="text-muted">Lista de lotes registrados en la temporada actual</p>
        </div>
    </div>
    
    <!-- Acciones -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="/calidad/recepcion-calidad/nueva-recepcion" class="btn btn-success btn-lg">
                <i class="fas fa-plus me-2"></i> Nueva Recepción
            </a>
            <button id="btn-refresh" class="btn btn-outline-secondary btn-lg ms-2">
                <i class="fas fa-sync-alt me-2"></i> Actualizar
            </button>
        </div>
    </div>
    
    <!-- Tabla de recepciones -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Recepciones</h5>
            <?php if (isset($pag)): ?>
                <span class="badge bg-light text-success"><?php echo number_format($pag['total'], 0, ',', '.'); ?> registros</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (isset($result) && count($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lote</th>
                                <th>Esperie</th>
                                <th>Variedad</th>
                                <th>Productor</th>
                                <th>Frutos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $index => $recepcion): ?>
                                <tr>
                                    <td><?php echo ($pag['offset'] ?? 0) + $index + 1; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($recepcion['lote']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($recepcion['especie']); ?></td>
                                    <td><?php echo htmlspecialchars($recepcion['variedad']); ?></td>
                                    <td><?php echo htmlspecialchars($recepcion['r_social']); ?></td>
                                    <td><?php echo number_format($recepcion['cantidad_frutos'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <a href="/calidad/recepcion-calidad/ver-recepcion/?lote=<?php echo urlencode($recepcion['lote']); ?>" 
                                           class="btn btn-sm btn-outline-success me-2" 
                                           title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/calidad/recepcion-calidad/eliminar-recepcion/<?php echo urlencode($recepcion['lote']); ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este lote?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay recepciones registradas</h5>
                    <p class="text-muted">Comience registrando una nueva recepción</p>
                    <a href="/calidad/recepcion-calidad/nueva-recepcion" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i> Nueva Recepción
                    </a>
                </div>
            <?php endif; ?>

            <?php
            $pagUrl = '/calidad/recepcion-calidad';
            $pagParams = [];
            include RUTA . '/calidad/view/layouts/pagination.php';
            ?>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-refresh')?.addEventListener('click', function() {
    location.reload();
});
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
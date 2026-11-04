<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-warehouse text-primary me-2"></i>Bodegas de Químicos</h1>
            <p class="text-muted mb-0">Bodegas donde se almacenan los productos químicos</p>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoMensaje ?? 'success'); ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-12 d-flex flex-wrap gap-2">
            <a href="/calidad/bodega-quimicos" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBodega" onclick="abrirModal('crear')">
                <i class="fas fa-plus me-2"></i> Nueva Bodega
            </button>
        </div>
    </div>

    <?php if (!empty($bodegas)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Bodegas</h5>
                <span class="badge bg-light text-primary"><?php echo count($bodegas); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bodegas as $bodega): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($bodega['codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($bodega['nombre']); ?></td>
                                    <td class="text-center"><span class="badge bg-success">Activa</span></td>
                                    <td class="text-center text-nowrap">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar"
                                                onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode([
                                                    'codigo' => $bodega['codigo'],
                                                    'nombre' => $bodega['nombre'],
                                                ]), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="post" action="/calidad/bodega-quimicos/bodegas/guardar" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar la bodega <?php echo htmlspecialchars($bodega['codigo']); ?>?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($bodega['codigo']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay bodegas registradas</h5>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal crear/editar -->
<div class="modal fade" id="modalBodega" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/calidad/bodega-quimicos/bodegas/guardar">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="titulo-modal-bodega">Nueva Bodega</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <input type="hidden" name="accion" id="accion-bodega" value="crear">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="b-codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="codigo" id="b-codigo" maxlength="10" required autocomplete="off">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="b-nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="b-nombre" maxlength="60" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModal(modo, datos) {
    datos = datos || {};
    const esEditar = modo === 'editar';

    document.getElementById('titulo-modal-bodega').textContent = esEditar ? 'Editar Bodega' : 'Nueva Bodega';
    document.getElementById('accion-bodega').value = esEditar ? 'editar' : 'crear';

    const codigo = document.getElementById('b-codigo');
    codigo.value = datos.codigo || '';
    codigo.readOnly = esEditar;
    codigo.classList.toggle('bg-light', esEditar);

    document.getElementById('b-nombre').value = datos.nombre || '';

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBodega')).show();
}
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
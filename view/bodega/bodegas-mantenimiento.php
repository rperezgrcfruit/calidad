<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-warehouse text-primary me-2"></i>Bodegas de Químicos</h1>
            <p class="text-muted">Gestión de bodegas donde se almacenan los productos químicos</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="/calidad/bodega-quimicos" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
            <button id="btn-agregar-bodega" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBodega">
                <i class="fas fa-plus me-2"></i> Nueva Bodega
            </button>
        </div>
    </div>
    
    <?php if (isset($bodegas) && count($bodegas) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Lista de Bodegas (<?php echo count($bodegas); ?> registros)</h5>
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
                                    <td class="text-center">
                                        <?php if (isset($bodega['activo']) && $bodega['activo'] == 1): ?>
                                            <span class="badge bg-success">Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar" onclick="openModalEditar('<?php echo $bodega['codigo']; ?>', '<?php echo addslashes($bodega['nombre']); ?>', '<?php echo $bodega['activo'] ?? 1; ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Desactivar" onclick="return confirm('¿Desactivar esta bodega?')">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
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
                <p class="text-muted">Use el botón "Nueva Bodega" para agregar</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Agregar/Editar Bodega -->
<div class="modal fade" id="modalBodega" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-title"><i class="fas fa-plus me-2"></i>Nueva Bodega</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="form-bodega">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" pattern="[A-Z0-9]+" title="Solo letras mayúsculas y números" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1">Activa</option>
                                <option value="0">Inactiva</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalEditar(codigo, nombre, activo) {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Bodega';
    document.querySelector('#modalBodega .modal-body input[name="accion"]').value = = 'editar';
    document.querySelector('#modalBodega .modal-body input[name="codigo"]').value = codigo;
    document.querySelector('#modalBodega .modal-body input[name="codigo"]').disabled = true;
    document.querySelector('#modalBodega .modal-body input[name="nombre"]').value = nombre;
    document.querySelector('#modalBodega .modal-body select[name="activo"]').value = activo;
    new bootstrap.Modal('#modalBodega').show();
}
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
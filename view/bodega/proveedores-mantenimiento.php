<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-building text-primary me-2"></i>Proveedores Químicos</h1>
            <p class="text-muted mb-0">Proveedores de productos químicos</p>
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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProveedor" onclick="abrirModal('crear')">
                <i class="fas fa-plus me-2"></i> Nuevo Proveedor
            </button>
        </div>
    </div>

    <?php if (!empty($proveedores)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Proveedores</h5>
                <span class="badge bg-light text-primary"><?php echo count($proveedores); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proveedores as $prov): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($prov['codigo'] ?? '—'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($prov['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($prov['contacto'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prov['telefono'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prov['email'] ?? ''); ?></td>
                                    <td class="text-center text-nowrap">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar"
                                                onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode([
                                                    'id' => (int) $prov['id'],
                                                    'codigo' => $prov['codigo'],
                                                    'nombre' => $prov['nombre'],
                                                    'contacto' => $prov['contacto'],
                                                    'telefono' => $prov['telefono'],
                                                    'email' => $prov['email'],
                                                    'direccion' => $prov['direccion'],
                                                ]), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="post" action="/calidad/bodega-quimicos/proveedores/guardar" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar el proveedor <?php echo htmlspecialchars($prov['nombre']); ?>?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id" value="<?php echo (int) $prov['id']; ?>">
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
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay proveedores registrados</h5>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal crear/editar -->
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="/calidad/bodega-quimicos/proveedores/guardar">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="titulo-modal-proveedor">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <input type="hidden" name="accion" id="accion-proveedor" value="crear">
                    <input type="hidden" name="id" id="p-id" value="0">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="p-codigo">Código</label>
                            <input type="text" class="form-control" name="codigo" id="p-codigo" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="p-nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="p-nombre" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="p-contacto">Contacto</label>
                            <input type="text" class="form-control" name="contacto" id="p-contacto" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="p-telefono">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="p-telefono" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="p-email">Email</label>
                            <input type="email" class="form-control" name="email" id="p-email" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="p-direccion">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="p-direccion" maxlength="200">
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

    document.getElementById('titulo-modal-proveedor').textContent = esEditar ? 'Editar Proveedor' : 'Nuevo Proveedor';
    document.getElementById('accion-proveedor').value = esEditar ? 'editar' : 'crear';
    document.getElementById('p-id').value = datos.id || 0;

    document.getElementById('p-codigo').value = datos.codigo || '';
    document.getElementById('p-nombre').value = datos.nombre || '';
    document.getElementById('p-contacto').value = datos.contacto || '';
    document.getElementById('p-telefono').value = datos.telefono || '';
    document.getElementById('p-email').value = datos.email || '';
    document.getElementById('p-direccion').value = datos.direccion || '';

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProveedor')).show();
}
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
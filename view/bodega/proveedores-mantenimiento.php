<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-drafting-folder text-primary me-2"></i>Proveedores Químicos</h1>
            <p class="text-muted">Gestión de proveedores de productos químicos</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="/calidad/bodega-quimicos" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
            <button id="btn-agregar-proveedor" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProveedor">
                <i class="fas fa-plus me-2"></i> Nuevo Proveedor
            </button>
        </div>
    </div>
    
    <?php if (isset($proveedores) && count($proveedores) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Lista de Proveedores (<?php echo count($proveedores); ?> registros)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proveedores as $prov): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($prov['codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($prov['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($prov['contacto']); ?></td>
                                    <td><?php echo htmlspecialchars($prov['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($prov['email']); ?></td>
                                    <td class="text-center">
                                        <?php if (isset($prov['activo']) && $prov['activo'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este proveedor?')">
                                            <i class="fas fa-trash"></i>
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
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay proveedores registrados</h5>
                <p class="text-muted">Use el botón "Nuevo Proveedor" para agregar</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Agregar/Editar Proveedor -->
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" pattern="[A-Z0-9]+" title="Solo letras mayúsculas y números" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control" name="contacto">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
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

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
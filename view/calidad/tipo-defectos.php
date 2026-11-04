<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-tags text-primary me-2"></i>Tipos de Defectos</h1>
            <p class="text-muted">Gestión de categorías de defectos</p>
        </div>
    </div>
    
    <!-- Tabla de tipos de defectos -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoMensaje ?? 'success'); ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tipos de Defectos Registrados</h5>
        </div>
        <div class="card-body">
            <?php if (isset($tipoDefectos) && count($tipoDefectos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Defecto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tipoDefectos as $index => $tipo): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($tipo['tipo_defecto']); ?></strong></td>
                                    <td class="text-center">
                                        <a href="/calidad/recepcion-calidad/tipos-defectos/?delete=<?php echo $tipo['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este tipo de defecto?')">
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
                    <h5 class="text-muted">No hay tipos de defectos registrados</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Formulario para agregar tipo de defecto -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-plus-circle text-success me-2"></i>Agregar Nuevo Tipo de Defecto</h5>
        </div>
        <div class="card-body">
            <form action="/calidad/recepcion-calidad/tipos-defectos" method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="txtTipoDefecto" class="form-label">Nombre del Tipo</label>
                    <input type="text" class="form-control" name="txtTipoDefecto" id="txtTipoDefecto" 
                           placeholder="Ej: Calidad, Insecto, Condición..." required>
                </div>
                
                <div class="col-md-3">
                    <label for="submit" class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save me-2"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
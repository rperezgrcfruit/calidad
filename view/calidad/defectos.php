<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-bug text-danger me-2"></i>Defectos Calidad</h1>
            <p class="text-muted">Gestión de defectos y tipos de defectos</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="/calidad/recepcion-calidad/tipos-defectos" class="btn btn-outline-primary btn-lg w-100 mb-3">
                <i class="fas fa-tags me-2"></i> Tipos de Defectos
            </a>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoMensaje ?? 'success'); ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Tabla de defectos -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Defectos Registrados</h5>
            <?php if (isset($pag)): ?>
                <span class="badge bg-light text-danger"><?php echo number_format($pag['total'], 0, ',', '.'); ?> registros</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (isset($Defectos) && count($Defectos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Defecto</th>
                                <th>Defecto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($Defectos as $index => $defecto): ?>
                                <tr>
                                    <td><?php echo ($pag['offset'] ?? 0) + $index + 1; ?></td>
                                    <td><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($defecto['tipo_defecto']); ?></span></td>
                                    <td><?php echo htmlspecialchars($defecto['descripcion']); ?></td>
                                    <td class="text-center">
                                        <a href="/calidad/recepcion-calidad/defectos?delete=<?php echo urlencode($defecto['id']); ?>&pagina=<?php echo (int) ($pag['pagina'] ?? 1); ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este defecto?')">
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
                    <h5 class="text-muted">No hay defectos registrados</h5>
                </div>
            <?php endif; ?>

            <?php
            $pagUrl = '/calidad/recepcion-calidad/defectos';
            $pagParams = [];
            include RUTA . '/calidad/view/layouts/pagination.php';
            ?>
        </div>
    </div>
    
    <!-- Formulario para agregar defecto -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-plus-circle text-success me-2"></i>Agregar Nuevo Defecto</h5>
        </div>
        <div class="card-body">
            <form action="/calidad/recepcion-calidad/defectos" method="post" class="row g-3">
                <div class="col-md-4">
                    <label for="cbTipoDefecto" class="form-label">Tipo Defecto</label>
                    <select class="form-select" name="cbTipoDefecto" id="cbTipoDefecto" required>
                        <option value="">Seleccionar tipo...</option>
                        <?php if (!empty($tipoDefectos)): ?>
                            <?php foreach ($tipoDefectos as $tipo): ?>
                                <option value="<?php echo htmlspecialchars($tipo['tipo_defecto']); ?>"><?php echo htmlspecialchars($tipo['tipo_defecto']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="CALIDAD">Calidad</option>
                            <option value="CONDICION">Condición</option>
                            <option value="INSECTO">Insecto</option>
                            <option value="GRAVE">Grave</option>
                            <option value="LEVE">Leve</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-5">
                    <label for="txtDefecto" class="form-label">Descripción del Defecto</label>
                    <input type="text" class="form-control" name="txtDefecto" id="txtDefecto" 
                           placeholder="Nombre del defecto" required>
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
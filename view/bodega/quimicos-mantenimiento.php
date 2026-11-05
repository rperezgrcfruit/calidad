<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Mantenimiento de Químicos</h1>
            <p class="text-muted mb-0">Catálogo de productos químicos</p>
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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalQuimico"
                    onclick="abrirModal('crear')">
                <i class="fas fa-plus me-2"></i> Nuevo Químico
            </button>
        </div>
    </div>

    <?php if (!empty($quimicos)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Químicos</h5>
                <span class="badge bg-light text-primary"><?php echo number_format($pag['total'] ?? count($quimicos), 0, ',', '.'); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Unidad</th>
                                <th>Reg. SAG</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quimicos as $quimico): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($quimico['codigo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($quimico['nombre']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($quimico['tipo'] ?: '—'); ?></span></td>
                                    <td><?php echo htmlspecialchars($quimico['unidad_medida'] ?: '—'); ?></td>
                                    <td><?php echo htmlspecialchars($quimico['registro_sag'] ?: '—'); ?></td>
                                    <td class="text-center text-nowrap">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar"
                                                onclick="abrirModal('editar', <?php echo htmlspecialchars(json_encode([
                                                    'codigo' => $quimico['codigo'],
                                                    'nombre' => $quimico['nombre'],
                                                    'tipo' => $quimico['tipo'],
                                                    'unidad_medida' => $quimico['unidad_medida'],
                                                    'registro_sag' => $quimico['registro_sag'],
                                                ]), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form method="post" action="/calidad/bodega-quimicos/quimicos/guardar" class="d-inline"
                                              onsubmit="return confirm('¿Dar de baja el químico <?php echo htmlspecialchars($quimico['codigo']); ?>?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($quimico['codigo']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Dar de baja">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $pagUrl = '/calidad/bodega-quimicos/quimicos';
                include RUTA . '/calidad/view/layouts/pagination.php';
                ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay químicos registrados</h5>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal crear/editar -->
<div class="modal fade" id="modalQuimico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/calidad/bodega-quimicos/quimicos/guardar">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="titulo-modal-quimico">Nuevo Químico</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <input type="hidden" name="accion" id="accion-quimico" value="crear">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="q-codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="codigo" id="q-codigo" maxlength="10" required autocomplete="off">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="q-nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="q-nombre" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="q-tipo">Tipo</label>
                            <select class="form-select" name="tipo" id="q-tipo">
                                <option value="">Seleccione...</option>
                                <?php foreach (['FUMIGANTE', 'FUNGICIDA', 'INSECTICIDA', 'HERBICIDA', 'FERTILIZANTE FOLIAR', 'DESVERTIDIZANTE', 'OTRO'] as $t): ?>
                                    <option value="<?php echo $t; ?>"><?php echo ucfirst(strtolower($t)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="q-unidad">Unidad</label>
                            <select class="form-select" name="unidad_medida" id="q-unidad">
                                <option value="">—</option>
                                <?php foreach (['LT', 'KG', 'GR', 'ML', 'UND'] as $u): ?>
                                    <option value="<?php echo $u; ?>"><?php echo $u; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="q-sag">Reg. SAG</label>
                            <input type="text" class="form-control" name="registro_sag" id="q-sag" maxlength="50">
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

    document.getElementById('titulo-modal-quimico').textContent = esEditar ? 'Editar Químico' : 'Nuevo Químico';
    document.getElementById('accion-quimico').value = esEditar ? 'editar' : 'crear';

    const codigo = document.getElementById('q-codigo');
    codigo.value = datos.codigo || '';
    codigo.readOnly = esEditar;
    codigo.classList.toggle('bg-light', esEditar);

    document.getElementById('q-nombre').value = datos.nombre || '';
    document.getElementById('q-tipo').value = datos.tipo || '';
    document.getElementById('q-unidad').value = datos.unidad_medida || '';
    document.getElementById('q-sag').value = datos.registro_sag || '';

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalQuimico')).show();
}
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
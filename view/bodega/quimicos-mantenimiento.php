<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Mantenimiento de Químicos</h1>
            <p class="text-muted">Catálogo de productos químicos: fumigantes, fungicidas, insecticidas, herbicidas</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="/calidad/bodega-quimicos" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
            <button id="btn-agregar-nuevo" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="fas fa-plus me-2"></i> Nuevo Químico
            </button>
        </div>
    </div>
    
    <?php if (isset($quimicos) && count($quimicos) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Lista de Químicos (<?php echo number_format($pag['total'] ?? count($quimicos), 0, ',', '.'); ?> registros)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                    <td>
                                        <?php
                                        $tipos = ['FUMIGANTE', 'FUNGICIDA', 'INSECTICIDA', 'HERBICIDA', 'FERTILIZANTE FOLIAR', 'DESVERTIDIZANTE', 'OTRO'];
                                        $tipo = $quimico['tipo'];
                                        if (in_array($tipo, $tipos)):
                                            echo '<span class="badge bg-secondary">' . htmlspecialchars($tipo) . '</span>';
                                        else:
                                            echo '<span class="badge bg-info">' . htmlspecialchars($tipo) . '</span>';
                                        endif;
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($quimico['unidad_medida']); ?></td>
                                    <td><?php echo htmlspecialchars($quimico['registro_sag'] ?? '-'); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este químico?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $pagUrl = '/calidad/bodega-quimicos/quimicos';
                $pagParams = [];
                include RUTA . '/calidad/view/layouts/pagination.php';
                ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay químicos registrados</h5>
                <p class="text-muted">Use el botón "Nuevo Químico" para agregar</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Agregar/Editar -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Químico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" required pattern="[A-Z0-9]+" title="Solo letras mayúsculas y números">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="FUMIGANTE">Fumigante</option>
                                <option value="FUNGICIDA">Fungicida</option>
                                <option value="INSECTICIDA">Insecticida</option>
                                <option value="HERBICIDA">Herbicida</option>
                                <option value="FERTILIZANTE FOLIAR">Fertilante Foliar</option>
                                <option value="DESVERTIDIZANTE">Desvertidizante</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unidad</label>
                            <select class="form-select" name="unidad_medida" required>
                                <option value="LT">LT</option>
                                <option value="KG">KG</option>
                                <option value="GR">GR</option>
                                <option value="ML">ML</option>
                                <option value="UND">UND</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reg. SAG</label>
                            <input type="text" class="form-control" name="registro_sag">
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
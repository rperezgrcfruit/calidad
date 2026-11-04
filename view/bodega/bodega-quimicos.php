<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Bodega Químicos</h1>
            <p class="text-muted">Análisis químicos y parámetros de calidad</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="/calidad/recepcion-calidad" class="btn btn-success btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Volver a Recepción
            </a>
            <button id="btn-agregar-quimico" class="btn btn-primary btn-lg ms-2" data-lote="<?php echo $_GET['lote'] ?? ''; ?>">
                <i class="fas fa-plus me-2"></i> Nuevo Registro Químico
            </button>
        </div>
    </div>
    
    <?php if (isset($result) && count($result) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Registros Químicos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Fecha</th>
                                <th>Fruta</th>
                                <th>Variedad</th>
                                <th>Peso Neto (kg)</th>
                                <th>Humedad (%)</th>
                                <th>Azúcar (%)</th>
                                <th>Acidez (%)</th>
                                <th>Solidos (%) </th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $registro): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($registro['lote']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($registro['fecha_recepcion'])); ?></td>
                                    <td><?php echo htmlspecialchars($registro['fruta']); ?></td>
                                    <td><?php echo htmlspecialchars($registro['variedad']); ?></td>
                                    <td><?php echo number_format($registro['peso_neto'], 2, ',', '.'); ?></td>
                                    <td><?php echo number_format($registro['humedad'], 2); ?>%</td>
                                    <td><?php echo number_format($registro['azucar'], 2); ?>%</td>
                                    <td><?php echo number_format($registro['acidez'], 2); ?>%</td>
                                    <td><?php echo number_format($registro['solidos_totales'], 2); ?>%</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary me-2" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar registro?')">
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
                <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay registros químicos</h5>
                <p class="text-muted">Comienza registrando un lote desde Recepción Calidad</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAgregar = document.getElementById('btn-agregar-quimico');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function() {
            const lote = this.getAttribute('data-lote');
            if (lote) {
                window.location.href = '/calidad/bodega-quimicos/nuevo?lote=' + encodeURIComponent(lote);
            } else {
                alert('Selecciona un lote de Recepción Calidad primero');
            }
        });
    }
});
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
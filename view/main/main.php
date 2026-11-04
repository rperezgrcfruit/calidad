<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2">Panel de Control</h1>
            <p class="text-muted">Bienvenido al sistema de gestión de calidad</p>
        </div>
    </div>
    
    <!-- Cards de estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard fa-2x text-success mb-2"></i>
                    <h6 class="card-title">Recepciones</h6>
                    <?php if (isset($result)): ?>
                        <p class="display-6"><?php echo count($result); ?></p>
                    <?php else: ?>
                        <p class="display-6">0</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-bug fa-2x text-danger mb-2"></i>
                    <h6 class="card-title">Defectos</h6>
                    <p class="display-6">12</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-baby fa-2x text-primary mb-2"></i>
                    <h6 class="card-title">Lotes Activos</h6>
                    <p class="display-6">24</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                    <h6 class="card-title">Calidad Prom.</h6>
                    <p class="display-6">98%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mensaje principal -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Acciones Disponibles</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="/calidad/recepcion-calidad" class="btn btn-outline-success w-100 py-3">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i><br>
                        <strong>Recepción Calidad</strong>
                        <p class="small text-muted mt-1 mb-0">Registrar y gestionar lotes</p>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/calidad/bodega-quimicos" class="btn btn-outline-info w-100 py-3">
                        <i class="fas fa-flask fa-2x mb-2"></i><br>
                        <strong>Bodega Químicos</strong>
                        <p class="small text-muted mt-1 mb-0">Gestión de químicos</p>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/calidad/recepcion-calidad/defectos" class="btn btn-outline-danger w-100 py-3">
                        <i class="fas fa-bug fa-2x mb-2"></i><br>
                        <strong>Defectos Calidad</strong>
                        <p class="small text-muted mt-1 mb-0">Gestionar tipos y defectos</p>
                    </a>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <a href="/calidad/bodega-quimicos/recepciones/nuevo" class="btn btn-primary w-100 py-3">
                        <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                        <strong>Nueva Recepción</strong>
                        <p class="small text-muted mt-1 mb-0">Crear nuevo registro químico</p>
                    </a>
                </div>
                <div class="col-md-4 offset-md-4">
                    <a href="/calidad/recepcion-calidad/nueva-recepcion" class="btn btn-success w-100 py-3">
                        <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                        <strong>Nueva Recepción Calidad</strong>
                        <p class="small text-muted mt-1 mb-0">Crear nuevo lote de calidad</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
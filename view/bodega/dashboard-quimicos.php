<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Bodega de Químicos</h1>
            <p class="text-muted">Sistema de gestión de productos químicos y recepciones</p>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-flask fa-2x text-success mb-2"></i>
                    <h3 class="card-title">Químicos</h3>
                    <p class="display-4" id="count-quimicos">--</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-warehouse fa-2x text-info mb-2"></i>
                    <h3 class="card-title">Bodegas</h3>
                    <p class="display-4" id="count-bodegas">--</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-drafting-folder fa-2x text-warning mb-2"></i>
                    <h3 class="card-title">Proveedores</h3>
                    <p class="display-4" id="count-proveedores">--</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list fa-2x text-primary mb-2"></i>
                    <h3 class="card-title">Recepciones Mes</h3>
                    <p class="display-4" id="count-recepciones">--</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-success btn-lg me-3">
                        <i class="fas fa-file-alt me-2"></i> Nueva Recepción
                    </a>
                    <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-outline-primary btn-lg me-3">
                        <i class="fas fa-search me-2"></i> Ver Recepciones
                    </a>
                    <a href="/calidad/bodega-quimicos/quimicos" class="btn btn-outline-info btn-lg me-3">
                        <i class="fas fa-list me-2"></i> Mantenimiento Químicos
                    </a>
                    <a href="/calidad/bodega-quimicos/proveedores" class="btn btn-outline-warning btn-lg">
                        <i class="fas fa-users me-2"></i> Proveedores
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menú principal -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-sigma me-2"></i>Módulos del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-clipboard-list text-primary me-2"></i>Recepción Calidad</h5>
                                    <p class="card-text text-muted">Registrar nuevas recepciones de calidad</p>
                                    <a href="/calidad/recepcion-calidad" class="btn btn-sm btn-outline-primary">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-info">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-flask text-info me-2"></i>Bodega Químicos</h5>
                                    <p class="card-text text-muted">Gestión de productos químicos</p>
                                    <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-sm btn-outline-info">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-bug text-warning me-2"></i>Defectos Calidad</h5>
                                    <p class="card-text text-muted">Gestión de defectos y no conformidades</p>
                                    <a href="/calidad/recepcion-calidad/defectos" class="btn btn-sm btn-outline-warning">Acceder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Estadísticas simuladas (en producción se obtienen de la BD)
    document.getElementById('count-quimicos').textContent = '127';
    document.getElementById('count-bodegas').textContent = '8';
    document.getElementById('count-proveedores').textContent = '42';
    document.getElementById('count-recepciones').textContent = '15';
});
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
<?php 
// La sesión ya está iniciada por index.php
// Si no hay usuario autenticado, redirigir al login
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// Header simplificado: sin menús de módulos en la pantalla principal
define('SIMPLE_HEADER', true);

include RUTA . '/calidad/view/layouts/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i> Seleccione su Módulo</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="mb-2">Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuario'); ?></h5>
                        <p class="text-muted mb-0">Empresa: <?php echo htmlspecialchars($_SESSION['empresa'] ?? 'Empresa'); ?></p>
                        <p class="text-muted mb-0">Temporada: <?php echo htmlspecialchars($_SESSION['temporada'] ?? 'Temporada'); ?></p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <a href="/calidad/recepcion-calidad" class="card card-link d-flex flex-column align-items-center p-4 m-2">
                                <i class="fas fa-clipboard-list fa-3x text-success mb-3"></i>
                                <h5 class="mb-2">Recepción Calidad</h5>
                                <p class="text-muted text-center mb-0">Gestión de calidad, defectos y no conformidades</p>
                            </a>
                        </div>
                        
                        <div class="col-12">
                            <a href="/calidad/bodega-quimicos/recepciones" class="card card-link d-flex flex-column align-items-center p-4 m-2">
                                <i class="fas fa-flask fa-3x text-info mb-3"></i>
                                <h5 class="mb-2">Gestión Químicos</h5>
                                <p class="text-muted text-center mb-0">Recepciones y análisis químicos</p>
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="/calidad/close" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card.card-link {
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card.card-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
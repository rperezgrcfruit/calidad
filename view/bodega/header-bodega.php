<?php
// ============================================================
// HEADERS DE SEGURIDAD - Configuración dinámica por entorno
// ============================================================

$isProduction = is_production();

if ($isProduction) {
    header('X-XSS-Protection: 1; mode=block');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=()');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.plot.ly; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com;");
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calidad - Bodega Químicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/calidad/view/assets/css/styles.css?v=<?php echo @filemtime(RUTA . '/calidad/view/assets/css/styles.css') ?: '1'; ?>">
    <link rel="icon" href="/calidad/favicon.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.plot.ly/plotly-2.30.0.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body class="main-page">
    <div id="global-spinner" class="d-none">
        <div class="spinner-layer">
            <div class="circle-closing"></div>
        </div>
    </div>
    
    <!-- Navbar específico de Bodega Químicos -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/calidad/bodega-quimicos">
                <i class="fas fa-flask me-2"></i>Bodega Químicos
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/bodega-quimicos/recepciones">
                            <i class="fas fa-file-alt me-1"></i> Recepciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/bodega-quimicos/quimicos">
                            <i class="fas fa-cube me-1"></i> Químicos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/bodega-quimicos/bodegas">
                            <i class="fas fa-warehouse me-1"></i> Bodegas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/bodega-quimicos/proveedores">
                            <i class="fas fa-users me-1"></i> Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/">
                            <i class="fas fa-layer-group me-1"></i> Módulos
                        </a>
                    </li>
                </ul>
                
                <span class="navbar-text me-3 text-nowrap">
                    <i class="fas fa-user me-1"></i>
                    <strong><?php echo $_SESSION['username'] ?? 'Usuario'; ?></strong>
                </span>
                
                <a href="/calidad/close" class="btn btn-outline-light ms-lg-3">
                    <i class="fas fa-sign-out-alt"></i> Cerrar
                </a>
            </div>
        </div>
    </nav>

    <?php if (!empty($_SESSION['flash_contexto'])): ?>
        <?php $flash = $_SESSION['flash_contexto']; unset($_SESSION['flash_contexto']); ?>
        <div class="container-fluid pt-3">
            <div class="alert alert-<?php echo htmlspecialchars($flash['tipo'] ?? 'success'); ?> alert-dismissible fade show mb-0" role="alert">
                <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($flash['mensaje'] ?? ''); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div id="overlay"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="alert-container"></div>
            </div>
        </div>
    </div>

    <style>
        .main-page { background-color: var(--gray-100); min-height: 100vh; }
        .navbar-brand { font-size: 1.25rem; }
        .nav-link { color: var(--white) !important; font-weight: 500; }
        .navbar-dark .navbar-text { opacity: 0.9; }
        #global-spinner { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; align-items: center; justify-content: center; }
        .spinner-layer { width: 50px; height: 50px; position: relative; overflow: hidden; }
        .circle-closing { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 4px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <!-- Bootstrap y scripts se cargan una sola vez al final, desde footer.php -->
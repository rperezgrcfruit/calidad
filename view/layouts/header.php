<?php
// ============================================================
// HEADERS DE SEGURIDAD - Configuración dinámica por entorno
// ============================================================

// Los headers de seguridad HTTPS solo se envían en producción (APP_ENV=production y HTTPS activo)
$isProduction = is_production();

// Enviar headers de seguridad HTTPS solo en producción
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
    <title>Calidad - Sistema Industrial</title>
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
    
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-chart-line me-2"></i>Calidad Pro
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (!defined('SIMPLE_HEADER')): $rutaActual = $_SERVER['REQUEST_URI'] ?? ''; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($rutaActual, '/defectos') === false && strpos($rutaActual, '/recepcion-calidad') !== false ? 'active' : ''; ?>" href="/calidad/recepcion-calidad">
                            <i class="fas fa-clipboard-list me-1"></i> Recepción Calidad
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($rutaActual, '/recepcion-calidad/defectos') !== false ? 'active' : ''; ?>" href="/calidad/recepcion-calidad/defectos">
                            <i class="fas fa-bug me-1"></i> Defectos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($rutaActual, '/tipos-defectos') !== false ? 'active' : ''; ?>" href="/calidad/recepcion-calidad/tipos-defectos">
                            <i class="fas fa-tags me-1"></i> Tipos Defectos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calidad/seleccion-modulo">
                            <i class="fas fa-layer-group me-1"></i> Módulos
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <span class="navbar-text me-3 text-nowrap">
                    <i class="fas fa-user me-1"></i>
                    <strong><?php echo $_SESSION['username'] ?? 'Usuario'; ?></strong>
                </span>
                
                <span class="navbar-text me-3 text-nowrap d-lg-none d-xl-inline">
                    <i class="fas fa-building me-1"></i>
                    <?php echo $_SESSION['empresa'] ?? 'Empresa'; ?>
                </span>
                
                <span class="navbar-text text-nowrap d-lg-none d-xl-inline">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <?php echo $_SESSION['temporada'] ?? 'Temporada'; ?>
                </span>
                
                <a href="/calidad/close" class="btn btn-outline-light ms-3">
                    <i class="fas fa-sign-out-alt"></i> Cerrar
                </a>
            </div>
        </div>
    </nav>
    
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
        .nav-link { color: var(--white) !important; font-weight: 500; position: relative; transition: color 0.2s; }
        .nav-link::after { content: ''; position: absolute; bottom: -5px; left: 50%; width: 0; height: 2px; background-color: var(--white); transition: all 0.3s; transform: translateX(-50%); }
        .nav-link:hover::after, .nav-link.active::after { width: 80%; }
        .navbar-dark .navbar-text { opacity: 0.9; }
        #global-spinner { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; align-items: center; justify-content: center; }
        .spinner-layer { width: 50px; height: 50px; position: relative; overflow: hidden; }
        .circle-closing { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 4px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <!-- Bootstrap y scripts se cargan una sola vez al final, desde footer.php -->
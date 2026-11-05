<?php
/**
 * Pantalla pública de selección de módulo.
 * No requiere sesión: cada módulo pide su propio login.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccione módulo - GRCFRUIT SPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/calidad/view/assets/css/styles.css?v=<?php echo @filemtime(RUTA . '/calidad/view/assets/css/styles.css') ?: '1'; ?>">
    <link rel="icon" href="/calidad/favicon.ico" type="image/x-icon">
</head>
<body class="login-page">
    <div class="selector-panel">
        <div class="selector-marca">
            <img src="/calidad/view/assets/images/icons/grc_sf.png" alt="GRCFRUIT SPA" title="GRCFRUIT SPA">
            <h1>Sistema de Gestión de Calidad</h1>
            <p>Seleccione el módulo con el que desea trabajar</p>
        </div>

        <div class="selector-opciones">
            <a href="/calidad/recepcion-calidad" class="selector-card">
                <span class="selector-icono">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                <span class="selector-texto">
                    <strong>Recepción Calidad</strong>
                    <small>Lotes, productores, defectos y calibres</small>
                </span>
                <i class="fas fa-chevron-right selector-flecha"></i>
            </a>

            <a href="/calidad/bodega-quimicos/recepciones" class="selector-card">
                <span class="selector-icono">
                    <i class="fas fa-flask"></i>
                </span>
                <span class="selector-texto">
                    <strong>Bodega Químicos</strong>
                    <small>Recepciones, existencias y proveedores</small>
                </span>
                <i class="fas fa-chevron-right selector-flecha"></i>
            </a>
        </div>

        <p class="selector-pie">
            <i class="fas fa-shield-alt me-1"></i> GRCFRUIT SPA · Sistema de Gestión de Calidad
        </p>
    </div>
</body>
</html>

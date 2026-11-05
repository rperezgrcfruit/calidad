<?php
// Vista: login del módulo Bodega Químicos (solo usuario y clave)
// Espera: $errores (array), $old (array), $csrf_token
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Bodega Químicos - GRCFRUIT SPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/calidad/view/assets/css/styles.css?v=<?php echo @filemtime(RUTA . '/calidad/view/assets/css/styles.css') ?: '1'; ?>">
    <link rel="icon" href="/calidad/favicon.ico" type="image/x-icon">
</head>
<body class="login-page">
    <div id="global-spinner"></div>
    <div id="spinner"></div>
    <div id="overlay"></div>

    <div class="login-wrapper">
        <div class="login-image">
            <img src="/calidad/view/assets/images/icons/grc_sf.png" alt="GRCFRUIT SPA" title="GRCFRUIT SPA - Sistema de Calidad">
        </div>

        <div class="login-form">
            <div class="login-form-inner">
                <a href="/calidad/" class="login-volver">
                    <i class="fas fa-arrow-left me-1"></i> Volver a módulos
                </a>

                <h1>Bodega Químicos</h1>
                <p class="subtitle">Ingrese su usuario y contraseña</p>

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars(implode(' ', $errores)); ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" id="form-login" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="Ingrese su usuario" required maxlength="50" autofocus
                                   value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control password" id="password" name="password"
                                   placeholder="Ingrese su contraseña" required maxlength="100">
                            <span class="toggle-password"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="btn btn-success">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>

                    <p class="login-nota">
                        <i class="fas fa-info-circle me-1"></i>
                        Este módulo no requiere empresa ni temporada.
                    </p>
                </form>

                <p class="login-footer">
                    <i class="fas fa-shield-alt me-1"></i> GRCFRUIT SPA · Sistema de Gestión de Calidad
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="/calidad/view/assets/js/scripts.js?<?php echo time(); ?>"></script>
</body>
</html>

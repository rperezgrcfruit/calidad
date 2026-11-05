<?php
/**
 * Modal para cambiar el cliente (empresa) y la temporada activa
 * sin cerrar sesión.
 *
 * Requiere: $empresasNav, $temporadasNav (arreglos cargados por el header)
 */
$empresaActual = $_SESSION['empresa'] ?? '';
$temporadaActual = $_SESSION['temporada'] ?? '';
$volverActual = $_SERVER['REQUEST_URI'] ?? '/calidad/seleccion-modulo';
?>

<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="tituloModalCliente" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="/calidad/cambiar-empresa">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloModalCliente">
                        <i class="fas fa-building me-2"></i>Cambiar cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                    <input type="hidden" name="volver" value="<?php echo htmlspecialchars($volverActual); ?>">

                    <div class="mb-3">
                        <label for="ctx-empresa" class="form-label">Cliente (empresa)</label>
                        <select class="form-select" name="empresa" id="ctx-empresa" required>
                            <?php foreach ($empresasNav as $e): ?>
                                <option value="<?php echo htmlspecialchars($e['R_SOCIAL']); ?>"
                                    <?php echo $e['R_SOCIAL'] === $empresaActual ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($e['R_SOCIAL']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ctx-temporada" class="form-label">Temporada</label>
                        <select class="form-select" name="temporada" id="ctx-temporada" required>
                            <?php foreach ($temporadasNav as $t): ?>
                                <option value="<?php echo htmlspecialchars($t['temporada']); ?>"
                                    <?php echo (string) $t['temporada'] === (string) $temporadaActual ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['temporada']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <p class="small text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Se mantiene tu sesión activa. Solo cambia el cliente y la temporada con los que trabajas.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Aplicar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

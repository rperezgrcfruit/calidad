<?php
/**
 * ============================================================
 * BARRA DE PAGINACIÓN
 * ============================================================
 * Variables esperadas:
 *   $pag       -> resultado de paginar()
 *   $pagUrl    -> ruta base, ej: '/calidad/recepcion-calidad'
 *   $pagParams -> filtros a conservar (array, opcional)
 * ============================================================
 */
if (!isset($pag) || !is_array($pag)) {
    return;
}

if (!isset($pagUrl)) {
    $pagUrl = strtok($_SERVER['REQUEST_URI'] ?? '/calidad', '?');
}

if (!isset($pagParams) || !is_array($pagParams)) {
    $pagParams = [];
}

$total    = (int) $pag['total'];
$paginas  = (int) $pag['paginas'];
$actual   = (int) $pag['pagina'];
$desde    = (int) $pag['desde'];
$hasta    = (int) $pag['hasta'];

$numeros  = function_exists('pag_rango') ? pag_rango($actual, $paginas) : [$actual];
$prev     = max(1, $actual - 1);
$next     = min($paginas, $actual + 1);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">

    <small class="text-muted">
        <?php if ($total === 0): ?>
            Sin registros
        <?php else: ?>
            Mostrando <strong><?php echo $desde; ?></strong>–<strong><?php echo $hasta; ?></strong>
            de <strong><?php echo number_format($total, 0, ',', '.'); ?></strong> registros
        <?php endif; ?>
    </small>

    <?php if ($paginas > 1): ?>
        <nav aria-label="Paginación">
            <ul class="pagination pagination-sm mb-0">

                <li class="page-item pag-extremo <?php echo $actual <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, 1)); ?>" title="Primera">
                        <i class="fas fa-angles-left"></i>
                    </a>
                </li>

                <li class="page-item <?php echo $actual <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, $prev)); ?>" title="Anterior">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>

                <?php if ($numeros && $numeros[0] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, 1)); ?>">1</a>
                    </li>
                    <?php if ($numeros[0] > 2): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php foreach ($numeros as $n): ?>
                    <li class="page-item <?php echo $n === $actual ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, $n)); ?>"><?php echo $n; ?></a>
                    </li>
                <?php endforeach; ?>

                <?php if ($numeros && end($numeros) < $paginas): ?>
                    <?php if (end($numeros) < $paginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, $paginas)); ?>"><?php echo $paginas; ?></a>
                    </li>
                <?php endif; ?>

                <li class="page-item <?php echo $actual >= $paginas ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, $next)); ?>" title="Siguiente">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>

                <li class="page-item pag-extremo <?php echo $actual >= $paginas ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo htmlspecialchars(pag_enlace($pagUrl, $pagParams, $paginas)); ?>" title="Última">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>

            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-chart-column text-primary me-2"></i>Informes de Químicos</h1>
            <p class="text-muted mb-0">Reportes de recepciones y existencias</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <a href="/calidad/bodega-quimicos/informes/acumulados" class="card shadow-sm h-100 text-decoration-none text-reset informe-card">
                <div class="card-body d-flex gap-3 align-items-start">
                    <span class="informe-icono bg-primary">
                        <i class="fas fa-layer-group"></i>
                    </span>
                    <span>
                        <strong class="d-block mb-1">Acumulados de recepción</strong>
                        <small class="text-muted">
                            Cantidades recibidas por químico, bodega, proveedor o mes,
                            en un rango de fechas.
                        </small>
                    </span>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="/calidad/bodega-quimicos/informes/existencia" class="card shadow-sm h-100 text-decoration-none text-reset informe-card">
                <div class="card-body d-flex gap-3 align-items-start">
                    <span class="informe-icono bg-success">
                        <i class="fas fa-warehouse"></i>
                    </span>
                    <span>
                        <strong class="d-block mb-1">Existencia de químicos</strong>
                        <small class="text-muted">
                            Stock actual por químico y bodega, calculado con las
                            recepciones registradas.
                        </small>
                    </span>
                </div>
            </a>
        </div>
    </div>

    <div class="alert alert-warning mt-4 mb-0" role="alert">
        <i class="fas fa-triangle-exclamation me-2"></i>
        <strong>Existencia parcial:</strong> todavía no existe el módulo de consumo de químicos.
        Mientras tanto, la existencia refleja únicamente lo recibido (no descuenta salidas ni mermas).
    </div>
</div>

<style>
    .informe-card { transition: transform .2s, box-shadow .2s; }
    .informe-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1.2rem rgba(0,0,0,.12); }
    .informe-icono {
        width: 52px; height: 52px; flex: 0 0 52px;
        border-radius: 12px; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
</style>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
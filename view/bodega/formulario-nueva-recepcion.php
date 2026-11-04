<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<?php
if (!function_exists('q_old')) {
    function q_old($arr, $key, $def = '')
    {
        return htmlspecialchars((string) ($arr[$key] ?? $def), ENT_QUOTES, 'UTF-8');
    }
}
$oldItems = (isset($old['items']) && is_array($old['items'])) ? array_values($old['items']) : [];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-file-import text-success me-2"></i>Recepción de Químicos</h1>
            <p class="text-muted mb-0">Registro de ingreso de productos químicos a bodega</p>
        </div>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>No se pudo guardar:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errores as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="/calidad/bodega-quimicos/recepciones/guardar" id="form-recepcion-quimicos">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">

        <!-- ================= CABECERA ================= -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard me-2"></i>Datos de la Guía</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="bodega" class="form-label">Bodega <span class="text-danger">*</span></label>
                        <select class="form-select" name="bodega" id="bodega" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($bodegas as $b): ?>
                                <option value="<?php echo htmlspecialchars($b['codigo']); ?>"
                                    <?php echo $b['codigo'] === $bodegaSel ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($b['codigo'] . ' - ' . $b['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="proveedor_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                        <select class="form-select" name="proveedor_id" id="proveedor_id">
                            <option value="0">(LIBRE - escribir manualmente)</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>"
                                    <?php echo (int) ($old['proveedor_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="proveedor_libre" class="form-label">Proveedor manual</label>
                        <input type="text" class="form-control" name="proveedor_libre" id="proveedor_libre"
                               maxlength="100" placeholder="Nombre del proveedor"
                               value="<?php echo q_old($old, 'proveedor_libre'); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="guia" class="form-label">GUIA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="guia" id="guia" maxlength="20" required
                               value="<?php echo q_old($old, 'guia'); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="factura" class="form-label">Factura</label>
                        <input type="text" class="form-control" name="factura" id="factura" maxlength="20"
                               value="<?php echo q_old($old, 'factura'); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="lote_proveedor" class="form-label">Lote proveedor</label>
                        <input type="text" class="form-control" name="lote_proveedor" id="lote_proveedor" maxlength="40"
                               value="<?php echo q_old($old, 'lote_proveedor'); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <input type="text" class="form-control" name="observaciones" id="observaciones" maxlength="255"
                               value="<?php echo q_old($old, 'observaciones'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MONTOS ================= -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-coins me-2"></i>Montos de la Guía</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="neto_clp" class="form-label">Neto CLP ($)</label>
                        <input type="text" class="form-control monto" name="neto_clp" id="neto_clp" inputmode="decimal"
                               value="<?php echo q_old($old, 'neto_clp'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="iva_clp" class="form-label">IVA CLP ($)</label>
                        <input type="text" class="form-control monto" name="iva_clp" id="iva_clp" inputmode="decimal"
                               value="<?php echo q_old($old, 'iva_clp'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="total_clp" class="form-label">Total CLP ($)</label>
                        <input type="text" class="form-control monto" name="total_clp" id="total_clp" inputmode="decimal"
                               value="<?php echo q_old($old, 'total_clp'); ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="neto_usd" class="form-label">Neto USD (US$)</label>
                        <input type="text" class="form-control monto" name="neto_usd" id="neto_usd" inputmode="decimal"
                               value="<?php echo q_old($old, 'neto_usd'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="total_usd" class="form-label">Total USD (US$)</label>
                        <input type="text" class="form-control monto" name="total_usd" id="total_usd" inputmode="decimal"
                               value="<?php echo q_old($old, 'total_usd'); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <small class="text-muted">
                            El IVA se calcula al 19% si deja el campo vacío.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= DETALLE ================= -->
        <div class="row g-4">
            <!-- Catálogo -->
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Catálogo</h5>
                        <span class="badge bg-light text-dark"><?php echo count($catalogo); ?></span>
                    </div>
                    <div class="card-body">
                        <input type="search" class="form-control mb-3" id="buscar-catalogo"
                               placeholder="Buscar por código, nombre o tipo..." autocomplete="off">

                        <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" id="tabla-catalogo">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th class="text-end">Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($catalogo as $q): ?>
                                        <tr data-texto="<?php echo htmlspecialchars(mb_strtolower($q['codigo'] . ' ' . $q['nombre'] . ' ' . $q['tipo'], 'UTF-8')); ?>">
                                            <td><strong><?php echo htmlspecialchars($q['codigo']); ?></strong></td>
                                            <td>
                                                <?php echo htmlspecialchars($q['nombre']); ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($q['tipo']); ?></small>
                                            </td>
                                            <td class="text-end">
                                                <?php echo number_format((float) $q['stock_actual'], 2, ',', '.'); ?>
                                                <small class="text-muted"><?php echo htmlspecialchars($q['unidad_medida']); ?></small>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-success btn-agregar"
                                                        data-codigo="<?php echo htmlspecialchars($q['codigo']); ?>"
                                                        data-nombre="<?php echo htmlspecialchars($q['nombre']); ?>"
                                                        data-unidad="<?php echo htmlspecialchars($q['unidad_medida']); ?>"
                                                        title="Agregar">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carrito -->
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Químicos de esta recepción</h5>
                        <span class="badge bg-light text-success" id="contador-items">0</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle" id="tabla-items">
                                <thead>
                                    <tr>
                                        <th style="min-width: 150px;">Químico</th>
                                        <th style="width: 110px;">Envases</th>
                                        <th style="width: 120px;">Cont./Env.</th>
                                        <th style="width: 100px;">Unidad</th>
                                        <th style="width: 110px;" class="text-end">Total</th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    <?php foreach ($oldItems as $i => $it): ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[<?php echo $i; ?>][quimico_codigo]"
                                                       value="<?php echo htmlspecialchars($it['quimico_codigo'] ?? ''); ?>">
                                                <span class="item-codigo"><?php echo htmlspecialchars($it['quimico_codigo'] ?? ''); ?></span>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm cant" min="0.01" step="0.01"
                                                       name="items[<?php echo $i; ?>][cant_envases]"
                                                       value="<?php echo htmlspecialchars($it['cant_envases'] ?? '1'); ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm cont" min="0.01" step="0.01"
                                                       name="items[<?php echo $i; ?>][contenido_envase]"
                                                       value="<?php echo htmlspecialchars($it['contenido_envase'] ?? '1'); ?>">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm unidad" name="items[<?php echo $i; ?>][unidad]">
                                                    <?php foreach (['LT', 'KG', 'GR', 'ML', 'UND'] as $u): ?>
                                                        <option value="<?php echo $u; ?>" <?php echo ($it['unidad'] ?? '') === $u ? 'selected' : ''; ?>><?php echo $u; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="text-end total-item">0</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-quitar" title="Quitar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div id="sin-items" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                Agregue químicos desde el catálogo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <button type="submit" class="btn btn-success btn-lg px-4">
                <i class="fas fa-save me-2"></i> Guardar Recepción
            </button>
            <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-arrow-left me-2"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
(function () {
    const tablaItems = document.getElementById('items-body');
    const sinItems = document.getElementById('sin-items');
    const contador = document.getElementById('contador-items');
    let idx = <?php echo count($oldItems); ?>;

    function money(n) {
        return n.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcularFila(tr) {
        const cant = parseFloat(tr.querySelector('.cant').value) || 0;
        const cont = parseFloat(tr.querySelector('.cont').value) || 0;
        tr.querySelector('.total-item').textContent = money(cant * cont);
    }

    function actualizarEstado() {
        const filas = tablaItems.querySelectorAll('tr').length;
        contador.textContent = filas;
        sinItems.style.display = filas ? 'none' : 'block';
    }

    function agregar(codigo, nombre, unidad) {
        const ya = Array.from(tablaItems.querySelectorAll('input[name$="[quimico_codigo]"]'))
            .some(i => i.value === codigo);
        if (ya) {
            return;
        }

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="hidden" name="items[${idx}][quimico_codigo]" value="${codigo}">
                <span class="item-codigo">${codigo}</span>
                <br><small class="text-muted">${nombre}</small>
            </td>
            <td><input type="number" class="form-control form-control-sm cant" min="0.01" step="0.01"
                       name="items[${idx}][cant_envases]" value="1"></td>
            <td><input type="number" class="form-control form-control-sm cont" min="0.01" step="0.01"
                       name="items[${idx}][contenido_envase]" value="1"></td>
            <td>
                <select class="form-select form-select-sm unidad" name="items[${idx}][unidad]">
                    ${['LT', 'KG', 'GR', 'ML', 'UND'].map(u =>
                        `<option value="${u}" ${u === unidad ? 'selected' : ''}>${u}</option>`).join('')}
                </select>
            </td>
            <td class="text-end total-item">0</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-quitar" title="Quitar">
                    <i class="fas fa-times"></i>
                </button>
            </td>`;

        tablaItems.appendChild(tr);
        idx++;
        recalcularFila(tr);
        actualizarEstado();
    }

    // Agregar desde catálogo
    document.querySelectorAll('.btn-agregar').forEach(function (btn) {
        btn.addEventListener('click', function () {
            agregar(this.dataset.codigo, this.dataset.nombre, this.dataset.unidad);
        });
    });

    // Recalcular / quitar (delegación)
    tablaItems.addEventListener('input', function (ev) {
        const tr = ev.target.closest('tr');
        if (tr && (ev.target.classList.contains('cant') || ev.target.classList.contains('cont'))) {
            recalcularFila(tr);
        }
    });

    tablaItems.addEventListener('click', function (ev) {
        const btn = ev.target.closest('.btn-quitar');
        if (btn) {
            btn.closest('tr').remove();
            actualizarEstado();
        }
    });

    // Buscador del catálogo
    const buscar = document.getElementById('buscar-catalogo');
    if (buscar) {
        buscar.addEventListener('input', function () {
            const t = this.value.trim().toLowerCase();
            document.querySelectorAll('#tabla-catalogo tbody tr').forEach(function (tr) {
                tr.style.display = tr.dataset.texto.includes(t) ? '' : 'none';
            });
        });
    }

    // Proveedor: mostrar/ocultar texto libre
    const cboProv = document.getElementById('proveedor_id');
    const txtLibre = document.getElementById('proveedor_libre');
    function syncProveedor() {
        const libre = cboProv.value === '0';
        txtLibre.disabled = !libre;
        txtLibre.required = libre;
        if (!libre) { txtLibre.value = ''; }
    }
    if (cboProv) {
        cboProv.addEventListener('change', syncProveedor);
        syncProveedor();
    }

    // Montos: IVA 19% y totales
    function parseMonto(v) {
        if (!v) { return 0; }
        v = v.replace(/\$/g, '').replace(/\s/g, '');
        if (v.includes(',')) {
            v = v.replace(/\./g, '').replace(',', '.');
        } else if (/^\d{1,3}(\.\d{3})+$/.test(v)) {
            v = v.replace(/\./g, '');
        }
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    const netoClp = document.getElementById('neto_clp');
    const ivaClp = document.getElementById('iva_clp');
    const totalClp = document.getElementById('total_clp');
    const netoUsd = document.getElementById('neto_usd');
    const totalUsd = document.getElementById('total_usd');

    function calcularClp() {
        const neto = parseMonto(netoClp.value);
        let iva = parseMonto(ivaClp.value);
        if (neto > 0 && iva === 0) {
            iva = Math.round(neto * 0.19);
            ivaClp.value = iva.toLocaleString('es-CL');
        }
        const total = neto + iva;
        if (neto > 0 || iva > 0) {
            totalClp.value = total.toLocaleString('es-CL');
        }
    }

    function calcularUsd() {
        const neto = parseMonto(netoUsd.value);
        if (neto > 0 && parseMonto(totalUsd.value) === 0) {
            totalUsd.value = neto.toFixed(2).replace('.', ',');
        }
    }

    if (netoClp) { netoClp.addEventListener('blur', calcularClp); }
    if (ivaClp) { ivaClp.addEventListener('blur', calcularClp); }
    if (netoUsd) { netoUsd.addEventListener('blur', calcularUsd); }

    // Validación antes de enviar
    document.getElementById('form-recepcion-quimicos').addEventListener('submit', function (ev) {
        const errores = [];

        if (!document.getElementById('bodega').value) { errores.push('Seleccione una bodega.'); }
        if (!document.getElementById('guia').value.trim()) { errores.push('Ingrese el número de GUIA.'); }

        const provLibre = cboProv.value === '0';
        if (provLibre && !txtLibre.value.trim()) { errores.push('Ingrese el proveedor.'); }

        if (tablaItems.querySelectorAll('tr').length === 0) {
            errores.push('Agregue al menos un químico.');
        }

        tablaItems.querySelectorAll('tr').forEach(function (tr) {
            const cant = parseFloat(tr.querySelector('.cant').value) || 0;
            const cont = parseFloat(tr.querySelector('.cont').value) || 0;
            if (cant <= 0 || cont <= 0) {
                errores.push('Revise las cantidades: deben ser mayores a 0.');
            }
        });

        if (errores.length) {
            ev.preventDefault();
            alert(errores.join('\n'));
        }
    });

    // Estado inicial
    tablaItems.querySelectorAll('tr').forEach(recalcularFila);
    actualizarEstado();

    // Cambio de bodega: recargar stock del catálogo
    document.getElementById('bodega').addEventListener('change', function () {
        if (tablaItems.querySelectorAll('tr').length > 0) {
            if (!confirm('Al cambiar de bodega se recargará el catálogo y se perderán los ítems agregados. ¿Continuar?')) {
                return;
            }
        }
        window.location = '/calidad/bodega-quimicos/recepciones/nuevo?bodega=' + encodeURIComponent(this.value);
    });
})();
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
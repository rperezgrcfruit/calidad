<?php include RUTA . '/calidad/view/bodega/header-bodega.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Nueva Recepción Química</h1>
            <p class="text-muted">Registro de entrada de productos químicos</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-forms me-2"></i>Datos de la Recepción</h5>
                </div>
                <div class="card-body">
                    <form method="post" id="form-recepcion">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Número Recepción</label>
                                <input type="text" class="form-control" value="<?php echo $nuevaNumero ?? date('Y-m') . '-0001'; ?>" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Bodega</label>
                                <select name="bodega" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php if (isset($bodegas)): foreach ($bodegas as $b): ?>
                                        <option value="<?php echo $b['codigo']; ?>"><?php echo htmlspecialchars($b['nombre']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Proveedor</label>
                                <input type="text" name="proveedor" class="form-control" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Guía</label>
                                <input type="text" name="guia" class="form-control">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Factura</label>
                                <input type="text" name="factura" class="form-control">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Buscar lote (opcional)</label>
                                <input type="text" name="lote_proveedor" class="form-control" placeholder="Buscar por lote existente..." list="lotes-existentes">
                                <datalist id="lotes-existentes">
                                    <!-- Se completan vía AJAX -->
                                </datalist>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones sobre la recepción..."></textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3"><i class="fas fa-box-open me-2"></i>Productos Químicos</h5>
                        <p class="text-muted">Ingrese los productos químicos recibidos</p>
                        
                        <div id="items-container">
                            <!-- Campo de búsqueda de químico -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="buscar-quimico" placeholder="Buscar químico por código o nombre...">
                                </div>
                            </div>
                            
                            <!-- Sugerencias de químicos -->
                            <div class="list-group mb-3" id="sugerencias-quimicos" style="max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        
                        <div id="tabla-detalles" class="table-responsive" style="display: none;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="detalles-body"></tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Guardar Recepción
                            </button>
                            <a href="/calidad/bodega-quimicos/recepciones" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Instrucciones</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Pasos para registrar:</h6>
                    <ol class="small">
                        <li class="mb-2">Complete los datos de la recepción</li>
                        <li class="mb-2">Busque el químico por código o nombre</li>
                        <li class="mb-2">Ingrese cantidad y unidad</li>
                        <li class="mb-2">Guarde la recepción</li>
                    </ol>
                    
                    <h6 class="mb-3">Tipos de químicos:</h6>
                    <ul class="list-unstyled small">
                        <?php if (isset($tipos)): foreach ($tipos as $tipo): ?>
                            <li class="mb-1"><span class="badge bg-secondary"><?php echo $tipo; ?></span></li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-quimico');
    const sugerenciasDiv = document.getElementById('sugerencias-quimicos');
    const tablaDetalles = document.getElementById('tabla-detalles');
    const detallesBody = document.getElementById('detalles-body');
    
    let quimicosSeleccionados = [];
    const quimicosDisponibles = <?php echo json_encode($tipos ?? []); ?>;
    
    buscarInput.addEventListener('input', function() {
        const term = this.value.trim();
        if (term.length > 0) {
            // Buscar en quimicos (simulado - en producción usar AJAX)
            const quimicos = [
                <?php if (isset($quimicos)): foreach ($quimicos as $q): ?>
                    {codigo: '<?php echo $q['codigo']; ?>', nombre: '<?php echo addslashes($q['nombre']); ?>', tipo: '<?php echo addslashes($q['tipo']); ?>', unidad_medida: '<?php echo $q['unidad_medida']; ?>'},
                <?php endforeach; endif; ?>
            ];
            
            const filtrados = quimicos.filter(q => 
                q.codigo.toLowerCase().includes(term.toLowerCase()) ||
                q.nombre.toLowerCase().includes(term.toLowerCase())
            );
            
            mostrarSugerencias(filtrados);
        } else {
            sugerenciasDiv.innerHTML = '';
        }
    });
    
    function mostrarSugerencias(quimicos) {
        sugerenciasDiv.innerHTML = '';
        
        quimicos.forEach(q => {
            const item = document.createElement('button');
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = '<strong>' + q.codigo + '</strong> - ' + q.nombre + ' <span class="badge bg-secondary">' + q.tipo + '</span>';
            item.addEventListener('click', () => {
                agregarDetalle(q);
                buscarInput.value = q.codigo;
                sugerenciasDiv.innerHTML = '';
            });
            sugerenciasDiv.appendChild(item);
        });
        
        tablaDetalles.style.display = 'block';
    }
    
    function agregarDetalle(quimico) {
        const filaNueva = document.createElement('tr');
        filaNueva.innerHTML = `
            <td><input type="hidden" name="detalles[${quimicosSeleccionados.length}][quimico_codigo]" value="${quimico.codigo}">${quimico.codigo}</td>
            <td>${quimico.nombre}</td>
            <td><input type="number" name="detalles[${quimicosSeleccionados.length}][cant_envases]" class="form-control" step="1" min="1" required></td>
            <td>${quimico.unidad_medida}</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFila(this)"><i class="fas fa-trash"></i></button></td>
        `;
        detallesBody.appendChild(filaNueva);
        quimicosSeleccionados.push(quimico);
    }
    
    window.eliminarFila = function(btn) {
        btn.closest('tr').remove();
    };
});
</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
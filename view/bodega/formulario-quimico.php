<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-flask text-primary me-2"></i>Nuevo Registro Químico</h1>
            <p class="text-muted">Completa los datos químicos del lote</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-form-meals me-2"></i>Datos del Lote</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="/calidad/bodega-quimicos/guardar" id="form-bodega">
                        <input type="hidden" name="lote" value="<?php echo htmlspecialchars($lote ?? ''); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Lote</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($lote ?? ''); ?>" readonly>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Fecha Recepción</label>
                                <input type="date" class="form-control" name="fecha_recepcion" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Fruta</label>
                                <input type="text" class="form-control" name="fruta" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Variedad</label>
                                <input type="text" class="form-control" name="variedad">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Peso Bruto (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="peso_bruto" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Tara (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="peso_taras" value="0" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Peso Neto (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="peso_neto" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Humedad (%)</label>
                                <input type="number" step="0.01" class="form-control" name="humedad">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Azúcar (%)</label>
                                <input type="number" step="0.01" class="form-control" name="azucar">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Acidez (%)</label>
                                <input type="number" step="0.01" class="form-control" name="acidez">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Sólidos Totales (%)</label>
                                <input type="number" step="0.01" class="form-control" name="solidos">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Color</label>
                                <select class="form-select" name="color">
                                    <option value="">Seleccione...</option>
                                    <option value="Verde brillante">Verde brillante</option>
                                    <option value="Verde opaco">Verde opaco</option>
                                    <option value="Marrón claro">Marrón claro</option>
                                    <option value="Marrón oscuro">Marrón oscuro</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Aroma</label>
                                <select class="form-select" name="aroma">
                                    <option value="">Seleccione...</option>
                                    <option value="Aroma fresco">Aroma fresco</option>
                                    <option value="Aroma ácido">Aroma ácido</option>
                                    <option value="Aroma oxidado">Aroma oxidado</option>
                                    <option value="Normal">Normal</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones sobre el lote..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Guardar Registro
                            </button>
                            <a href="/calidad/bodega-quimicos" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Campos Químicos</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Humedad:</strong> Pérdida de peso por evaporación
                        </li>
                        <li class="list-group-item">
                            <strong>Azúcar:</strong> Contenido de sacarosa y fructosa
                        </li>
                        <li class="list-group-item">
                            <strong>Acidez:</strong> Ácido cítrico (pH)
                        </li>
                        <li class="list-group-item">
                            <strong>Sólidos:</strong> Brix total
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
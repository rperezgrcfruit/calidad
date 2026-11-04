<?php include RUTA . '/calidad/view/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-2"><i class="fas fa-plus-circle text-success me-2"></i>Nueva Recepción Calidad</h1>
            <p class="text-muted">Complete los datos del lote para registrar la recepción</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-form-meals me-2"></i>Datos del Lote</h5>
                </div>
                <div class="card-body">
                    <form action="/calidad/recepcion-calidad/nueva-recepcion/ingreso" method="post">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="txtlote" class="form-label">N° Lote <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="txtlote" id="txtlote" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="txtProductor" class="form-label">Productor</label>
                                <input type="text" class="form-control" name="txtProductor" id="txtProductor">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="txtEspecie" class="form-label">Especie</label>
                                <input type="text" class="form-control" name="txtEspecie" id="txtEspecie">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="txtVariedad" class="form-label">Variedad</label>
                                <input type="text" class="form-control" name="txtVariedad" id="txtVariedad">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="fecha_recepcion" class="form-label">Fecha Recepción <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datetimepicker" name="fecha_recepcion" id="fecha_recepcion" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="dateFechaCosecha" class="form-label">Fecha Cosecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="dateFechaCosecha" id="dateFechaCosecha" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="dateFechaInspeccion" class="form-label">Fecha Inspección <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="dateFechaInspeccion" id="dateFechaInspeccion" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="horaControl" class="form-label">Hora Control <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="horaControl" id="horaControl" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="txtFrutos" class="form-label">N° Frutos <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="txtFrutos" id="txtFrutos" min="0" required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Guardar Recepción
                            </button>
                            <a href="/calidad/recepcion-calidad" class="btn btn-outline-secondary btn-lg px-4">
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
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Lote:</strong> Código único del lote
                        </li>
                        <li class="list-group-item">
                            <strong>Fechas:</strong> Todas son obligatorias
                        </li>
                        <li class="list-group-item">
                            <strong>Frutos:</strong> cantidad estimada
                        </li>
                    </ul>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Ayuda:</h6>
                        <p class="small mb-0">Después de registrar, podrá agregar:</p>
                        <ul class="small mb-0">
                            <li>Defectos por calibre</li>
                            <li>Calibres y porcentajes</li>
                            <li>Datos de jugo</li>
                            <li>Observaciones</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .datetimepicker {
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }
        
        .col-lg-8, .col-lg-4 {
            margin-bottom: 20px;
        }
    }
</style>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
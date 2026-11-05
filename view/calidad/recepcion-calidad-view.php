<?php
include RUTA . '/calidad/view/layouts/header.php';
//print_r($images);

$empresa = $_SESSION['empresa'];
$temporada = $_SESSION['temporada'];

$recepcion = new ModelRecepcionCalidad();
$recepciones = $recepcion->GetRecepcion($conexion, $empresa, $temporada, $_GET['lote']);

?>

<div class="" id="graph-calibre"></div>
<canvas id="myChart" style="width:100%;max-width:700px" hidden></canvas>
<canvas id="ChartDefectos" style="width:100%;max-width:700px" hidden></canvas>

<h1 class="text-success text-center">Ingreso Detalle Lote Calidad</h1>

<div id="spinner"></div>
<div id="overlay"></div>
<div class="container-fluid row shadow p-2 mb-3 bg-body-tertiary rounded">

    <div class="col-md-2">
        <div class="mb-2">
            <label for="txtLote" class="form-label">N° Lote</label>
            <input type="text" class="form-control txtLote" name="txtlote" value="<?php echo $recepciones[0]['lote']; ?>" id="exampleFormControlInput1" readonly>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-2">
            <label for="txtLote" class="form-label">GGN</label>
            <input type="text" class="form-control txtGgn" name="txtGgn" value="<?php echo $recepciones[0]['ggn']; ?>" id="exampleFormControlInput1" disabled>
        </div>
    </div>

    <div class="col-md-4">
        <label for="inputEmail4" class="form-label">Productor</label>
        <input type="text" class="form-control txtProductor" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['r_social']; ?>" disabled>
    </div>

    <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Especie</label>
        <input type="text" class="form-control txtEspecie" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['especie']; ?>" disabled>
    </div>

    <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Variedad</label>
        <input type="text" class="form-control txtVariedad" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['variedad']; ?>" disabled>
    </div>

    <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Bins</label>
        <input type="text" class="form-control txtBins" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['cant_bins']; ?>" disabled>
    </div>

    <div class="col-md-2">
        <label for="inputEmail4" class="form-label">Guia</label>
        <input type="text" class="form-control txtGuia" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['guia']; ?>" disabled>
    </div>

    <div class="col-md-2">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Fecha Recepción</label>
            <input type="text" class="form-control fecha-recepcion" name="fecha_recepcion" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['fecha_recepcion']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Fecha Cosecha</label>
            <input type="text" class="form-control dateFechaCosecha" name="dateFechaCosecha" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['fecha_cosecha']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Fecha Inspección</label>
            <input type="text" class="form-control dateFechaInspeccion" name="dateFechaInspeccion" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['fecha_inspeccion']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Hora Control</label>
            <input type="text" class="form-control" name="horaControl" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['hora_control']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-3">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Inspector</label>
            <input type="text" class="form-control textInspector" name="textInspector" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['recepcionista']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">N° Frutos</label>
            <input type="text" class="form-control txtFrutos" name="txtFrutos" id="exampleFormControlInput1" value="<?php echo $recepciones[0]['cantidad_frutos']; ?>" disabled>
        </div>
    </div>

    <div class="col-md-2">
        <div class="mb-2">
            <div class="d-grid gap-2 mt-4">
                <button type="button" id="btn-pdf" class="btn btn-success">
                    PDF
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid row">
        <div class="col-md-9">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Observaciones</label>
                <textarea class="form-control txtObservaciones" name="txtObservaciones" id="exampleFormControlTextarea1" rows="3"><?php echo $recepciones[0]['observaciones']; ?></textarea>
            </div>
        </div>

        <div class="col-md-2">
            <div class="mb-2">
                <div class="d-grid gap-2 mt-4">
                    <button type="button" id="btn-observaciones" class="btn btn-success" style="margin-top: 50px;">
                        Guardar Obs.
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="container-fluid row">
    <h2 class="text-success text-center">Defectos y Calibres</h2>
    <div class="col-lg-12">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center text-bg-success">Calibres</h5>

            <div class="row p-2">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Calibre</label>
                        <input type="text" class="form-control txtcalibre" name="txtcalibre" id="exampleFormControlInput1" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Cantidad</label>
                        <input type="number" class="form-control txtcantidad" name="txtcantidad" id="exampleFormControlInput1" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Porcentaje %</label>
                        <input type="text" class="form-control txtporcentaje" name="txtporcentaje" id="exampleFormControlInput1" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btn-agregar-calibre" class="btn btn-success">
                                Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered tabla-datos" id="table-calibre">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Calibre</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCantidad = 0;
                        $totalPorcentaje = 0;
                        if (!empty($calibres)) {
                            foreach ($calibres as $calibre) {
                                $totalCantidad += $calibre['cantidad'];
                                $totalPorcentaje += $calibre['porcentaje'];
                        ?>
                                <tr>
                                    <td><?php echo $calibre['id']; ?></td>
                                    <td><?php echo $calibre['calibre']; ?></td>
                                    <td><?php echo $calibre['cantidad']; ?></td>
                                    <td><?php echo $calibre['porcentaje']; ?></td>
                                    <td><a class="text text-danger calibre-delete" data-calibre-del="<?php echo $calibre['id']; ?>" href="#">Eliminar</a></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="fw-bold">Total</td>
                            <td class="fw-bold"><?php echo $totalCantidad; ?></td>
                            <td class="fw-bold"><?php echo $totalPorcentaje . "%"; ?></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center text-bg-success">Defectos</h5>

            <div class="row p-2">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Tipo Defecto</label>
                        <select class="form-select cbTipoDefecto" aria-label="Default select example" id="cbTipoDefecto">
                            <option>Tipo Defecto</option>
                            <option value="CALIDAD">Calidad</option>
                            <option value="CONDICION">Condición</option>
                            <option value="INSECTO">Insecto</option>
                            <option value="GRAVE">Grave</option>
                            <option value="LEVE">Leve</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Defecto</label>
                        <select class="form-select cbDefecto" aria-label="Default select example" id="cDefecto">
                            <option selected>Defecto</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Cantidad</label>
                        <input type="number" class="form-control txtcantidadDefecto" name="txtcantidad" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Porcentaje %</label>
                        <input type="text" class="form-control txtporcentajeDefecto" name="txtporcentaje" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btn-agregar-defecto" class="btn btn-success">
                                Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered tabla-datos" id="table-calibre">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tipo Defecto</th>
                            <th scope="col">Defecto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($defectos)) {
                            $totalCantidad = 0;
                            $totalPorcentaje = 0;
                            foreach ($defectos as $defecto) {
                                $totalCantidad += $defecto['cantidad'];
                                $totalPorcentaje += $defecto['porcentaje'];
                        ?>
                                <tr>
                                    <td><?php echo $defecto['id']; ?></td>
                                    <td><?php echo $defecto['tipo_defecto']; ?></td>
                                    <td><?php echo $defecto['defecto']; ?></td>
                                    <td><?php echo $defecto['cantidad']; ?></td>
                                    <td><?php echo $defecto['porcentaje']; ?></td>
                                    <td><a class="text text-danger defecto-delete" data-defecto-del="<?php echo $defecto['id']; ?>" href="#">Eliminar</a></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="fw-bold">Total</td>
                            <td class="fw-bold txtTotalExpoDefecto">Total Exportación:<?php echo "    " . 100 - $totalPorcentaje . "%"; ?></td>
                            <td class="fw-bold"><?php echo $totalCantidad; ?></td>
                            <td class="fw-bold"><?php echo $totalPorcentaje . "%"; ?></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center text-bg-success">Citricos - Jugo</h5>

            <div class="row p-2">

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Peso Fruta</label>
                        <input type="text" class="form-control txtPesoFruta" name="txtPesoFruta" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Peso Jugo</label>
                        <input type="text" class="form-control txtPesoJugo" name="txtPesoJugo" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Porcentaje Jugo</label>
                        <input type="number" class="form-control txtPorcentajeJugo" name="txtPorcentajeJugo" id="exampleFormControlInput1" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Gasto NAOH</label>
                        <input type="text" class="form-control txtGastoNaoh" name="txtGastoNaoh" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Acidez</label>
                        <input type="text" class="form-control txtAcidezJugo" name="txtAcidezJugo" id="exampleFormControlInput1" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Brix</label>
                        <input type="text" class="form-control txtBrix" name="txtBrix" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Calculos</label>
                        <input type="text" class="form-control txtCalculos" name="txtCalculos" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btn-agregar-jugo" class="btn btn-success">
                                Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered tabla-datos" id="table-calibre">
                    <thead>
                        <tr>
                            <th scope="col" hidden>ID</th>
                            <th scope="col">Peso Fruta</th>
                            <th scope="col">Peso Jugo</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Gasto Naoh</th>
                            <th scope="col">Acidez</th>
                            <th scope="col">Brix</th>
                            <th scope="col">Calculos</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($jugos) && isset($jugos)) {
                            foreach ($jugos as $jugo) {
                        ?>
                                <tr>
                                    <td hidden><?php echo $jugo['id']; ?></td>
                                    <td><?php echo $jugo['peso_fruta']; ?></td>
                                    <td><?php echo $jugo['peso_jugo']; ?></td>
                                    <td><?php echo $jugo['porcentaje']; ?></td>
                                    <td><?php echo $jugo['gasto_naoh']; ?></td>
                                    <td><?php echo $jugo['acidez']; ?></td>
                                    <td><?php echo $jugo['brix']; ?></td>
                                    <td><?php echo $jugo['calculos']; ?></td>
                                    <td><a class="text text-danger jugo-delete" data-jugo-del="<?php echo $jugo['id']; ?>" href="#">Eliminar</a></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center text-bg-success">Citricos - Semillas</h5>

            <div class="row p-2">

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtSemillas" class="form-label">Cantidad Semillas</label>
                        <input type="text" class="form-control txtSemillas" name="txtSemillas" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Frutos Revisados</label>
                        <input type="text" class="form-control txtCantidadSemilla" name="txtCantidadSemilla" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtLote" class="form-label">Porcentaje</label>
                        <input type="number" class="form-control txtPorcentajeSemilla" name="txtPorcentajeSemilla" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btn-agregar-semilla" class="btn btn-success">
                                Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered tabla-datos" id="table-calibre">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Semillas</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($semillas) && isset($semillas)) {
                            foreach ($semillas as $semilla) {
                        ?>
                                <tr>
                                    <td><?php echo $semilla['id']; ?></td>
                                    <td><?php echo $semilla['semillas']; ?></td>
                                    <td><?php echo $semilla['cantidad']; ?></td>
                                    <td><?php echo $semilla['porcentaje']; ?></td>
                                    <td><a class="text text-danger semilla-delete" data-semilla-del="<?php echo $semilla['id']; ?>" href="#">Eliminar</a></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center text-bg-success">Citricos - Presión</h5>

            <div class="row p-2">

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtCantidadFrutos" class="form-label">Cantidad Frutos</label>
                        <input type="text" class="form-control txtCantidadFrutos" name="txtCantidadFrutos" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtMedicion1" class="form-label">Medicion 1</label>
                        <input type="text" class="form-control txtMedicion1" name="txtMedicion1" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtMedicion2" class="form-label">Medicion 2</label>
                        <input type="text" class="form-control txtMedicion2" name="txtMedicion2" id="exampleFormControlInput1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="txtPromedio" class="form-label">Promedio Presión</label>
                        <input type="text" class="form-control txtPromedio" name="txtPromedio" id="exampleFormControlInput1" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-2">
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btn-agregar-pesos-citricos" class="btn btn-success">
                                Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered tabla-datos" id="table-calibre">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Cant. Fruta</th>
                            <th scope="col">Medición 1</th>
                            <th scope="col">Medición 2</th>
                            <th scope="col">Promedio Presión</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($pesosCitricos) && isset($pesosCitricos)) {
                            foreach ($pesosCitricos as $peso) {
                        ?>
                                <tr>
                                    <td><?php echo $peso['id']; ?></td>
                                    <td><?php echo $peso['cantidad_frutos']; ?></td>
                                    <td><?php echo $peso['medicion_1']; ?></td>
                                    <td><?php echo $peso['medicion_2']; ?></td>
                                    <td><?php echo $peso['promedio_presion']; ?></td>
                                    <td><a class="text text-danger peso-citrico-delete" data-peso-citrico-del="<?php echo $peso['id']; ?>" href="#">Eliminar</a></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No hay registros de presión citrica</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card shadow p-2 mb-3 bg-body-tertiary rounded">
            <h5 class="card-header text-center">Respaldo de Imagenes</h5>

            <div class="card-body">
                <div class="row">
                    <div class="col-7 mb-3">
                        <label for="formFileMultiple" class="form-label">Subir Imagenes (Max. 2MB)</label>
                        <input class="form-control" type="file" id="formFileMultiple" accept="image/png, image/jpeg, image/jpg" max-size="2000" multiple>
                    </div>
                    <div class="col-auto mt-4">
                        <button type="submit" id="uploadButton" class="btn btn-success mb-3">Subir</button>
                    </div>
                </div>

                <div class="row">
                    <?php
                    if (!empty($images)) {
                        foreach ($images as $img) { ?>
                            <div class="col-2">
                                <img src="<?php echo $img['ruta']; ?>" class="img-thumbnail img-recepcion" id="img-recepcion" style="width:200px;height:128px;" data-bs-toggle="modal" data-bs-target="#modalImages" />
                                <a href="<?php echo $img['id']; ?>" data-foto-del="<?php echo $img['id']; ?>" id="delete-foto" class="delete-foto">Eliminar</a>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="modalImages" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Imágenes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modal-img" src="" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>  

document.getElementById("btn-observaciones").addEventListener("click", function (e) { 
    console.log('click');
    e.preventDefault();
    observaciones();
});


function observaciones() {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";

  const observaciones = document.querySelector(".txtObservaciones").value;
  const lote = document.querySelector(".txtLote").value;

  const datos = {
    tipoDato: "ingresoObservaciones",
    observaciones: observaciones,
    lote: lote,
  };
console.log(datos);
  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log(xhr.responseText);
        const response = JSON.parse(xhr.responseText);
        if (response["respuesta"] == 1) {
          location.reload();
        } else {
          alert(response["mensaje"]);
        }
      }
    }
  };
  xhr.send(jsonData);
}

</script>

<?php include RUTA . '/calidad/view/layouts/footer.php'; ?>
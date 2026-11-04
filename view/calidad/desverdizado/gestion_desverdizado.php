<?php
require_once RUTA . '/calidad/view/layouts/header.php';
?>


<h1 class="text-success text-center">Gestión Desverdizado</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="lote" class="form-label">Lote</label>
                    <input type="text" class="form-control" id="lote" name="lote" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 form-check">
                <input class="form-check-input" type="radio" name="ingresarDesverdizado" id="ingresarDesverdizado">
                <label class="form-check-label" for="ingresarDesverdizado">
                    Ingresar a Desverdizado
                </label>
            </div>
            <div class="col-md-4 form-check">
                <input class="form-check-input" type="radio" name="liberarDesverdizado" id="liberarDesverdizado" checked>
                <label class="form-check-label" for="liberarDesverdizado">
                    Liberar Desverdizado
                </label>
            </div>
        </div>
    </div>



<script>
    document.getElementById('lote').focus();
    document.getElementById('ingresarDesverdizado').checked = false;
    document.getElementById('liberarDesverdizado').checked = false;

document.getElementById('lote').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        //event.preventDefault(); // Evita recarga de página
        const ingresarDesverdizado = document.getElementById('ingresarDesverdizado').checked;
        const liberarDesverdizado = document.getElementById('liberarDesverdizado').checked;
        const lote = this.value.trim();

        if (lote === '') {
            alert('Por favor, ingrese un lote válido.');
            return;
        }

        if ((ingresarDesverdizado && liberarDesverdizado) || (!ingresarDesverdizado && !liberarDesverdizado)) {
            alert('Por favor, seleccione solo una opción: Ingresar o Liberar Desverdizado.');
            location.reload(); // Recarga la página para restablecer el estado
            return;
        }
        // Mostrar feedback visual (Spinner/Overlay)
        if(document.getElementById("spinner")) document.getElementById("spinner").style.display = "block";
        if(document.getElementById("overlay")) document.getElementById("overlay").style.display = "block";

      

      

        const data = {
            lote: lote,
            ingresarDesverdizado: ingresarDesverdizado,
            liberarDesverdizado: liberarDesverdizado,
        };

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/calidad/desverdizado/movimiento-desverdizado', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                // Ocultar feedback visual
                if(document.getElementById("spinner")) document.getElementById("spinner").style.display = "none";
                if(document.getElementById("overlay")) document.getElementById("overlay").style.display = "none";

                if (xhr.status === 200) {
                    try {
                        console.log('Respuesta del servidor:', xhr.responseText);
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    } catch (e) {
                        alert('Respuesta recibida correctamente, pero no es JSON válido.');
                    }
                    
                    // Limpiar y preparar para el siguiente escaneo
                    const loteInput = document.getElementById('lote');
                    loteInput.value = '';
                    loteInput.focus();
                } else {
                    alert('Error en la solicitud: ' + xhr.status);
                }
            }
        };

        xhr.send(JSON.stringify(data));
    }
});
</script>
<?php
require_once RUTA . '/calidad/view/layouts/footer.php';
?>
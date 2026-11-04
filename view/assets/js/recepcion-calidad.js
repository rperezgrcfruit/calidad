//BUSCAR LOTE PARA MOSTRAR EN DETALLE

if (document.querySelector(".txtLote")) {
  const lote = document.querySelector(".txtLote");

  lote.addEventListener("blur", function (event) {
    event.preventDefault();

    searchLote(lote.value);
  });
}


function searchLote(lote) {
  let dato = {
    tipoDato: "buscarlote",
    lote: lote,
  };

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true); // Ruta directa al archivo PHP
  xhr.setRequestHeader("Content-Type", "application/json");
  let jsonData = JSON.stringify(dato);
  xhr.onreadystatechange = function (e) {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

        if (response["respuesta"] == 0) {
          alert(response["mensaje"]);
        } else {
          document.querySelector(".txtProductor").value =
            response[0]["r_social"];
          document.querySelector(".txtEspecie").value = response[0]["especie"];
          document.querySelector(".txtVariedad").value =
            response[0]["variedad"];
          document.querySelector(".fecha-recepcion").value =
            response[0]["fecha"];
        }
      }
    }
  };
  xhr.send(jsonData);
}

//BUSCAR LOTE PARA MOSTRAR EN DETALLE - FIN

//AGREGAR CALIBRE//////

if (document.getElementById("btn-agregar-calibre")) {
  const btnAgregarCalibre = document.getElementById("btn-agregar-calibre");

  btnAgregarCalibre.addEventListener("click", function (e) {
    e.preventDefault();
    let calibre = document.querySelector(".txtcalibre").value;
    let cantidad = document.querySelector(".txtcantidad").value;
    let porcentaje = document.querySelector(".txtporcentaje").value;
    let nlote = document.querySelector(".txtLote").value;

    let datos = {
      tipoDato: "ingresoCalibre",
      calibre: calibre,
      cantidad: cantidad,
      porcentaje: porcentaje,
      lote: nlote,
    };

    agregarCalibre(datos);

  });
}

function agregarCalibre(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  let jsonData = JSON.stringify(datos);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///AGREGAR CALIBRE FIN////////////////////////////

///CALCULAR PORCENTAJE DE ACUERDO A LA CANTIDAD DE FRUTOS///

if (document.querySelector(".txtcantidad")) {
  const cantidad = document.querySelector(".txtcantidad");

  cantidad.addEventListener("input", function (event) {
    let frutos = document.querySelector(".txtFrutos").value;
    let porcentaje = document.querySelector(".txtporcentaje");
    let cant = parseInt(event.target.value);

    if (cant <= frutos) {
      porc = parseInt(cant * 100) / parseInt(frutos);
      porcentaje.value = porc;
    } else {
      alert(
        "La cantidad de frutos usados no puede ser mayor a la cantidad de frutos revisados"
      );
    }
  });
}

///CALCULAR PORCENTAJE DE ACUERDO A LA CANTIDAD DE FRUTOS FIN///

///CALCULAR PORCENTAJE SEMILLAS DE ACUERDO A LA CANTIDAD DE FRUTOS///

if (document.querySelector(".txtCantidadSemilla")) {
  const cantidad = document.querySelector(".txtCantidadSemilla");

  cantidad.addEventListener("input", function (event) {
    let semilla = document.querySelector(".txtSemillas").value;
    let porcentaje = document.querySelector(".txtPorcentajeSemilla");
    let cant = parseInt(event.target.value);

    
      porc = parseInt(semilla * 100) / parseInt(cant);
      porcentaje.value = porc;
  
  });
}

///CALCULAR PORCENTAJE DE ACUERDO A LA CANTIDAD DE FRUTOS FIN///

///ELIMINAR CALIBRE DE LA RECEPCION CALIDAD////

let links = document.querySelectorAll(".calibre-delete");

links.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    let id = link.getAttribute("data-calibre-del");

    datos = {
      tipoDato: "eliminarcalibre",
      id: id,
    };

    deleteCalibre(datos);

  });
});

function deleteCalibre(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///ELIMINAR CALIBRE DE LA RECEPCION CALIDAD FIN////

///OBTENER LOS DEFECTOS A PARTIR DEL TIPO DE DEFECTO////

if (document.getElementById("cbTipoDefecto")) {
  let tipoDefecto = document.getElementById("cbTipoDefecto");

  tipoDefecto.addEventListener("change", function (event) {
    let valor = event.target.value;

    datos = {
      tipoDato: "obtenerdefectos",
      tipoDefecto: valor,
    };

    GetDefectos(datos);

  });
}

function GetDefectos(datos) {
 
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let cbdefecto = document.getElementById("cDefecto");
        cbdefecto.innerHTML = "";
        let response = JSON.parse(xhr.responseText);

        response.forEach(function (e) {
          let opt = document.createElement("option");
          opt.value = e["descripcion"];
          opt.innerHTML = e["descripcion"];
          cbdefecto.appendChild(opt);
        });
      }
    }
  };
  xhr.send(jsonData);
}

///OBTENER LOS DEFECTOS A PARTIR DEL TIPO DE DEFECTO  FIN////

///CALCULAR PORCENTAJE DE DEFECTOS DE ACUERDO A LA CANTIDAD DE FRUTOS///

if (document.querySelector(".txtcantidadDefecto")) {
  const cantidadDefectos = document.querySelector(".txtcantidadDefecto");

  cantidadDefectos.addEventListener("input", function (event) {
    let frutos = document.querySelector(".txtFrutos").value;
    let porcentaje = document.querySelector(".txtporcentajeDefecto");
    let cant = parseInt(event.target.value);

    if (cant <= frutos) {
      porc = parseInt(cant * 100) / parseInt(frutos);
      porcentaje.value = porc;
    } else {
      alert(
        "La cantidad de frutos usados no puede ser mayor a la cantidad de frutos revisados"
      );
    }
  });
}

///CALCULAR PORCENTAJE DE ACUERDO A LA CANTIDAD DE FRUTOS FIN///

//AGREGAR DEFECTOS/////////////////////////////////////////////

if (document.getElementById("btn-agregar-defecto")) {
  let btnAgregarDefecto = document.getElementById("btn-agregar-defecto");

  btnAgregarDefecto.addEventListener("click", function (e) {
    e.preventDefault();
    let cantidad = document.querySelector(".txtcantidadDefecto").value;
    let porcentaje = document.querySelector(".txtporcentajeDefecto").value;
    let nlote = document.querySelector(".txtLote").value;
    let cbTipoDefecto = document.querySelector(".cbTipoDefecto").value;
    let cbDefecto = document.querySelector(".cbDefecto").value;

    let datos = {
      tipoDato: "ingresoDefecto",
      tipoDefecto: cbTipoDefecto,
      defecto: cbDefecto,
      cantidad: cantidad,
      porcentaje: porcentaje,
      lote: nlote,
    };

    agregarDefecto(datos);
 
  });
}

function agregarDefecto(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  let jsonData = JSON.stringify(datos);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///AGREGAR DEFECTO FIN////////////////////////////

///ELIMINAR DEFECTO DE LA RECEPCION CALIDAD////

let linksDelete = document.querySelectorAll(".defecto-delete");

linksDelete.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    let id = link.getAttribute("data-defecto-del");

    datos = {
      tipoDato: "eliminardefecto",
      id: id,
    };

    document.getElementById("spinner").style.display = "block";
    document.getElementById("overlay").style.display = "block";
    deleteDefecto(datos);
    document.getElementById("spinner").style.display = "none";
    document.getElementById("overlay").style.display = "none";
  });
});

function deleteDefecto(datos) {
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///ELIMINAR CALIBRE DE LA RECEPCION CALIDAD FIN////

//AGREGAR JUGO/////////////////////////////////////////////

if (document.getElementById("btn-agregar-jugo")) {
  let btnAgregarJugo = document.getElementById("btn-agregar-jugo");

  btnAgregarJugo.addEventListener("click", function (e) {
    e.preventDefault();
    let porcentaje = document.querySelector(".txtPorcentajeJugo").value;
    let acidez = document.querySelector(".txtAcidezJugo").value;
    let brix = document.querySelector(".txtBrix").value;
    let pesoFruta = document.querySelector(".txtPesoFruta").value;
    let pesoJugo = document.querySelector(".txtPesoJugo").value;
    let gastoNaoh = document.querySelector(".txtGastoNaoh").value;
    let calculos = document.querySelector(".txtCalculos").value;
    let nlote = document.querySelector(".txtLote").value;

    let datos = {
      tipoDato: "ingresoJugo",
      acidez: acidez,
      brix: brix,
      pesoFruta: pesoFruta,
      porcentaje: porcentaje,
      pesoJugo: pesoJugo,
      gastoNaoh: gastoNaoh,
      calculos: calculos,
      lote: nlote,
    };

    agregarJugo(datos);

  });
}

function agregarJugo(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  let jsonData = JSON.stringify(datos);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);
        console.log(xhr.responseText);
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

///AGREGAR JUGO FIN////////////////////////////

///ELIMINAR JUGO DE LA RECEPCION CALIDAD////

let linksDelJugo = document.querySelectorAll(".jugo-delete");

linksDelJugo.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    let id = link.getAttribute("data-jugo-del");

    datos = {
      tipoDato: "eliminarJugo",
      id: id,
    };

    deleteJugo(datos);

  });
});

function deleteJugo(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///ELIMINAR JUGO DE LA RECEPCION CALIDAD FIN////

//AGREGAR SEMILLA/////////////////////////////////////////////

if (document.getElementById("btn-agregar-semilla")) {
  let btnAgregarSemilla = document.getElementById("btn-agregar-semilla");

  btnAgregarSemilla.addEventListener("click", function (e) {
    e.preventDefault();
    let semillas = document.querySelector(".txtSemillas").value;
    let porcentaje = document.querySelector(".txtPorcentajeSemilla").value;
    let cantidad = document.querySelector(".txtCantidadSemilla").value;
    let nlote = document.querySelector(".txtLote").value;

    let datos = {
      tipoDato: "ingresoSemilla",
      semillas: semillas,
      porcentaje: porcentaje,
      cantidad: cantidad,
      lote: nlote,
    };

    agregarSemilla(datos);
 
  });
}

function agregarSemilla(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  let jsonData = JSON.stringify(datos);

  xhr.onreadystatechange = function () {
    console.log(xhr.responseText);
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

        if (response["respuesta"] == 1) {
          //location.reload();
        } else {
          alert(response["mensaje"]);
        }
      }
    }
  };
  xhr.send(jsonData);
}

///AGREGAR SEMILLA FIN////////////////////////////


///AGREPAR PRESION DE ACUERDO A LAS MEDICIONES///
if (document.getElementById("btn-agregar-pesos-citricos")) {
  let btnAgregarPresion = document.getElementById("btn-agregar-pesos-citricos");
  btnAgregarPresion.addEventListener("click", function (e) {
    e.preventDefault();
    let cantidadFrutos = document.querySelector(".txtCantidadFrutos").value;
    let medicion1 = document.querySelector(".txtMedicion1").value;
    let medicion2 = document.querySelector(".txtMedicion2").value;
    let promedio = document.querySelector(".txtPromedio").value;
    let nlote = document.querySelector(".txtLote").value;
    let datos = {
      tipoDato: "ingresoPresion",
      medicion1: medicion1,
      medicion2: medicion2,
      promedio: promedio,
      cantidadFrutos: cantidadFrutos,
      lote: nlote,
    };
    //console.log(datos);
    agregarPresion(datos);
  });

}

function agregarPresion(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  let jsonData = JSON.stringify(datos);
  //console.log(jsonData);
  //return;
  xhr.onreadystatechange = function () {
    //console.log(xhr.response);
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        console.log('respuesta: ' + xhr.responseText);
        let response = JSON.parse(xhr.responseText);
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

///AGREPAR PRESION DE ACUERDO A LAS MEDICIONES FIN///



///ELIMINAR PESO CITRICO INICIO////

let linksDelPesoCitrico = document.querySelectorAll(".peso-citrico-delete");
linksDelPesoCitrico.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    let id = link.getAttribute("data-peso-citrico-del");

    datos = {
      tipoDato: "eliminarPesoCitrico",
      id: id,
    };

    deletePesoCitrico(datos);
  });

});

function deletePesoCitrico(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);
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

///ELIMINAR PESO CITRICO FIN////


///ELIMINAR SEMILLA DE LA RECEPCION CALIDAD////

let linksDelSemilla = document.querySelectorAll(".semilla-delete");

linksDelSemilla.forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    let id = link.getAttribute("data-semilla-del");

    datos = {
      tipoDato: "eliminarSemilla",
      id: id,
    };

    deleteSemilla(datos);

  });
});

function deleteSemilla(datos) {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  jsonData = JSON.stringify(datos);

  xhr = new XMLHttpRequest();

  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);

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

///ELIMINAR JUGO DE LA RECEPCION CALIDAD FIN////


///OBTENER CALCULOS JUGO RELACION Y PORCENTAJE

if ( document.querySelector('.txtPesoJugo')) {
  let pesoJugo = document.querySelector('.txtPesoJugo')
  let pesoFruta = document.querySelector('.txtPesoFruta')
  let porcJugo = document.querySelector('.txtPorcentajeJugo')

  pesoJugo.addEventListener('input', function (e) {
    if (pesoFruta.value) {
      let porcentajeJugo = (parseInt(e.target.value) * 100) / pesoFruta.value
      let truncadoPorcentajeJugo = Math.floor(porcentajeJugo * 100) / 100;
      porcJugo.value = truncadoPorcentajeJugo.toFixed(2);
    } else {
      alert('Falta Ingresar el peso de la fruta')
      pesoFruta.focus();
    }
  
  })

}

///OBTENER CALCULOS JUGO RELACION Y PORCENTAJE FIN///

///OBTENER ACIDEZ DE ACUERDO AL NAOH///

if (document.querySelector('.txtGastoNaoh')) {
  let gastoNaoh = document.querySelector('.txtGastoNaoh')
  let txtAcidez = document.querySelector('.txtAcidezJugo')

  gastoNaoh.addEventListener('input', function (e) {
   
      let acidez = (parseFloat(e.target.value) * 0.064);
      let truncadoAcidez = Math.floor(acidez * 100) / 100;
      txtAcidez.value = truncadoAcidez.toFixed(2);
  
  
  })
}

///OBTENER ACIDEZ DE ACUERDO AL NAOH FIN///

///OBTENER CALCULO FINAL DE ACUERDO A BRIX Y ACIDEZ///

if (document.querySelector('.txtBrix')) {
  let txtBrix = document.querySelector('.txtBrix')
  let txtAcidez = document.querySelector('.txtAcidezJugo')
  let txtCalculo = document.querySelector('.txtCalculos')

  txtBrix.addEventListener('input', function (e) {
   
      let truncadoCalculo = (parseFloat(e.target.value) / parseFloat(txtAcidez.value));
      txtCalculo.value = truncadoCalculo.toFixed(2);
  
  
  })
}

///OBTENER ACIDEZ DE ACUERDO AL NAOH FIN///


//OBTENER PROMEDIO DE PRESION DE ACUERDO A LAS MEDICIONES///
if (document.querySelector('.txtMedicion2')) {
  let medicion1 = document.querySelector('.txtMedicion1')
  let medicion2 = document.querySelector('.txtMedicion2')
  let promedio = document.querySelector('.txtPromedio')

  medicion1.addEventListener('input', function (e) {
    if (medicion2.value) {
      let truncadoPromedio = (parseFloat(e.target.value) + parseFloat(medicion2.value)) / 2;
      promedio.value = truncadoPromedio.toFixed(2);
    }
  })

  medicion2.addEventListener('input', function (e) {
    if (medicion1.value) {
      let truncadoPromedio = (parseFloat(e.target.value) + parseFloat(medicion1.value)) / 2;
      promedio.value = truncadoPromedio.toFixed(2);
    }
  })
}

///ENVIAR IMAGENES AL BACKEND PARA EL INGRESO///

if (document.getElementById("uploadButton")) {
  const uploadButton = document.getElementById("uploadButton");
  const nlote = document.querySelector(".txtLote").value;
  uploadButton.addEventListener("click", uploadFiles);

  function uploadFiles(event) {
    document.getElementById("spinner").style.display = "block";
    document.getElementById("overlay").style.display = "block";
    event.preventDefault();
    const fileInput = document.getElementById("formFileMultiple");
    const selectedFiles = fileInput.files;

    // Check if any files are selected
    if (selectedFiles.length === 0) {
      alert("Please select at least one file to upload.");
      return;
    } else {
      const formData = new FormData();
      // Append each selected file to the FormData object
      for (let i = 0; i < selectedFiles.length; i++) {
        let size = selectedFiles[i].size;
        if (size <= 2097152) {
          formData.append("files[]", selectedFiles[i]);
        } else {
          alert("Tamaño máximo 2MB por imagen.");
          location.reload();
          return false;
        }
      }


      const xhr = new XMLHttpRequest();
      xhr.open("POST", "/calidad/ajax/ajax/uploadFiles?lote=" + nlote, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            // Handle successful response from the server
            alert("Archivo Guardado con éxito.");
            location.reload();
          } else {
            // Handle error response from the server
            alert("Error al subir archivo.");
          }
        }
      };
      xhr.send(formData);
    }
  }
}

///ENVIAR IMAGENES AL BACKEND PARA EL INGRESO FIN/////////////

///MOSTRAR IMAGEN EN MODAL////////////////////

let img = document.querySelectorAll(".img-recepcion ");
let imgModal = document.getElementById("modal-img");

img.forEach((event) => {
  event.addEventListener("click", function (e) {
    e.preventDefault();
    imgModal.src = e.target.src;
  });
});

///MOSTRAR IMAGEN EN MODAL FIN///////////////

///GENERAR PDF INFORME DE RECEPCION///////

if (document.getElementById("btn-pdf")) {
  const btnPdf = document.getElementById("btn-pdf");

  btnPdf.addEventListener("click", function (e) {
    //e.preventDefault();
  
    pdf();
   
  });
}

function pdf() {
  document.getElementById("spinner").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  window.jsPDF = window.jspdf.jsPDF;

  let Inspector = document.querySelector('.textInspector').value;
  const cliente = document.getElementById("spn-cliente").textContent;
  const usuario = document.getElementById("spn-usuario").textContent;
  const especie = document.querySelector(".txtEspecie").value;
  const variedad = document.querySelector(".txtVariedad").value;
  const productor = document.querySelector(".txtProductor").value;
  const fechaCosecha = document.querySelector(".dateFechaCosecha").value;
  const fechaRecepcion = document.querySelector(".fecha-recepcion").value;
  const fechaInspeccion = document.querySelector(".dateFechaInspeccion").value;
  const lote = document.querySelector(".txtLote").value;
  const frutos = document.querySelector(".txtFrutos").value;
  const bins = document.querySelector(".txtBins").value;
  const guia = document.querySelector(".txtGuia").value;
  const ggn = document.querySelector(".txtGgn").value;
  const expoDefecto = document.querySelector(".txtTotalExpoDefecto").textContent;


  obtenerJugoRecepcionCalidad(lote, function (resJugo) {
    obtenerSemillasRecepcionCalidad(lote, function (resSemilla) {
      obtenerDefectosRecepcionCalidad(lote, function (result) {
        obtenerCalibresRecepcionCalidad(lote, function (response) {
          obtenerPesosCitricosRecepcionCalidad(lote, function (resPesosCitricos) {
          // Crear un nuevo documento PDF
          let pdf = new jsPDF();
          let img = new Image();

          img.src = "/calidad/view/assets/images/icons/grc_sf.png";
          pdf.addImage(img, "png", 10, -1, 20, 20);
          pdf.rect(150, 1, 50, 19);
          pdf.setFontSize(8);
          pdf.text("R5-POE-03", 155, 6);
          pdf.text("Rev. 01", 155, 11);
          pdf.text("Fecha Actualización 16-04-2024", 155, 16);
          pdf.setFontSize(12);
          pdf.setFont(undefined, 'bold');
          pdf.text("INFORME RECEPCION DE CALIDAD", 60, 10);
          pdf.setFont(undefined, 'normal');

          pdf.setFontSize(9);
          pdf.rect(10, 21, 190, 30);
          pdf.text("Cliente         " + cliente, 15, 26);
          pdf.text("Planta          " + "GRCFRUIT SPA", 15, 30);
          pdf.text("Ggn             " + ggn, 15, 34);
          pdf.text("Inspector      " + Inspector, 15, 38);
          pdf.text("Especie        " + especie, 15, 42);
          pdf.text("Variedad      " + variedad, 15, 46);
          pdf.text("Productor     " + productor, 15, 50);
          pdf.text("Fecha Cosecha       " + fechaCosecha, 120, 26);
          pdf.text("Fecha Recepcion    " + fechaRecepcion, 120, 30);
          pdf.text("Fecha Inspeccion    " + fechaInspeccion, 120, 34);
          pdf.text("Lote                         " + lote, 120, 38);
          pdf.text("N° Bins                    " + bins, 120, 42);
          pdf.text("N° Guia                   " + guia, 120, 46);
          pdf.text("N° Muestra              " + frutos, 120, 50);

          pdf.setFontSize(9);
          pdf.rect(10, 55, 190, 7);
          pdf.setFont(undefined, 'bold');
          pdf.text("ESTIMACION DISTRIBUCION DE CALIBRES", 60, 60);
          pdf.setFont(undefined, 'normal');

          pdf.text("Calibre", 15, 66);
          pdf.text("N° Frutos", 45, 66);
          pdf.text("Porcentaje %", 60, 66);

          let y = 0;
          let currentY = 66;
          let xArray = [];
          let yArray = [];
          let totalFrutos = 0;
          let totalPorcentaje = 0;

          for (const dato in response) {
            let cantidad = String(response[dato]["cantidad"]);
            let porce = String(response[dato]["porcentaje"]);
            let calibre = String(response[dato]["calibre"]);

            totalFrutos += parseInt(cantidad);
            totalPorcentaje += parseInt(porce);
            xArray[dato] = calibre;
            yArray[dato] = cantidad;

            y += 4;
            pdf.text(calibre, 15, currentY + y);
            pdf.text(cantidad, 46, currentY + y);
            pdf.text(porce, 60, currentY + y);
          }

          currentY += y + 5;
          pdf.setFont(undefined, 'bold');
          pdf.text("Total", 15, currentY);
          pdf.text(String(totalFrutos), 46, currentY);
          pdf.text(String(totalPorcentaje), 60, currentY);
          pdf.setFont(undefined, 'normal');

          pdf.rect(10, currentY + 5, 190, 7);
          pdf.setFont(undefined, 'bold');
          pdf.text("ESTIMACION CAUSAS DE DESCARTE", 60, currentY + 10);

          pdf.setFont(undefined, 'normal');
          pdf.text("Tipo Defecto", 15, currentY + 17);
          pdf.text("Defecto", 40, currentY + 17);
          pdf.text("Cantidad", 80, currentY + 17);
          pdf.text("Porcentaje %", 100, currentY + 17);

          let yy = 0;
          let xArrayDef = [];
          let yArrayDef = [];
          let totalCantidadDef = 0;
          let totalPorcentajeDef = 0;
          for (const dato in result) {
            let cantidad = String(result[dato]["cantidad"]);
            let porce = String(result[dato]["porcentaje"]);
            let calibre = String(result[dato]["calibre"]);
            let tipoDefecto = String(result[dato]["tipo_defecto"]);
            let defecto = String(result[dato]["defecto"]);

            totalCantidadDef += parseInt(cantidad);
            totalPorcentajeDef += parseInt(porce);

            xArrayDef[dato] = defecto;
            yArrayDef[dato] = cantidad;

            yy += 4;
            pdf.text(tipoDefecto, 15, currentY + 18 + yy);
            pdf.text(defecto, 40, currentY + 18 + yy);
            pdf.text(cantidad, 80, currentY + 18 + yy);
            pdf.text(porce, 100, currentY + 18 + yy);
          }

          pdf.setFont(undefined, 'bold');
          pdf.text("Total", 15, currentY + 19 + yy + 4);
          pdf.text(String(totalCantidadDef), 80, currentY + 19 + yy + 4);
          pdf.text(String(totalPorcentajeDef), 100, currentY + 19 + yy + 4);
          pdf.text(String(expoDefecto), 15, currentY + 22 + yy + 4);
          pdf.setFont(undefined, 'normal');

          const barColors = ["red", "green", "blue", "orange", "brown"];
          new Chart("myChart", {
            type: "bar",
            data: {
              labels: xArray,
              datasets: [
                {
                  backgroundColor: barColors,
                  data: yArray,
                },
              ],
            },
            options: {
              legend: { display: false },
              title: {
                display: true,
                text: "Calibres",
              },
            },
          });

          new Chart("ChartDefectos", {
            type: "bar",
            data: {
              labels: xArrayDef,
              datasets: [
                {
                  backgroundColor: barColors,
                  data: yArrayDef,
                },
              ],
            },
            options: {
              legend: { display: false },
              title: {
                display: true,
                text: "Defectos",
              },
            },
          });

          let porcentaje=0; 
          currentY = currentY + 20 + yy + 4;

          pdf.rect(10, currentY + 4, 190, 7);
          pdf.setFont(undefined, 'bold');
          pdf.text("INFORME SEMILLAS", 60, currentY + 9);
          pdf.setFont(undefined, 'normal');

          pdf.text("Semillas", 15, currentY + 16);
          pdf.text("N° Frutos", 32, currentY + 16);;
          pdf.text("Porcentaje %", 50, currentY + 16);

          let ey = 0;
          if (resSemilla.length > 0) {
        
          for (dato in resSemilla) {
          cantidad = String(resSemilla[dato]["cantidad"]);
          porcentaje = String(resSemilla[dato]["porcentaje"]);
          semillas = String(resSemilla[dato]["semillas"]);
          ey += 4;
          pdf.text(semillas, 15, currentY + 18 + ey);
          pdf.text(cantidad, 32, currentY + 18 + ey);
          pdf.text(porcentaje, 50, currentY + 18 + ey);
          
          }
        }
         
       
        let acidez=0; 
        let brix=0; 
        let pesoFruta=0; 
        let pesoJugo=0; 
        let calculos=0; 
        let gastoNaoh=0; 

         if(resJugo.length > 0){
          
          porcentaje = String(resJugo[0]["porcentaje"]);
          acidez = String(resJugo[0]["acidez"]);
          brix = String(resJugo[0]["brix"]);
          pesoFruta = String(resJugo[0]["peso_fruta"]);
          pesoJugo = String(resJugo[0]["peso_jugo"]);
          calculos = String(resJugo[0]["calculos"]);
          gastoNaoh = String(resJugo[0]["gastoNaoh"]);
          
         }
          currentY = currentY + 30;

          pdf.rect(10, currentY, 190, 7);
          pdf.setFont(undefined, 'bold');
          pdf.text("INFORME JUGOS", 60, currentY + 4);
          pdf.setFont(undefined, 'normal');

          pdf.text("Peso Fruta", 15, currentY + 12);
          pdf.text("Peso Jugo", 35, currentY + 12);
          pdf.text("Porcentaje %", 52, currentY + 12);
          pdf.text("Gasto Naoh", 80, currentY + 12);
          pdf.text("Acidez", 100, currentY + 12);
          pdf.text("Brix", 115, currentY + 12);
          pdf.text("Calculos", 130, currentY + 12);

          pdf.text(String(pesoFruta), 15, currentY + 18);
          pdf.text(String(pesoJugo), 35, currentY + 18);
          pdf.text(String(porcentaje), 52, currentY + 18);
          pdf.text(String(gastoNaoh), 80, currentY + 18);
          pdf.text(String(acidez), 100, currentY + 18);
          pdf.text(String(brix), 115, currentY + 18);
          pdf.text(String(calculos), 130, currentY + 18);

          currentY = currentY + 30;

          pdf.rect(10, currentY, 190, 20);
          pdf.setFont(undefined, 'bold');
          pdf.text("Informe Pesos Citricos", 60, currentY + 5);
          pdf.setFont(undefined, 'normal');
          pdf.text("Cantidad Frutos", 15, currentY + 12);
          pdf.text("Medicion 1", 50, currentY + 12);
          pdf.text("Medicion 2", 80, currentY + 12);
          pdf.text("Promedio", 110, currentY + 12);

          if (resPesosCitricos.length > 0) {
            for (dato in resPesosCitricos) {
              cantidad = String(resPesosCitricos[dato]["cantidad_frutos"]);
              medicion1 = String(resPesosCitricos[dato]["medicion_1"]);
              medicion2 = String(resPesosCitricos[dato]["medicion_2"]);
              promedio = String(resPesosCitricos[dato]["promedio_presion"]);
              pdf.text(cantidad, 15, currentY + 18);
              pdf.text(medicion1, 50, currentY + 18);
              pdf.text(medicion2, 80, currentY + 18);
              pdf.text(promedio, 110, currentY + 18);
            }
          }

          

          pdf.setFont(undefined, 'bold');
          pdf.text("Observaciones", 15, currentY + 40);
          pdf.setFont(undefined, 'normal');
          pdf.text(document.querySelector(".txtObservaciones").value, 15, currentY + 45);

          
          pdf.text("Inspector", 15, currentY + 60);
          pdf.text(Inspector, 15, currentY + 65);

          pdf.text("Verificado por", 120, currentY + 60);

          // Esperar un momento antes de obtener la imagen del canvas y agregarla al PDF
          setTimeout(function () {
            let canvas = document.getElementById("myChart");
            let canvasDef = document.getElementById("ChartDefectos");
            let imageData = canvas.toDataURL("image/png");
            let imageDataDef = canvasDef.toDataURL("image/png");
            //let pdf = new jsPDF();
            pdf.addPage("a4", "portrait");
            pdf.addImage(imageData, "PNG", 15, 10, 180, 100);
            pdf.addImage(imageDataDef, "PNG", 15, 150, 180, 100);
            document.getElementById("spinner").style.display = "none";
            document.getElementById("overlay").style.display = "none";
            pdf.save("Recepcion Calidad Lote: " + lote + ".pdf");
          }, 1000); // Esperar 1 segundo (ajusta este valor según sea necesario)
        });
      });
    });
  });
});

function obtenerPesosCitricosRecepcionCalidad(lote, callback) {
  const datos = {
    tipoDato: "obtenerPesosCitricos",
    lote: lote,
  };
  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        callback(response);
      }
    }
  };
  xhr.send(jsonData);
}

function obtenerCalibresRecepcionCalidad(lote, callback) {
  const datos = {
    tipoDato: "obtenerDistribucionCalibres",
    lote: lote,
  };

  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        callback(response);
      }
    }
  };
  xhr.send(jsonData);
}

function obtenerDefectosRecepcionCalidad(lote, callback) {
  const datos = {
    tipoDato: "obtenerDefectosCalibres",
    lote: lote,
  };

  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        callback(response);
      }
    }
  };
  xhr.send(jsonData);
}

function obtenerSemillasRecepcionCalidad(lote, callback) {
  const datos = {
    tipoDato: "obtenerSemillas",
    lote: lote,
  };

  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        
        callback(response);
       
      }
    }
  };
  xhr.send(jsonData);
}

function obtenerJugoRecepcionCalidad(lote, callback) {
  const datos = {
    tipoDato: "obtenerJugo",
    lote: lote,
  };

  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
      
        const response = JSON.parse(xhr.responseText);
      
          callback(response);
      
      }
    }
  };
  xhr.send(jsonData);
}

function obtenerPesosCitricos(lote, callback) {
  const datos = {
    tipoDato: "obtenerPesosCitricos",
    lote: lote,
  };

  jsonData = JSON.stringify(datos);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/calidad/ajax/ajax", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
      
        const response = JSON.parse(xhr.responseText);
      
          callback(response);
      
      }
    }
  };
  xhr.send(jsonData);
}





if (document.getElementById("delete-foto")) {
  let linksDelete = document.querySelectorAll(".delete-foto");
  linksDelete.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      let id = link.getAttribute("data-foto-del");
      console.log(id);  
      const datos = {
        tipoDato: "eliminarFoto",
        id: id,
      };

      jsonData = JSON.stringify(datos);
      console.log(jsonData);  
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "/calidad/ajax/ajax", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response["respuesta"] == 1) {
              location.reload();
            } else {
              alert(response["mensaje"]);
            }
          }
        }
      }
      xhr.send(jsonData);
    });
  });
}

}

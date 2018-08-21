var numDivNivel = [];  //Llevará la cuenta de los divs que se han creado en cada nivel
var numNivel = [];     //Contendrá el número de divs que hay en el momento, este decrece cada que se borra un div
var form = "#formFormulario";
var campos = "";
var IdPlantillaDiv = [];

$(document).ready(function () {
    var paginaExito = "catalogos/lista_formularios.php";
    var controlador = "WEB-INF/Controllers/Catalogos/Controller_Tipo_Documento.php";

    /*validate form*/
    $(form).validate({
        errorElement: 'div',
        rules: {
            nombre: {required: true, maxlength: 200}       
        }, messages: {
            nombre: {required: " * Ingresa el nombre", maxlength: " * Ingresa m\u00e1ximo 200 caracteres"}
        }
    });

    numDivNivel["0"] = 1;
    numDivNivel["1"] = 0;
    
    numNivel["0"] = 1;
    numNivel["1"] = 0;
    
    campos = "1";

    /*Prevent form*/
    $(form).submit(function (event) {
        if ($(form).valid()) {            
            loading("Cargando ...");
            /* stop form from submitting normally */
            event.preventDefault();
            /*Serialize and post the form*/
            $.post(controlador, {form: $(form).serialize(), campos: campos}).done(function (data) {
                $('#mensajes').html(data);
                if(data.toString().indexOf("Error:") === -1) {
                    $('#contenidos').load(paginaExito, function() {
                        finished();
                    });
                } else {
                    finished();
                }
            });
        }
    });

    $('.boton').button().css('margin-top', '20px');

    addRulesCampo("1");

    $("#seccion").multiselect({
        multiple: false,
        noneSelectedText: "No ha seleccionado",
        selectedList: 1
    }).multiselectfilter({
        label: 'Filtro',
        placeholder: 'Escribe el filtro'
    }).css('max-width', '150px');

    $("#tema").multiselect({
        multiple: false,
        noneSelectedText: "No ha seleccionado",
        selectedList: 1
    }).multiselectfilter({
        label: 'Filtro',
        placeholder: 'Escribe el filtro'
    }).css('max-width', '150px');
    
    $("#subtema").multiselect({
        multiple: false,
        noneSelectedText: "No ha seleccionado",
        selectedList: 1
    }).multiselectfilter({
        label: 'Filtro',
        placeholder: 'Escribe el filtro'
    }).css('max-width', '150px');

    $("#xml").change(function(){
         if ($("#xml").val() == "") {
            return;
        }
        // get the inputs value
        var inputs = $("input[type=file]"),
        files = [];
        for (var i = 0; i < inputs.length; i++) {
            files.push(inputs.eq(i).prop("files")[0]);
        }

        var formData = new FormData();
        $.each(files, function(key, value)
        {
            formData.append(key, value);
        });
        $.ajax({
            url: "WEB-INF/Controllers/Catalogos/Controller_Analizar_XML.php",
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(datas, textStatus, jqXHR)
            {
                $("#nivel_0").html(datas);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            },
            complete: function()
            {
            }
        });
        
    });
    
});

function agregarMismoNivel(divSuperior,divActual,descripcion,IdPlantilla){
    //Aquí se agregará otro div al nivel superior para que queden los divs en la misma posición
    
    var elementoMismoNivel = numDivNivel[divSuperior]; //Con esto obtenemos el ultimo elemento que hay en este nivel
    var aux = divActual.split("_");
    aux[(aux.length) - 1] = elementoMismoNivel + 1;
    var nuevoDiv = aux.join("_");

    var paddingLeft = $("#nivel_" + divActual).css('padding-left');
    
    $("#nivel_" + divSuperior).append(
        "<div id='nivel_" + nuevoDiv + "' style='padding-left: " + paddingLeft + "'>" +
        "<div style='margin-top: 6px; float:left;'><input type='text' id='campo_" + nuevoDiv + "' name='campo_" + nuevoDiv + "'><select id= 'campo_'"+nuevoDiv+"><option>Varchar</option><option>int</option></select></div>" +
        "<div><img src='resources/images/add.png' title='Nuevo campo' width='30px' height='30px' onclick='agregarMismoNivel(\"" + divSuperior + "\",\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' />" +
        "<img src='resources/images/add.png' title='Nuevo campo' width='20px' height='20px' onclick='agregarNuevoNivel(\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' />" +
        "<img src='resources/images/Erase.png' title='Eliminar elemento' width='20px' height='20px' onclick='eliminarCampo(\"" + divSuperior + "\",\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' /></div>" +
        "</div>"
    );
    
    addRulesCampo(nuevoDiv);
    if(typeof descripcion != "undefined"){  //Solo entra en esta condición cuando se edita un registro
        $("#campo_" + nuevoDiv).val(descripcion);
        IdPlantillaDiv[IdPlantilla] = nuevoDiv;
    }
    
    campos = campos + "," + nuevoDiv;
    numDivNivel[divSuperior] = numDivNivel[divSuperior] + 1;
    numNivel[divSuperior] = numNivel[divSuperior] + 1;
}

function agregarNuevoNivel(divActual,descripcion,IdPlantilla,IdPlantillaSuperior){  //El divActual es como el divPadre del que vamos a generar
    
    if(typeof IdPlantillaSuperior != "undefined"){
        divActual = IdPlantillaDiv[IdPlantillaSuperior];
    }
    
    var numElemento = 1;
    if(typeof numDivNivel[divActual] != "undefined"){
        numElemento = numDivNivel[divActual] + 1;
        numDivNivel[divActual] = numDivNivel[divActual] + 1;
        numNivel[divActual] = numNivel[divActual] + 1;
    }else{
        numDivNivel[divActual] = 1;
        numNivel[divActual] = 1;
    }
    
    var nuevoDiv = divActual + "_" + numElemento;
    
    var paddingLeft = $("#nivel_" + divActual).css('padding-left');
    
    $("#nivel_" + divActual).append(
        "<div id='nivel_" + nuevoDiv + "' style='padding-left: " + paddingLeft + "'>" +
        "<div style='margin-top: 6px; float:left;'><input type='text' id='campo_" + nuevoDiv + "' name='campo_" + nuevoDiv + "'></div>" +
        "<div><img src='resources/images/add.png' title='Nuevo campo' width='30px' height='30px' onclick='agregarMismoNivel(\"" + divActual + "\",\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' />" +
        "<img src='resources/images/add.png' title='Nuevo campo' width='20px' height='20px' onclick='agregarNuevoNivel(\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' />" +
        "<img src='resources/images/Erase.png' title='Eliminar elemento' width='20px' height='20px' onclick='eliminarCampo(\"" + divActual + "\",\"" + nuevoDiv + "\");return false;' style='cursor: pointer;' /></div>" +
        "</div>"
    );
    
    campos = campos + "," + nuevoDiv;
    addRulesCampo(nuevoDiv);
    if(typeof descripcion != "undefined"){  //Solo entra en esta condición cuando se edita un registro
        $("#campo_" + nuevoDiv).val(descripcion);
        IdPlantillaDiv[IdPlantilla] = nuevoDiv;
    }
}

function eliminarCampo(divSuperior,divEliminar){
    numNivel[divSuperior] = numNivel[divSuperior] - 1;
    $("#nivel_"+divEliminar).remove();
}

function addRulesCampo(elem){
    $("#campo_" + elem).rules("add", {
        required: true,
        maxlength: 150,
        messages: {
            required: " * Ingresa el nombre del campo",
            maxlength: " * Ingresa m\u00e1ximo 150 caracteres"
        }
    });
}

function deleteRulesCampo(elem){
    $("#campo_" + elem).rules("remove");
}

function setPrimerId(id){
    IdPlantillaDiv[id] = "1";
}

function aumentarContadorDiv(div, nuevoDiv){
    if(typeof numDivNivel[div] != "undefined"){
        numDivNivel[div] = numDivNivel[div] + 1;
        numNivel[div] = numNivel[div] + 1;
    }else{
        numDivNivel[div] = 1;
        numNivel[div] = 1;
    }
    campos = campos + "," + nuevoDiv;
    addRulesCampo(nuevoDiv);
}
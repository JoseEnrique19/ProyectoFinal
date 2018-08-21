$(document).ready(function () {
    var paginaExito = "catalogos/lista_alumnos.php";
    var controlador = "WEB-INF/Controllers/Catalogos/Controller_Alumnos.php";
    var form = "#formAlumno";

    /*validate form*/
    $(form).validate({
        errorElement: 'div',
        rules: {
            matricula: {required:true, maxlength: 10, number: true},
            nombre: {required: true, maxlength: 100},
            aPaterno: {required: true, maxlength: 100},
            aMaterno: {maxlength: 100},
            telefono: {maxlength: 10, number: true},
            grupo: {required:true, maxlength: 10},
            generacion: {required: true}
        }, messages: {
            matricula: {required: "* Ingresa la matricula", maxlength: "* Ingresa 10 caracteres", number: "* Ingresa solo numeros"},
            nombre: {required: " * Ingresa el nombre", maxlength: " * Ingresa m\u00e1ximo 100 caracteres"},
            aPaterno: {required: " * Ingresa el apellido materno", maxlength: " * Ingresa m\u00e1ximo 100 caracteres"},
            aMaterno: {maxlength: " * Ingresa m\u00e1ximo 100 caracteres"},
            telefono: {maxlength: "*Ingresa m\u00e1ximo 10 caracteres", number: "* Ingresa solo numeros"},
            grupo: {required:"* Ingresa el grupo" ,maxlength: "* Ingresa solo 6 digitos"},
            generacion: {required: "*Selecione un periodo"}
        }
    });
    
    /*Prevent form*/
    $(form).submit(function (event) {
        if ($(form).valid()) {            
            loading("Cargando ...");
            /* stop form from submitting normally */
            event.preventDefault();
            /*Serialize and post the form*/
            $.post(controlador, {form: $(form).serialize()}).done(function (data) {
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
    
    //$('.boton').button().css('margin-top', '20px');

    $('.generacion').each(function() {
        $(this).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            minDate: '-0D'
        });
    });

    if ($('#activo').is(':checked')) {
        $('#comentario').hide();
        $('#coment').hide();
        $('#comentario').val('*Sin estado*'); 
    }else{
        $('#comentario').show(); //muestro mediante id
        $('#coment').show(); 
    }

    $("#activo").on( 'change', function() {
        if( $(this).is(':checked') ) {
            // Hacer algo si el checkbox ha sido seleccionado
            $('#comentario').hide(); //oculto mediante id
            $('#coment').hide();
            $('#comentario').val('*Sin estado*');
            //alert("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            $('#comentario').show(); //muestro mediante id
            $('#coment').show();    
            //alert("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
        }
    });
});



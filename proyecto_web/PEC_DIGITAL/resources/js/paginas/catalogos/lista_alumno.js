var oTable;
$(document).ready(function() {
    var espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ning\u00fan dato disponible en esta tabla",
        "sInfo": "Mostrando de _START_ a _END_ de  _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 registros",
        "sInfoFiltered": "(filtrado de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "\u00daltimo",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    };
    oTable = $('#tAlumno').dataTable({
        "bJQueryUI": true,
        "bRetrieve": true,
        "bDestroy": true,
        "oLanguage": espanol,
        "sPaginationType": "full_numbers",
        "bDeferRender": true,
        "iDisplayLength": 25
    });

    function cambiarContenidosSinSesion(pagina, li) {

        $("#contenidos").empty();
        limpiarMensaje();
        $("#contenidos").load(pagina, function (response, status, xhr) {
            if (status == "error") {
                var msg = "Lo sentimos, se ha presentado el error 101 del sistema, nuestros ingenieros están actualmente trabajando en el desarrollo de éste módulo: ";
                $("#mensajes").html(msg + xhr.status + " " + xhr.statusText);
            } 
        });
        
        //Esto se hace para cargar el span en el li elegido, y quitar el span que este puesto actualmente
        var html = $('#nav span').html();
        $('#nav span').remove();
        $("#li"+anterior).html(html);
        $("#li"+li).html("<span>"+ $("#li"+li).html() +"</span>");
        anterior = li;
    }

    function borrados() {
        if ($('#checksc').is(':checked')) {
            cambiarContenidosSinSesion("catalogos/lista_alumnos.php?mostrar=" + $('#checksc').val(), "Alumnos Borrados");
        } else {
            cambiarContenidosSinSesion("catalogos/lista_alumnos.php?mostrar=0", "Alumnos Borrados");
        }
    }
    $("#checksc").on( 'change', function() {
        if( $(this).is(':checked') ) {
            // Hacer algo si el checkbox ha sido seleccionado
            cambiarContenidosSinSesion("catalogos/lista_alumnos.php?mostrar=" + $('#checksc').val(), "Alumnos Borrados");
            //$('#comentario').hide(); //oculto mediante id
            //$('#coment').hide();
            //$('#comentario').val('*Sin estado*');
            //alert("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            //$('#comentario').show(); //muestro mediante id
            //$('#coment').show();    
            cambiarContenidosSinSesion("catalogos/lista_alumnos.php?mostrar=0", "Alumnos Borrados");
            //alert("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
        }
    });

});
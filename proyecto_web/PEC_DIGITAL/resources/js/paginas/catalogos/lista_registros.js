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
    oTable = $('#tRegistros').dataTable({
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

});
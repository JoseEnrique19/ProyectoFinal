var anterior;

$(document).ready(function () {

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

function cambiarContenidos(pagina) {
    var indice = "principal.php";
    $("#contenidos").empty();
    limpiarMensaje();
    $('#loading_text').load("verificaSession.php", function (data) {
        if (data.toString().indexOf("false") === -1) {/*En caso de que la sesion siga existiendo*/
            $("#contenidos").load(pagina, function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Lo sentimos, se ha presentado el error 101 del sistema, nuestros ingenieros están actualmente trabajando en el desarrollo de éste módulo: ";
                    mostrarMensaje(msg + xhr.status + " " + xhr.statusText);
                } 
            });
        } else {
            mostrarMensaje("Tu sesión ha caducado o ha sido cerrada desde otra pestaña.");
            window.location = indice + "?session=4";
        }
    });
}

function limpiarMensaje() {
    $('#mensajes').empty();
}

function cambiarContenidosEditando1Id(pagina, funcion, id) {
    var indice = "principal.php";

    $("#contenidos").empty();
    limpiarMensaje();
    $('#loading_text').load("verificaSession.php", function (data) {
        if (data.toString().indexOf("false") === -1) {/*En caso de que la sesion siga existiendo*/
            $("#contenidos").load(pagina, {'id': id,'accion':funcion}, function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Lo sentimos, se ha presentado el error 101 del sistema, nuestros ingenieros están actualmente trabajando en el desarrollo de éste módulo: ";
                    $("#mensajes").html(msg + xhr.status + " " + xhr.statusText);
                } 
            });
        } else {
            $('#mensajes').html("Tu sesión ha caducado o ha sido cerrada desde otra pestaña.");
            window.location = indice + "?session=4";
        }
    });
}

function eliminarRegistro1Id(controlador, lista, id) {
    var indice = "principal.php";

    $('#loading_text').load("verificaSession.php", function (data) {
        if (data.toString().indexOf("false") === -1) {/*En caso de que la sesion siga existiendo*/            
            if (confirmarMensaje("¿Esta seguro que desea eliminar este registro?")) {
                $('#mensajes').load(controlador, {'erase': true, 'id': id}, function (data) {
                    $("#mensajes").html(data);
                    $('#contenidos').load(lista, function () {
                        
                    });
                });
            }
        } else {
            $('#mensajes').html("Tu sesión ha caducado o ha sido cerrada desde otra pestaña.");
            window.location = indice + "?session=4";
        }
    });
}

function confirmarMensaje(mensaje) {
    if(confirm(mensaje)){
        return true;
    }
    return false;
}

function mostrarMensaje(mensaje){
    $("#mensajes").html(mensaje);
    $(function() {
        $( "#mensajes" ).dialog();
    });
}

function loading(mensaje) {
    $("#cargando").show();
    $("#loading_text").text(mensaje);
}

function finished() {
    $("#loading_text").text("");
    $("#cargando").hide();
}

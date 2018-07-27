$(document).ready(function() {
    var form = "#frmLogin";
    
    /*validate form*/
    $(form).validate({
        errorElement: 'div',
        rules: {
            username: {required: true, maxlength: 50, minlength: 2},
            password: {required: true, maxlength: 100, minlength: 2}            
        }, messages: {
            username: {required: " * Ingrese el nombre de usuario", maxlength: " * Ingresa m\u00e1ximo {0} caracteres", minlength: " * Ingresa m\u00ednimo {0} caracteres"},
            password: {required: " * Ingrese el password", maxlength: " * Ingresa m\u00e1ximo {0} caracteres", minlength: " * Ingresa m\u00ednimo {0} caracteres"}
        }
    });
    
    if($("#mensaje").length){
        $(function() {
            $( "#dialog" ).dialog();
        });
    }
});

function Encriptar() {
    if($("#password").val() !== ""){
        $("#password").val($.md5($("#password").val()));
    }
}

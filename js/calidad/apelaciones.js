$(document).ready(function(){
    
    $('#tablaEjecutivos').DataTable({});

    $("body").on("change","input[name='options']",function(){
        var ObjectMe = $(this);
        var idObject = ObjectMe.attr("id");
        $(".contenedorTablaEjecutivos").show();
    });
    $("body").on("click",".Notas",function(){
        var Template = $("#notasTemplate").html();
        bootbox.dialog({
            title: "NOTAS",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: "medium"
        });
        $('#tablaNotas').DataTable({});
    });
    $("body").on("change",".checkEntregada",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectCheckAprobada = ObjectTR.find(".checkAprobada");
        if(!ObjectMe.is(':checked')){
            ObjectCheckAprobada.prop("checked",false);
            ObjectCheckAprobada.prop("disabled",true);
        }else{
            ObjectCheckAprobada.prop("disabled",false);
        }
    });
    $("body").on("click",".verObjeciones",function(){
        var Template = $("#objecionesTemplate").html();
        bootbox.dialog({
            title: "OBJECIONES",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: "large"
        });
        $('#tablaObjeciones').DataTable({});
    });
    $("body").on("click","button[name='agregarObjecion']",function(){
        var Template = $("#agregarObjecionTemplate").html();
        bootbox.dialog({
            title: "AGREGAR OBJECIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: "medium"
        });
    });

});
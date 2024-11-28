$(document).ready(function(){
    getTipoContactoConfigurados();

    getEvaluadores();

    $("button[name='addTipoContacto']").click(function(){
        var Template = $("#addTipoContactoTemplate").html();
        bootbox.dialog({
            title: "TIPO DE CONTACTO",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var idTipoContacto = $("select[name='TipoContacto']").val();
                        var duracionMin = $("input[name='duracionMin']").val();
                        var duracionMax = $("input[name='duracionMax']").val();
                        if(idTipoContacto != ""){
                            configurarTipoContacto(idTipoContacto,duracionMin,duracionMax);
                        }else{
                            bootbox.alert("Debe seleccionar un tipo de Contacto");
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'small'
        }).off("shown.bs.modal");
        getTipoContactoNoConfigurados();
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("click",".updateCantEvaluaciones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPersonal = ObjectDiv.attr("id");
        var Template = $("#updateCantidadEvaluacionesTemplate").html();
        bootbox.dialog({
            title: "CANTIDAD DE EVALUACIONES",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var cantidadEvaluaciones = $("input[name='cantidadEvaluaciones']").val();
                        if((cantidadEvaluaciones != "") && (cantidadEvaluaciones > 0)){
                            updateCantidadEvaluaciones(idPersonal,cantidadEvaluaciones);
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'small'
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("click",".deleteTipoContacto",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idTipoContacto = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar el tipo de contacto seleccionado?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    deleteTipoContacto(idTipoContacto);
                }
            }
        });
    });

    function getEvaluadores(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getEvaluacionesSemanalesPorEvaluador.php",
            data: { },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    $('#TablaEvaluadores').DataTable({
                        data: data,
                        bDestroy: true,
                        columns: [
                            { data: 'nombreEvaluador' },
                            { data: 'Cantidad' },
                            { data: 'Accion' }
                        ],
                        "columnDefs": [ 
                            {
                                "targets": 2,
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    if(row.Status != ""){
                                        ToReturn = "<div style='text-align: center;' id='"+data+"'>"+
                                                        "<i style='cursor: pointer;font-size: 20px;' class='fa fa-edit updateCantEvaluaciones'></i>"+
                                                    "</div>";
                                    }
                                    return ToReturn;
                                }
                            }
                        ]
                    });
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateCantidadEvaluaciones(idPersonal,cantidadEvaluaciones){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/updateEvaluacionesSemanalesPorEvaluador.php",
            data: {
                idPersonal: idPersonal,
                cantidadEvaluaciones: cantidadEvaluaciones
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    getEvaluadores();
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function getTipoContactoConfigurados(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipoContactoEvaluacionesAutomaticas.php",
            data: { },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    $('#TablaTipoContacto').DataTable({
                        data: data,
                        bDestroy: true,
                        columns: [
                            { data: 'TipoContacto' },
                            { data: 'DuracionMin' },
                            { data: 'DuracionMax' },
                            { data: 'Accion' }
                        ],
                        "columnDefs": [ 
                            {
                                "targets": 3,
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    if(row.Status != ""){
                                        ToReturn = "<div style='text-align: center;' id='"+data+"'>"+
                                            "<i style='cursor: pointer;font-size: 20px;color: red;' class='fa fa-times-circle deleteTipoContacto'></i>"+
                                        "</div>"
                                    }
                                    return ToReturn;
                                }
                            }
                        ]
                    });
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function deleteTipoContacto(idTipoContacto){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/deleteTipoContactoEvaluacionesAutomaticas.php",
            data: {
                idTipoContacto: idTipoContacto
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    getTipoContactoConfigurados();
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function getTipoContactoNoConfigurados(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipoContactoEvaluacionesAutomaticasNoConfigurdos.php",
            data: { },
            async: false,
            success: function(data){
                $("select[name='TipoContacto']").html(data);
                $("select[name='TipoContacto']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function configurarTipoContacto(idTipoContacto,duracionMin,duracionMax){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/configurarTipoContactoEvaluacionesAutomaticas.php",
            data: {
                idTipoContacto: idTipoContacto,
                duracionMin: duracionMin,
                duracionMax: duracionMax
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getTipoContactoConfigurados();
                    }else{
                        bootbox.alert("Hubo un error al configurar el tipo de contacto.");
                    }
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
});
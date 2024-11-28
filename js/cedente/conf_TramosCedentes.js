$(document).ready(function(){
    var TableDataSet = [];
    var TableTramos;

    getTramos();
    updateTable();
    
    $("#AddTramo").click(function(){
        var Template = $("#AddTramoTemplate").html();
        bootbox.dialog({
            title: "CREACIÓN DE TRAMO",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Tramo = $("input[name='Descripcion']").val();
                        var Operacion = $("select[name='Operacion']").val();
                        var Desde = $("input[name='Desde']").val();
                        var Hasta = $("input[name='Hasta']").val();
                        var CanAdd = false;
                        if(Tramo != ""){
                            if(Operacion != ""){
                                if(Desde != ""){
                                    switch(Operacion){
                                        case "0":
                                            if(Hasta != ""){
                                                CanAdd = true;
                                            }
                                        break;
                                        default:
                                            CanAdd = true;
                                        break;
                                    }
                                }else{
                                    bootbox.alert("Debe Ingresar un Rango Desde");
                                }
                            }else{
                                bootbox.alert("Debe seleccionar una operacion");
                            }
                        }else{
                            bootbox.alert("Debe ingresar una descripcion del Tramo");
                        }
                        if(CanAdd){
                            saveTramo();
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        }).off("shown.bs.modal");
        $("select.selectpicker").selectpicker("refresh");
        //getColumnasAgregar();
        $("select.selectpicker").selectpicker("refresh");
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar el Tramo seleccionado?",
            buttons:{
                confirm:{
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    deleteTramo(ID);
                }
            }
        });
    });
    $("body").on("change","select[name='Operacion']",function(){
        var ObjectMe = $(this);
        var Operacion = ObjectMe.val();
        $("input[name='Hasta']").val("");
        switch(Operacion){
            case "0":
                $("input[name='Hasta']").prop("disabled",false);
            break;
            default:
                $("input[name='Hasta']").prop("disabled",true);
            break;
        }
    });
    function getTramos(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/cedente/getTramos.php",
            data:{},
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    TableDataSet = JSON.parse(data);
                }
            }
        });
    }
    function updateTable(){
        TableTramos = $('#Tramos').DataTable({
            data: TableDataSet,
            columns: [
                { data: 'Descripcion', width: "40%" },
                { data: 'Desde', width: "10%" },
                { data: 'Hasta', width: "10%" },
                { data: 'Operacion', width: "30%" },
                { data: 'Accion', width: "10%" }
            ],
            "order": [[ 1, "asc" ]],
            "columnDefs": [
                {
                    "targets": 4,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        ToReturn = "<div style='font-size: 15px;' id='"+data+"'><i style='margin: 0px 5px; cursor: pointer;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                        return ToReturn;
                    }
                }
            ]
        });
    }
    function saveTramo(){
        var Tramo = $("input[name='Descripcion']").val();
        var Operacion = $("select[name='Operacion']").val();
        var Desde = $("input[name='Desde']").val();
        var Hasta = $("input[name='Hasta']").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/cedente/saveTramo.php",
            data:{
                Tramo: Tramo,
                Operacion: Operacion,
                Desde: Desde,
                Hasta: Hasta
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                $('#Cargando').modal('hide');
                console.log(data);
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        bootbox.alert("Tramo registrado satisfactoriamente");
                        TableTramos.destroy();
                        getTramos();
                        updateTable();
                    }
                }
            }
        });
    }
    function deleteTramo(ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/cedente/deleteTramo.php",
            data: {
                ID: ID,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    bootbox.alert("Tramo eliminado satisfactoriamente.");
                    TableTramos.destroy();
                    getTramos();
                    updateTable();
                }
            },
            error: function(){

            }
        });
    }
});
$(document).ready(function(){
    var TableDataSet = [];
    var TableFields;

    getFields();
    updateTable();
    
    $("#AddColumna").click(function(){
        var Template = $("#AddColumnaTemplate").html();
        bootbox.dialog({
            title: "ADICIÓN DE CAMPO",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Column = $("select[name='Campo']").val();
                        if(Column != ""){
                            saveColumn();
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
        getColumnasAgregar();
        $("select.selectpicker").selectpicker("refresh");
    });
    $("body").on("dblclick",".PrioridadColumn",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.html();
        var html = "<input type='text' value='"+Value+"' class='Prioridad' style='background-color: #579ddb;color:  #FFFFFF;position: absolute;left: 0;width: 100%;text-align: center;' />";
        ObjectMe.append(html);
        $(".Prioridad").focus();
        $(".Prioridad").select();
        $(".Prioridad").focus();
    });
    $("body").on("dblclick",".Prioridad",function(){
        return false;
    });
    $("body").on("keyup",".Prioridad",function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    $("body").on("blur",".Prioridad",function(){
        $(this).remove();
    });
    $("body").on("change",".Prioridad",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        var ObjectTD = ObjectMe.closest("td");
        var ObjectTR = ObjectMe.closest("tr");
        var Row = TableFields.row(ObjectTR).data();
        var cell = TableFields.cell( ObjectTD );
        var ID = Row.Accion;
        console.log(ID);
        if(Value != ""){
            cell.data(Value);
            SavePrioridad(Value,ID);
        }
    });
    $("body").on('change', '.Destacar', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var Row = TableFields.row(ObjectTR).data();
        var ID = Row.Accion;
        if($(this).is(':checked')){
            Value = 1;
        }else{
            Value = 0;
        }
        SaveDestacar(Value, ID);
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar la columna seleccionad?",
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
                    deleteColumn(ID);
                }
            }
        });
    });
    function getFields(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/getColumns_ConfCRM.php",
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
        TableFields = $('#Columnas').DataTable({
            data: TableDataSet,
            columns: [
                { data: 'Prioridad', width: "20%" },
                { data: 'Campo', width: "60%" },
                { data: 'Destacar', width: "10%" },
                { data: 'Accion', width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    className: "PrioridadColumn",
                    "data": 'Prioridad',
                },
                {
                    className: "dt-center",
                    "targets": 2,
                    "render": function (data, type, row) {
                        if(data == 1){
                            checked = 'checked'
                        }else{
                            checked = '';
                        }
                        return "<div style='text-align: center;' id='" + data + "'><input type='checkbox' id='" + row.id + "' class='toggle-switch Destacar' "+checked+"><label class='toggle-switch-label'></label></div>";
                    }
                },
                {
                    "targets": 3,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.CodigoFoco != ""){
                            ToReturn = "<div style='font-size: 15px;' id='" + data +"'><i style='margin: 0px 5px; cursor: pointer;' class='btn fa fa-trash btn-danger btn-icon icon-lg Delete'></i></div>";
                        }
                        return ToReturn;
                    }
                }
            ]
        });
    }
    function getColumnasAgregar(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/getColumnasAgregar_ConfCRM.php",
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
                $("select[name='Campo']").html(data);
            }
        });
    }
    function saveColumn(){
        var Column = $("select[name='Campo']").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/saveColumna_ConfCRM.php",
            data:{
                Column: Column
            },
            async: false,
            beforeSend: function() {
				/* $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                }); */
			},
            success: function(data){
                /* $('#Cargando').modal('hide'); */
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        bootbox.alert("Columna agregada satisfactoriamente");
                        TableFields.destroy();
                        getFields();
                        updateTable();
                    }
                }
            }
        });
    }
    function SavePrioridad(Value,ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/updatePrioridad_ConfCRM.php",
            data: {
                Value: Value,
                ID: ID,
            },
            async: false,
            success: function(data){
            },
            error: function(){

            }
        });
    }
    function SaveDestacar(Value, ID) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/updateDestacar_ConfCRM.php",
            data: {
                Value: Value,
                ID: ID,
            },
            async: false,
            success: function (data) {
            },
            error: function () {

            }
        });
    }
    function deleteColumn(ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/deleteColumn_ConfCRM.php",
            data: {
                ID: ID,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    bootbox.alert("Columna eliminada satisfactoriamente.");
                    TableFields.destroy();
                    getFields();
                    updateTable();
                }
            },
            error: function(){

            }
        });
    }
});
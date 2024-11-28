$(document).ready(function(){
    var OrdenTable;
    var OrdenArray = [];

    getOrdenTableList();
    updateOrdenTable();

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
        var Row = OrdenTable.row(ObjectTR).data();
        var cell = OrdenTable.cell( ObjectTD );
        var ID = Row.Accion;
        if(Value != ""){
            cell.data(Value);
            SavePrioridad(Value,ID);
        }
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        EliminarOrden(ID);
    });
    $("#AgregarCampo").click(function(){
        var Template = $("#AgregarCampoTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE CAMPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function(){
                        var Titulo = $("select[name='Titulo']").val();
                        var Campo = $("select[name='Campo']").val();
                        if(Titulo != ""){
                            if(Campo != ""){
                                SaveCampo();
                            }else{
                                bootbox.alert("Debe seleccionar almenos 1 campo.");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar un titulo.");
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        getOrdenCampos();
        $(".selectpicker").selectpicker("refresh");
    });

    function getOrdenTableList(Modal = true){
        $.ajax({
            type: "POST",
            url: "ajax/getOrdenNotasAspirantesExcelTableList.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                
                OrdenArray = [];
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    OrdenArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateOrdenTable(){
        OrdenTable = $('#ListaOrden').DataTable({
            data: OrdenArray,
            "bDestroy": true,
            columns: [
                { data: 'Prioridad' },
                { data: 'Titulo' },
                { data: 'Campo' },
                { data: 'Accion' },
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    className: "PrioridadColumn",
                    "data": 'Prioridad',
                },
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-times-circle icon-lg Delete'></div>";
                    }
                },
            ]
        });
    }
    function SavePrioridad(Value,ID){
        $.ajax({
            type: "POST",
            url: "ajax/updatePrioridadOrdenNotasAspirantesExcel.php",
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
    function EliminarOrden(ID){
        $.ajax({
            type: "POST",
            url: "ajax/deleteOrdenNotasAspirantesExcel.php",
            data: {
                ID: ID,
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        getOrdenTableList(false);
                        updateOrdenTable();
                    }
                }
            },
            error: function(){

            }
        });
    }
    function getOrdenCampos(){
        $.ajax({
            type: "POST",
            url: "ajax/getCamposConOrdenSelect.php",
            data: {
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                $("select[name='Campo']").html(data);
            },
            error: function(){

            }
        });
    }
    function SaveCampo(){
        var Titulo = $("input[name='Titulo']").val();
        var Campo = $("select[name='Campo']").val();
        $.ajax({
            type: "POST",
            url: "ajax/agregarOrdenNotasAspirantesExcel.php",
            data: {
                Campo: Campo,
                Titulo: Titulo
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        getOrdenTableList(false);
                        updateOrdenTable();
                    }
                }
            },
            error: function(){

            }
        });
    }
});
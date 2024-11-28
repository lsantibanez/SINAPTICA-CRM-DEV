$(document).ready(function(){

    var ClasificationTable;
    var dataSet = [];
    
    getClasificaciones();
    UpdateTableClasificacion();

    $("#AddClasificacion").click(function(){
        
        var Template = $("#TemplateAddClasificacion").html();

        bootbox.dialog({
            title: "FORMULARIO DE CLASIFICACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var nombreClasificacion = $("input[name='nombreClasificacion']").val();
                        var notaDesde = $("input[name='notaDesde']").val();
                        var notaHasta = $("input[name='notaHasta']").val();
                        var descripcionClasificacion = $("textarea[name='descripcionClasificacion']").val();
                        var CanSave = false;

                        if(nombreClasificacion != ""){
                            if(notaDesde != ""){
                                if(notaHasta != ""){
                                    if(descripcionClasificacion != ""){
                                        if(notaDesde <= notaHasta){
                                            if(CanAddNotaDesde()){
                                                if((notaDesde <= GlobalData.focoConfig.NotaMaximaEvaluacion) && (notaHasta <= GlobalData.focoConfig.NotaMaximaEvaluacion)){
                                                    CanSave = true;
                                                }else{
                                                    bootbox.alert("El Rango debe estar dentro de la nota maxima configurada para las evaluaciones");
                                                }
                                            }else{
                                                bootbox.alert("Ya existe una clasificacion con las caracteristicas ingresadas. Verifique e intentelo nuevamente.");
                                            }
                                        }else{
                                            bootbox.alert("La nota Desde debe ser mayor a la nota Hasta");
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripcion para la clasificación");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar la nota Hasta");
                                }
                            }else{
                                bootbox.alert("Debe ingresar la nota Desde");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un nombre de clasificación");
                        }
                        if(CanSave){
                            SaveClasificacion();
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'medium'
        }).off("shown.bs.modal");
    });
    $("body").on("click",".updateClasificacion",function(){
        
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idClasificacion = ObjectDiv.attr("id");
        var Template = $("#TemplateAddClasificacion").html();

        bootbox.dialog({
            title: "FORMULARIO DE CLASIFICACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var nombreClasificacion = $("input[name='nombreClasificacion']").val();
                        var notaDesde = $("input[name='notaDesde']").val();
                        var notaHasta = $("input[name='notaHasta']").val();
                        var descripcionClasificacion = $("textarea[name='descripcionClasificacion']").val();
                        var CanUpdate = false;

                        if(nombreClasificacion != ""){
                            if(notaDesde != ""){
                                if(notaHasta != ""){
                                    if(descripcionClasificacion != ""){
                                        if(notaDesde <= notaHasta){
                                            if(CanAddNotaDesdeClasificacion(idClasificacion)){
                                                if((notaDesde <= GlobalData.focoConfig.NotaMaximaEvaluacion) && (notaHasta <= GlobalData.focoConfig.NotaMaximaEvaluacion)){
                                                    CanUpdate = true;
                                                }else{
                                                    bootbox.alert("El Rango debe estar dentro de la nota maxima configurada para las evaluaciones");
                                                }
                                            }else{
                                                bootbox.alert("Ya existe una clasificacion con las caracteristicas ingresadas. Verifique e intentelo nuevamente.");
                                            }
                                        }else{
                                            bootbox.alert("La nota Desde debe ser mayor a la nota Hasta");
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripcion para la clasificación");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar la nota Hasta");
                                }
                            }else{
                                bootbox.alert("Debe ingresar la nota Desde");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un nombre de clasificación");
                        }
                        if(CanUpdate){
                            UpdateClasificacion(idClasificacion);
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'medium'
        }).off("shown.bs.modal");

        var Clasificacion = GetClasificacion(idClasificacion);
        $("input[name='nombreClasificacion']").val(Clasificacion.nombreClasificacion);
        $("input[name='notaDesde']").val(Clasificacion.notaDesde);
        $("input[name='notaHasta']").val(Clasificacion.notaHasta);
        $("textarea[name='descripcionClasificacion']").val(Clasificacion.Descripcion);
    });
    $("body").on("click",".removeClasificacion",function(){
        
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idClasificacion = ObjectDiv.attr("id");

        bootbox.confirm("¿Desea eliminar la clasificación seleccionada?", function(Result) {
            if(Result){
                DeleteClasificacion(idClasificacion);
            }
        });
    });

    function getClasificaciones(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getClasificaciones_Notas.php",
            data: {},
            async: false,
            success: function(data){
                if(isJson(data)){
                    dataSet = JSON.parse(data);
                    console.log(data);
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableClasificacion(){
        ClasificationTable = $('#Clasificaciones').DataTable({
            data: dataSet,
            bDestroy: true,
            "ordering": false,
            columns: [
                { data: 'Nombre' },
                { data: 'Descripcion' },
                { data: 'NotaDesde' },
                { data: 'NotaHasta' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 4,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='" + data +"'><i style='cursor: pointer; margin: 0 1px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateClasificacion'></i><i style='cursor: pointer; margin: 0 1px;' class='fa fa-trash btn btn-danger btn-icon icon-lg removeClasificacion'></i></div>";
                    }
                },
                { 
                    "width": "60%", 
                    "targets": 1 
                }
            ]
        });
        ClasificationTable.order([2, 'asc']).draw();
    }
    function SaveClasificacion(){
        var nombreClasificacion = $("input[name='nombreClasificacion']").val();
        var notaDesde = $("input[name='notaDesde']").val();
        var notaHasta = $("input[name='notaHasta']").val();
        var descripcionClasificacion = $("textarea[name='descripcionClasificacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveClasificacion.php",
            data: {
                nombreClasificacion: nombreClasificacion,
                notaDesde: notaDesde,
                notaHasta: notaHasta,
                descripcionClasificacion: descripcionClasificacion,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getClasificaciones();
                        UpdateTableClasificacion();
                    }
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function CanAddNotaDesde(){
        var ToReturn = false;
        var notaDesde = $("input[name='notaDesde']").val();
        var Cont = 0;
        ClasificationTable.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            if(Number(notaDesde) <= Number(data.NotaHasta)){
                Cont++;
            }
        });
        if(Cont == 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    function GetClasificacion(idClasificacion){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetClasificacion.php",
            data: {
                idClasificacion: idClasificacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function CanAddNotaDesdeClasificacion(idClasificacion){
        var ToReturn = false;
        var notaDesde = $("input[name='notaDesde']").val();
        var Cont = 0;
        ClasificationTable.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            if(Number(idClasificacion) != Number(data.Accion)){
                if(Number(notaDesde) <= Number(data.NotaHasta)){
                    Cont++;
                }
            }
        });
        if(Cont == 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    function UpdateClasificacion(idClasificacion){
        var nombreClasificacion = $("input[name='nombreClasificacion']").val();
        var notaDesde = $("input[name='notaDesde']").val();
        var notaHasta = $("input[name='notaHasta']").val();
        var descripcionClasificacion = $("textarea[name='descripcionClasificacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateClasificacion.php",
            data: {
                idClasificacion: idClasificacion,
                nombreClasificacion: nombreClasificacion,
                notaDesde: notaDesde,
                notaHasta: notaHasta,
                descripcionClasificacion: descripcionClasificacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getClasificaciones();
                        UpdateTableClasificacion();
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    function DeleteClasificacion(idClasificacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteClasificacion.php",
            data: {
                idClasificacion: idClasificacion,
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getClasificaciones();
                        UpdateTableClasificacion();
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
});
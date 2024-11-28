$(document).ready(function(){
    var CampoArray = [];
    var CamposTable;
    var ListaOpciones = [];
    var OpcionesTable;

    getCampoTableList();
    updateCamposTable();

    $("#CrearCampo").click(function(){
        var Template = $("#CrearCampoTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE CAMPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var Contenedor = $("select[name='Contenedor']").val();
                        var Codigo = $("input[name='Codigo']").val();
                        var Titulo = $("input[name='Titulo']").val();
                        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
                        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
                        var Tipo = $("select[name='Tipo']").val();
                        var Mandatorio = $("select[name='Mandatorio']").val();
                        var Deshabilitado = $("select[name='Deshabilitado']").val();
                        var CanAdd = false;
                        if(Contenedor != ""){
                            if(Codigo != ""){
                                if(Titulo != ""){
                                    if(Tipo != ""){
                                        CanAdd = true;
                                    }else{
                                        bootbox.alert("Debe ingresar un Tipo de Campo");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un Título");
                                }
                            }else{
                                bootbox.alert("Debe ingresar un Código");
                            }
                        }else{
                            bootbox.alert("Debe seleccionar un contenedor.");
                        }
                        if(CanAdd){
                            if(CanAddMandatorioSeleccionado){
                                if(CanAddCodigo(Codigo)){
                                    CrearCampoReclutamiento();   
                                }else{
                                    bootbox.alert("Código ya registrado, modifiquelo e intente nuevamente.");
                                    return false;
                                }
                            }else{
                                bootbox.alert("El campo no puede estar configurado como mandatorio y Deshabilitado.");
                                return false;
                            }
                        }else{
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        getContenedores();
        getTipos();
        updateListaOpcionesTable();
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("change","select[name='Tipo']",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        switch(Value){
            case '3':
            case '4':
                $("#SelectOptionsContainer").show();
            break;
            default:
                $("#SelectOptionsContainer").hide();
            break;
        }
    });
    $("body").on("click","#AgregarOpcion",function(){
        var Template = $("#AgregarOpcionTemplate").html()
        bootbox.dialog({
            title: "NUEVA OPCIÓN",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var Prioridad = $("input[name='Prioridad']").val();
                        var Opcion = $("input[name='Opcion']").val();
                        var Seleccionado = $("select[name='Seleccionado']").val();
                        var CanAdd = false;
                        if(Prioridad != ""){
                            if(Opcion != ""){
                                if(Seleccionado != ""){
                                    CanAdd = true;
                                }else{
                                    bootbox.alert("Debe ingresar el estado de la opción");
                                }
                            }else{
                                bootbox.alert("Debe ingresar el texto de la opción");
                            }
                        }else{
                            bootbox.alert("Debe ingresar una prioridad");
                        }
                        if(CanAdd){
                            if(CanAddPrioridad(Prioridad)){
                                if(CanAddSeleccionado(Seleccionado)){
                                    AgregarOpcionReclutamiento();
                                }else{
                                    bootbox.alert("Ya existe una opcion Con valor Seleccionado, elimine la anterior e intente nuevamente.");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Numero de prioridad existente, intente con un numero distinto.");
                                return false;
                            }
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("click",".DeleteOpcion",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        OpcionesTable.rows(ObjectTR).remove().draw();
    });
    $("body").on("keyup","input[name='Codigo']",function(){
        this.value = (this.value + '').replace(/\s/, '');
    });

    function getCampoTableList(Modal = true){
        $.ajax({
            type: "POST",
            url: "ajax/getCampoTableList.php",
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
                
                CampoArray = [];
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    CampoArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateCamposTable(){
        CamposTable = $('#ListaCampos').DataTable({
            data: CampoArray,
            "bDestroy": true,
            columns: [
                { data: 'Contenedor' },
                { data: 'Codigo' },
                { data: 'Titulo' },
                { data: 'ValorEjemplo' },
                { data: 'ValorPredeterminado' },
                { data: 'Tipo' },
                { data: 'Dinamico' },
                { data: 'Mandatorio' },
                { data: 'Deshabilitado' },
                { data: 'Accion' },
            ],
            "columnDefs": [ 
                {
                    "targets": 6,
                    "searchable": false,
                    "data": "Dinamico",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 7,
                    "searchable": false,
                    "data": "Mandatorio",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 8,
                    "searchable": false,
                    "data": "Deshabilitado",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 9,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-times-circle icon-lg Delete'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil Update'></i></div>";
                    }
                },
            ]
        });
    }
    function getContenedores(){
        $.ajax({
            type: "POST",
            url: "ajax/getContenedoresSelect.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
            },
            success: function(data){
                $("select[name='Contenedor']").html(data);
            },
            error: function(){
            }
        });
    }
    function getTipos(){
        $.ajax({
            type: "POST",
            url: "ajax/getTiposCamposSelect.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
            },
            success: function(data){
                $("select[name='Tipo']").html(data);
            },
            error: function(){
            }
        });
    }
    function updateListaOpcionesTable(){
        OpcionesTable = $('#ListaOpciones').DataTable({
            data: ListaOpciones,
            columns: [
                { data: 'Prioridad' },
                { data: 'Opcion' },
                { data: 'Seleccionado' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": [0,1],
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'>"+data+"</div>";
                    }
                },
                {
                    "targets": [2],
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-times-circle icon-lg DeleteOpcion'></i></div>";
                    }
                },
            ]
        });
    }
    function AgregarOpcionReclutamiento(){
        var Prioridad = $("input[name='Prioridad']").val();
        var Opcion = $("input[name='Opcion']").val();
        var Seleccionado = $("select[name='Seleccionado']").val();
        OpcionesTable.row.add( {
            "Prioridad": Prioridad,
            "Opcion": Opcion,
            "Seleccionado": Seleccionado,
            "Accion": "0"
        }).draw();
    }
    function CanAddPrioridad(Prioridad){
        var ToReturn = true;
        OpcionesTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.Prioridad == Prioridad){
                ToReturn = false;
            }
        });
        return ToReturn;
    }
    function CanAddSeleccionado(Seleccionado){
        var ToReturn = true;
        if(Seleccionado == "1"){
            OpcionesTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data.Seleccionado == "1"){
                    ToReturn = false;
                }
            });
        }
        return ToReturn;
    }
    function CrearCampoReclutamiento(){
        var Contenedor = $("select[name='Contenedor']").val();
        var Codigo = $("input[name='Codigo']").val();
        var Titulo = $("input[name='Titulo']").val();
        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
        var Tipo = $("select[name='Tipo']").val();
        var Mandatorio = $("select[name='Mandatorio']").val();
        var Deshabilitado = $("select[name='Deshabilitado']").val();
        var ArrayOpciones = [];
        switch(Tipo){
            case "3":
            case "4":
                OpcionesTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                    var data = this.data();
                    ArrayOpciones.push(data);
                });
            break;
        }
        console.log(ArrayOpciones);
        $.ajax({
            type: "POST",
            url: "ajax/CrearCampo.php",
            dataType: "html",
            data: {
                Contenedor: Contenedor,
                Codigo: Codigo,
                Titulo: Titulo,
                ValorEjemplo: ValorEjemplo,
                ValorPredeterminado: ValorPredeterminado,
                Tipo: Tipo,
                Mandatorio: Mandatorio,
                Deshabilitado: Deshabilitado,
                ArrayOpciones: ArrayOpciones
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
                console.log(data);
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        getCampoTableList(false);
                        updateCamposTable();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function CanAddMandatorioSeleccionado(Mandatorio,Seleccionado){
        var ToReturn = true;
        if((Mandatorio == "1") && (Seleccionado == "1")){
            ToReturn = false;
        }
        return ToReturn;
    }
    function CanAddCodigo(Codigo){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "ajax/ValidacionCodigoAgregar.php",
            dataType: "html",
            data: {
                Codigo: Codigo
            },
            async: false,
            beforeSend: function(){
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        ToReturn = true;
                    }
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
});
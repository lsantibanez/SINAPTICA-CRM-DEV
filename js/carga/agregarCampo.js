$(document).ready(function(){

    var TablaCampos;
    var datosTabla;
    var nombreTabla;
    var TablaId;

    listarTablas();

    $("body").on("click","#GuardarCampo",function(){
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("input[name='Campo']").val();
        var Tipo = $("select[name='TipoCampo']").val();
        saveCampo(Tabla,Campo,Tipo);
    });
    $('body').on( 'click', '.configurar', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idTabla = ObjectDiv.attr("id");
        TablaId = idTabla;
        nombreTabla = $(this).parents("tr").find("td").eq(0).html();  // obtenfo o que tiene el td  
          
        //$('#camposTabla').val()      
        bootbox.dialog({
            title: "Configuración de Campos",
            message: $("#configurarCampos").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        TablaCampos.draw();
                        var myArray = [];
                        TablaCampos.rows().eq(0).each( function ( index ) {
                            var array = [];
                            var row = TablaCampos.row( index );
                            var data;
                            data = row.data();
                            $.each(data,function(indexCol,value){
                                array.push(value);
                            });
                            myArray.push(array);
                        });
                        console.log(myArray);
                        insertaModificaCampos(myArray,idTabla); 
                        bootbox.alert("Datos actualizados satisfactoriamente");
                    }
                }                
            },
            size: 'large'
       }).off("shown.bs.modal");
       listarCamposConfigurados(idTabla);
       var valorDiv = $("#nomtabla").html();
       $("#nomtabla").html(valorDiv + "<b>"+nombreTabla+"</b>");   
    });
    $("body").on("change","select.RefreshTable", function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        var Row = ObjectMe.attr("row");
        var ObjectTD = ObjectMe.closest("td");
        var cell = TablaCampos.cell( ObjectTD );
        cell.data( Number(Value) );
    });
    $("body").on("click",".eliminarCampo", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");

        bootbox.confirm("¿Esta seguro que desea eliminar el campo?", function(result) {
            if (result) {
                if (ID != ""){
                  eliminarCampo(ObjectTR, ID);
                }else{
                  TablaCampos.row(ObjectTR).remove().draw();
                  listarCamposConfigurados(TablaId);
                }
            }
        });
    });
    $("body").on("click",".agregarCampo",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idTabla = ObjectDiv.attr("id"); 
        bootbox.dialog({
            title: "Campos",
            message: $("#listaCamposNoConfigurados").html(),
            buttons: {
                success: {
                    label: "Agregar",
                    className: "btn-primary",
                    callback: function() {
                        var nombreCampo = $("#campoBD option:selected").html();
                        TablaCampos.row.add({
                            nombre: nombreCampo, 
                            tipo_dato: "",
                            orden: "",
                            logica: "",
                            cedente: "",
                            Actions: ""
                        });
                        TablaCampos.draw();
                    }
                }                
            }
        }).off("shown.bs.modal");
        var myArray = [];
        TablaCampos.rows().eq(0).each( function ( index ) {
            var array = [];
            var row = TablaCampos.row( index );
            var data;
            data = row.data();
            $.each(data,function(indexCol,value){
                switch(indexCol){
                    case 'nombre':
                        myArray.push(value);
                    break;
                }
            });
        }); 
        FiltrarCamposNoConfig(nombreTabla,myArray);
        var valorDiv = $("#nomtabla2").html();
        $("#nomtabla2").html(valorDiv + "<b>"+nombreTabla+"</b>");
    
    });

    function saveCampo(Tabla,Campo,Tipo){
        $.ajax({
            type: "POST",
            url: "../carga/ajax/agregarCampo.php",
            dataType: "html",
            async: false,
            data: {
                Tabla: Tabla,
                Campo: Campo,
                Tipo: Tipo
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        $("select[name='Tabla']").val("");
                        $("input[name='Campo']").val("");
                        $("select[name='TipoCampo']").val("");
                        $(".selectpicker").selectpicker("refresh");
                    }
                    bootbox.alert(json.message);
                }
            },
            error: function(){
            }
        });
    }
    function listarTablas(){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/GetListar_Tablas.php",
            //data: data,
            dataType: "json",
            success: function(data){

                datosTabla = $('#listaTablas').DataTable({
                    data: data, // este es mi json
                    columns: [
                        { data : 'nombre' }, // campos que trae el json
                        { data: 'Actions' }
                    ],
                    "columnDefs": [
                    
                        {
                            "targets": 1,
                            "data": 'Actions',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='configurar ti-settings btn btn-primary btn-icon icon-lg'></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(){

            }
        });
    }
    function insertaModificaCampos(arrayCampos, idTabla){        
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/campos_config_creaModi.php",
            dataType: "html",
            data: { arrayCampos: arrayCampos, idTabla:idTabla },
            success: function(data){
                console.log(data);
            },
            error: function(){

            }
        });
    }
    function listarCamposConfigurados(idTabla){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/GetListar_camposConfig.php",
            data: {idTabla: idTabla},
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    TablaCampos = $('#listaCampos').DataTable({
                        data: data, // este es mi json
                        "iDisplayLength": 5,
                        "pageLength": 5,
                        "bLengthChange": false,
                        columns: [
                            { data : 'nombre' }, // campos que trae el json
                            { data : 'tipo_dato' },
                            { data : 'orden' },
                            { data : 'logica' },
                            { data : 'cedente' },
                            { data: 'Actions' }
                        ],
                        "columnDefs": [                      

                            {
                                "targets": 1,
                                "data": 'tipo_dato',
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    var SelectedOne = "";
                                    var SelectedTwo = "";
                                    var SelectedThree = "";
                                    var SelectedFour = "";
                                    var SelectedSix = "";
                                    switch(Number(data)){
                                        case 0:
                                            SelectedOne = "selected='selected'";
                                        break;
                                        case 1:
                                            SelectedTwo = "selected='selected'";
                                        break;
                                        case 2:
                                            SelectedThree = "selected='selected'";
                                        break;
                                        case 3:
                                            SelectedFour = "selected='selected'";
                                        break;
                                        case 6:
                                            SelectedSix = "selected='selected'";
                                        break;
                                    }
                                    ToReturn = "<select style='display: block !important;' class='selectpicker form-control RefreshTable' row='tipo_dato' title='Seleccione' data-live-search='true' data-width='100%' ><option value=''>Seleccione</option><option "+SelectedOne+" value='0'>Int</option><option "+SelectedTwo+" value='1'>Date</option><option "+SelectedThree+" value='2'>Varchar</option><option "+SelectedFour+" value='3'>Distinct</option><option "+SelectedSix+" value='6'>Date Time</option></select>";
                                    return ToReturn;
                                }
                            },
                            {
                                "targets": 2,
                                "data": 'orden',
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    var SelectedOne = "";
                                    var SelectedTwo = "";
                                    switch(Number(data)){
                                        case 0:
                                            SelectedOne = "selected='selected'";
                                        break;
                                        case 1:
                                            SelectedTwo = "selected='selected'";
                                        break;
                                    }
                                    ToReturn = "<select style='display: block !important;' class='selectpicker form-control RefreshTable' row='orden' title='Seleccione' data-live-search='true' data-width='100%'><option value=''>Seleccione</option><option "+SelectedOne+" value='0'>ASC</option><option "+SelectedTwo+" value='1'>DESC</option></select>";
                                    return ToReturn;
                                }
                            },
                            {
                                "targets": 3,
                                "data": 'logica',
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    var SelectedOne = "";
                                    var SelectedTwo = "";
                                    switch(Number(data)){
                                        case 0:
                                            SelectedOne = "selected='selected'";
                                        break;
                                        case 1:
                                            SelectedTwo = "selected='selected'";
                                        break;
                                    }
                                    ToReturn = "<select style='display: block !important;' class='selectpicker form-control RefreshTable' row='logica' title='Seleccione' data-live-search='true' data-width='100%'><option value=''>Seleccione</option><option "+SelectedOne+" value='0'>Todas</option><option "+SelectedTwo+" value='1'>Igual o Distinto</option></select>";
                                    return ToReturn;
                                }
                            },
                            {
                                "targets": 4,
                                "data": 'logica',
                                "render": function( data, type, row ) {
                                    var ToReturn = "";
                                    var SelectedOne = "";
                                    var SelectedTwo = "";
                                    switch(Number(data)){
                                        case 0:
                                            SelectedOne = "selected='selected'";
                                        break;
                                        case 1:
                                            SelectedTwo = "selected='selected'";
                                        break;
                                    }
                                    ToReturn = "<select style='display: block !important;' class='selectpicker form-control RefreshTable' row='cedente' title='Seleccione' data-live-search='true' data-width='100%'><option value=''>Seleccione</option><option "+SelectedOne+" value='0'>No</option><option "+SelectedTwo+" value='1'>Sí</option></select>";
                                    return ToReturn;
                                }
                            },

                            {
                                "targets": 5,
                                "data": 'Actions',
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='eliminarCampo fa fa-trash btn btn-danger btn-icon icon-lg'></div>";
                                }
                            }
                        ]
                    }); 
                }
            },
            error: function(){

            }
        });
    }
    function eliminarCampo(TableRow, ID){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/eliminar_campoConfig.php",
            dataType: "html",
            data: {
                idCampo: ID
            },
            success: function(data){
                TablaCampos.row(TableRow).remove().draw();
                $("#listaCampos").trigger('update');                
            },
            error: function(){
 
            }
        });
    }
    function FiltrarCamposNoConfig(nombreTabla,camposArray){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/GetListar_camposNoConfig.php",
            dataType: "html",
            data: {nombreTabla: nombreTabla, camposArray:camposArray},
            success: function(data){
                $("#campoBD").html(data);
                $("#campoBD").selectpicker('refresh');
            },
            error: function(){    

            }
        });
    }
});
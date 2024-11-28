$(document).ready(function(){
    var DiscadorTable;
    var ColasDiscadores = [];
    var DialProvider;
    var DialProviderConfigured = false;
    GetServerStatus();
    getColas();
    getTipoTelefono();
    //getColasDiscadores();
    UpdateTable();
    IntervaloUpdateTabla();

    $("#Continuar").click(function(){
        var Cola = $("select[name='Asignacion']").val();
        var Canales = $("select[name='Canales']").val();
        var TlfxRut = $("input[name='TlfxRut']").val();
        var Salida = $("select[name='Salida']").val();
        if(Cola != ""){
            if(Canales != ""){
                if(TlfxRut != ""){
                    if(Salida != ""){
                        var Template = $("#TipoCategoriaTemplate").html();
                        bootbox.dialog({
                            title: "Crear Campaña Predictivo",
                            message: Template,
                            buttons: {
                                confirm: {
                                    label: "Actualizar",
                                    callback: function() {
                                        var TipoTelefono = $("select[name='Categorias']").val();
                                        var TipoCategorias = $("select[name='TipoCategoria']").val();
                                        var CantTipoTelefono = 0;
                                        jQuery.each(TipoTelefono,function(i,val){
                                            CantTipoTelefono++;
                                        });
                                        if(CantTipoTelefono > 0){
                                            crearQueryDiscador(Cola,TipoTelefono,Canales,TlfxRut,Salida,TipoCategorias);
                                        }else{
                                            bootbox.alert("Debe seleccionar un tipo de telefono");
                                        }
                                    }
                                }
                            }
                        }).off("shown.bs.modal");
                        $(".selectpicker").selectpicker("refresh");
                    }else{
                        bootbox.alert("Debe seleccionar una Salida");
                    }
                }else{
                    bootbox.alert("Debe ingresar una cantidad de telefonos por rut");
                }
            }else{
                bootbox.alert("Debe ingresar una cantidad de Canales");
            }
        }else{
                bootbox.alert("Debe seleccionar una Cola");
        }
    });
    $("body").on("change","select[name='TipoCategoria']",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/getCategoriasFromTipoCategoria.php",
            data:{
                Tipo: Value
            },
            success: function(response){
                $("select[name='Categorias']").html(response);
                $("select[name='Categorias']").selectpicker("refresh");
            }
        });
    });
    $("body").on("click",".btn-repro",function(){
        var ObjectMe = $(this);
        var SelectedValue = ObjectMe.attr("id");
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayidDiv = idDiv.split("_");
        var TipoDiscado = "";
        var PAbandono = "";
        switch(SelectedValue){
            case '0':
                //Reiniciar Cola.
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Reiniciar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                            ReiniciarColaDiscado(ArrayidDiv[0]);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                            location.reload();
                        }
                    }
                });
            break;
            case '1':
                var Template = '<div class="col-sm-8 col-sm-offset-2 row">'+
                                    '<div class="form-group">'+
                                        '<label class="control-label">Tipo de Discado:</label>'+
                                        '<select class="selectpicker form-control" name="TipoDiscado" title="Seleccione" data-live-search="true" data-width="100%">'+
                                            '<option value="progresivo">Progresivo</option>' +
                                            '<option value="predictivo">Predictivo</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="row"><div class="col-sm-8 col-sm-offset-2 row" id="PAbandonoContainer" style="display: none;">'+
                                    '<div class="form-group">'+
                                        '<label class="control-label">Porcentaje de Abandono:</label>'+
                                        '<input type="number" class="form-control" name="PAbandono">'+
                                    '</div>'+
                                '</div></div>';
                bootbox.dialog({
                    title: "<div style='font-size: 20px;'>¿Desea Iniciar la cola?</div>",
                    message: Template,
                    buttons: {
                        success: {
                            label: "SI",
                            className: "btn-success",
                            callback: function() {
                                var TipoDiscado = $("select[name='TipoDiscado']").val();
                                var PAbandono = $("input[name='PAbandono']").val();
                                if(TipoDiscado != ""){
                                    if((TipoDiscado == "predictivo") && (PAbandono == "")){
                                        bootbox.alert("Debe ingresar un porcentaje de abandono");
                                        return false;
                                    }
                                    CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                                    IniciarColaDiscado(ArrayidDiv[0]);
                                    ObjectDiv.find(".btn-repro").removeClass("Selected");
                                    ObjectMe.addClass("Selected");
                                    location.reload();
                                }else{
                                    bootbox.alert("Debe seleccionar un tipo de Discado");
                                    return false;
                                }
                                
                            }
                        },
                        cancel: {
                            label: "NO",
                            className: "btn-purple",
                            callback: function() {
                                
                            }
                        }
                    }
                }).off("shown.bs.modal");
                $(".selectpicker").selectpicker("refresh");
                /*bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Iniciar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                            IniciarColaDiscado(ArrayidDiv[0]);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                        }
                    }
                });*/
            break;
            case '2':
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Pausar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                            location.reload();
                        }
                    }
                });
            break;
            case '3':
                var Template = '<div class="col-sm-8 col-sm-offset-2 row">'+
                                    '<div class="form-group">'+
                                        '<label class="control-label">Tipo de Discado:</label>'+
                                        '<select class="selectpicker form-control" name="TipoDiscado" title="Seleccione" data-live-search="true" data-width="100%">'+
                                            '<option value="predictivo">Predictivo</option>'+
                                            '<option value="progresivo">Progresivo</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="row"><div class="col-sm-8 col-sm-offset-2 row" id="PAbandonoContainer" style="display: none;">'+
                                    '<div class="form-group">'+
                                        '<label class="control-label">Porcentaje de Abandono:</label>'+
                                        '<input type="number" class="form-control" name="PAbandono">'+
                                    '</div>'+
                                '</div></div>';
                bootbox.dialog({
                title: "<div style='font-size: 20px;'>¿Desea Reiniciar la cola con solo los Telefonos No Contactados?</div>",
                message: Template,
                buttons: {
                    success: {
                        label: "SI",
                        className: "btn-success",
                        callback: function() {
                            var TipoDiscado = $("select[name='TipoDiscado']").val();
                            var PAbandono = $("input[name='PAbandono']").val();
                            if(TipoDiscado != ""){
                                if((TipoDiscado == "predictivo") && (PAbandono == "")){
                                    bootbox.alert("Debe ingresar un porcentaje de abandono");
                                    return false;
                                }
                                CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                                IniciarColaDiscado(ArrayidDiv[0]);
                                ObjectDiv.find(".btn-repro").removeClass("Selected");
                                ObjectMe.addClass("Selected");
                                location.reload();
                            }else{
                                bootbox.alert("Debe seleccionar un tipo de Discado");
                                return false;
                            }
                            
                        }
                    },
                    cancel: {
                        label: "NO",
                        className: "btn-purple",
                        callback: function() {
                            
                        }
                    }
                }
                }).off("shown.bs.modal");
                $(".selectpicker").selectpicker("refresh");
                /*bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Reiniciar la cola con solo los Telefonos No Contactados?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                        }
                    }
                });*/
            break;
            case '4':
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea actualizar la Cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue,TipoDiscado,PAbandono);
                            DiscadorTable.destroy().draw();
                            getColasDiscadores(false);
                            UpdateTable();
                            location.reload();
                            /*ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");*/
                        }
                    }
                });
            break;
        }
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayID = idDiv.split("_");
        var idDiscador = ArrayID[1];
        bootbox.confirm({
            message: "¿Esta seguro de eliminar la cola seleccionada?",
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
                if (result) {
                    EliminarColaDiscador(idDiscador);
                }
            }
        });
    });
    $("body").on("click",".Status",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayidDiv = idDiv.split("_");
        var SelectedValue = 0;
        if(ObjectMe.is(":checked")){
            SelectedValue = 1;
        }
        CambiarStatusColaDiscado(ArrayidDiv[0],SelectedValue);
    });
    $("body").on("change","select[name='TipoDiscado']",function(){
        var TipoDiscado = $(this).val();
        switch(TipoDiscado){
            case "predictivo":
                $("#PAbandonoContainer").show();
            break;
            case "progresivo":
                $("#PAbandonoContainer").hide();
            break;
        }
    });
    function getTipoTelefono(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getTipoTelefono.php",
            data:{},
            async: false,
            success: function(response){
                $("select[name='Tipo_Telefono']").html(response);
                $("select[name='Tipo_Telefono']").selectpicker("refresh");
            }	
        });
    }
    function getColas(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColas.php",
            data:{},
            async: false,
            success: function(response){
                $("select[name='Asignacion']").html(response);
                $("select[name='Asignacion']").selectpicker("refresh");
            }	
        });
    }
    function crearQueryDiscador(Cola,TipoTelefono,Canales,TlfxRut,Salida,TipoCategorias){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/crearQueryDiscador.php",
            data:{
                Cola: Cola,
                TipoTelefono: TipoTelefono,
                Canales: Canales,
                TlfxRut: TlfxRut,
                Salida:Salida,
                TipoCategorias: TipoCategorias
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                console.log(response);
                if(isJson(response)){
                    var json = JSON.parse(response);
                    if(json.result){
                        var Queue = json.Queue;
                        //CrearColaAsterisk(Queue);
                        ActualizarTabla();
                    }
                }
            }	
        });
    }
    function CrearColaAsterisk(Queue){
        $.ajax({
            type: "POST",
            url: "../discador/AGI/CrearCola.php",
            data:{
                Queue: Queue
            },
            async: false,
            success: function(response){
                //$('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function EliminarColaAsterisk(Queue){
        $.ajax({
            type: "POST",
            url: "../discador/AGI/EliminarCola.php",
            data:{
                Queue: Queue
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
            }	
        });
    }
    function UpdateTable(){
        DiscadorTable = $('#DiscadorTable').DataTable({
            data: ColasDiscadores,
            columns: [
                { data: 'Cola' },
                { data: 'Queue' },
                { data: 'Reproduccion' },
                { data: 'Status' },
                { data: 'Accion' }
            ],
            "columnDefs": [            
                {
                    "targets": 2,
                    "data": 'Reproduccion',
                    "render": function( data, type, row ) {
                        var ArrayData = data.split("_");
                        var SelectedPlay = "";
                        var SelectedPause = "";
                        var SelectedStop = "";
                        var SelectedReinicio = "";
                        switch(ArrayData[1]){
                            case '0':
                                SelectedStop = " Selected ";
                            break;
                            case '1':
                                SelectedPlay = " Selected ";
                            break;
                            case '2':
                                SelectedPause = " Selected ";
                            break;
                            case '3':
                                SelectedReinicio = " Selected ";
                            break;
                            case '4':
                                SelectedStop = " Selected ";
                            break;
                        }
                        return "<div style='text-align: center; font-size: 25px;' id='"+data+"'>"+
                                    "<i style='padding: 0 5px;' id='1' class='fa fa-play btn-repro "+SelectedPlay+"'></i>"+
                                    "<i style='padding: 0 5px;' id='2' class='fa fa-pause btn-repro "+SelectedPause+"'></i>"+
                                    "<i style='padding: 0 5px;' id='0' class='fa fa-stop btn-repro "+SelectedStop+"'></i>"+
                                    "<i style='padding: 0 5px;' id='3' class='fa fa-exchange btn-repro "+SelectedReinicio+"'></i>"+
                                    "<i style='padding: 0 5px;' id='4' class='fa fa-refresh btn-repro'></i>"+
                               "</div>";
                    }
                },
                {
                    "targets": 3,
                    "render": function (data, type, row) {
                        data = row.Accion
                        return "<div style='text-align: center; font-size: 15px;' id='" + data + "'><i style='cursor: pointer;' class='fa fa-search btn btn-primary btn-icon icon-lg Ver'></i></div>";
                    }
                },
                {
                    "targets": 4,
                    "render": function( data, type, row ) {
                        data = row.Status
                        var ArrayData = data.split("_");
                        var idDiscador = ArrayData[0];
                        var Status = ArrayData[1];
                        var Checked = "";
                        if(Status > 0){
                            Checked = "checked"
                        }
                        return "<div style='text-align: center;' id='" + data + "'><input type='checkbox' " + Checked +" class='toggle-switch Status' /><label class='toggle-switch-label'></label></div>";
                    }
                },
                {
                    "targets": 5,
                    "render": function( data, type, row ) {
                        data = row.Accion
                        return "<div style='text-align: center; font-size: 15px;' id='Cola_" + data +"'><i style='cursor: pointer;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                    }
                }
            ]
        });
    }
    function getColasDiscadores(Modal = true){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColasDiscadores.php",
            async: false,
            beforeSend: function() {
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
                ColasDiscadores = JSON.parse(response);
            }
        });
    }
    $("body").on("click", ".Ver", function (Modal = true) {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        $.ajax({
            type: "POST",
            url: "../includes/tareas/verColaDiscador.php",
            data:{
                id: id
            },
            async: false,
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function (response) {
                $('#Cargando').modal('hide');
                if (isJson(response)) {
                    var Cola = JSON.parse(response);
                    ColaTable = $('#ColaTable').DataTable({
                        data: Cola,
                        destroy: true,
                        columns: [
                            { data: 'Cola' },
                            { data: 'Queue' },
                            { data: 'Canales' },
                            { data: 'TlfxRut' },
                            { data: 'tipoTelefono' },
                            { data: 'TipoCategorias' },
                            { data: 'ProgresoRuts' },
                            { data: 'ProgresoFonos' },
                            { data: 'ProgresoReinicio' }
                        ],
                        "columnDefs": [ 
                            {
                                "targets": 2,
                                "data": 'Accion',
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'><input type='hidden' id='queue' value='"+row.Queue+"' name='canales' ><input type='text' class='canales_cambiar textTransparent canal' value='"+data+"'></div>";
                                }
                            },
                            {
                                "targets": 3,
                                "data": 'Accion',
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'><input type='text' class='canales_cambiar textTransparent tlfxrut' value='"+data+"'></div>";
                                }
                            }
                        ]
                    });
                    $('#modalCola').modal('show');
                }
            }
        });
    });
    function CambiarEstadoColaDiscado(Cola,Valor,TipoDiscado,PAbandono){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/CambiarEstadoColaDiscado.php",
            data:{
                Cola: Cola,
                Value: Valor,
                TipoDiscado: TipoDiscado,
                PAbandono: PAbandono,
                Provider: DialProvider
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
                if(isJson(response)){
                    var Json = JSON.parse(response);
                    bootbox.alert(Json.message);
                }
            },
            error: function(response){
                console.log(response);
            }
        });
    }
    function EliminarColaDiscador(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/EliminarColaDiscador.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                console.log(response);
                var json = JSON.parse(response);
                if(json.result){
                    //EliminarColaAsterisk(json.Queue);
                    ActualizarTabla();
                }
            }	
        });
    }
    function ActualizarTabla(){
        DiscadorTable.destroy().draw();
        getColasDiscadores();
        UpdateTable();
        $('#Cargando').modal('hide');
    }
    function ReiniciarColaDiscado(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/ReiniciarColaDiscado.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function CambiarStatusColaDiscado(Cola,Valor){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/CambiarStatusColaDiscado.php",
            data:{
                Cola: Cola,
                Value: Valor
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function IniciarColaDiscado(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/IniciarColaDiscado.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function GetServerStatus(){
        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        });
        setTimeout(() => {
            $.ajax({
                type: "POST",
                url: "../includes/admin/GetServerStatus.php",
                data:{
                    codigoFoco: GlobalData.focoConfig.CodigoFoco,
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    if(isJson(data)){
                        var json = JSON.parse(data);
                        console.log(json);
                        if(json.result){
                            DialProvider = json.Proveedor;
                            if(DialProvider != ""){
                                DialProviderConfigured = true;
                            }
                        }else{
                            //bootbox.alert(json.message);
                            $('#TableProveedores').hide()
                            $('#newProveedor').hide()
                        }
                    }                
                }
            });
        }, 200);
    }

    $(document).on('change', '.canales_cambiar', function(){
			var ObjectMe = $(this);
            var ObjectTR = ObjectMe.closest('tr');
            var queue = ObjectTR.find("#queue").val();
			var canal = ObjectTR.find(".canal").val();
			var tlfxrut = ObjectTR.find(".tlfxrut").val();

            console.log("canal " + canal + " tlfxrut " + tlfxrut + " queue " + queue);

            if ((Number(canal) === 0) || (Number(tlfxrut) === 0)){
                bootbox.alert("No se puede establecer 'Canales' o 'Telefono por Rut' con valor cero (0)");
                return;
            }

            var post = "canal="+canal+"&tlfxrut="+tlfxrut+"&queue="+queue;

            $.ajax({
                type: "POST",
                url: "../includes/tareas/actualizar_canales.php",
                data: post,
                success: function(response){
                    console.log(response);
                    if(isJson(response)){
                        var json = JSON.parse(response);
                        if(json.result){
                            $.niftyNoty({
                                type: 'success',
                                icon : 'fa fa-check',
                                message : 'Datos Actualizados!',
                                container : 'floating',
                                timer : 1000
                            });
                        }
                    }
                    console.log(response);
                }
            });
        });
        
    function IntervaloUpdateTabla(){
        // setInterval(function(){
            DiscadorTable.destroy().draw();
            getColasDiscadores(false);
            UpdateTable();
            
        // },10000);
    }
});
$(document).ready(function(){
    var TableProveedores = null;
    var TableDataSet;
    GetServerStatus();

    // $.ajax({
    //     type: "POST",
    //     url: "../includes/admin/GetExtensionDisponibleDiscado.php",
    //     async:false,
    //     data:{
    //         codigoFoco: GlobalData.focoConfig.CodigoFoco,
    //     },
    //     success: function(data){
    //         console.log(data);
    //     },
    //     error: function(){
    //     }
    // });

    $("body").on("click","#newProveedor",function(){
        var Template = $("#CreacionProveedorTeemplate").html();
        bootbox.dialog({
            title: "FORMULARIO DE CREACIÓN DE PROVEEDOR DE PLAN DE DISCADO",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var CodigoProveedor = $("#CodigoProveedor").val();
                        var NombreProveedor = $("#NombreProveedor").val();
                        var ProviderRules = $("#ProviderRules").val();
                        var DialPlan = $("#DialPlan").val();
                        var CanInsert = false;
                        if(CodigoProveedor != ""){
                            if(NombreProveedor != ""){
                                if(ProviderRules != ""){
                                    if(DialPlan != ""){
                                        CanInsert = true;
                                        newProveedor();
                                    }else{
                                        bootbox.alert("Debe ingresar un plan de discado");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar una regla de proveedor");
                                }
                            }else{
                                bootbox.alert("Debe ingresar el nombre del proveedor");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Codigo para el proveedor");
                        }
                        if(!CanInsert){
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
    });
    $("body").on("change",".InputSelectProveedor",function(){
        var idProveedor = $(this).attr("id");
        if ($(this).is(':checked')) {
            seleccionarProveedor(idProveedor);
        }else{
            deseleccionarProveedor();
        }
    });
    $("body").on("click",".deleteProveedor",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idProveedor = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar el proveedor seleccionado?",
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
                    DeleteProveedor(idProveedor);
                }
            }
        });
    });
    $("body").on("click",".updateProveedor",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idProveedor = ObjectDiv.attr("id"); 
        var Template = $("#CreacionProveedorTeemplate").html();
        bootbox.dialog({
            title: "FORMULARIO DE CREACIÓN DE PROVEEDOR DE PLAN DE DISCADO",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var CodigoProveedor = $("#CodigoProveedor").val();
                        var NombreProveedor = $("#NombreProveedor").val();
                        var ProviderRules = $("#ProviderRules").val();
                        var DialPlan = $("#DialPlan").val();
                        var CanUpdate = false;
                        if(CodigoProveedor != ""){
                            if(NombreProveedor != ""){
                                if(ProviderRules != ""){
                                    if(DialPlan != ""){
                                        CanUpdate = true;
                                        updateProveedor(idProveedor);
                                    }else{
                                        bootbox.alert("Debe ingresar un plan de discado");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar una regla de proveedor");
                                }
                            }else{
                                bootbox.alert("Debe ingresar el nombre del proveedor");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Codigo para el proveedor");
                        }
                        if(!CanUpdate){
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
        var Proveedor = getProveedor(idProveedor);
        $("#CodigoProveedor").val(Proveedor.Codigo);
        $("#NombreProveedor").val(Proveedor.Nombre);
        $("#ProviderRules").val(Proveedor.ProviderRules);
        $("#DialPlan").val(Proveedor.DialPlan);
    });

    $("body").on("click","#updateIpServidorDiscado",function(){
        var Template = $("#TemplateUpdateIpServidorDiscado").html();
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DEL IP DEL SERVIDOR",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var IpServidorDiscado = $("input[name='IpServidorDiscado']").val();
                        if(IpServidorDiscado != ""){
                            updateIpServidorDiscado(); 
                        }else{
                            bootbox.alert("Debe ingresar la ip del servidor");
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
        });
        getIpServidorDiscado();
    })

    function newProveedor(){
        var CodigoProveedor = $("#CodigoProveedor").val();
        var NombreProveedor = $("#NombreProveedor").val();
        var ProviderRules = $("#ProviderRules").val();
        var DialPlan = $("#DialPlan").val();
        ProviderRules = ProviderRules.replace(/\r?\n/g, '<br>');
        DialPlan = DialPlan.replace(/\r?\n/g, '<br>');
        $.ajax({
            type: "POST",
            url: "../includes/admin/insertProveedorDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
                CodigoProveedor: CodigoProveedor,
                NombreProveedor: NombreProveedor,
                ProviderRules: ProviderRules,
                DialPlan: DialPlan
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        getProveedores();
                        TableProveedores.destroy();
                        updateTable();
                    }else{
                        bootbox.alert(json.message);
                    }
                }
                $('#Cargando').modal('hide');
            }
        });
    }
    function deseleccionarProveedor(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/deseleccionarProveedorDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        location.reload();
                    }
                }
                $('#Cargando').modal('hide');
            }
        });
    }
    function seleccionarProveedor(idProveedor){
        $.ajax({
            type: "POST",
            url: "../includes/admin/seleccionarProveedorDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
                idProveedor: idProveedor
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        location.reload();
                    }
                }
                $('#Cargando').modal('hide');
            }
        });
    }
    function DeleteProveedor(idProveedor){
        $.ajax({
            type: "POST",
            url: "../includes/admin/deleteProveedorDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
                idProveedor: idProveedor
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        getProveedores();
                        TableProveedores.destroy();
                        updateTable();
                    }
                }
                $('#Cargando').modal('hide');
            }
        });
    }
    function getProveedor(idProveedor){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/admin/getProveedorDiscado.php",
            data:{
                idProveedor: idProveedor
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
                $('#Cargando').modal('hide');
            }
        });
        return ToReturn;
    }
    function updateProveedor(idProveedor){
        var CodigoProveedor = $("#CodigoProveedor").val();
        var NombreProveedor = $("#NombreProveedor").val();
        var ProviderRules = $("#ProviderRules").val();
        var DialPlan = $("#DialPlan").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/updateProveedorDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
                idProveedor: idProveedor,
                CodigoProveedor: CodigoProveedor,
                NombreProveedor: NombreProveedor,
                ProviderRules: ProviderRules,
                DialPlan: DialPlan
            },
            async: false,
            beforeSend: function() {
				$('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			},
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        getProveedores();
                        TableProveedores.destroy();
                        updateTable();
                    }
                }
                $('#Cargando').modal('hide');
            }
        });
    }

    function GetServerStatus(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetServerStatus.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    console.log(json);
                    if(json.result){
                        //console.log(data)
                        getProveedores();
                        updateTable();
                    }else{
                        bootbox.alert(json.message);
                        $('#TableProveedores').hide()
                        $('#newProveedor').hide()
                    }
                }                
            }
        });
    }
    function getProveedores(){
        TableDataSet = null;
        $.ajax({
            type: "POST",
            url: "../includes/admin/getProveedoresDiscado.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco
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
                    TableDataSet = JSON.parse(data);
                    console.log(TableDataSet);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    function updateTable(){
        TableProveedores = $('#TableProveedores').DataTable({
            data: TableDataSet,
            columns: [
                { data: 'Codigo', width: "10%" },
                { data: 'Nombre', width: "10%" },
                { data: 'DialPlan', width: "60%" },
                { data: 'Selected', width: "10%" },
                { data: 'Accion', width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "data": 'Selected',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        var Checked = "";
                        var idProveedor = row.id;
                        if(data){
                            Checked = "checked";
                        }
                        ToReturn = "<div class='text-center' style='font-size: 15px;' id='" + data + "'><input class='toggle-switch InputSelectProveedor' "+Checked+" type='checkbox' id='"+idProveedor+"'><label class='toggle-switch-label'></label></div>";
                        return ToReturn;
                    }
                },
                {
                    "targets": 4,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.CodigoFoco != ""){
                            ToReturn = "<div class='text-center' style='font-size: 15px;' id='" + data +"'><i style='margin: 0px 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateProveedor'></i><i style='margin: 0px 5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg deleteProveedor'></i></div>";
                        }
                        return ToReturn;
                    }
                }
            ]
        });
    }

    function getIpServidorDiscado(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/getIpServidorDiscado.php",
            async:false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    data = JSON.parse(data);
                    $("#IpServidorDiscado").val(data.IpServidorDiscado);
                    $("#IpServidorDiscadoAux").val(data.IpServidorDiscadoAux);
                }
                
            },
            error: function(){
            }
        });
    }

    function updateIpServidorDiscado(){
        var IpServidorDiscado = $("input[name='IpServidorDiscado']").val();
        var IpServidorDiscadoAux = $("input[name='IpServidorDiscadoAux']").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/updateIpServidorDiscado.php",
            dataType: "html",
            data: {
                IpServidorDiscado: IpServidorDiscado,
                IpServidorDiscadoAux: IpServidorDiscadoAux,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        $('.modal').modal('hide')
                        bootbox.alert("La ip del servidor ha sido guardada exitosamente");
                        location.reload();
                    }
                }
            },
            error: function(){
            }
        });
    }
});
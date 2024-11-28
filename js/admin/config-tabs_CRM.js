$(document).ready(function(){
    var TableDataSet = [];
    var TableFields;
    var ColumnasArray = [];
    var Columnas;

    getTabs();
    updateTabTable();
    
    $("#AddTab").click(function(){
        var Template = $("#TabTemplate").html();
        bootbox.dialog({
            title: "CREACIÓN DE TAB",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Tab = $("#Tab").val();
                        var Prioridad = $("#Prioridad").val();
                        if (Tab != ""){
                            if(Prioridad != ""){
                                saveTab();
                            }else{
                                bootbox.alert('Debe ingresar una prioridad')    
                            }
                        }else{
                            bootbox.alert('Debe ingresar un nombre')
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
    });
    function saveTab() {
        var Tab = $("#Tab").val();
        var Prioridad = $("#Prioridad").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/saveTab.php",
            data: {
                Tab: Tab,
                Prioridad: Prioridad
            },
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        bootbox.alert("Tab agregado satisfactoriamente");
                        TableFields.destroy();
                        getTabs();
                        updateTabTable();
                    }
                }
            }
        });
    }
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
        var Tab = Row.Tab;
        var Sistema = Row.Sistema;
        var IdSistema = Row.IdSistema;
        console.log(ID);
        if(Value != ""){
            cell.data(Value);
            UpdatePrioridad(Value, ID, Tab, Sistema, IdSistema);
        }
    });
    function UpdatePrioridad(Value, ID, Tab, Sistema, IdSistema) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/updatePrioridad.php",
            data: {
                Value: Value,
                ID: ID,
                Tab: Tab,
                Sistema: Sistema,
                IdSistema: IdSistema
            },
            async: false,
            success: function (data) {
                getTabs();
                updateTabTable();
            }
        });
    }
    function getTabs(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/getTabs.php",
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
    function updateTabTable(){
        TableFields = $('#Tabs').DataTable({
            data: TableDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Prioridad', width: "20%" },
                { data: 'Tab', width: "60%" },
                { data: 'Activo', width: "10%" },
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
                        return "<div style='text-align: center;' id='" + data + "'><input type='checkbox' id='" + row.id + "' class='toggle-switch Activo' "+checked+"><label class='toggle-switch-label'></label></div>";
                    }
                },
                {
                    "targets": 3,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(!row.Sistema){
                            ToReturn = "<div style='font-size: 15px;' id='" + data +"'><i style='margin: 0px 5px; cursor: pointer;' class='btn fa fa-plus btn-purple btn-icon icon-lg ModalColumn'></i><i style='margin: 0px 5px; cursor: pointer;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteTab'></i></div>";
                        }
                        return ToReturn;
                    }
                }
            ]
        });
    }
    $("body").on('change', '.Activo', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var Row = TableFields.row(ObjectTR).data();
        var ID = Row.Accion;
        var Tab = Row.Tab;
        var Sistema = Row.Sistema;
        var IdSistema = Row.IdSistema;
        if ($(this).is(':checked')) {
            Value = 1;
        } else {
            Value = 0;
        }
        UpdateActivo(Value, ID, Tab, Sistema, IdSistema);
    });
    function UpdateActivo(Value, ID, Tab, Sistema, IdSistema) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/updateActivo.php",
            data: {
                Value: Value,
                ID: ID,
                Tab: Tab,
                Sistema: Sistema,
                IdSistema: IdSistema
            },
            async: false,
            success: function (data) {
                getTabs();
                updateTabTable();
            }
        });
    }
    $("body").on("click", ".DeleteTab", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar el tab seleccionado? al aceptar se eliminaran todos los registros que referencien al mismo",
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
                    deleteTab(ID);
                }
            }
        });
    });
    function deleteTab(ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/deleteTab.php",
            data: {
                ID: ID,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    bootbox.alert("Tab eliminado satisfactoriamente.");
                    TableFields.destroy();
                    getTabs();
                    updateTabTable();
                }
            }
        });
    }
    function getColumnasTab(IdTab) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/getColumnasTab.php",
            dataType: "html",
            async: false,
            data: {
                IdTab: IdTab
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    ColumnasArray = JSON.parse(data);
                }
            }
        });
    }
    function updateColumnasTable() {
        Columnas = $('#Columnas').DataTable({
            data: ColumnasArray,
            "bDestroy": true,
            columns: [
                { data: 'Prioridad' },
                { data: 'Tabla' },
                { data: 'Columna' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                },
            ]
        });
    }
    $(document).on('click', '.ModalColumn', function () {
        var Template = $("#ColumnTemplate").html()
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var IdTab = ObjectDiv.attr("id");
        bootbox.dialog({
            title: "COLUMNAS",
            message: Template,
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $(".AddColumn").attr('id', IdTab);
            getColumnasTab(IdTab);
            updateColumnasTable();
        }, 200);
    });
    $(document).on('click', '.AddColumn', function () {
        var ID = $(this).attr('id');
        var Template = $("#AddColumnTemplate").html();
        bootbox.dialog({
            title: "CREACIÓN DE TAB",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var Tabla = $("#Tabla").val();
                        var Columna = $("#Columna").val();
                        var Prioridad = $("#Prioridad").val();
                        if (Tabla != "") {
                            if (Columna != "") {
                                if (Prioridad != "") {
                                    saveColumna();
                                } else {
                                    bootbox.alert('Debe ingresar una prioridad')
                                }
                            } else {
                                bootbox.alert('Debe seleccionar una columna')
                            }
                        }else {
                            bootbox.alert('Debe seleccionar una tabla')
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function () {
                    }
                }
            }
        }).off("shown.bs.modal");
        $('#IdTab').val(ID)
        $(".selectpicker").selectpicker("refresh");
    });
    function getColumnas(ID, Tabla) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/getColumnas.php",
            data: {
                ID: ID,
                Tabla: Tabla
            },
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                data = JSON.parse(data)
                $("#Columna").empty();
                $.each(data, function (index, columna) {
                    $('#Columna').append('<option value="' + columna + '">' + columna + '</option>');
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    $("body").on("change", "#Tabla", function () {
        ID = $('#IdTab').val();
        Tabla = $(this).val();
        getColumnas(ID, Tabla);
    });
    function saveColumna() {
        var Tabla = $("#Tabla").val();
        var Columna = $("#Columna").val();
        var Prioridad = $("#Prioridad").val();
        var IdTab = $("#IdTab").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/saveColumna.php",
            data: {
                Tabla: Tabla,
                Columna: Columna,
                Prioridad: Prioridad,
                IdTab: IdTab
            },
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        bootbox.alert("Tab agregado satisfactoriamente");
                        getColumnasTab(IdTab);
                        updateColumnasTable();
                    }
                }
            }
        });
    }
    $("body").on("click", ".DeleteColumn", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar la columna seleccionada?",
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
                    deleteColumna(ID);
                }
            }
        });
    });
    function deleteColumna(ID) {
        IdTab = $('.AddColumn').attr('id');
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_tabs_CRM/deleteColumna.php",
            data: {
                ID: ID,
            },
            async: false,
            success: function (data) {
                if (isJson(data)) {
                    bootbox.alert("Columna eliminada satisfactoriamente.");
                    getColumnasTab(IdTab);
                    updateColumnasTable();
                }
            }
        });
    }
});
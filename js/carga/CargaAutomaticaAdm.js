$(document).ready(function(){
    var TableSheet;

    var TableColumnPersona = [];
    var TableColumnDeuda = [];
    var TableColumnFono = [];
    var TableColumnDireccion = [];
    var TableColumnMail = [];
    var TableColumnPagos = [];
    var TableColumnGestiones = [];
    var ColumnsDataSet = [];
    var TablasInicializadas = false;

    var idSheet;
    
    var idColumn;

    var isColumnDate = false;

    var FileType;

    var ArrayTables = [];

    var Tables = "";

    var id_template

    setTimeout(function(){
        getTemplates();
    },100);

    $("body").on("click",".Columns",function(){
        Tables = "";
        TablasInicializadas = false;
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        idSheet = ID;
        var Sheet = getSheetData(idSheet);
        switch(Sheet.TipoCarga){
            case "carga":
                var Template = $("#ColumnasTemplate").html();
            break;
            case "pagos":
                var Template = $("#ColumnasTemplatePagos").html();
            break;
            case "cargagestiones":
                var Template = $("#ColumnasTemplateGestiones").html();
            break;
        }
        bootbox.dialog({
            title: "ASIGNACIÓN DE COLUMNAS A HOJA",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        getColumnsTemplate();
        switch (FileType) {
            case "xlsx":
            case "xls":
            case "csv":
                $(".ColumnaPosicion").text('Columna');
                $(".FuncionCantCaracteres").text('Funcion');
                break;
            case "txt":
                if (!Sheet.Separador) {
                    $(".ColumnaPosicion").text('Posicion');
                    $(".FuncionCantCaracteres").text('Cant Caracteres');
                } else {
                    $(".ColumnaPosicion").text('Columna');
                    $(".FuncionCantCaracteres").text('Funcion');
                }
                break
        }
    });
    $("body").on("click","#AddColumna",function(){
        Tables = "";
        $(".checkboxTable input[type='checkbox']").each(function(index){
            var ObjectMe = $(this);
            var ObjectLabel = ObjectMe.closest("label");
            var Table = ObjectLabel.attr("tablename");
            var TableDesc = ObjectLabel.attr("tabledesc");
            if(ObjectMe.is(":checked")){
                Tables += "<option value='"+Table+"'>"+TableDesc+"</option>";
            }
        });
        if(Tables == ""){
            bootbox.alert("Debe seleccionar al menos una tabla.");
            return false;
        }
        var Template = $("#AddColumnasTemplate").html();
        bootbox.dialog({
            title: "NUEVA COLUMNA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Tabla = $("select[name='Tabla']").val();
                        var Campo = $("select[name='Campo']").val();
                        var Patron = $("input[name='PatronFecha']").val();
                        var ColumnaExcel = $("input[name='ColumnaExcel']").val();
                        var PosicionInicio = $("input[name='CaracterDesde']").val();
                        var CantCaracteres = $("input[name='CaracterHasta']").val();
                        var Funcion = $("select[name='Funcion']").val();
                        var Parametro = $("input[name='Parametros']").val();
                        var PrioridadFono = $("input[name='PrioridadFono']").val();
                        var CanSave = false;
                        if(Tabla != ""){
                            if(Campo != ""){
                                if(Funcion != ""){
                                    if(Parametro != ""){
                                        CanSave = true;
                                    }else{
                                        bootbox.alert("Debe ingresar los parametros",function(){AddClassModalOpen();});
                                    }
                                }else{
                                    CanSave = true;
                                }
                            }else{
                                bootbox.alert("Debe seleccionar un campo de base de datos",function(){AddClassModalOpen();});
                            }
                        }else{
                            bootbox.alert("Debe seleccionar una tabla",function(){AddClassModalOpen();});
                        }
                        if((isColumnDate) && (Patron == "")){
                            CanSave = false;
                            bootbox.alert("Debe ingresar un patron de fecha Valido",function(){AddClassModalOpen();});
                        }
                        if((Tabla == "fono_cob") && (Campo == "formato_subtel") && (PrioridadFono == "")){
                            CanSave = false;
                            bootbox.alert("Debe ingresar un numero de Prioridad al campo de teléfono.",function(){AddClassModalOpen();});
                        }
                        switch(FileType){
                            case "txt":
                                if(!Sheet.Separador){
                                    if(PosicionInicio == ""){
                                        if(CanSave){
                                            CanSave = false;
                                            bootbox.alert("Debe ingresar una posicion de inicio valida",function(){AddClassModalOpen();});
                                        }
                                    }else{
                                        if(CantCaracteres == ""){
                                            if(CanSave){
                                                CanSave = false;
                                                bootbox.alert("Debe ingresar una cantidad de caracteres valida",function(){AddClassModalOpen();});
                                            }
                                        }
                                    }
                                }else{
                                    if (ColumnaExcel == "") {
                                        if (CanUpdate) {
                                            CanUpdate = false;
                                            bootbox.alert("Debe ingresar un numero de columna de Excel", function () { AddClassModalOpen(); });
                                        }
                                    }
                                }
                            break;
                            default:
                                if(ColumnaExcel == ""){
                                    if(CanSave){
                                        CanSave = false;
                                        bootbox.alert("Debe ingresar un numero de columna de Excel",function(){AddClassModalOpen();});
                                    }
                                }
                            break;
                        }
                        if(CanSave){
                            saveColumn();
                            AddClassModalOpen();
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        var Sheet = getSheetData(idSheet);
        /* switch(Sheet.TipoCarga){
            case "carga":
                var Template = $("#AddColumnasTemplate").html();
            break;
            case "pagos":
                var Template = $("#AddColumnasTemplatePagos").html();
            break;
            case "cargagestiones":
                var Template = $("#AddColumnasTemplateGestiones").html();
            break;
        } */
        switch(FileType){
            case "xlsx":
            case "xls":
            case "csv":
                $("#ContainerNumeroColumna").show();
            break;
            case "txt":
                if(!Sheet.Separador){
                    $("#ContainerCaracteresTxt").show();
                    $("#ContainerFuncion").hide();
                }else{
                    $("#ContainerNumeroColumna").show();
                }
            break
        }
        $(".selectpicker").selectpicker("refresh");
        $("select[name='Tabla']").html(Tables);
        getFunciones();
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("change","select[name='Tabla']",function(){
        var Tabla = $(this).val();
        $("#ContainerPatronFecha").hide();
        $("#ContainerPatronFecha").val("");
        $("#ContainerPrioridadFono").hide();
        getTableFields(Tabla);
    });
    $("body").on("click",".DeleteColumn",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Desea eliminar la columna seleccionada?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-purple'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    deleteColumn(ID);
                }
                AddClassModalOpen();
            }
        });
    });
    $("body").on("click",".EditColumn",function(){
        Tables = "";
        $(".checkboxTable input[type='checkbox']").each(function(index){
            var ObjectMe = $(this);
            var ObjectLabel = ObjectMe.closest("label");
            var Table = ObjectLabel.attr("tablename");
            var TableDesc = ObjectLabel.attr("tabledesc");
            if(ObjectMe.is(":checked")){
                Tables += "<option value='"+Table+"'>"+TableDesc+"</option>";
            }
        });
        if(Tables == ""){
            bootbox.alert("Debe seleccionar al menos una tabla.");
            return false;
        }
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        idColumn = ID;
        var Template = $("#UpdateColumnasTemplate").html();
        bootbox.dialog({
            title: "EDICIÓN DE COLUMNA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Actualizar",
                    className: "btn-purple",
                    callback: function() {
                        var Tabla = $("select[name='Tabla']").val();
                        var Campo = $("select[name='Campo']").val();
                        var Patron = $("input[name='PatronFecha']").val();
                        var ColumnaExcel = $("input[name='ColumnaExcel']").val();
                        var PosicionInicio = $("input[name='CaracterDesde']").val();
                        var CantCaracteres = $("input[name='CaracterHasta']").val();
                        var Funcion = $("select[name='Funcion']").val();
                        var Parametro = $("input[name='Parametros']").val();
                        var PrioridadFono = $("input[name='PrioridadFono']").val();
                        var CanUpdate = false;
                        if(Tabla != ""){
                            if(Campo != ""){
                                if(Funcion != ""){
                                    if(Parametro != ""){
                                        CanUpdate = true;
                                    }else{
                                        bootbox.alert("Debe ingresar los parametros",function(){AddClassModalOpen();});
                                    }
                                }else{
                                    CanUpdate = true;
                                }
                            }else{
                                bootbox.alert("Debe seleccionar un campo de base de datos",function(){AddClassModalOpen();});
                            }
                        }else{
                            bootbox.alert("Debe seleccionar una tabla",function(){AddClassModalOpen();});
                        }
                        if((isColumnDate) && (Patron == "")){
                            CanUpdate = false;
                            bootbox.alert("Debe ingresar un patron de fecha Valido",function(){AddClassModalOpen();});
                        }
                        if((Tabla == "fono_cob") && (Campo == "formato_subtel") && (PrioridadFono == "")){
                            CanUpdate = false;
                            bootbox.alert("Debe ingresar un numero de Prioridad al campo de teléfono.",function(){AddClassModalOpen();});
                        }
                        switch(FileType){
                            case "txt":
                                if (!Sheet.Separador) {
                                    if(PosicionInicio == ""){
                                        if(CanUpdate){
                                            CanUpdate = false;
                                            bootbox.alert("Debe ingresar una posicion de inicio valida",function(){AddClassModalOpen();});
                                        }
                                    }else{
                                        if(CantCaracteres == ""){
                                            if(CanUpdate){
                                                CanUpdate = false;
                                                bootbox.alert("Debe ingresar una cantidad de caracteres valida",function(){AddClassModalOpen();});
                                            }
                                        }
                                    }
                                }else{
                                    if (ColumnaExcel == "") {
                                        if (CanUpdate) {
                                            CanUpdate = false;
                                            bootbox.alert("Debe ingresar un numero de columna de Excel", function () { AddClassModalOpen(); });
                                        }
                                    } 
                                }
                            break;
                            default:
                                if(ColumnaExcel == ""){
                                    if(CanUpdate){
                                        CanUpdate = false;
                                        bootbox.alert("Debe ingresar un numero de columna de Excel",function(){AddClassModalOpen();});
                                    }
                                }
                            break;
                        }
                        if(CanUpdate){
                            updateColumn();
                            AddClassModalOpen();
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        $("select[name='Tabla']").html(Tables);
        $(".selectpicker").selectpicker("refresh");
        var Column = getColumnData(ID);
        $("select[name='Tabla']").val(Column.Tabla);
        $("select[name='Tabla']").selectpicker("refresh");
        getTableFields(Column.Tabla);
        $("select[name='Campo']").val(Column.Campo);
        $("select[name='Campo']").selectpicker("refresh");
        if(isDateField(Column.Tabla,Column.Campo)){
            isColumnDate = true;
            var Changed = false;
            $("select[name='PatronFechaSelect'] option").each(function(index){
                var Value = $(this).val();
                if(Value == Column.PatronFecha){
                    Changed = true;
                    $(this).prop("selected",true);
                }
                if(Value == "o"){
                    if(!Changed){
                        $(this).prop("selected",true);
                    }
                }
            });
            $("select[name='PatronFechaSelect']").selectpicker("refresh");
            $("input[name='PatronFecha']").val(Column.PatronFecha);
            $("#ContainerPatronFecha").show();
        }
        if(Column.Tabla == "fono_cob"){
            if(Column.Campo == "formato_subtel"){
                $("input[name='PrioridadFono']").val(Column.PrioridadFono);
                $("#ContainerPrioridadFono").show();
            }
        }
        var Sheet = getSheetData(idSheet);
        switch(FileType){
            case "xlsx":
            case "xls":
            case "csv":
                $("input[name='ColumnaExcel']").val(Column.Columna);
                $("#ContainerNumeroColumna").show();
            break;
            case "txt":
                if (!Sheet.Separador) {
                    $("input[name='CaracterDesde']").val(Column.posicionInicio);
                    $("input[name='CaracterHasta']").val(Column.cantCaracteres);
                    $("#ContainerCaracteresTxt").show();
                    $("#ContainerFuncion").hide();
                } else {
                    $("input[name='ColumnaExcel']").val(Column.Columna);
                    $("#ContainerNumeroColumna").show();
                }
            break
        }
        getFunciones();
        $("select[name='Funcion']").val(Column.Funcion);
        $("select[name='Funcion']").selectpicker("refresh");
        $("input[name='Parametros']").val(Column.Parametros);
    });
    $("#AddSheet").click(function(){
        var Template = $("#AddSheetsTemplate").html();
        bootbox.dialog({
            title: "ASIGNAR HOJA A PLANTILLA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var NombreHoja = $("input[name='NombreHoja']").val();
                        var NumeroHoja = $("input[name='NumeroHoja']").val();
                        var TipoCarga = $("select[name='TipoCarga']").val();
                        var CanSave = false;
                        if(TipoCarga != ""){
                            if(NombreHoja != ""){
                                if (NumeroHoja != "" || (FileType == 'csv' || FileType == 'txt')){
                                    CanSave = true;
                                }else{
                                    bootbox.alert("Debe ingresar el numero de hoja que se relacione con el documento.",function(){AddClassModalOpen();});
                                }
                            }else{
                                bootbox.alert("Debe ingresar un nombre que identifique la Hoja.",function(){AddClassModalOpen();});
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Tipo de Carga.",function(){AddClassModalOpen();});
                        }
                        
                        if(CanSave){
                            saveSheet();
                            AddClassModalOpen();
                        }else{
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        var TablasCarga = "";
        if(GlobalData.focoConfig.tipoMenu.indexOf("foco") != -1){
            TablasCarga += "<option value='carga'>Carga</option>";
            TablasCarga += "<option value='pagos'>Pagos</option>";
        }
        if(GlobalData.focoConfig.tipoMenu.indexOf("cal") != -1){
            TablasCarga += "<option value='cargagestiones'>Gestiones</option>";
        }
        $("select[name='TipoCarga']").html(TablasCarga);
        $(".selectpicker").selectpicker("refresh");
        if (FileType == 'csv' || FileType == 'txt'){
            $("#ContainerNumeroHoja").hide()
        }else{
            $("#ContainerNumeroHoja").show()
        }
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Desea eliminar la hoja seleccionada?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-purple'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    deleteSheet(ID);
                }
                AddClassModalOpen();
            }
        });
    });
    $("body").on("click",".Edit",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        idSheet = ID;
        var Template = $("#UpdateSheetsTemplate").html();
        bootbox.dialog({
            title: "EDICIÓN DE HOJA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Actualizar",
                    className: "btn-purple",
                    callback: function() {
                        var NombreHoja = $("input[name='NombreHoja']").val();
                        var NumeroHoja = $("input[name='NumeroHoja']").val();
                        var TipoCarga = $("select[name='TipoCarga']").val();
                        var CanUpdate = false;
                        if(TipoCarga != ""){
                            if(NombreHoja != ""){
                                if (NumeroHoja != "" || (FileType == 'csv' || FileType == 'txt')) {
                                    CanUpdate = true;
                                } else {
                                    bootbox.alert("Debe ingresar el numero de hoja que se relacione con el documento.", function () { AddClassModalOpen(); });
                                }
                            } else {
                                bootbox.alert("Debe ingresar un nombre que identifique la Hoja.", function () { AddClassModalOpen(); });
                            }
                        } else{
                            bootbox.alert("Debe ingresar un Tipo de Carga.", function () { AddClassModalOpen(); });
                        }
                        if(CanUpdate){
                            updateSheet()
                        }else{
                            return false;
                        }
                        AddClassModalOpen();
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        var Sheet = getSheetData(ID);
        $("input[name='NombreHoja']").val(Sheet.Nombre);
        $("input[name='NumeroHoja']").val(Sheet.Sheet);
        $("select[name='TipoCarga']").val(Sheet.TipoCarga);
        $(".selectpicker").selectpicker("refresh");
        if (FileType == 'csv' || FileType == 'txt'){
            $("#ContainerNumeroHoja").hide()
        }else{
            $("#ContainerNumeroHoja").show()
        }
    });
    $("body").on("change","select[name='TipoArchivo']",function(){
        var Value = $(this).val();
        $("[name='Separador']").val('');
        switch(Value){
            case "xlsx":
            case "xls":
                $("#ContainerSeparador").hide();
                $("#ContainerCabecero").show();
                $("[name='Separador']").prop("disabled",true);
            break;
            case "csv":
                $("#ContainerSeparador").show();
                $("#ContainerCabecero").show();
                $("[name='Separador']").prop("disabled",false);
            break;
            case "txt":
                $("#ContainerSeparador").show();
                $("#ContainerCabecero").show();
                $("[name='Separador']").prop("disabled",false);
            break;
        }
        $("[name='Separador']").selectpicker('refresh');
    });
    $("body").on("change","select[name='Campo']",function(){
        var Tabla = $("select[name='Tabla']").val();
        var Field = $(this).val();
        if(isDateField(Tabla,Field)){
            $("#ContainerPatronFecha").show();
        }else{
            $("#ContainerPatronFecha").hide();
            $("#ContainerPatronFecha").val("");
        }
        if(Tabla == "fono_cob"){
            if(Field == "formato_subtel"){
                $("#ContainerPrioridadFono").show();
            }else{
                $("#ContainerPrioridadFono").hide();
            }
        }
    });
    $("body").on("change","select[name='PatronFechaSelect']",function(){
        var Value = $(this).val();
        switch(Value){
            case "o":
                $("input[name='PatronFecha']").val("");
                $("input[name='PatronFecha']").focus();
            break;
            default:
                $("input[name='PatronFecha']").val(Value);
                $("input[name='PatronFecha']").focus();
            break;
        }
    });
    $("body").on("change",".checkboxTable input[type='checkbox']",function(){
        var ObjectMe = $(this);
        var ObjectLabel = ObjectMe.closest("label");
        var Table = ObjectLabel.attr("tablename");
        var TableDesc = ObjectLabel.attr("tabledesc");
        if(ObjectMe.is(":checked")){
            addColumnsRequired(Table);
            destroyColumnTables();
            getColumnsTemplate();
        }else{
            bootbox.confirm({
                message: "Esta desactivando la tabla "+TableDesc+". ¿Desea eliminar las columnas configuradas de la mencionada tabla?",
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
                        removeColumnsRequired(Table);
                        destroyColumnTables();
                        getColumnsTemplate();
                    }else{
                        ObjectMe.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                    AddClassModalOpen();
                }
            });
        }
    });
    function getTemplates() {
        $.ajax({
            type: "POST",
            url: "ajax/getTemplates.php",
            dataType: "json",
            async: false,
            success: function (data) {
                TableTemplate = $('#TableTemplate').DataTable({
                    data: data,
                    destroy: true,
                    columns: [
                        { data: 'NombreTemplate' },
                        { data: 'TipoArchivo' },
                        { data: 'Separador' },
                        { data: 'Cabecero' },
                        { data: 'Sheets' },
                        { data: 'id' }
                    ],
                    "columnDefs": [
                        {
                            "targets": 5,
                            "data": 'Accion',
                            "render": function (data, type, row) {
                                return "<div style='text-align: center;' id='" + data + "'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditTemplate'></i><i style = 'margin: 0px 1.5px;' class='fa fa-file btn btn-purple btn-icon icon-lg Sheets' FileType='"+row.FileType+"'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteTemplate'></i></div>";
                            }
                        }
                    ]
                });
                $('#Sheets').hide();
            }
        });
    }
    $("body").on("click", "#AddTemplateCarga", function () {
        var Template = $("#CargaTemplate").html();
        bootbox.dialog({
            title: "AGREGAR TEMPLATE",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var NombreTemplate = $("[name='NombreTemplate']").val();
                        var TipoArchivo = $("select[name='TipoArchivo']").val();
                        var CanAdd = false;
                        if (NombreTemplate != "") {
                            if (TipoArchivo != "") {
                                CanAdd = true
                            }else{
                                bootbox.alert("Debes seleccionar un tipo de archivo.");
                            }
                        }else{
                            bootbox.alert("Debes ingresar un nombre.");
                        }
                        if (CanAdd) {
                            SaveTemplate()
                        } else {
                            return false;
                        }
                        AddClassModalOpen();
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function () {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("click", ".EditTemplate", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        var Template = $("#CargaTemplate").html();
        bootbox.dialog({
            title: "EDITAR TEMPLATE",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var NombreTemplate = $("[name='NombreTemplate']").val();
                        var TipoArchivo = $("select[name='TipoArchivo']").val();
                        var CanAdd = false;
                        if (NombreTemplate != "") {
                            if (TipoArchivo != "") {
                                CanAdd = true
                            } else {
                                bootbox.alert("Debes seleccionar un tipo de archivo.");
                            }
                        } else {
                            bootbox.alert("Debes ingresar un nombre.");
                        }
                        if (CanAdd) {
                            UpdateTemplate()
                        } else {
                            return false;
                        }
                        AddClassModalOpen();
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function () {
                        AddClassModalOpen();
                    }
                }
            }
        }).off("shown.bs.modal");
        setTimeout(() => {
            getTemplate(id)
        }, 200);
        $(".selectpicker").selectpicker("refresh");
    });
    function UpdateTemplate() {
        var NombreTemplate = $("[name='NombreTemplate']").val();
        var TipoArchivo = $("select[name='TipoArchivo']").val();
        var Separador = $("[name='Separador']").val();
        var CabeceroObject = $("input[name='Cabecero']");
        if (CabeceroObject.is(":checked")) {
            var Cabecero = "1";
        } else {
            var Cabecero = "0";
        }
        var id = $("input[name='id']").val();
        $.ajax({
            type: "POST",
            url: "ajax/addTemplateCarga.php",
            dataType: "json",
            data: {
                NombreTemplate: NombreTemplate,
                TipoArchivo: TipoArchivo,
                Separador: Separador,
                Cabecero: Cabecero,
                id: id
            },
            success: function (data) {
                getTemplates();
            }
        });
    }
    function getTemplate(id){
        $.ajax({
            type: "POST",
            url: "ajax/getTemplate.php",
            dataType: "json",
            data:{
                id: id
            },
            success: function (data){
                $("[name='NombreTemplate']").val(data.NombreTemplate);
                $("select[name='TipoArchivo']").val(data.TipoArchivo);
                $("[name='Separador']").val(data.Separador);
                if (data.Cabecero == "1") {
                    $("#ContainerCabecero label.form-checkbox").addClass("active");
                    $("#ContainerCabecero input[name='Cabecero']").prop("checked", true);
                }
                $(".selectpicker").selectpicker("refresh");
                switch (data.TipoArchivo) {
                    case "xlsx":
                    case "xls":
                        // $("#AddSheet").show();
                        $("[name='Separador']").prop("disabled", true);
                        $("input[name='Cabecero']").prop("disabled", false);
                        $("#ContainerSeparador").hide();
                        $("#ContainerCabecero").show();
                        break;
                    case "csv":
                        // $("#AddSheet").remove();
                        //$("#AddSheet").show();
                        $("[name='Separador']").prop("disabled", false);
                        $("input[name='Cabecero']").prop("disabled", false);
                        $("#ContainerSeparador").show();
                        $("#ContainerCabecero").show();
                        // $("#TableSheets").find(".Edit").remove();
                        // $("#TableSheets").find(".Delete").remove();
                        break;
                    case "txt":
                        //$("#AddSheet").remove();
                        // $("#AddSheet").show();
                        $("[name='Separador']").prop("disabled", false);
                        $("input[name='Cabecero']").prop("disabled", false);
                        $("#ContainerSeparador").show();
                        $("#ContainerCabecero").show();
                        /* $("#TableSheets").find(".Edit").remove();
                        $("#TableSheets").find(".Delete").remove(); */
                        break;
                }
                $("[name='Separador']").selectpicker('refresh')
                $('#id').val(id)
            }
        });
    }
    $(document).on('click', '.Sheets', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        FileType = ObjectMe.attr('FileType');
        getSheets(id);
        $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check',
            message: "Template Seleccionada",
            container: 'floating',
            timer: 2000
        });
    })
    function getSheets(id){
        $.ajax({
            type: "POST",
            url: "ajax/getSheets.php",
            dataType: "json",
            data:{
                id: id
            },
            async: false,
            success: function (data) {
                TableSheet = $('#TableSheets').DataTable({
                    data: data,
                    destroy: true,
                    columns: [
                        { data: 'N' },
                        { data: 'Nombre' },
                        { data: 'Sheet' },
                        { data: 'TipoCarga' },
                        { data: 'Accion' }
                    ],
                    "columnDefs": [
                        {
                            "targets": 4,
                            "data": 'Accion',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg Edit'></i><i style = 'margin: 0px 1.5px;' class='fa fa-columns btn btn-purple btn-icon icon-lg Columns'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                            }
                        }
                    ]
                });
                $('table').css('width', '100%');
                $('#Sheets').show();
                id_template = id
            }
        });
    }
    $("body").on("click", ".DeleteTemplate", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Desea eliminar la template seleccionada?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-purple'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    deleteTemplate(ID);
                }
                AddClassModalOpen();
            }
        });
    });
    function deleteTemplate(id) {
        $.ajax({
            type: "POST",
            url: "ajax/deleteTemplate.php",
            dataType: "json",
            async: false,
            data: {
                id: id
            },
            success: function (data) {
                getTemplates();
                AddClassModalOpen();
            }
        });
    }
    function getColumnsTemplate(){
        $.ajax({
            type: "POST",
            url: "ajax/getColumnsTemplate.php",
            dataType: "html",
            async: false,
            data: {
                Sheet: idSheet
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    ColumnsDataSet = json.Columnas;
                }
            },
            error: function(response){
            }
        });
        updateTableColumn();
        CheckTables();
    }
    function updateTableColumn(){
        TableColumnPersona = $('#ColumnsTablePersona').DataTable({
            data: ColumnsDataSet.Persona,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnDeuda = $('#ColumnsTableDeuda').DataTable({
            data: ColumnsDataSet.Deuda,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnFono = $('#ColumnsTableFono').DataTable({
            data: ColumnsDataSet.Fono,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnDireccion = $('#ColumnsTableDireccion').DataTable({
            data: ColumnsDataSet.Direccion,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnMail = $('#ColumnsTableMail').DataTable({
            data: ColumnsDataSet.Mail,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnPagos = $('#ColumnsTablePagos').DataTable({
            data: ColumnsDataSet.Pagos,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TableColumnGestiones = $('#ColumnsTableGestiones').DataTable({
            data: ColumnsDataSet.Gestiones,
            columns: [
                { data: 'N' },
                { data: 'Tabla' },
                { data: 'ColumnDB' },
                { data: 'ColumnExcel' },
                { data: 'Funcion' },
                { data: 'Parametro' },
                { data: 'Configurado' },
                { data: 'Mandatorio' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 6,
                    "data": 'Configurado',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 7,
                    "data": 'Mandatorio',
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return ToReturn;
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg EditColumn'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg DeleteColumn'></i></div>";
                    }
                }
            ]
        });

        TablasInicializadas = true;
    }
    function destroyColumnTables(){
        if(TablasInicializadas){
            TableColumnPersona.destroy().draw();
            TableColumnDeuda.destroy().draw();
            TableColumnFono.destroy().draw();
            TableColumnDireccion.destroy().draw();
            TableColumnMail.destroy().draw();
            TableColumnPagos.destroy().draw();
            TableColumnGestiones.destroy().draw();
        }
    }
    function getTableFields(Tabla){
        $.ajax({
            type: "POST",
            url: "ajax/selectCamposTablas.php",
            dataType: "html",
            async: false,
            data: {
                tabla: Tabla
            },
            success: function(data){
                $("select[name='Campo']").html(data);
                $("select[name='Campo']").selectpicker("refresh");
            },
            error: function(response){
            }
        });
    }
    function getFunciones(){
        $.ajax({
            type: "POST",
            url: "ajax/getFuncionesColumnasExcel.php",
            dataType: "html",
            async: false,
            data: {},
            success: function(data){
                $("select[name='Funcion']").html(data);
                $("select[name='Funcion']").selectpicker("refresh");
            },
            error: function(response){
            }
        });
    }
    function saveColumn(){
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("select[name='Campo']").val();
        var PatronFecha = $("input[name='PatronFecha']").val();
        var ColumnaExcel = $("input[name='ColumnaExcel']").val();
        var PosicionInicio = $("input[name='CaracterDesde']").val();
        var CantCaracteres = $("input[name='CaracterHasta']").val();
        var Funcion = $("select[name='Funcion']").val();
        var Parametro = $("input[name='Parametros']").val();
        var PrioridadFono = $("input[name='PrioridadFono']").val();
        $.ajax({
            type: "POST",
            url: "ajax/addColumnCarga.php",
            dataType: "html",
            async: false,
            data: {
                Sheet: idSheet,
                Tabla: Tabla,
                PatronFecha: PatronFecha,
                Campo: Campo,
                ColumnaExcel: ColumnaExcel,
                PosicionInicio: PosicionInicio,
                CantCaracteres: CantCaracteres,
                Funcion: Funcion,
                Parametro: Parametro,
                PrioridadFono: PrioridadFono,
                id_template: id_template
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        destroyColumnTables();
                        getColumnsTemplate();
                    }else{
                        bootbox.alert("Error: "+json.Status,function(){AddClassModalOpen();});
                    }
                }
            },
            error: function(response){
            }
        });
    }
    function deleteColumn(Column){
        $.ajax({
            type: "POST",
            url: "ajax/deleteColumnCarga.php",
            dataType: "html",
            async: false,
            data: {
                Sheet: idSheet,
                Column: Column
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        destroyColumnTables();
                        getColumnsTemplate();
                    }
                }
                AddClassModalOpen();
            },
            error: function(response){
            }
        });
    }
    function getColumnData(Column){
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "ajax/getColumnCarga.php",
            dataType: "html",
            async: false,
            data: {
                Sheet: idSheet,
                Column: Column
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        ToReturn = json.Column;
                    }
                }
            },
            error: function(response){
            }
        });
        return ToReturn;
    }
    function updateColumn(){
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("select[name='Campo']").val();
        var PatronFecha = $("input[name='PatronFecha']").val();
        var ColumnaExcel = $("input[name='ColumnaExcel']").val();
        var PosicionInicio = $("input[name='CaracterDesde']").val();
        var CantCaracteres = $("input[name='CaracterHasta']").val();
        var Funcion = $("select[name='Funcion']").val();
        var Parametro = $("input[name='Parametros']").val();
        var PrioridadFono = $("input[name='PrioridadFono']").val();
        $.ajax({
            type: "POST",
            url: "ajax/updateColumnCarga.php",
            dataType: "html",
            async: false,
            data: {
                Column: idColumn,
                Tabla: Tabla,
                Campo: Campo,
                PatronFecha: PatronFecha,
                ColumnaExcel: ColumnaExcel,
                PosicionInicio: PosicionInicio,
                CantCaracteres: CantCaracteres,
                Funcion: Funcion,
                Parametro: Parametro,
                PrioridadFono: PrioridadFono
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        destroyColumnTables();
                        getColumnsTemplate();
                    }
                }
                AddClassModalOpen();
            },
            error: function(response){
            }
        });
    }
    function saveSheet(){
        var NombreHoja = $("input[name='NombreHoja']").val();
        var NumeroHoja = $("input[name='NumeroHoja']").val();
        var TipoCarga = $("select[name='TipoCarga']").val();
        $.ajax({
            type: "POST",
            url: "ajax/addSheetCarga.php",
            dataType: "json",
            async: false,
            data: {
                NombreHoja: NombreHoja,
                NumeroHoja: NumeroHoja,
                TipoCarga: TipoCarga,
                id_template: id_template
            },
            success: function(data){
                if (data.result){
                    getSheets(id_template);
                }else{
                    bootbox.alert("Error: " + data.Status,function(){AddClassModalOpen();});
                }
                AddClassModalOpen();
            },
            error: function(response){
            }
        });
    }
    function deleteSheet(idSheet){
        $.ajax({
            type: "POST",
            url: "ajax/deleteSheetCarga.php",
            dataType: "json",
            async: false,
            data: {
                Sheet: idSheet
            },
            success: function(data){
                if (data.result){
                    getSheets(id_template);
                }
                AddClassModalOpen();
            },
            error: function(response){
            }
        });
    }
    function getSheetData(){
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "ajax/getSheetCarga.php",
            dataType: "html",
            async: false,
            data: {
                Sheet: idSheet
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        ToReturn = json.Sheet;
                    }
                }
            },
            error: function(response){
            }
        });
        return ToReturn;
    }
    function updateSheet(){
        var NombreHoja = $("input[name='NombreHoja']").val();
        var NumeroHoja = $("input[name='NumeroHoja']").val();
        var TipoCarga = $("select[name='TipoCarga']").val();
        $.ajax({
            type: "POST",
            url: "ajax/updateSheetCarga.php",
            dataType: "json",
            async: false,
            data: {
                Sheet: idSheet,
                NombreHoja: NombreHoja,
                NumeroHoja: NumeroHoja,
                TipoCarga: TipoCarga
            },
            success: function(data){
                if (data.result){
                    getSheets(id_template);
                }
                AddClassModalOpen();
            },
            error: function(response){
            }
        });
    }
    function SaveTemplate(){
        var NombreTemplate = $("[name='NombreTemplate']").val();
        var TipoArchivo = $("select[name='TipoArchivo']").val();
        var Separador = $("[name='Separador']").val();
        var CabeceroObject = $("input[name='Cabecero']");
        if (CabeceroObject.is(":checked")) {
            var Cabecero = "1";
        } else {
            var Cabecero = "0";
        }
        $.ajax({
            type: "POST",
            url: "ajax/addTemplateCarga.php",
            dataType: "html",
            async: false,
            data: {
                NombreTemplate: NombreTemplate,
                TipoArchivo: TipoArchivo,
                Separador: Separador,
                Cabecero: Cabecero
            },
            success: function(data){
                getTemplates();
            }
        });
    }
    function isDateField(Table,Field){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "ajax/isDateField.php",
            dataType: "html",
            async: false,
            data: {
                Table: Table,
                Field: Field
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    ToReturn = json.result;
                    isColumnDate = ToReturn;
                }
            },
            error: function(response){
            }
        });
        return ToReturn;
    }
    function addColumnsRequired(Tabla){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "ajax/addColumnsRequired.php",
            dataType: "html",
            async: false,
            data: {
                Tabla: Tabla,
                idSheet: idSheet,
                id_template: id_template
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        ToReturn = true;
                    }
                }
            }
        });
        return ToReturn;
    }
    function removeColumnsRequired(Tabla){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "ajax/deleteColumnsCargaFromTable.php",
            dataType: "html",
            async: false,
            data: {
                Tabla: Tabla,
                idSheet: idSheet
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        ToReturn = true;
                    }
                }
            },
            error: function(response){
            }
        });
        return ToReturn;
    }
    function CheckTables(){
        var Sheet = getSheetData(idSheet);
        switch(Sheet.TipoCarga){
            case "carga":
                var ObjectLabel = $('#ColumnsTablePersona').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnPersona.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
                var ObjectLabel = $('#ColumnsTableDeuda').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnDeuda.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
                var ObjectLabel = $('#ColumnsTableFono').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnFono.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
                var ObjectLabel = $('#ColumnsTableDireccion').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnDireccion.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
                var ObjectLabel = $('#ColumnsTableMail').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnMail.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
            break;
            case "pagos":
                var ObjectLabel = $('#ColumnsTablePagos').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnPagos.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
            break;
            case "cargagestiones":
                var ObjectLabel = $('#ColumnsTableGestiones').closest(".panel").find(".checkboxTable");
                var ObjectCheckbox = ObjectLabel.find("input");
                ObjectCheckbox.prop("checked",false);
                ObjectLabel.removeClass("active");
                if(TableColumnGestiones.page.info().recordsTotal > 0){
                    if(!ObjectCheckbox.is(":checked")){
                        ObjectCheckbox.prop("checked",true);
                        ObjectLabel.addClass("active");
                    }
                }
            break;
        }
    }
    function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
    }
});
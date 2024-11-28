$(document).ready(function(){
    var CampoArray = [];
    var CamposTable;
    var ListaOpciones = [];
    var OpcionesTable;

    getCampoTableList();
    updateCamposTable();

    $("#CrearCampo").click(function(){
        var Template = $("#CampoTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE CAMPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var Codigo = $("input[name='Codigo']").val();
                        var Titulo = $("input[name='Titulo']").val();
                        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
                        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
                        var Tipo = $("select[name='Tipo']").val();
                        var Mandatorio = $("select[name='Mandatorio']").val();
                        var Deshabilitado = $("select[name='Deshabilitado']").val();
                        var Cedente = $('#Cedente').val();
                        var Cedente_Length = $("#Cedente option:selected").length;
                        var CanAdd = false;
                        if(Codigo != ""){
                            if(Titulo != ""){
                                if(Tipo != ""){
                                    if (Cedente_Length > 0) {
                                        CanAdd = true;
                                    }else{
                                        bootbox.alert("Debe seleccionar un Cedente");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un Tipo de Campo");
                                }
                            }else{
                                bootbox.alert("Debe ingresar un Título");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Código");
                        }
                        if(CanAdd){
                            if(CanAddMandatorioSeleccionado){
                                if(CanAddCodigo(Codigo)){
                                    CrearCampoGestion();   
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
        getTipos();
        showNivel3();
        updateListaOpcionesTable('');
        setTimeout(() => {
            $("input[name='idCampo']").val('');
            $("input[name='Codigo']").prop("disabled", false);
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    $("body").on("change", "select[name='Tipo']", function () {
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        switch (Value) {
            case '3':
            case '4':
                $("#SelectOptionsContainer").show();
                break;
            default:
                $("#SelectOptionsContainer").hide();
                break;
        }
    });
    $("body").on("click", "#AgregarOpcion", function () {
        var Template = $("#AgregarOpcionTemplate").html()
        bootbox.dialog({
            title: "NUEVA OPCIÓN",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Prioridad = $("input[name='Prioridad']").val();
                        var Opcion = $("input[name='Opcion']").val();
                        var Seleccionado = $("select[name='Seleccionado']").val();
                        var CanAdd = false;
                        if (Prioridad != "") {
                            if (Opcion != "") {
                                if (Seleccionado != "") {
                                    CanAdd = true;
                                } else {
                                    bootbox.alert("Debe ingresar el estado de la opción");
                                }
                            } else {
                                bootbox.alert("Debe ingresar el texto de la opción");
                            }
                        } else {
                            bootbox.alert("Debe ingresar una prioridad");
                        }
                        if (CanAdd) {
                            if (CanAddPrioridad(Prioridad)) {
                                if (CanAddSeleccionado(Seleccionado)) {
                                    AgregarOpcionGestion();
                                } else {
                                    bootbox.alert("Ya existe una opcion Con valor Seleccionado, elimine la anterior e intente nuevamente.");
                                    return false;
                                }
                            } else {
                                bootbox.alert("Numero de prioridad existente, intente con un numero distinto.");
                                return false;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });
    function CrearCampoGestion() {
        var Codigo = $("input[name='Codigo']").val();
        var Titulo = $("input[name='Titulo']").val();
        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
        var Tipo = $("select[name='Tipo']").val();
        var Mandatorio = $("select[name='Mandatorio']").val();
        var Deshabilitado = $("select[name='Deshabilitado']").val();
        var Cedente = $('#Cedente').val();
        var Respuesta_Nivel3 = $('#Respuesta_Nivel3').val();
        var ArrayOpciones = [];
        switch (Tipo) {
            case "3":
            case "4":
                OpcionesTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    ArrayOpciones.push(data);
                });
                break;
        }
        console.log(ArrayOpciones);
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/CrearCampo.php",
            dataType: "html",
            data: {
                Codigo: Codigo,
                Titulo: Titulo,
                ValorEjemplo: ValorEjemplo,
                ValorPredeterminado: ValorPredeterminado,
                Tipo: Tipo,
                Mandatorio: Mandatorio,
                Deshabilitado: Deshabilitado,
                ArrayOpciones: ArrayOpciones,
                Cedente: Cedente,
                Respuesta_Nivel3: Respuesta_Nivel3
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
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getCampoTableList(false);
                        updateCamposTable();
                    }
                }
            },
            error: function () {
            }
        });
    }
    $("body").on("click", ".Update", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCampo = ObjectDiv.attr("id");
        var Campo = getCampo(idCampo);
        var Template = $("#CampoTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR CAMPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Codigo = $("input[name='Codigo']").val();
                        var Titulo = $("input[name='Titulo']").val();
                        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
                        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
                        var Tipo = $("select[name='Tipo']").val();
                        var Mandatorio = $("select[name='Mandatorio']").val();
                        var Deshabilitado = $("select[name='Deshabilitado']").val();
                        var Cedente = $('#Cedente').val();
                        var Cedente_Length = $("#Cedente option:selected").length;
                        var CanUpdate = false;
                        if (Codigo != "") {
                            if (Titulo != "") {
                                if (Tipo != "") {
                                    if (Cedente_Length > 0) {
                                        CanUpdate = true;
                                    } else {
                                        bootbox.alert("Debe seleccionar un Cedente");
                                    }
                                } else {
                                    bootbox.alert("Debe ingresar un Tipo de Campo");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Título");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Código");
                        }
                        if (CanUpdate) {
                            UpdateCampo(idCampo);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        getTipos();
        showNivel3();
        updateListaOpcionesTable(Campo.Opciones);
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            $("input[name='idCampo']").val(idCampo);
            $("input[name='Codigo']").prop("disabled", true);
            $("input[name='Codigo']").val(Campo.Codigo);
            $("input[name='Titulo']").val(Campo.Titulo);
            $("input[name='ValorEjemplo']").val(Campo.ValorEjemplo);
            $("input[name='ValorPredeterminado']").val(Campo.ValorPredeterminado);
            $("select[name='Tipo']").val(Campo.Tipo);
            $("select[name='Mandatorio']").val(Campo.Mandatorio);
            $("select[name='Deshabilitado']").val(Campo.Deshabilitado);
            $.each(Campo.Cedente.split(","), function (i, e) {
                $("#Cedente option[value='" + e + "']").prop("selected", true);
            });
            $.each(Campo.Respuesta_Nivel3.split(","), function (i, e) {
                $("#Respuesta_Nivel3 option[value='" + e + "']").prop("selected", true);
            });
            if(Campo.Tipo == 1 || Campo.Tipo == 2){
                $("#SelectOptionsContainer").hide();
            }else{
                $("#SelectOptionsContainer").show();
            }
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function UpdateCampo(idCampo) {
        var Titulo = $("input[name='Titulo']").val();
        var ValorEjemplo = $("input[name='ValorEjemplo']").val();
        var ValorPredeterminado = $("input[name='ValorPredeterminado']").val();
        var Tipo = $("select[name='Tipo']").val();
        var Mandatorio = $("select[name='Mandatorio']").val();
        var Deshabilitado = $("select[name='Deshabilitado']").val();
        var Cedente = $('#Cedente').val();
        var Respuesta_Nivel3 = $('#Respuesta_Nivel3').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/updateCampo.php",
            dataType: "html",
            data: {
                Titulo: Titulo,
                ValorEjemplo: ValorEjemplo,
                ValorPredeterminado: ValorPredeterminado,
                Tipo: Tipo,
                Mandatorio: Mandatorio,
                Deshabilitado: Deshabilitado,
                Cedente: Cedente,
                Respuesta_Nivel3: Respuesta_Nivel3,
                idCampo: idCampo
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
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getCampoTableList(false);
                        updateCamposTable();
                    }
                }
            },
            error: function () {
            }
        });
    }
    function getCampo(idCampo) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/getCampo.php",
            dataType: "html",
            data: {
                idCampo: idCampo
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
                    ToReturn = JSON.parse(data);
                }
            },
            error: function () {
            }
        });
        return ToReturn;
    }
    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCampo = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el campo seleccionado? al aceptar se eliminaran todos los registros que referencien a la misma.",
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
                    deleteCampo(idCampo);
                }
            }
        });
    });
    function deleteCampo(idCampo) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/deleteCampo.php",
            dataType: "html",
            data: {
                idCampo: idCampo
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
                    getCampoTableList(false);
                    updateCamposTable();
                }
            },
            error: function () {
            }
        });
    }
    $("body").on("click",".DeleteOpcion",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        idCampo = $('#idCampo').val()
        if(idCampo){
            bootbox.confirm({
                message: "¿Esta seguro de eliminar el campo seleccionado? al aceptar se eliminaran todos los registros que referencien a la misma.",
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
                        deleteOpcionCampo(ObjectTR);
                    }
                }
            });
        }else{
            OpcionesTable.rows(ObjectTR).remove().draw();
        }
    });
    function deleteOpcionCampo(ObjectTR) {
        var idOpcion = ObjectTR.attr("id");
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/deleteOpcionCampo.php",
            data: {
                idOpcion: idOpcion
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
                    OpcionesTable.rows(ObjectTR).remove().draw();
                }
            },
            error: function () {
            }
        });
    }
    $("body").on("keyup","input[name='Codigo']",function(){
        this.value = (this.value + '').replace(/\s/, '');
    });

    function getCampoTableList(Modal = true){
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/getCampoTableList.php",
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
                { data: 'Codigo' },
                { data: 'Titulo' },
                { data: 'ValorEjemplo' },
                { data: 'ValorPredeterminado' },
                { data: 'Tipo' },
                { data: 'Dinamico' },
                { data: 'Mandatorio' },
                { data: 'Deshabilitado' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 5,
                    "searchable": false,
                    "data": "Dinamico",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 6,
                    "searchable": false,
                    "data": "Mandatorio",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 7,
                    "searchable": false,
                    "data": "Deshabilitado",
                    "render": function( data, type, row ) {
                        var ToReturn = data == "1" ? "Si" : "No";
                        return "<div style='text-align: center;'>"+ToReturn+"</div>";
                    }
                },
                {
                    "targets": 8,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id=" + data +"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg Update'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg Delete'></i></div>";
                    }
                },
            ]
        });
    }
    function getTipos(){
        $.ajax({
            type: "POST",
            url: "../reclutamiento/ajax/getTiposCamposSelect.php",
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
    function updateListaOpcionesTable(ListaOpciones){
        OpcionesTable = $('#ListaOpciones').DataTable({
            data: ListaOpciones,
            columns: [
                { data: 'Prioridad' },
                { data: 'Nombre' },
                { data: 'Seleccionado' },
                { data: 'Accion' }
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', data.id)
            },
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
                        return "<div style='text-align: center;'><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteOpcion'></i></div>";
                    }
                },
            ]
        });
    }
    function AgregarOpcionGestion(){
        idCampo = $('#idCampo').val();
        var Prioridad = $("input[name='Prioridad']").val();
        var Opcion = $("input[name='Opcion']").val();
        var Seleccionado = $("select[name='Seleccionado']").val();
        if (idCampo) {
            $.ajax({
                type: "POST",
                url: "../includes/admin/conf_campos_gestion/CrearOpcionCampo.php",
                dataType: "html",
                data: {
                    Prioridad: Prioridad,
                    Opcion: Opcion,
                    Seleccionado: Seleccionado,
                    idCampo: idCampo
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
                    console.log(data);
                    if (isJson(data)) {
                        var Json = JSON.parse(data);
                        if (Json.result) {
                            var rowNode = OpcionesTable.row.add({
                                "Prioridad": Prioridad,
                                "Nombre": Opcion,
                                "Seleccionado": Seleccionado,
                                "Accion": "0"
                            }).draw(false).node();

                            $(rowNode).attr('id', Json.id);
                        }
                    }
                }
            });
        }else{
            OpcionesTable.row.add({
                "Prioridad": Prioridad,
                "Nombre": Opcion,
                "Seleccionado": Seleccionado,
                "Accion": "0"
            }).draw();
        }
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
            url: "../includes/admin/conf_campos_gestion/ValidacionCodigoAgregar.php",
            dataType: "html",
            data: {
                Codigo: Codigo
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    if(Json.result){
                        ToReturn = true;
                    }
                }
            }
        });
        return ToReturn;
    }
    function showNivel3() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/showNivel3.php",
            dataType: "html",
            async: false,
            success: function (data) {
                $("select[name='Respuesta_Nivel3']").html(data);
            }
        });
    }
});
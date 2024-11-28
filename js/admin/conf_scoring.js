$(document).ready(function () {
    var VariablesArray = [];
    var VariableTable;
    var Nivel
    var PorcentajesArray = [];
    var PorcentajeTable;
    var Variable;

    getVariables();
    updateVariableTable();
    getPorcentajes();
    updatePorcentajeTable();

    $(document).on('click','#CrearVariable', function () {
        var Template = $("#VariableTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE VARIABLE",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var TipoVariable = $("#id_tipo_variable option:selected");
                        var TipoEscala = $("#id_tipo_escala option:selected");
                        var NombreColumna = $("#nombre_columna option:selected");
                        var CanAdd = false;
                        if (TipoVariable.length > 0 && TipoVariable.val()) {
                            if (NombreColumna.length > 0 && NombreColumna.val()) {
                                if (TipoEscala.length > 0 && TipoEscala.val()) {
                                    CanAdd = true;
                                } else {
                                    bootbox.alert("Debe seleccionar una Escala");
                                }
                            } else {
                                bootbox.alert("Debe seleccionar una Columna");
                            }
                        } else {
                            bootbox.alert("Debe seleccionar una Variable");
                        }
                        if (CanAdd) {
                            CrearVariable();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            getTiposVariablesCreate();
            getTiposEscalas();
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    $(document).on("change", "#id_tipo_variable", function () {
        var IdVariable = $(this).val();
        if ($("#definida").is(':checked')) {
            Definida = 1
        } else {
            Definida = 0;
        }
        getColumnas(IdVariable, Definida);   
    });
    $(document).on("change", "#definida", function () {
        var IdVariable = $("#id_tipo_variable").val();
        if ($(this).is(':checked')) {
            Definida = 1
        } else {
            Definida = 0;
        }
        getColumnas(IdVariable, Definida);
    });
    function getColumnas(IdVariable, Definida) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getColumnas.php",
            async: false,
            dataType: 'json',
            data:{
                IdVariable: IdVariable,
                Definida: Definida
            },
            success: function (data) {
                $('#nombre_columna').empty();
                $(".selectpicker").selectpicker("refresh");
                $.each(data, function (index, array) {
                    $('#nombre_columna').append($('<option>', {
                        value: array.Columna,
                        text: array.Columna
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function CrearVariable() {
        var TipoVariable = $("#id_tipo_variable").val();
        var TipoEscala = $('#id_tipo_escala').val();
        var NombreColumna = $('#nombre_columna').val();
        if($('#definida').is(':checked')){
            Definida = 1
        }else{
            Definida = 0;
        }
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/CrearVariable.php",
            dataType: "html",
            data: {
                TipoVariable: TipoVariable,
                TipoEscala: TipoEscala,
                NombreColumna: NombreColumna,
                Definida: Definida
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getVariables(false);
                        updateVariableTable();
                    }
                }
            }
        });
    }
    $(document).on("change", ".Activo", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idVariable = ObjectDiv.attr("id");
        if(ObjectMe.is(':checked')){
            Activo = 1;
        }else{
            Activo = 0;
        }
        UpdateActivo(idVariable,Activo);
    });
    function UpdateActivo(idVariable,Activo) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/updateActivo.php",
            dataType: "html",
            data: {
                idVariable: idVariable,
                Activo: Activo
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getPorcentajes();
                        updatePorcentajeTable();
                    }
                }
            }
        });
    }
    $(document).on("click", ".UpdateVariable", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idVariable = ObjectDiv.attr("id");
        var Variable = getVariable(idVariable);
        var Template = $("#VariableTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR VARIABLE",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var TipoVariable = $("#id_tipo_variable option:selected");
                        var TipoEscala = $("#id_tipo_escala option:selected");
                        var NombreColumna = $("#nombre_columna option:selected");
                        var CanUpdate = false;
                        if (TipoVariable.length > 0 && TipoVariable.val()) {
                            if (NombreColumna.length > 0 && NombreColumna.val()) {
                                if (TipoEscala.length > 0 && TipoEscala.val()) {
                                    CanUpdate = true;
                                } else {
                                    bootbox.alert("Debe seleccionar una Escala");
                                }
                            } else {
                                bootbox.alert("Debe seleccionar una Columna");
                            }
                        } else {
                            bootbox.alert("Debe seleccionar una Variable");
                        }
                        if (CanUpdate) {
                            UpdateVariable(idVariable);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            getTiposVariablesUpdate(Variable.id_tipo_variable);
            getTiposEscalas();
            $("input[name='idVariable']").val(idVariable);
            $("#id_tipo_variable").val(Variable.id_tipo_variable);
            getColumnas(Variable.id_tipo_variable,Variable.definida);
            $(".selectpicker").selectpicker("refresh");
            $("#nombre_columna option[value='" + Variable.nombre_columna + "']").prop("selected", true);
            $("#id_tipo_escala option[value='" + Variable.id_tipo_escala + "']").prop("selected", true);
            if(Variable.definida == 1){
                $('#definida').prop("checked", true);
            }else{
                $('#definida').prop("checked", false);
            }
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function getVariable(idVariable) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getVariable.php",
            dataType: "html",
            data: {
                idVariable: idVariable
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
            }
        });
        return ToReturn;
    }
    function getTiposVariablesUpdate(idVariable) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getTiposVariablesUpdate.php",
            async: false,
            dataType: 'json',
            data:{
                idVariable: idVariable
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_tipo_variable').append($('<option>', {
                        value: array.id,
                        text: array.nombre_variable
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function UpdateVariable(idVariable) {
        var TipoVariable = $("#id_tipo_variable").val();
        var TipoEscala = $('#id_tipo_escala').val();
        var NombreColumna = $('#nombre_columna').val();
        if ($('#definida').is(':checked')) {
            Definida = 1
        } else {
            Definida = 0;
        }
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/updateVariable.php",
            dataType: "html",
            data: {
                TipoVariable: TipoVariable,
                TipoEscala: TipoEscala,
                NombreColumna: NombreColumna,
                Definida: Definida,
                idVariable: idVariable
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getVariables(false);
                        updateVariableTable();
                        getPorcentajes();
                        updatePorcentajeTable();
                    }
                }
            }
        });
    }
    $(document).on("click", ".DeleteVariable", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idVariable = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro? al aceptar se eliminaran todos los registros que referencien al mismo",
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
                    deleteVariable(idVariable);
                }
            }
        });
    });
    function deleteVariable(idVariable) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/deleteVariable.php",
            dataType: "html",
            data: {
                idVariable: idVariable
            },
            async: false,
            success: function (data) {
                if (isJson(data)) {
                    getVariables(false);
                    updateVariableTable();
                    getPorcentajes();
                    updatePorcentajeTable();
                }
            }
        });
    }
    function getVariables() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getVariables.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                VariablesArray = [];
            },
            success: function (data) {
                if (isJson(data)) {
                    VariablesArray = JSON.parse(data);
                }
            }
        });
    }
    function updateVariableTable() {
        VariableTable = $('#VariableTable').DataTable({
            data: VariablesArray,
            "bDestroy": true,
            columns: [
                { data: 'Variable' },
                { data: 'Columna' },
                { data: 'Escala' },
                { data: 'Activo' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        Checked =  data ? "checked" : "";
                        return "<div style='text-align: center;' id=" + row.id + "><input class='toggle-switch Activo' type='checkbox' " + Checked + "><label class='toggle-switch-label'></label></div>";
                    }
                }, 
                {
                    "targets": 4,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 5px;' class='btn fa fa-plus btn-purple btn-icon icon-lg ModalNivel'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg UpdateVariable'></i><i style='cursor: pointer; margin: 0 5px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteVariable'></i></div>";
                    }
                },
            ]
        });
        $('#VariableTable').css('width', '100%')
        VariableTable.columns.adjust().draw();
    }
    function getTiposVariablesCreate() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getTiposVariablesCreate.php",
            async: false,
            dataType: 'json',
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_tipo_variable').append($('<option>', {
                        value: array.id,
                        text: array.nombre_variable
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function getTiposEscalas() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getTiposEscalas.php",
            async: false,
            dataType: 'json',
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_tipo_escala').append($('<option>', {
                        value: array.id,
                        text: array.nombre_escala
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }

    //NIVELES

    function getNiveles(idVariable) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getNiveles.php",
            dataType: "html",
            async: false,
            data: {
                idVariable: idVariable
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    NivelesArray = JSON.parse(data);
                }
            }
        });
    }
    function updateNivelesTable() {
        NivelTable = $('#NivelTable').DataTable({
            data: NivelesArray,
            "bDestroy": true,
            columns: [
                { data: 'Escala' },
                { data: 'Porcentaje' },
                { data: 'Valor' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteNivel'></i></div>";
                    }
                },
            ]
        });
    }
    $(document).on('click', '.ModalNivel', function () {
        var Template = $("#NivelTemplate").html()
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idVariable = ObjectDiv.attr("id");
        bootbox.dialog({
            title: "NIVELES",
            message: Template,
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $(".CrearNivel").attr('id',idVariable);
            getNiveles(idVariable);
            updateNivelesTable();
        }, 200);
    });
    $(document).on('click', '.CrearNivel', function () {
        var Template = $("#CrearNivelTemplate").html()
        var ObjectMe = $(this);
        var idVariable = ObjectMe.attr("id");
        bootbox.dialog({
            title: "CREACIÓN DE NIVEL",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Escala = $("#id_escala option:selected");
                        var Porcentaje = $("#porcentaje").val()
                        var Valor = $("#valor").val()
                        var CanAdd = false;
                        if (Escala.length > 0 && Escala.val()) {
                            if (Porcentaje != '' && Porcentaje <= 100 && Porcentaje > 0) {
                                if (Valor != '') {
                                    ComprobarNivelEscalas(Escala.val(), Porcentaje, Valor, idVariable);
                                    if (Nivel) {
                                        CanAdd = true;
                                    }
                                } else {
                                    bootbox.alert("Debe ingresar un Valor");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Porcentaje valido");
                            }
                        } else {
                            bootbox.alert("Debe seleccionar una Escala");
                        }
                        if (CanAdd) {
                            CrearNivel(idVariable);
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("#id_variable").val(idVariable);
            getEscalas(idVariable);
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function getEscalas(Id) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getEscalas.php",
            async: false,
            dataType: 'json',
            data: {
                Id: Id
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_escala').append($('<option>', {
                        value: array.id,
                        text: array.nombre_escala
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function ComprobarNivelEscalas(Escala, Porcentaje, Valor, idVariable) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/ComprobarNivelEscalas.php",
            async: false,
            data: {
                Escala: Escala,
                Porcentaje: Porcentaje,
                Valor: Valor,
                idVariable: idVariable
            },
            success: function (json) {
                data = JSON.parse(json)
                if (!data.result) {
                    bootbox.alert(data.message);
                }
                Nivel = data.result;
                return data.result;
            }
        });
    }
    function CrearNivel(idVariable) {
        var Escala = $("#id_escala").val();
        var Porcentaje = $('#porcentaje').val();
        var Valor = $('#valor').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/CrearNivel.php",
            dataType: "html",
            data: {
                Escala: Escala,
                Porcentaje: Porcentaje,
                Valor: Valor,
                idVariable: idVariable
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getNiveles(idVariable);
                        updateNivelesTable();
                    }
                }
            }
        });
    }
    $(document).on("click", ".DeleteNivel", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idNivel = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    deleteNivel(idNivel);
                }
            }
        });
    });
    function deleteNivel(idNivel) {
        idVariable = $('.CrearNivel').attr('id');
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/deleteNivel.php",
            dataType: "html",
            data: {
                idNivel: idNivel
            },
            async: false,
            success: function (data) {
                if (isJson(data)) {
                    getNiveles(idVariable);
                    updateNivelesTable();
                }
            }
        });
    }

    //PORCENTAJES

    function getPorcentajes() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getPorcentajes.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                PorcentajesArray = [];
            },
            success: function (data) {
                if (isJson(data)) {
                    PorcentajesArray = JSON.parse(data);
                }
            }
        });
    }
    function updatePorcentajeTable() {
        PorcentajeTable = $('#PorcentajeTable').DataTable({
            data: PorcentajesArray,
            "bDestroy": true,
            columns: [
                { data: 'Variable' },
                { data: 'Porcentaje' },
                { data: 'Scoring' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg UpdatePorcentaje'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeletePorcentaje'></i></div>";
                    }
                },
            ]
        });
        $('#PorcentajeTable').css('width', '100%')
        PorcentajeTable.columns.adjust().draw();
    }

    $(document).on('click', '#CrearPorcentaje', function () {
        var Template = $("#PorcentajeTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE PORCENTAJE",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var idVariable = $("#id_variable option:selected");
                        var Porcentaje = $("#porcentaje").val()
                        var Scoring = $("#scoring").val()
                        var CanAdd = false;
                        if (idVariable.length > 0 && idVariable.val()) {
                            if (Porcentaje != '' && Porcentaje <= 100 && Porcentaje > 0) {
                                if (Scoring != '') {
                                    ComprobarPorcentajesVariableCreate(Porcentaje);
                                    if (Variable) {
                                        CanAdd = true;
                                    }
                                } else {
                                    bootbox.alert("Debe ingresar un Scoring");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Porcentaje valido");
                            }
                        } else {
                            bootbox.alert("Debe seleccionar una Variable");
                        }
                        if (CanAdd) {
                            CrearPorcentaje();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            getVariablesPorcentajeCreate();
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function getVariablesPorcentajeCreate() {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getVariablesPorcentajeCreate.php",
            async: false,
            dataType: 'json',
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_variable').append($('<option>', {
                        value: array.id,
                        text: array.nombre_variable
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function ComprobarPorcentajesVariableCreate(Porcentaje) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/ComprobarPorcentajesVariableCreate.php",
            async: false,
            data: {
                Porcentaje: Porcentaje
            },
            success: function (json) {
                data = JSON.parse(json)
                if (!data.result) {
                    bootbox.alert(data.message);
                }
                Variable = data.result;
                return data.result;
            }
        });
    }
    function CrearPorcentaje() {
        var idVariable = $("#id_variable").val();
        var Porcentaje = $("#porcentaje").val()
        var Scoring = $("#scoring").val()
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/CrearPorcentaje.php",
            dataType: "html",
            data: {
                idVariable: idVariable,
                Porcentaje: Porcentaje,
                Scoring: Scoring
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getPorcentajes(false);
                        updatePorcentajeTable();
                    }
                }
            }
        });
    }
    $(document).on("click", ".UpdatePorcentaje", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPorcentaje = ObjectDiv.attr("id");
        var Porcentaje = getPorcentaje(idPorcentaje);
        var Template = $("#PorcentajeTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR PORCENTAJE",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var idVariable = $("#id_variable option:selected");
                        var Porcentaje = $("#porcentaje").val();
                        var Scoring = $("#scoring").val();
                        var CanUpdate = false;
                        if (idVariable.length > 0 && idVariable.val()) {
                            if (Porcentaje != '' && Porcentaje <= 100 && Porcentaje > 0) {
                                if (Scoring != '') {
                                    ComprobarPorcentajesVariableUpdate(Porcentaje, idPorcentaje);
                                    if (Variable) {
                                        CanUpdate = true;
                                    }
                                } else {
                                    bootbox.alert("Debe ingresar un Scoring");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Porcentaje valido");
                            }
                        } else {
                            bootbox.alert("Debe seleccionar una Variable");
                        }
                        if (CanUpdate) {
                            UpdatePorcentaje(idPorcentaje);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        setTimeout(() => {
            getVariablesPorcentajeUpdate(Porcentaje.id_variable);
            $("input[name='idPorcentaje']").val(idPorcentaje);
            $("#id_variable").val(Porcentaje.id_variable);
            $("#porcentaje").val(Porcentaje.porcentaje);
            $("#scoring").val(Porcentaje.scoring);
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function getVariablesPorcentajeUpdate(idVariable) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getVariablesPorcentajeUpdate.php",
            async: false,
            dataType: 'json',
            data: {
                idVariable: idVariable
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_variable').append($('<option>', {
                        value: array.id,
                        text: array.nombre_variable
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }
    function ComprobarPorcentajesVariableUpdate(Porcentaje, idPorcentaje) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/ComprobarPorcentajesVariableUpdate.php",
            async: false,
            data: {
                Porcentaje: Porcentaje,
                idPorcentaje: idPorcentaje
            },
            success: function (json) {
                data = JSON.parse(json)
                if (!data.result) {
                    bootbox.alert(data.message);
                }
                Variable = data.result;
                return data.result;
            }
        });
    }
    function getPorcentaje(idPorcentaje) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/getPorcentaje.php",
            dataType: "html",
            data: {
                idPorcentaje: idPorcentaje
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
            }
        });
        return ToReturn;
    }
    function UpdatePorcentaje(idPorcentaje) {
        var idVariable = $("#id_variable").val();
        var Porcentaje = $("#porcentaje").val();
        var Scoring = $("#scoring").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/updatePorcentaje.php",
            dataType: "html",
            data: {
                idVariable: idVariable,
                Porcentaje: Porcentaje,
                Scoring: Scoring,
                idPorcentaje: idPorcentaje
            },
            async: false,
            success: function (data) {
                console.log(data);
                if (isJson(data)) {
                    var Json = JSON.parse(data);
                    if (Json.result) {
                        getPorcentajes();
                        updatePorcentajeTable();
                    }
                }
            }
        });
    }
    $(document).on("click", ".DeletePorcentaje", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPorcentaje = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    deletePorcentaje(idPorcentaje);
                }
            }
        });
    });
    function deletePorcentaje(idPorcentaje) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_scoring/deletePorcentaje.php",
            dataType: "html",
            data: {
                idPorcentaje: idPorcentaje
            },
            async: false,
            success: function (data) {
                if (isJson(data)) {
                    getPorcentajes(false);
                    updatePorcentajeTable();
                }
            }
        });
    }
});
$(document).ready(function () {
    var ScriptArray = [];
    var ScriptTable;

    getScriptTableList();
    updateScriptTableList();

    $("#CrearScript").click(function () {
        var Template = $("#ScriptTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE SCRIPT",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Script = $("#script").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanAdd = false;
                        if (Script != "") {
                            if (Cedente_Length > 0) {
                                CanAdd = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Script");
                        }
                        if (CanAdd) {
                            CrearScript();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("input[name='idScript']").val('');
            getCedentesCreate();
            $(".selectpicker").selectpicker("refresh");
            $('#script').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
        }, 200);
    });
    function CrearScript() {
        alert("aca");
        var Script = $("#script").summernote('code');
        var Cedente = $('#id_cedente').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/CrearScript.php",
            dataType: "html",
            data: {
                Script: Script,
                Cedente: Cedente
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
                        getScriptTableList(false);
                        updateScriptTableList();
                    }
                }
            }
        });
    }
    $("body").on("click", ".UpdateScript", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idScript = ObjectDiv.attr("id");
        var Script = getScript(idScript);
        var Template = $("#ScriptTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR SCRIPT",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Script = $("#script").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanUpdate = false;
                        if (Script != "") {
                            if (Cedente_Length > 0) {
                                CanUpdate = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Script");
                        }
                        if (CanUpdate) {
                            UpdateScript(idScript);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            $("input[name='idScript']").val(idScript);
            getCedentesUpdate(Script.id_cedente);
            $(".selectpicker").selectpicker("refresh");
            $("#id_cedente option[value='" + Script.id_cedente + "']").prop("selected", true);
            $(".selectpicker").selectpicker("refresh");
            $('#script').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
            $('.modal-dialog').addClass('modal-lg')
            $('#script').summernote('code', Script.script);
        }, 200);
    });
    function UpdateScript(idScript) {
        var Script = $("#script").summernote('code');
        var Cedente = $('#id_cedente').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/updateScript.php",
            dataType: "html",
            data: {
                Script: Script,
                Cedente: Cedente,
                idScript: idScript
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
                        getScriptTableList(false);
                        updateScriptTableList();
                    }
                }
            }
        });
    }
    function getScript(idScript) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getScript.php",
            dataType: "html",
            data: {
                idScript: idScript
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
    $("body").on("click", ".DeleteScript", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idScript = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el script seleccionado?",
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
                    deleteScript(idScript);
                }
            }
        });
    });
    function deleteScript(idScript) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/deleteScript.php",
            dataType: "html",
            data: {
                idScript: idScript
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
                    getScriptTableList(false);
                    updateScriptTableList();
                }
            }
        });
    }
    function getScriptTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getScriptTableList.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    ScriptArray = JSON.parse(data);
                }
            }
        });
    }
    function updateScriptTableList() {
        ScripTable = $('#ScriptTable').DataTable({
            data: ScriptArray,
            destroy: true,
            columns: [
                { data: 'Nombre_Cedente' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg UpdateScript'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteScript'></i></div>";
                    }
                },
            ]
        });
        $('#ScriptTable').css('width', '100%');
    }
    function getCedentesCreate(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesCreate.php",
            async: false,
            dataType: 'json',
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    function getCedentesUpdate(Cedente, Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesUpdate.php",
            async: false,
            dataType: 'json',
            data: {
                Cedente: Cedente
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }

    //SCRIPT COMPLETO

    var ScriptCompletoArray = [];
    var ScriptCompletoTable;

    getScriptCompletoTableList();
    updateScriptCompletoTableList();

    $("#CrearScriptCompleto").click(function () {
        var Template = $("#ScriptTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE SCRIPT",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Script = $("#script").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanAdd = false;
                        if (Script != "") {
                            if (Cedente_Length > 0) {
                                CanAdd = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Script");
                        }
                        if (CanAdd) {
                            CrearScriptCompleto();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("input[name='idScript']").val('');
            getCedentesCreateScriptCompleto();
            $(".selectpicker").selectpicker("refresh");
            $('#script').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
        }, 200);
    });
    function CrearScriptCompleto() {
        var Script = $("#script").summernote('code');
        var Cedente = $('#id_cedente').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/CrearScriptCompleto.php",
            dataType: "html",
            data: {
                Script: Script,
                Cedente: Cedente
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
                        getScriptCompletoTableList(false);
                        updateScriptCompletoTableList();
                    }
                }
            }
        });
    }
    $("body").on("click", ".UpdateScriptCompleto", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idScript = ObjectDiv.attr("id");
        var Script = getScriptCompleto(idScript);
        var Template = $("#ScriptTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR SCRIPT",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Script = $("#script").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanUpdate = false;
                        if (Script != "") {
                            if (Cedente_Length > 0) {
                                CanUpdate = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Script");
                        }
                        if (CanUpdate) {
                            UpdateScriptCompleto(idScript);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            $("input[name='idScript']").val(idScript);
            getCedentesUpdateScriptCompleto(Script.id_cedente);
            $(".selectpicker").selectpicker("refresh");
            $("#id_cedente option[value='" + Script.id_cedente + "']").prop("selected", true);
            $(".selectpicker").selectpicker("refresh");
            $('#script').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
            $('.modal-dialog').addClass('modal-lg')
            $('#script').summernote('code', Script.script);
        }, 200);
    });
    function UpdateScriptCompleto(idScript) {
        var Script = $("#script").summernote('code');
        var Cedente = $('#id_cedente').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/updateScriptCompleto.php",
            dataType: "html",
            data: {
                Script: Script,
                Cedente: Cedente,
                idScript: idScript
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
                        getScriptCompletoTableList(false);
                        updateScriptCompletoTableList();
                    }
                }
            }
        });
    }
    function getScriptCompleto(idScript) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getScriptCompleto.php",
            dataType: "html",
            data: {
                idScript: idScript
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
    $("body").on("click", ".DeleteScriptCompleto", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idScript = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el script seleccionado?",
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
                    deleteScriptCompleto(idScript);
                }
            }
        });
    });
    function deleteScriptCompleto(idScript) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/deleteScriptCompleto.php",
            dataType: "html",
            data: {
                idScript: idScript
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
                    getScriptCompletoTableList(false);
                    updateScriptCompletoTableList();
                }
            }
        });
    }
    function getScriptCompletoTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getScriptCompletoTableList.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    ScriptCompletoArray = JSON.parse(data);
                }
            }
        });
    }
    function updateScriptCompletoTableList() {
        ScripTable = $('#ScriptCompletoTable').DataTable({
            data: ScriptCompletoArray,
            destroy: true,
            columns: [
                { data: 'Nombre_Cedente' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg UpdateScriptCompleto'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteScriptCompleto'></i></div>";
                    }
                },
            ]
        });
        $('#ScriptCompletoTable').css('width', '100%');
    }
    function getCedentesCreateScriptCompleto(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesCreateScriptCompleto.php",
            async: false,
            dataType: 'json',
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    function getCedentesUpdateScriptCompleto(Cedente, Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesUpdateScriptCompleto.php",
            async: false,
            dataType: 'json',
            data: {
                Cedente: Cedente
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }

    //POLITICAS

    var PoliticaArray = [];
    var PoliticaTable;

    getPoliticaTableList();
    updatePoliticaTableList();

    $("#CrearPolitica").click(function () {
        var Template = $("#PoliticaTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE POLITICA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Politica = $("#politica").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanAdd = false;
                        if (Politica != "") {
                            if (Cedente_Length > 0) {
                                CanAdd = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar una Politica");
                        }
                        if (CanAdd) {
                            CrearPolitica();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("input[name='id']").val('');
            getCedentesCreatePolitica();
            $(".selectpicker").selectpicker("refresh");
            $('#politica').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
        }, 200);
    });
    function CrearPolitica() {
        var Politica = $("#politica").summernote('code');
        var Cedente = $('#id_cedente').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/CrearPolitica.php",
            dataType: "html",
            data: {
                Politica: Politica,
                Cedente: Cedente
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
                        getPoliticaTableList(false);
                        updatePoliticaTableList();
                    }
                }
            }
        });
    }
    $("body").on("click", ".UpdatePolitica", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        var Politica = getPolitica(id);
        var Template = $("#PoliticaTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR POLITICA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Politica = $("#politica").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanUpdate = false;
                        if (Politica != "") {
                            if (Cedente_Length > 0) {
                                CanUpdate = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar una Politica");
                        }
                        if (CanUpdate) {
                            UpdatePolitica(id);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            $("input[name='id']").val(id);
            getCedentesUpdatePolitica(Politica.id_cedente);
            $(".selectpicker").selectpicker("refresh");
            $("#id_cedente option[value='" + Politica.id_cedente + "']").prop("selected", true);
            $(".selectpicker").selectpicker("refresh");
            $('#politica').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
            $('.modal-dialog').addClass('modal-lg')
            $('#politica').summernote('code', Politica.politica);
        }, 200);
    });
    function UpdatePolitica(id) {
        var Politica = $("#politica").summernote('code');
        var Cedente = $('#id_cedente').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/updatePolitica.php",
            dataType: "html",
            data: {
                Politica: Politica,
                Cedente: Cedente,
                id: id
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
                        getPoliticaTableList(false);
                        updatePoliticaTableList();
                    }
                }
            }
        });
    }
    function getPolitica(id) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getPolitica.php",
            dataType: "html",
            data: {
                id: id
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
    $("body").on("click", ".DeletePolitica", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar la politica seleccionada?",
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
                    deletePolitica(id);
                }
            }
        });
    });
    function deletePolitica(id) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/deletePolitica.php",
            dataType: "html",
            data: {
                id: id
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
                    getPoliticaTableList(false);
                    updatePoliticaTableList();
                }
            }
        });
    }
    function getPoliticaTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getPoliticaTableList.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    PoliticaArray = JSON.parse(data);
                }
            }
        });
    }
    function updatePoliticaTableList() {
        PoliticaTable = $('#PoliticaTable').DataTable({
            data: PoliticaArray,
            destroy: true,
            columns: [
                { data: 'Nombre_Cedente' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg UpdatePolitica'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeletePolitica'></i></div>";
                    }
                },
            ]
        });
        $('#PoliticaTable').css('width', '100%');
    }
    function getCedentesCreatePolitica(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesCreatePolitica.php",
            async: false,
            dataType: 'json',
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    function getCedentesUpdatePolitica(Cedente, Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesUpdatePolitica.php",
            async: false,
            dataType: 'json',
            data: {
                Cedente: Cedente
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    
    //MEDIOS DE PAGO

    var MedioPagoArray = [];
    var MedioPagoTable;

    getMedioPagoTableList();
    updateMedioPagoTableList();

    $("#CrearMedioPago").click(function () {
        var Template = $("#MedioPagoTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE MEDIO DE PAGO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var MedioPago = $("#medio_pago").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanAdd = false;
                        if (MedioPago != "") {
                            if (Cedente_Length > 0) {
                                CanAdd = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Medio de Pago");
                        }
                        if (CanAdd) {
                            CrearMedioPago();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("input[name='id']").val('');
            getCedentesCreateMedioPago();
            $(".selectpicker").selectpicker("refresh");
            $('#medio_pago').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
        }, 200);
    });
    function CrearMedioPago() {
        var MedioPago = $("#medio_pago").summernote('code');
        var Cedente = $('#id_cedente').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/CrearMedioPago.php",
            dataType: "html",
            data: {
                MedioPago: MedioPago,
                Cedente: Cedente
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
                        getMedioPagoTableList(false);
                        updateMedioPagoTableList();
                    }
                }
            }
        });
    }
    $("body").on("click", ".UpdateMedioPago", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        var MedioPago = getMedioPago(id);
        var Template = $("#MedioPagoTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR MEDIO DE PAGO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var MedioPago = $("#medio_pago").summernote('code');
                        var Cedente = $('#id_cedente').val();
                        var Cedente_Length = $("#id_cedente option:selected").length;
                        var CanUpdate = false;
                        if (MedioPago != "") {
                            if (Cedente_Length > 0) {
                                CanUpdate = true;
                            } else {
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Medio de Pago");
                        }
                        if (CanUpdate) {
                            UpdateMedioPago(id);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        setTimeout(() => {
            $("input[name='id']").val(id);
            getCedentesUpdateMedioPago(MedioPago.id_cedente);
            $(".selectpicker").selectpicker("refresh");
            $("#id_cedente option[value='" + MedioPago.id_cedente + "']").prop("selected", true);
            $(".selectpicker").selectpicker("refresh");
            $('#medio_pago').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                ],
            });
            $('.modal-dialog').addClass('modal-lg')
            $('#medio_pago').summernote('code', MedioPago.medio_pago);
        }, 200);
    });
    function UpdateMedioPago(id) {
        var MedioPago = $("#medio_pago").summernote('code');
        var Cedente = $('#id_cedente').val();

        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/updateMedioPago.php",
            dataType: "html",
            data: {
                MedioPago: MedioPago,
                Cedente: Cedente,
                id: id
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
                        getMedioPagoTableList(false);
                        updateMedioPagoTableList();
                    }
                }
            }
        });
    }
    function getMedioPago(id) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getMedioPago.php",
            dataType: "html",
            data: {
                id: id
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
    $("body").on("click", ".DeleteMedioPago", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el medio de pago seleccionado?",
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
                    deleteMedioPago(id);
                }
            }
        });
    });
    function deleteMedioPago(id) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/deleteMedioPago.php",
            dataType: "html",
            data: {
                id: id
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
                    getMedioPagoTableList(false);
                    updateMedioPagoTableList();
                }
            }
        });
    }
    function getMedioPagoTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getMedioPagoTableList.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    MedioPagoArray = JSON.parse(data);
                }
            }
        });
    }
    function updateMedioPagoTableList() {
        MedioPagoTable = $('#MedioPagoTable').DataTable({
            data: MedioPagoArray,
            destroy: true,
            columns: [
                { data: 'Nombre_Cedente' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg UpdateMedioPago'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteMedioPago'></i></div>";
                    }
                },
            ]
        });
        $('#MedioPagoTable').css('width', '100%');
    }
    function getCedentesCreateMedioPago(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesCreateMedioPago.php",
            async: false,
            dataType: 'json',
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    function getCedentesUpdateMedioPago(Cedente, Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_script/getCedentesUpdateMedioPago.php",
            async: false,
            dataType: 'json',
            data: {
                Cedente: Cedente
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#id_cedente').append($('<option>', {
                        value: array.idCedente,
                        text: array.NombreCedente
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
});
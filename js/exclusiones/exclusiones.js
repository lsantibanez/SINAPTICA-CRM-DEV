$(document).ready(function () {
    setTimeout(function () {
        getExclusiones();
    }, 100);

    function getExclusiones() {
        $.ajax({
            type: "POST",
            url: "../includes/exclusiones/getExclusiones.php",
            dataType: "json",
            async: false,
            success: function (data) {
                ExclusionesTable = $('#ExclusionesTable').DataTable({
                    data: data,
                    destroy: true,
                    columns: [
                        { data: 'Tipo' },
                        { data: 'Dato' },
                        { data: 'Fecha_Inic' },
                        { data: 'Fecha_Term' },
                        { data: 'Descripcio' },
                        { data: 'id_registr' }
                    ],
                    "columnDefs": [
                        {
                            "targets": 5,
                            "data": 'Accion',
                            "render": function (data, type, row) {
                                return "<div style='text-align: center;' id='" + data + "'><i style = 'margin: 0px 1.5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateExclusion'></i><i style = 'margin: 0px 1.5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg deleteExclusion'></i></div>";
                            }
                        }
                    ]
                });
            }
        });
    }

    $("body").on("click", "#AddExclusion", function () {
        var Template = $("#ExclusionTemplate").html();
        bootbox.dialog({
            title: "NUEVA EXCLUSIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var Fecha_Inic = $("#Fecha_Inic").val();
                        var Fecha_Term = $("#Fecha_Term").val();
                        CanSave = false;
                        isValid = validarTipo()
                        if (isValid) {
                            if (Fecha_Inic != "") {
                                if (Fecha_Term != "") {
                                    CanSave = true;
                                } else {
                                    bootbox.alert("Debe ingresar una fecha de termino valida", function () { AddClassModalOpen(); });
                                }
                            } else {
                                bootbox.alert("Debe ingresar una fecha de inicio valida", function () { AddClassModalOpen(); });
                            }
                        } else {
                            bootbox.alert(ExclusionMessage, function () { AddClassModalOpen(); });
                        }
                        if (CanSave) {
                            storeExclusion();
                            AddClassModalOpen();
                        } else {
                            return false;
                        }
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
        $('.input-daterange').datepicker({
            format: "yyyy/mm/dd",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es',
            startDate: new Date()
        });
        $('.selectpicker').selectpicker('refresh')
    });
    function validarTipo(){
        var Tipo = $("#Tipo").val();
        var Dato = $("#Dato").val();
        switch (Tipo) {
            case "1":
                if (!isNaN(Dato) && Dato) {
                    isValid = true;
                } else {
                    isValid = false;
                    ExclusionMessage = 'Ingrese un Rut valido (solo digitos)'
                }
                break
            case "2":
                if (!isNaN(Dato) && Dato.length == 9) {
                    isValid = true;
                } else {
                    isValid = false;
                    ExclusionMessage = 'Ingrese un telefono valido (9 digitos)'
                }
                break
            case "3":
                var emailRegex = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
                if (emailRegex.test(Dato) && Dato) {
                    isValid = true;
                } else {
                    isValid = false;
                    ExclusionMessage = 'Ingrese un correo valido'
                }
                break
        }
        return isValid
    }
    $("body").on("change", "#isInhibicion", function () {
        var isInhibicion = $(this).is(':checked');
        if(isInhibicion){
            $('#Fecha_Term').val('2999/01/31');
            $('.Fecha_Term').hide();
        }else{
            $('#Fecha_Term').val('');
            $('.Fecha_Term').show();
        }
    });
    function storeExclusion() {
        var Tipo = $("#Tipo").val();
        var Dato = $("#Dato").val();
        var Fecha_Inic = $("#Fecha_Inic").val();
        var Fecha_Term = $("#Fecha_Term").val();
        var Descripcio = $("#Descripcio").val();
        var isInhibicion = $("#isInhibicion").is(':checked');
        if (isInhibicion) {
            isInhibicion = 1
        } else {
            isInhibicion = 0;
        }
        $.ajax({
            type: "POST",
            url: "../includes/exclusiones/storeExclusion.php",
            dataType: "json",
            async: false,
            data: {
                Tipo,
                Dato,
                Fecha_Inic,
                Fecha_Term,
                Descripcio,
                isInhibicion
            },
            success: function (data) {
                getExclusiones()
            }
        });
    }
    $("body").on("click", ".updateExclusion", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var Template = $("#ExclusionTemplate").html();
        bootbox.dialog({
            title: "EDICIÓN DE EXCLUSIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Actualizar",
                    className: "btn-purple",
                    callback: function () {
                        var Fecha_Inic = $("#Fecha_Inic").val();
                        var Fecha_Term = $("#Fecha_Term").val();
                        CanUpdate = false;
                        isValid = validarTipo()
                        if (isValid) {
                            if (Fecha_Inic != "") {
                                if (Fecha_Term != "") {
                                    CanUpdate = true;
                                } else {
                                    bootbox.alert("Debe ingresar una fecha de termino valida", function () { AddClassModalOpen(); });
                                }
                            } else {
                                bootbox.alert("Debe ingresar una fecha de inicio valida", function () { AddClassModalOpen(); });
                            }
                        } else {
                            bootbox.alert(ExclusionMessage, function () { AddClassModalOpen(); });
                        }
                        if (CanUpdate) {
                            updateExclusion();
                            AddClassModalOpen();
                        } else {
                            return false;
                        }
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
        $('.input-daterange').datepicker({
            format: "yyyy/mm/dd",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es',
            startDate: new Date()
        });
        getExclusion(ID);
    });
    function getExclusion(id_registr) {
        $.ajax({
            type: "POST",
            url: "../includes/exclusiones/getExclusion.php",
            dataType: "json",
            data: {
                id_registr
            },
            success: function (data) {
                $("#Tipo").val(data.Tipo);
                $("#Dato").val(data.Dato);
                $('#Fecha_Inic').val(data.Fecha_Inic)
                $('#Fecha_Term').val(data.Fecha_Term)
                if (data.Fecha_Term == '2999/01/31'){
                    $('.Fecha_Term').hide();
                    $('#isInhibicion').prop('checked',true)
                } else {
                    $('.Fecha_Term').show();
                }
                $('#Descripcio').val(data.Descripcio)
                $(".selectpicker").selectpicker("refresh");
                $('#id_registr').val(id_registr)
            }
        });
    }
    function updateExclusion() {
        var Tipo = $("#Tipo").val();
        var Dato = $("#Dato").val();
        var Fecha_Inic = $("#Fecha_Inic").val();
        var Fecha_Term = $("#Fecha_Term").val();
        var Descripcio = $("#Descripcio").val();
        var isInhibicion = $("#isInhibicion").is(':checked');
        if (isInhibicion) {
            isInhibicion = 1
        } else {
            isInhibicion = 0;
        }
        var id_registr = $("#id_registr").val();
        $.ajax({
            type: "POST",
            url: "../includes/exclusiones/updateExclusion.php",
            dataType: "json",
            async: false,
            data: {
                Tipo,
                Dato,
                Fecha_Inic,
                Fecha_Term,
                Descripcio,
                isInhibicion,
                id_registr
            },
            success: function (data) {
                getExclusiones()
            }
        });
    }
    $("body").on("click", ".deleteExclusion", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Desea eliminar el registro seleccionado?",
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
                    deleteExclusion(ID);
                }
                AddClassModalOpen();
            }
        });
    });
    function deleteExclusion(id_registr) {
        $.ajax({
            type: "POST",
            url: "../includes/exclusiones/deleteExclusion.php",
            dataType: "html",
            async: false,
            data: {
                id_registr
            },
            success: function (data) {
                getExclusiones()
                AddClassModalOpen();
            }
        });
    }
    function AddClassModalOpen() {
        setTimeout(function () {
            if (!$("body").hasClass("modal-open")) {
                $("body").addClass("modal-open");
            }
        }, 500);
    }
});
$(document).ready(function () {
    var NotificacionArray = [];
    var NotificacionTable;
    var Prioridad

    getNotificacionTableList();
    updateNotificacionTableList();

    $("#CrearNotificacion").click(function () {
        var Template = $("#NotificacionTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE NOTIFICACIÓN",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Cantidad = $("#cantidad_horas").val();
                        var TipoNotificacion = $('#tipo_notificacion').val();
                        var TipoNotificacion_Length = $("#tipo_notificacion option:selected").length;
                        var CanAdd = false;
                        if (Cantidad != "") {
                            if (TipoNotificacion_Length > 0) {
                                ComprobarPrioridadNotificacionesCreate(Cantidad,TipoNotificacion);
                                if (Prioridad){
                                    CanAdd = true;
                                }
                            } else {
                                bootbox.alert("Debe seleccionar un Tipo");
                            }
                        } else {
                            bootbox.alert("Debe ingresar una Cantidad");
                        }
                        if (CanAdd) {
                            CrearNotificacion();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        setTimeout(() => {
            getTipoNotificacionesCreate();
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function CrearNotificacion() {
        var Cantidad = $("#cantidad_horas").val();
        var TipoNotificacion = $('#tipo_notificacion').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/CrearNotificacion.php",
            dataType: "html",
            data: {
                Cantidad: Cantidad,
                TipoNotificacion: TipoNotificacion
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
                        getNotificacionTableList(false);
                        updateNotificacionTableList();
                    }
                }
            }
        });
    }
    $(document).on("click", ".Update", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idNotificacion = ObjectDiv.attr("id");
        var Notificacion = getNotificacion(idNotificacion);
        var Template = $("#NotificacionTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR NOTIFICACIÓN",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Cantidad = $("#cantidad_horas").val();
                        var TipoNotificacion = $('#tipo_notificacion').val();
                        var TipoNotificacion_Length = $("#tipo_notificacion option:selected").length;
                        var CanUpdate = false;
                        if (Cantidad != "") {
                            if (TipoNotificacion_Length > 0) {
                                ComprobarPrioridadNotificacionesUpdate(Cantidad, TipoNotificacion, idNotificacion);
                                if (Prioridad) {
                                    CanUpdate = true;
                                }
                            } else {
                                bootbox.alert("Debe seleccionar un Tipo");
                            }
                        } else {
                            bootbox.alert("Debe ingresar una Cantidad");
                        }
                        if (CanUpdate) {
                            UpdateNotificacion(idNotificacion);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("input[name='idNotificacion']").val(idNotificacion);
            $("#cantidad_horas").val(Notificacion.cantidad_horas);
            getTipoNotificacionesUpdate(Notificacion.tipo_notificacion);
            $("#tipo_notificacion").val(Notificacion.tipo_notificacion);
            $(".selectpicker").selectpicker("refresh");
        }, 200);
    });
    function getNotificacion(idNotificacion) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/getNotificacion.php",
            dataType: "html",
            data: {
                idNotificacion: idNotificacion
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
    function getTipoNotificacionesUpdate(idNotificacion) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/getTipoNotificacionesUpdate.php",
            async: false,
            dataType: 'json',
            data:{
                idNotificacion: idNotificacion
            },
            success: function (data) {
                $.each(data, function (index, array) {
                    $('#tipo_notificacion').append($('<option>', {
                        value: array.id,
                        text: array.nombre
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
    function ComprobarPrioridadNotificacionesUpdate(Cantidad, TipoNotificacion, idNotificacion) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/ComprobarPrioridadNotificacionesUpdate.php",
            async: false,
            data: {
                Cantidad: Cantidad,
                TipoNotificacion: TipoNotificacion,
                idNotificacion: idNotificacion
            },
            success: function (json) {
                data = JSON.parse(json)
                if (!data.result) {
                    bootbox.alert(data.message);
                }
                Prioridad = data.result;
                return data.result;
            }
        });
    }
    function UpdateNotificacion(idNotificacion) {
        var Cantidad = $("#cantidad_horas").val();
        var TipoNotificacion = $('#tipo_notificacion').val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/updateNotificacion.php",
            dataType: "html",
            data: {
                Cantidad: Cantidad,
                TipoNotificacion: TipoNotificacion,
                idNotificacion: idNotificacion
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
                        getNotificacionTableList(false);
                        updateNotificacionTableList();
                    }
                }
            }
        });
    }
    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idNotificacion = ObjectDiv.attr("id");

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
                    deleteNotificacion(idNotificacion);
                }
            }
        });
    });
    function deleteNotificacion(idNotificacion) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/deleteNotificacion.php",
            dataType: "html",
            data: {
                idNotificacion: idNotificacion
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
                    getNotificacionTableList(false);
                    updateNotificacionTableList();
                }
            }
        });
    }
    function ComprobarPrioridadNotificacionesCreate(Cantidad,TipoNotificacion) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/ComprobarPrioridadNotificacionesCreate.php",
            async: false,
            data:{
                Cantidad: Cantidad,
                TipoNotificacion: TipoNotificacion
            },
            success: function (json) {
                data = JSON.parse(json)
                if(!data.result){
                    bootbox.alert(data.message);
                }
                Prioridad = data.result;
                return data.result;
            }
        });
    }
    function getNotificacionTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/getNotificacionTableList.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                NotificacionArray = [];
            },
            success: function (data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    NotificacionArray = JSON.parse(data);
                }
            }
        });
    }
    function updateNotificacionTableList() {
        NotificacionTable = $('#NotificacionTable').DataTable({
            data: NotificacionArray,
            "bDestroy": true,
            columns: [
                { data: 'TipoNotificacion' },
                { data: 'Cantidad' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "searchable": false,
                    "data": "Accion",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg Update'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg Delete'></i></div>";
                    }
                },
            ]
        });
    }
    function getTipoNotificacionesCreate(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_notificacion/getTipoNotificacionesCreate.php",
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
                    $('#tipo_notificacion').append($('<option>', {
                        value: array.id,
                        text: array.nombre
                    }));
                });
                $(".selectpicker").selectpicker("refresh");
                $('#Cargando').modal('hide');
            }
        });
    }
});
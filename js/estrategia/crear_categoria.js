$(document).ready(function () {
    var ColorArray = [];
    var ColorTable;

    getColorTableList();
    updateColorTableList();

    function getColorTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_categoria/getColorTableList.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                ColorArray = [];
            },
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    ColorArray = JSON.parse(data);
                }
            }
        });
    }
    function updateColorTableList() {
        ColorTable = $('#ColorTable').DataTable({
            data: ColorArray,
            "bDestroy": true,
            columns: [
                { data: 'color' },
                { data: 'nombre' },
                { data: 'comentario' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "searchable": false,
                    "data": "color",
                    "render": function (data, type, row) {
                        return "<input type='text' style='background:"+data+"; width: 30px;'/>";
                    }
                },
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "id",
                    "render": function (data, type, row) {
                        return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg Update'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg Delete'></i></div>";
                    }
                },
            ]
        });
    }

    $("#CrearColor").click(function () {
        var Template = $("#ColorTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE COLOR",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var color = $('#color').val();
                        var nombre = $('#nombre').val();
                        var comentario = $('#comentario').val();
                        var CanAdd = false;
                        if (nombre != "") {
                            CanAdd = true;
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanAdd) {
                            CrearColor();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
    });
    function CrearColor() {
        var color = $('#color').val();
        var nombre = $('#nombre').val();
        var comentario = $('#comentario').val();
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_categoria/crearColor.php",
            dataType: "html",
            data: {
                color: color,
                nombre: nombre,
                comentario: comentario
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
                getColorTableList(false);
                updateColorTableList();
            }
        });
    } $("body").on("click", ".Update", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        var Color = getColor(id);
        var Template = $("#ColorTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR COLOR",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var color = $('#color').val();
                        var nombre = $('#nombre').val();
                        var comentario = $('#comentario').val();
                        var CanUpdate = false;
                        if (nombre != "") {
                            CanUpdate = true;
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanUpdate) {
                            UpdateColor(id);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        setTimeout(() => {
            $("#id").val(id);
            $('#color').val(Color.color);
            $('#nombre').val(Color.nombre);
            $('#comentario').val(Color.comentario);
        }, 200);
    });
    function UpdateColor(idScript) {
        var color = $('#color').val();
        var nombre = $('#nombre').val();
        var comentario = $('#comentario').val();
        var id = $('#id').val();

        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_categoria/updateColor.php",
            dataType: "html",
            data: {
                color: color,
                nombre: nombre,
                comentario: comentario,
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
                getColorTableList(false);
                updateColorTableList();
            }
        });
    }
    function getColor(id) {
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_categoria/getColor.php",
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
    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el color seleccionado?",
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
                    deleteColor(id);
                }
            }
        });
    });
    function deleteColor(id) {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_categoria/deleteColor.php",
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
                getColorTableList(false);
                updateColorTableList();
            }
        });
    }
});
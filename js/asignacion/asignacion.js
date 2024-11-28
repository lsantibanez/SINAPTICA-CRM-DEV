$(document).ready(function ($) {

    listEmpresas();
    listGrupos();

    //EMPRESAS EXTERNAS

    function listEmpresas() {
        $('#listEmpresas').load('../includes/asignacion/listEmpresas.php', function () {
            $('#TableEmpresas').dataTable({
                "columnDefs": [{
                    'orderable': false,
                    'targets': [3]
                },],
                "order": [
                    [0, "desc"]
                ]
            });
        });
    }

    $(document).on('click', '#CrearEmpresa', function () {
        var Template = $("#CrearEmpresaTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE EMPRESA EXTERNA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Nombre = $("#nombre").val();
                        var Telefono = $("#telefono").val();
                        var Correo = $("#correo").val();
                        var CanAdd = false;
                        if (Nombre) {
                            if (Telefono) {
                                if (Correo) {
                                    CanAdd = true;
                                } else {
                                    bootbox.alert("Debe ingresar un Correo");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Teléfono");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanAdd) {
                            CrearEmpresa();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
    });

    function CrearEmpresa() {
        var nombre = $("#nombre").val();
        var telefono = $("#telefono").val();
        var correo = $("#correo").val();
        var direccion = $("#direccion").val();

        $.ajax({
            type: "POST",
            url: '../includes/asignacion/insertEmpresa.php',
            dataType: "html",
            data: {
                nombre: nombre,
                telefono: telefono,
                correo: correo,
                direccion: direccion
            },
            async: false,
            success: function (data) {
                listEmpresas();
            }
        });
    }

    $(document).on("click", ".EditEmpresa", function () {
        var id = $(this).attr('id')
        var Template = $("#UpdateEmpresaTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR EMPRESA EXTERNA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Nombre = $("#nombre").val();
                        var Telefono = $("#telefono").val();
                        var Correo = $("#correo").val();
                        var CanUpdate = false;
                        if (Nombre) {
                            if (Telefono) {
                                if (Correo) {
                                    CanUpdate = true;
                                } else {
                                    bootbox.alert("Debe ingresar un Correo");
                                }
                            } else {
                                bootbox.alert("Debe ingresar un Teléfono");
                            }
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanUpdate) {
                            UpdateEmpresa();
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $.post('../includes/asignacion/editEmpresa.php', { id: id }, function (data) {
            value = $.parseJSON(data);
            $('[name="nombre"]').val(value[0].Nombre);
            $('[name="telefono"]').val(value[0].Telefono);
            $('[name="correo"]').val(value[0].Correo);
            $('[name="direccion"]').val(value[0].Direccion);
            $('#IdEmpresaExterna').val(value[0].IdEmpresaExterna);
        });
    });

    function UpdateEmpresa() {
        var nombre = $("#nombre").val();
        var telefono = $("#telefono").val();
        var correo = $("#correo").val();
        var direccion = $("#direccion").val();
        var id = $("#IdEmpresaExterna").val();

        $.ajax({
            type: "POST",
            url: '../includes/asignacion/updateEmpresa.php',
            dataType: "html",
            data: {
                nombre: nombre,
                telefono: telefono,
                correo: correo,
                direccion: direccion,
                id: id
            },
            async: false,
            success: function (data) {
                listEmpresas();
            }
        });
    }
    $(document).on("click", ".DeleteEmpresa", function () {
        var id = $(this).attr('id')

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
                    deleteEmpresa(id);
                }
            }
        });
    });
    function deleteEmpresa(id) {
        $.ajax({
            type: "POST",
            url: "../includes/asignacion/deleteEmpresa.php",
            dataType: "html",
            data: {
                id: id
            },
            async: false,
            success: function (data) {
                listEmpresas();
            }
        });
    }

    //GRUPOS

    function listGrupos() {
        $('#listGrupos').load('../includes/asignacion/listGrupos.php', function () {
            $('#TableGrupos').dataTable({
                "columnDefs": [{
                    'orderable': false,
                    'targets': [1]
                },],
                "order": [
                    [0, "desc"]
                ]
            });
        });
    }
    $(document).on('click', '#CrearGrupo', function () {
        var Template = $("#CrearGrupoTemplate").html()
        bootbox.dialog({
            title: "CREACIÓN DE GRUPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function () {
                        var Nombre = $("#nombre").val();
                        var CanAdd = false;
                        if (Nombre) {
                            CanAdd = true;
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanAdd) {
                            CrearGrupo();
                        } else {
                            return false;
                        }
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
    });

    function CrearGrupo() {
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();

        $.ajax({
            type: "POST",
            url: '../includes/asignacion/insertGrupo.php',
            dataType: "html",
            data: {
                nombre: nombre,
                descripcion: descripcion
            },
            async: false,
            success: function (data) {
                listGrupos();
            }
        });
    }

    $(document).on("click", ".EditGrupo", function () {
        var id = $(this).attr('id')
        var Template = $("#UpdateGrupoTemplate").html();

        bootbox.dialog({
            title: "MODIFICAR GRUPO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function () {
                        var Nombre = $("#nombre").val();
                        var CanUpdate = false;
                        if (Nombre) {
                            CanUpdate = true;
                        } else {
                            bootbox.alert("Debe ingresar un Nombre");
                        }
                        if (CanUpdate) {
                            UpdateGrupo();
                        } else {
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $.post('../includes/asignacion/editGrupo.php', { id: id }, function (data) {
            value = $.parseJSON(data);
            $('[name="nombre"]').val(value[0].Nombre);
            $('[name="descripcion"]').val(value[0].Descripcion);
            $('#IdGrupo').val(value[0].IdGrupo);
        });
    });

    function UpdateGrupo() {
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var id = $("#IdGrupo").val();

        $.ajax({
            type: "POST",
            url: '../includes/asignacion/updateGrupo.php',
            dataType: "html",
            data: {
                nombre: nombre,
                descripcion: descripcion,
                id: id
            },
            async: false,
            success: function (data) {
                listGrupos();
            }
        });
    }
    $(document).on("click", ".DeleteGrupo", function () {
        var id = $(this).attr('id')

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
                    deleteGrupo(id);
                }
            }
        });
    });
    function deleteGrupo(id) {
        $.ajax({
            type: "POST",
            url: "../includes/asignacion/deleteGrupo.php",
            dataType: "html",
            data: {
                id: id
            },
            async: false,
            success: function (data) {
                listGrupos();
            }
        });
    }
});
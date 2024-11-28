$(document).ready(function() {
    var AcuerdosTable = null;
    var ReprogramacionesTable;

    $("select[name='TipoDerivacion']").change(function() {
        var TipoDerivacion = $(this).val();
        $("#panelReporgramaciones").hide();
        $("#panelAcuerdos").hide();
        $("#panelCesantia").hide();
        $("#panelReclamo").hide();
        switch (TipoDerivacion) {
            case "repro":
                $("#reprogramacionesDiarias").hide();
                $("#reprogramacionesMensuales").hide();
                $("#panelReporgramaciones").show();
                break;
            case "acuerdo":
                $("#acuerdosDiarias").hide();
                $("#acuerdosMensuales").hide();
                $("#panelAcuerdos").show();
                break;
            case "cesantia":
                $("#activoCesantia").hide();
                $("#activaraCesantia").hide();
                $("#panelCesantia").show();
                break;
            case "reclamo":
                $("#activoReclamo").hide();
                $("#panelReclamo").show();
                break;
        }
    });
    $("#buscarRepro").click(function() {
        var TipoReprogramacion = $("select[name='TipoReprogramacion']").val();
        var fecha = $("input[name='fechaRepro']").val();
        if (TipoReprogramacion != "") {
            if (fecha != "") {
                $("#reprogramacionesDiarias").hide();
                $("#reprogramacionesMensuales").hide();
                switch (TipoReprogramacion) {
                    case "diario":
                        $("#reprogramacionesDiarias").show();
                        break;
                    case "mensual":
                        $("#reprogramacionesMensuales").show();
                        break;
                }
                getReprogramaciones(TipoReprogramacion, fecha);
            } else {
                bootbox.alert("Debe ingresar una fecha.");
            }
        } else {
            bootbox.alert("Debe seleccionar un tipo de Reprogramacion");
        }
    });
    $("#downloadRepros").click(function() {
        var TipoReprogramacion = $("select[name='TipoReprogramacion']").val();
        var fecha = $("input[name='fechaRepro']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadRepros.php?idMandante=" + idMandante + "&fecha=" + fecha + "&tipoRepro=" + TipoReprogramacion;
    });
    $("#downloadAcuerdos").click(function() {
        var TipoAcuerdos = $("select[name='TipoAcuerdos']").val();
        var fecha = $("input[name='fechaAcuerdos']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadAcuerdos.php?idMandante=" + idMandante + "&fecha=" + fecha + "&tipoAcuerdo=" + TipoAcuerdos;
    });
    $("#buscarAcuerdos").click(function() {
        var TipoAcuerdos = $("select[name='TipoAcuerdos']").val();
        var fecha = $("input[name='fechaAcuerdos']").val();
        if (TipoAcuerdos != "") {
            if (fecha != "") {
                $("#acuerdosDiarias").hide();
                $("#acuerdosMensuales").hide();
                switch (TipoAcuerdos) {
                    case "diario":
                        $("#acuerdosDiarios").show();
                        break;
                    case "mensual":
                        $("#acuerdosMensuales").show();
                        break;
                }
                getAcuerdosCastigo(TipoAcuerdos, fecha);
            } else {
                bootbox.alert("Debe ingresar una fecha.");
            }
        } else {
            bootbox.alert("Debe seleccionar un tipo de Acuerdo");
        }
    });
    $("#sendRepros").click(function() {
        var TipoReprogramacion = $("select[name='TipoReprogramacion']").val();
        var fecha = $("input[name='fechaRepro']").val();
        bootbox.confirm({
            message: "<div style='font-size: 20px;'>¿Confirma que desea enviar los correos electrónicos hacia las sucursales corresponientes?</div>",
            size: 'small',
            buttons: {
                confirm: {
                    label: 'SI',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'NO',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    sendReprogramaciones(TipoReprogramacion, fecha);
                }
            }
        });
    });
    $("#sendAcuerdos").click(function() {
        var TipoAcuerdos = $("select[name='TipoAcuerdos']").val();
        var fecha = $("input[name='fechaAcuerdos']").val();
        bootbox.confirm({
            message: "<div style='font-size: 20px;'>¿Confirma que desea enviar los correos electrónicos hacia las sucursales corresponientes?</div>",
            size: 'small',
            buttons: {
                confirm: {
                    label: 'SI',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'NO',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    sendAcuerdos(TipoAcuerdos, fecha);
                }
            }
        });
    });
    $("#buscarCesantia").click(function() {
        var TipoCesantia = $("select[name='TipoCesantia']").val();
        var fechaStart = $("input[name='fechaCesantiaStart']").val();
        var fechaEnd = $("input[name='fechaCesantiaEnd']").val();
        if (TipoCesantia != "") {
            if (fechaStart != "") {
                if (fechaEnd != "") {
                    $("#activoCesantia").hide();
                    $("#activaraCesantia").hide();
                    switch (TipoCesantia) {
                        case "activo":
                            $("#activoCesantia").show();
                            break;
                        case "activara":
                            $("#activaraCesantia").show();
                            break;
                    }
                    getCesantia(TipoCesantia, fechaStart, fechaEnd);
                } else {
                    bootbox.alert("Debe ingresar una fecha Hasta.");
                }
            } else {
                bootbox.alert("Debe ingresar una fecha Desde.");
            }
        } else {
            bootbox.alert("Debe seleccionar un tipo de Cesantia");
        }
    });
    $(".downloadCesantias").click(function() {
        var TipoCesantia = $("select[name='TipoCesantia']").val();
        var fechaStart = $("input[name='fechaCesantiaStart']").val();
        var fechaEnd = $("input[name='fechaCesantiaEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadCesantias.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd + "&tipoCesantia=" + TipoCesantia;
    });
    $("#buscarReclamo").click(function() {
        var fechaStart = $("input[name='fechaReclamoStart']").val();
        var fechaEnd = $("input[name='fechaReclamoEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#showReclamos").show();
                getReclamo(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadReclamos").click(function() {
        var fechaStart = $("input[name='fechaReclamoStart']").val();
        var fechaEnd = $("input[name='fechaReclamoEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadReclamos.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });

    function getReprogramaciones(TipoReprogramacion, fecha) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getReprogramaciones.php",
            data: {
                TipoReprogramacion: TipoReprogramacion,
                idMandante: GlobalData.id_mandante,
                fecha: fecha
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    var ToReturn = JSON.parse(data);
                    console.log(ToReturn);
                    showReprogramaciones(ToReturn.Fields, ToReturn.Reprogramaciones, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }

    function showReprogramaciones(Fields, Reprogramaciones, Table) {
        $('#' + Table).DataTable({
            data: Reprogramaciones,
            bDestroy: true,
            columns: Fields
        });
    }

    function sendReprogramaciones(TipoReprogramacion, fecha) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/sendRepros.php",
            data: {
                TipoReprogramacion: TipoReprogramacion,
                idMandante: GlobalData.id_mandante,
                fecha: fecha
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                console.log(data);
            },
            error: function(data) {}
        });
    }

    function getAcuerdosCastigo(TipoAcuerdo, fecha) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/geAcuerdosCastigo.php",
            data: {
                TipoAcuerdo: TipoAcuerdo,
                idMandante: GlobalData.id_mandante,
                fecha: fecha
            },
            beforeSend: function() {
                if (AcuerdosTable != null) {
                    AcuerdosTable.destroy();
                }
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                console.log(data);
                if (isJson(data)) {
                    var ToReturn = JSON.parse(data);
                    console.log(ToReturn);
                    console.log(ToReturn.Acuerdos.ResultanteAbono);
                    showAcuerdosCastigos(ToReturn.Fields, ToReturn.Acuerdos, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }

    function showAcuerdosCastigos(Fields, Acuerdos, Table) {
        AcuerdosTable = $('#' + Table).DataTable({
            data: Acuerdos,
            bDestroy: true,
            columns: Fields
        });
    }

    function sendAcuerdos(TipoAcuerdo, fecha) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/sendAcuerdos.php",
            data: {
                TipoAcuerdo: TipoAcuerdo,
                idMandante: GlobalData.id_mandante,
                fecha: fecha
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                console.log(data);
            },
            error: function(data) {}
        });
    }
    function getCesantia(TipoCesantia, fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getCesantias.php",
            data: {
                TipoCesantia: TipoCesantia,
                idMandante: GlobalData.id_mandante,
                fechaStart: fechaStart,
                fechaEnd: fechaEnd
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    var ToReturn = JSON.parse(data);
                    console.log(ToReturn);
                    showCesantias(ToReturn.Fields, ToReturn.Cesantias, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showCesantias(Fields, Cesantias, Table) {
        $('#' + Table).DataTable({
            data: Cesantias,
            bDestroy: true,
            columns: Fields
        });
    }
    function getReclamo(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getReclamos.php",
            data: {
                idMandante: GlobalData.id_mandante,
                fechaStart: fechaStart,
                fechaEnd: fechaEnd
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data) {
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    var ToReturn = JSON.parse(data);
                    console.log(ToReturn);
                    showReclamos(ToReturn.Fields, ToReturn.Reclamos, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showReclamos(Fields, Reclamos, Table) {
        $('#' + Table).DataTable({
            data: Reclamos,
            bDestroy: true,
            columns: Fields
        });
    }











    $('#tipoTemplate').on('change', function() {
        var tipoTemplate = $('#tipoTemplate').val();
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getTemplate.php",
            data: { tipoTemplate: tipoTemplate },
            dataType: "html",
            async: false,
            success: function(response) {
                $('#summernote').summernote('code', response);
            }
        });
    });
    $("#updateTemplate").on('click', function() {
        var tipoTemplate = $("#tipoTemplate").val();
        var Template = $('#summernote').summernote('code');

        if (tipoTemplate) {
            $.ajax({
                type: "POST",
                url: "../includes/derivaciones/updateTemplate.php",
                data: { tipoTemplate: tipoTemplate, Template: Template },
                dataType: "html",
                async: false,
                success: function(result) {
                    niftySuccess("Template actualizado");
                },
                error: function() {
                    niftyDanger("Error al actualizar template");
                }
            });
        } else {
            niftyWarning("Debe seleccionar una template.");
        }
    });

    $.ajax({
        type: "POST",
        url: "../includes/derivaciones/getCorreosCC.php",
        async: false,
        success: function(response) {
            $('#correosCC').val(response);
        }
    });
    $("#updateCorreosCC").on('click', function() {
        var correosCC = $("#correosCC").val();

        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/updateCorreosCC.php",
            data: { correosCC: correosCC },
            dataType: "html",
            async: false,
            success: function(result) {
                niftySuccess("Correos CC actualizados");
            },
            error: function() {
                niftyDanger("Error al actualizar CorreosCC");
            }
        });
    });

    $.ajax({
        type: "POST",
        url: "../includes/crm/nivel_1.php",
        data: {
            cedente: $('#cedente').val(),
            busqueda: 1
        },
        success: function(response) {
            $('.nivel_1_mostrar').html(response);
            $('.selectpicker').selectpicker('refresh')
        }
    });

    $(document).on('change', '#seleccione_nivel1', function() {
        Nivel1 = $('#seleccione_nivel1').val()
        getNivel2(Nivel1, 0);
    });

    function getNivel2(Nivel1, Nivel2) {
        $.ajax({
            type: "POST",
            url: "../includes/crm/nivel_2.php",
            data: {
                nivel2: Nivel1
            },
            async: false,
            success: function(response) {
                $('.nivel_2_mostrar').html(response);
                if (Nivel2) {
                    $('#seleccione_nivel2').val(Nivel2)
                }
                $('.selectpicker').selectpicker('refresh')
            }
        });
    }
    $(document).on('change', '#seleccione_nivel2', function() {
        Nivel2 = $('#seleccione_nivel2').val()
        getNivel3(Nivel2, 0);
    });

    function getNivel3(Nivel2, Nivel3) {
        $.ajax({
            type: "POST",
            url: "../includes/crm/nivel_3.php",
            data: {
                nivel3: Nivel2
            },
            async: false,
            success: function(response) {
                $('.nivel_3_mostrar').html(response);
                $("#seleccione_nivel3 option[value='0']").remove();
                $('#seleccione_nivel3').attr('multiple', true)
                if (Nivel3) {
                    $('#seleccione_nivel3').val(Nivel3)
                }
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }

    $(document).on('change', '#tipoNivel', function() {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getNiveles.php",
            data: {
                tipoNivel: $('#tipoNivel').val()
            },
            async: false,
            dataType: 'json',
            success: function(response) {
                $('#seleccione_nivel1').val(response.Nivel1);
                $('.selectpicker').selectpicker('refresh')
                setTimeout(() => {
                    getNivel2(response.Nivel1, response.Nivel2);
                }, 500);
                setTimeout(() => {
                    getNivel3(response.Nivel2, response.Nivel3)
                }, 500);
            }
        });
    });

    $("#updateNiveles").on('click', function() {
        var tipoNivel = $("#tipoNivel").val();
        var Niveles = $("#seleccione_nivel3").val();

        if (tipoNivel) {
            $.ajax({
                type: "POST",
                url: "../includes/derivaciones/updateNiveles.php",
                data: { tipoNivel: tipoNivel, Niveles: Niveles },
                async: false,
                success: function(result) {
                    niftySuccess("Niveles actualizados");
                },
                error: function() {
                    niftyDanger("Error al actualizar niveles");
                }
            });
        } else {
            niftyWarning("Debe seleccionar un tipo.");
        }
    });

    function niftyWarning(mensaje) {
        $.niftyNoty({
            type: 'warning',
            icon: 'fa fa-exclamation',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }

    function niftyDanger(mensaje) {
        $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-times-circle',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }

    function niftySuccess(mensaje) {
        $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }
});
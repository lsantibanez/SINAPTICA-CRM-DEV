$(document).ready(function() {
    var AcuerdosTable = null;
    var ReprogramacionesTable;

    $("select[name='TipoDerivacion']").change(function() {
        var TipoDerivacion = $(this).val();
        $("#panelOferta75DescuentoCruzVerdeConsumo").hide();
        $("#panelRenegociacionCruzVerdeConsumo").hide();
        $("#panelRenegociacionCruzVerdeTarjeta").hide();
        switch (TipoDerivacion) {
            case "oferta75DescuentoCruzVerdeConsumo":
                $("#panelOferta75DescuentoCruzVerdeConsumo").show();
                break;
            case "renegociacionCruzVerdeConsumo":
                $("#panelRenegociacionCruzVerdeConsumo").show();
                break;
            case "renegociacionCruzVerdeTarjeta":
                $("#panelRenegociacionCruzVerdeTarjeta").show();
                break;
        }
    });
    $("#buscarOferta75DescuentoCruzVerdeConsumo").click(function() {
        var fechaStart = $("input[name='fechaOferta75DescuentoCruzVerdeConsumoStart']").val();
        var fechaEnd = $("input[name='fechaOferta75DescuentoCruzVerdeConsumoEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#Oferta75DescuentoCruzVerdeConsumo").show();
                getOferta75DescuentoCruzVerdeConsumo(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadOferta75DescuentoCruzVerdeConsumo").click(function() {
        var fechaStart = $("input[name='fechaOferta75DescuentoCruzVerdeConsumoStart']").val();
        var fechaEnd = $("input[name='fechaOferta75DescuentoCruzVerdeConsumoEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadOferta75DescuentoCruzVerdeConsumo.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });
    $("#buscarRenegociacionCruzVerdeConsumo").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionCruzVerdeConsumoStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionCruzVerdeConsumoEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#RenegociacionCruzVerdeConsumo").show();
                getRenegociacionCruzVerdeConsumo(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadRenegociacionCruzVerdeConsumo").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionCruzVerdeConsumoStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionCruzVerdeConsumoEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadRenegociacionCruzVerdeConsumo.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });
    $("#buscarRenegociacionCruzVerdeTarjeta").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionCruzVerdeTarjetaStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionCruzVerdeTarjetaEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#RenegociacionCruzVerdeTarjeta").show();
                getRenegociacionCruzVerdeTarjeta(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadRenegociacionCruzVerdeTarjeta").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionCruzVerdeTarjetaStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionCruzVerdeTarjetaEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadRenegociacionCruzVerdeTarjeta.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });

    function getOferta75DescuentoCruzVerdeConsumo(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getOferta75DescuentoCruzVerdeConsumo.php",
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
                    Oferta75DescuentoCruzVerdeConsumo(ToReturn.Fields, ToReturn.Ofertas, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function Oferta75DescuentoCruzVerdeConsumo(Fields, Ofertas, Table) {
        $('#' + Table).DataTable({
            data: Ofertas,
            bDestroy: true,
            columns: Fields
        });
    }
    function getRenegociacionCruzVerdeConsumo(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getRenegociacionCruzVerdeConsumo.php",
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
                    showRenegociacionCruzVerdeConsumo(ToReturn.Fields, ToReturn.Renegociaciones, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showRenegociacionCruzVerdeConsumo(Fields, Renegociaciones, Table) {
        $('#' + Table).DataTable({
            data: Renegociaciones,
            bDestroy: true,
            columns: Fields
        });
    }
    function getRenegociacionCruzVerdeTarjeta(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getRenegociacionCruzVerdeTarjeta.php",
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
                    showRenegociacionCruzVerdeTarjeta(ToReturn.Fields, ToReturn.Renegociaciones, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showRenegociacionCruzVerdeTarjeta(Fields, Renegociaciones, Table) {
        $('#' + Table).DataTable({
            data: Renegociaciones,
            bDestroy: true,
            columns: Fields
        });
    }

});
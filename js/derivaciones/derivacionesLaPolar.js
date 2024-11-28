$(document).ready(function() {
    var AcuerdosTable = null;
    var ReprogramacionesTable;

    $("select[name='TipoDerivacion']").change(function() {
        var TipoDerivacion = $(this).val();
        $("#panelCompromisosLaPolarCastigo").hide();
        $("#panelRenegociacionesLaPolar").hide();
        $("#panelAjustesDePagoLaPolarCastigo").hide();
        switch (TipoDerivacion) {
            case "compromisosLaPolarCastigo":
                $("#panelCompromisosLaPolarCastigo").show();
                break;
            case "renegociacionesLaPolar":
                $("#panelRenegociacionesLaPolar").show();
                break;
            case "ajustesDePagoLaPolarCastigo":
                $("#panelAjustesDePagoLaPolarCastigo").show();
                break;
        }
    });
    $("#buscarCompromisosLaPolarCastigo").click(function() {
        var fechaStart = $("input[name='fechaCompromisoLaPolarCastigoStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoLaPolarCastigoEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#CompromisosLaPolarCastigo").show();
                getCompromisosLaPolarCastigo(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadCompromisosLaPolarCastigo").click(function() {
        var fechaStart = $("input[name='fechaCompromisoLaPolarCastigoStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoLaPolarCastigoEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadCompromisosLaPolarCastigo.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });
    $("#buscarRenegociacionesLaPolar").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionesLaPolarStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionesLaPolarEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#RenegociacionesLaPolar").show();
                getRenegociacionesLaPolar(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadRenegociacionesLaPolar").click(function() {
        var fechaStart = $("input[name='fechaRenegociacionesLaPolarStart']").val();
        var fechaEnd = $("input[name='fechaRenegociacionesLaPolarEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadRenegociacionesLaPolar.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });
    ///////////////////////////////
    ///////////////////////////////
    ///////////////////////////////
    $("#buscarAjusteDePagosLaPolarCastigo").click(function() {
        var fechaStart = $("input[name='fechaAjusteDePagoLaPolarCastigoStart']").val();
        var fechaEnd = $("input[name='fechaAjusteDePagoLaPolarCastigoEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#AjusteDePagosLaPolarCastigo").show();
                getAjustesDePagoLaPolarCastigo(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $(".downloadAjusteDePagosLaPolarCastigo").click(function() {
        var fechaStart = $("input[name='fechaAjusteDePagoLaPolarCastigoStart']").val();
        var fechaEnd = $("input[name='fechaAjusteDePagoLaPolarCastigoEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadAjusteDePagoLaPolarCastigo.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });

    function getCompromisosLaPolarCastigo(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getCompromisosLaPolarCastigo.php",
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
                    showCompromisosLaPolarCastigo(ToReturn.Fields, ToReturn.Compromisos, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showCompromisosLaPolarCastigo(Fields, Compromisos, Table) {
        $('#' + Table).DataTable({
            data: Compromisos,
            bDestroy: true,
            columns: Fields
        });
    }
    function getRenegociacionesLaPolar(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getRenegociacionesLaPolar.php",
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
                    showRenegociacionesLaPolar(ToReturn.Fields, ToReturn.Renegociaciones, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showRenegociacionesLaPolar(Fields, Renegociaciones, Table) {
        $('#' + Table).DataTable({
            data: Renegociaciones,
            bDestroy: true,
            columns: Fields
        });
    }
    function getAjustesDePagoLaPolarCastigo(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getAjustesDePagoLaPolarCastigo.php",
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
                    showAjustesDePagoLaPolarCastigo(ToReturn.Fields, ToReturn.AjustesDePago, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }
    function showAjustesDePagoLaPolarCastigo(Fields, AjustesDePago, Table) {
        $('#' + Table).DataTable({
            data: AjustesDePago,
            bDestroy: true,
            columns: Fields
        });
    }

});
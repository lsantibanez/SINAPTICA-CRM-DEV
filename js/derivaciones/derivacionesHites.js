$(document).ready(function() {
    var AcuerdosTable = null;
    var ReprogramacionesTable;

    $("select[name='TipoDerivacion']").change(function() {
        var TipoDerivacion = $(this).val();
        $("#panelCompromisos").hide();
        $("#panelCompromisosTributarios").hide();
        switch (TipoDerivacion) {
            case "compromisosHites":
                $("#showCompromisos").hide();
                $("#panelCompromisos").show();
                break;
            case "compromisosHitesTributario":
                $("#CompromisosTributariosNormal").hide();
                $("#panelCompromisosTributarios").show();
                break;
        }
    });
    $("#buscarCompromisosHites").click(function() {
        var fechaStart = $("input[name='fechaCompromisoHitesStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoHitesEnd']").val();
        if (fechaStart != "") {
            if (fechaEnd != "") {
                $("#showCompromisos").show();
                getCompromisos(fechaStart, fechaEnd);
            } else {
                bootbox.alert("Debe ingresar una fecha Hasta.");
            }
        } else {
            bootbox.alert("Debe ingresar una fecha Desde.");
        }
    });
    $("#downloadCompromisosHites").click(function() {
        var fechaStart = $("input[name='fechaCompromisoHitesStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoHitesEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadCompromisosHites.php?idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });
    $("#buscarCompromisosTributarios").click(function() {
        var tipoCompromiso = $("select[name='TipoCompromisosTributarios']").val();
        var fechaStart = $("input[name='fechaCompromisoHitesTributarioStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoHitesTributarioEnd']").val();
        $("#CompromisosTributariosNormal").hide();
        $("#CompromisosTributariosEspecial").hide();
        if(tipoCompromiso != ""){
            if (fechaStart != "") {
                if (fechaEnd != "") {
                    $("#showCompromisos").show();
                    switch(tipoCompromiso){
                        case "normales":
                            $("#CompromisosTributariosNormal").show();
                        break;
                        case "especiales":
                            $("#CompromisosTributariosEspecial").show();
                        break;
                    }
                    getCompromisosTributarios(tipoCompromiso,fechaStart, fechaEnd);
                } else {
                    bootbox.alert("Debe ingresar una fecha Hasta.");
                }
            } else {
                bootbox.alert("Debe ingresar una fecha Desde.");
            }
        }else{
            bootbox.alert("Debe ingresar un tipo de compromiso.");
        }
    });
    $(".downloadCompromisosTributarios").click(function() {
        var tipoCompromiso = $("select[name='TipoCompromisosTributarios']").val();
        var fechaStart = $("input[name='fechaCompromisoHitesTributarioStart']").val();
        var fechaEnd = $("input[name='fechaCompromisoHitesTributarioEnd']").val();
        var idMandante = GlobalData.id_mandante;
        window.location = "../includes/derivaciones/downloadCompromisosHitesTributario.php?tipoCompromiso="+tipoCompromiso+"&idMandante=" + idMandante + "&fechaStart=" + fechaStart + "&fechaEnd=" + fechaEnd;
    });

    function getCompromisos(fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getCompromisosHites.php",
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
                    showCompromisos(ToReturn.Fields, ToReturn.Compromisos, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }

    function showCompromisos(Fields, Compromisos, Table) {
        $('#' + Table).DataTable({
            data: Compromisos,
            bDestroy: true,
            columns: Fields
        });
    }

    function getCompromisosTributarios(TipoCompromiso, fechaStart, fechaEnd) {
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getCompromisosHitesTributario.php",
            data: {
                idMandante: GlobalData.id_mandante,
                fechaStart: fechaStart,
                fechaEnd: fechaEnd,
                tipoCompromiso: TipoCompromiso
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
                    showCompromisos(ToReturn.Fields, ToReturn.Compromisos, ToReturn.Table);
                }
            },
            error: function(data) {}
        });
    }

    function showCompromisosTributarios(Fields, Compromisos, Table) {
        $('#' + Table).DataTable({
            data: Compromisos,
            bDestroy: true,
            columns: Fields
        });
    }
});
$(document).ready(function(){
    getPeriodosFechas();
    $("#DownloadPeriodo").click(function () {
        var Periodo = $("select[name='Periodos']").val();
        if (Periodo != "") {
            DownloadInformePeriodo();
        } else {
            bootbox.alert("Debe seleccionar un periodo");
        }
    });
    $("#DownloadGeneral").click(function(){
        DownloadInformeGeneral();
    });
    $("#DownloadGenerales").click(function () {
        var Periodo = $("select[name='PeriodosGenerales']").val();
        if (Periodo != "") {
            DownloadInformesGenerales();
        } else {
            bootbox.alert("Debe seleccionar un periodo");
        }
    });

    function getPeriodosFechas(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getEvaluacionesByMonthsAndYearsAndMandanteAndCedente.php",
            dataType: "html",
            data: {},
            success: function(data){
                $("select[name='Periodos']").html(data);
                $("select[name='Periodos']").selectpicker('refresh');
                $("select[name='PeriodosGenerales']").html(data);
                $("select[name='PeriodosGenerales']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function DownloadInformePeriodo() {
        var Periodo = $("select[name='Periodos']").val();
        var TipoBusqueda = $("select[name='TipoBusquedaPeriodo']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DownloadInformePeriodo.php",
            dataType: "html",
            data: {
                Month: Periodo,
                TipoBusqueda: TipoBusqueda
            },
            success: function (data) {
                var json = JSON.parse(data);
                var $a = $("<a id='AnclaTemp'>");
                $a.attr("href", json.file);
                $a.attr("download", json.filename + ".xlsx");
                $("body").append($a);
                $("#AnclaTemp")[0].click();
                $("#AnclaTemp").remove();
            },
            error: function () {
            }
        });
    }
    function DownloadInformeGeneral(){
        var TipoBusqueda = $("select[name='TipoBusquedaGeneral']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DownloadInformeGeneral.php",
            dataType: "html",
            data: {
                TipoBusqueda: TipoBusqueda
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    var $a = $("<a id='AnclaTemp'>");
                    $a.attr("href",json.file);
                    $a.attr("download",json.filename+".xlsx");
                    $("body").append($a);
                    $("#AnclaTemp")[0].click();
                    $("#AnclaTemp").remove();
                }
            },
            error: function(){
            }
        });
    }
    function DownloadInformesGenerales() {
        var Periodo = $("select[name='PeriodosGenerales']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DownloadInformesGenerales.php",
            dataType: "html",
            data: {
                Month: Periodo
            },
            success: function (data) {
                var json = JSON.parse(data);
                var $a = $("<a id='AnclaTemp'>");
                $a.attr("href", json.file);
                $a.attr("download", json.filename + ".xlsx");
                $("body").append($a);
                $("#AnclaTemp")[0].click();
                $("#AnclaTemp").remove();
            },
            error: function () {
            }
        });
    }
});
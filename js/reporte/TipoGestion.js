$(document).ready(function(){
    var data = [];
    var TipoGestionTable ;
    var dataSetTable = [];
    getReportData("1");
    showTipoReport();
    updateTableTipoGestion();
    function getReportData(Nivel,Codigo){
        if(typeof Codigo == "undefined"){
            Codigo = "";
        }
        $.ajax({
            type: "POST",
            url: "../includes/reporte/tipoGestion/tipoGestion.php",
            dataType: "html",
            data: {
                Nivel: Nivel,
                Codigo: Codigo
            },
            async: false,
            success: function(response){
                if(isJson(response)){
                    data = JSON.parse(response);
                    dataSetTable = data;
                    if(data.length >= 1){
                        var Nivel = data[0].Nivel == "-" ? "2" : data[0].Nivel == "1" ? "1" : data[0].Nivel - 1;
                        var idNivelAnterior = data[0].idAnterior;
                        if(data[0].Nivel != "-"){
                            $("#BackNivel").attr("nivel",Nivel);
                            $("#BackNivel").attr("idnivelanterior",idNivelAnterior);
                        }else{
                            $("#BackNivel").attr("nivel","3");
                        }
                    }
                }else{
                    data = [];
                }
            },
            error: function(){
            }
        });
    }
    function showTipoReport(){
        $.plot('#ReportTipoGestion', data, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    label: {
                        show: true,
                        radius: 3/4,
                        formatter: labelFormatter,
                        background: {
                            opacity: 0.5
                        }
                    }
                }
            },
            legend: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: true
            }
        });
    }
    $("#ReportTipoGestion").bind("plotclick", function(event, pos, obj) {
        if (!obj) {
            return;
        }
        var idNivel = obj.series.idNivel;
        var idNivelAnterior = obj.series.idAnterior;
        var Nivel = obj.series.Nivel;
        if(Nivel != "-"){
            $("#BackNivel").attr("nivel",Nivel);
            $("#BackNivel").attr("idnivelanterior",idNivelAnterior);
            getReportData(Nivel,idNivel);
            showTipoReport();
            TipoGestionTable.destroy();
            updateTableTipoGestion();
            $("#BackNivel").closest(".row").show();
        }
    });
    $("body").on("click","#BackNivel",function(){
        var ObjectMe = $(this);
        var Nivel = ObjectMe.attr("nivel");
        var idNivel = ObjectMe.attr("idnivelanterior");
        if(Nivel - 1 == "1"){
            ObjectMe.closest(".row").hide();
        }
        getReportData(Nivel - 1,idNivel);
        showTipoReport();
        TipoGestionTable.destroy();
        updateTableTipoGestion();
    });
    function labelFormatter(label, series) {
        console.log();
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:#333333;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }
    function updateTableTipoGestion(){
        TipoGestionTable = $('#TablaTipoGestion').DataTable({
            data: dataSetTable,
            columns: [
                { data: 'label' },
                { data: 'data' },
            ]
        });
    }
});
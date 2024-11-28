$(document).ready(function(){
    var data = [];
    getReportData();
    showFonoReport();
    function getReportData(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/datosContactos/datosContactos.php",
            dataType: "html",
            data: {
            },
            async: false,
            success: function(response){
                if(isJson(response)){
                    data = JSON.parse(response);
                }else{
                    data = [];
                }
            },
            error: function(){
            }
        });
    }
    function showFonoReport(){
        $.plot('#ReportFonos', data.Fonos, {
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
            }
        });
    }
    showMailReport();
    function getMailReportData(){

    }
    function showMailReport(){
        $.plot('#ReportMails', data.Mails, {
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
            }
        });
    }
    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + series.datapoints.points[1] + " Ruts<br/>" + Math.round(series.percent) + "%</div>";
	}
});
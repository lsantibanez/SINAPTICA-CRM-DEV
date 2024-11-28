$(document).ready(function(){

    var ReporteSMSTable;

    $("#FiltrarPorFecha").click(function(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        if((startDate != "") && (endDate != "")){
            var start = startDate.split("/").join("-");;
            var end = endDate.split("/").join("-");;

            var post = "start="+start+"&end="+end;

            getReporte(post);
        }
    });

    function getReporte(post){
        $.ajax({
            type: "POST",
            url: "../includes/email/getReporteSMS.php",
            dataType: "html",
            async: false,
            data: post,
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#reporte_sms' ) ) {
                    ReporteSMSTable.destroy();
                }

                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
            console.log(data);

                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    ReporteSMSTable = $('#reporte_sms').DataTable({
                        data: dataSet.detalle,
                        columns: [
                            { data: 'nombre' },
                            { data: 'cantidad' },
                            { data: 'costoSMS' },
                            { data: '' },
                            { data: 'fecha' },
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": [0,1,2,4],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 3,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+(row.cantidad * row.costoSMS)+"</div>";
                                }
                            }
                        ]
                    });

                    var ms = 0;
                    var costo = 0;

                    $.each(dataSet.detalle, function(){
                        ms = Number(this.cantidad) + Number(ms);
                        costo = this.costoSMS;
                    });

                    $("#mensajesMes").text(dataSet.totalMes);
                    $("#pesosMes").text(dataSet.totalMes * costo);
                    $("#mensajesSeleccion").text(ms);
                    $("#pesosSeleccion").text(ms * costo);
                }
                $('#Cargando').modal('hide');
            },
            error: function(){   
            }
        });
    }
});
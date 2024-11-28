$(document).ready(function(){

    var EnviadosTable;
    var DetalleEnviadosTable;

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
            url: "../includes/email/getEmailEnviados.php",
            data: post,
            dataType: "html",
            success: function(data){
            console.log(data);

                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    EnviadosTable = $('#email_enviados').DataTable({
                        data: dataSet,
                        destroy: true,
                        columns: [
                            { data: 'asignacion' },
                            { data: 'template' },
                            { data: 'asunto' },
                            { data: 'estatus' },
                            { data: '' },
                            { data: 'leidos' }
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": [0,1,2,3,5],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 4,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+row.cantidad+"/"+row.enviados+"</div>";
                                }
                            }
                        ]
                    });
                }
                $('#Cargando').modal('hide');
            },
            error: function(){   
            }
        });
    }
});
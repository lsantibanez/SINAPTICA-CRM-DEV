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
            url: "../includes/email/getSMSEnviados.php",
            dataType: "html",
            async: false,
            data: post,
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#sms_enviados' ) ) {
                    EnviadosTable.destroy();
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

                    EnviadosTable = $('#sms_enviados').DataTable({
                        data: dataSet,
                        columns: [
                            { data: 'estrategia' },
                            { data: 'template' },
                            { data: 'cantidad' },
                            { data: 'colores' },
                            { data: 'usuario' },
                            { data: 'fechaHora' },
                            { data: '' }
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": [0,1,2,3,4,5],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 6,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;' id='"+row.id+"'><button class='verDetalle btn'>Detalle</button></div>";
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

    $(document).on('click', '.verDetalle', function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        fillDialog(id);
    });
    
    function detalleDialog(label){
        var ModalDetalleSMS = $("#modalDetalle").html();
        bootbox.dialog({
            title: label,
            message: ModalDetalleSMS,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
    }

    function fillDialog(id){
        post = "id="+id;

        $.ajax({
            type: "POST",
            url: "../includes/email/getDetalleSMSEnviados.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#detalleEnviados' ) ) {
                    DetalleEnviadosTable.destroy();
                }

                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                console.log(data);
                detalleDialog("Detalle de SMS");
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    DetalleEnviadosTable = $('#detalleEnviados').DataTable({
                        data: dataSet,
                        columns: [
                            { data: 'rut' },
                            { data: 'fono' },
                            { data: 'estado' }
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": [0,1,2],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                        ],
                        destroy: true,
                    });
                }
                $('#Cargando').modal('hide');
            },
            error: function(){
            }
        });
    }
});
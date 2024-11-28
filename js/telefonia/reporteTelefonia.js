$(document).ready(function(){

    ReporteTelefoniaTable = $('#ReporteTelefoniaTable').DataTable({
        paging: false,
        iDisplayLength: 100,
        processing: true,
        serverSide: false,  
        bInfo:false,
        order: [[0, 'asc']],
        language: {
            processing:     "Procesando ...",
            search:         'Buscar',
            lengthMenu:     "Mostrar _MENU_ Registros",
            info:           "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty:      "Mostrando 0 a 0 de 0 Registros",
            infoFiltered:   "(filtrada de _MAX_ registros en total)",
            infoPostFix:    "",
            loadingRecords: "...",
            zeroRecords:    "No se encontraron registros coincidentes",
            emptyTable:     "No hay datos disponibles en la tabla",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Ultimo"
            },
            aria: {
                sortAscending:  ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });
    getProveedor();

    $("#FiltrarPorFecha").click(function(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        var proveedor = $('#proveedor').val();

        if((startDate != "") && (endDate != "") && (proveedor != "")){
            var start = startDate.split("/").join("-");
            var end = endDate.split("/").join("-");
            start = start + ' 00:00:00';
            end = end + ' 23:59:59';
            var post = "start="+start+"&end="+end+"&proveedor="+proveedor;
            getReporte(post);
            
        }
        else{
            $.niftyNoty({
                type: 'danger',
                icon : 'fa fa-close',
                message : "Debe completar todos los campos!" ,
                container : 'floating',
                timer : 5000
            });
        }
    });

    function getReporte(post){
        
        $.ajax({
            type: "POST",
            url: "../includes/telefonia/getReporteTelefonia.php",
            dataType: "html",
            async: false,
            data: post,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                ReporteTelefoniaTable.clear().draw()
            },
            success: function(data){

                if(isJson(data)){

                    var data = JSON.parse(data);

                    var proveedor = data.proveedor

                    var timeMovil = data.billMovil;
                    var hours = Math.floor( timeMovil / 3600 );  
                    var minutes = Math.floor( (timeMovil % 3600) / 60 );
                    var seconds = timeMovil % 60;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    var hhmmssMovil = hours + ":" + minutes + ":" + seconds;

                    var timeFijo = data.billFijo;
                    var hours = Math.floor( timeFijo / 3600 );  
                    var minutes = Math.floor( (timeFijo % 3600) / 60 );
                    var seconds = timeFijo % 60;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    var hhmmssFijo = hours + ":" + minutes + ":" + seconds;

                    var timeTotal = data.billTotal;
                    var hours = Math.floor( timeTotal / 3600 );  
                    var minutes = Math.floor( (timeTotal % 3600) / 60 );
                    var seconds = timeTotal % 60;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    var hhmmssTotal = hours + ":" + minutes + ":" + seconds; 

                    var costoMovil = data.costoMovil
                    var costoFijo = data.costoFijo
                    var costoTotal = data.costoTotal

                    var rowNode = ReporteTelefoniaTable.row.add([
                      ''+proveedor+'',
                      ''+hhmmssMovil+'',
                      ''+hhmmssFijo+'',
                      ''+hhmmssTotal+'',
                      ''+costoMovil+'',
                      ''+costoFijo+'',
                      ''+costoTotal+'',
                      
                    ]).draw(false).node();

                    $( rowNode )
                        .addClass('text-center');

                }
                $('#Cargando').modal('hide');
            },
            error: function(){   
            }
        });
    }

    function getProveedor(){
        $.ajax({
            type: "POST",
            url: "../includes/telefonia/getProveedor.php",
            data: {},
            dataType: "html",
            success: function(data){
                console.log(data);
                $("select[name='listaProveedor']").html(data);
                $("select[name='listaProveedor']").selectpicker('refresh');
            }
        });
    }
});
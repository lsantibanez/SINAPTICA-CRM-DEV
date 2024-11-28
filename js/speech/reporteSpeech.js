audiojs.events.ready(function() {
    audiojs.createAll();
});
$(document).ready(function(){

    var DistribuidoresTable;
    var DistribuidoresArray = [];
    var TranscripcionesTable;
    var TranscripcionesArray = [];

    updateDistribuidoresTable();

    getMonthsFromTranscriptions();

    $("#Filtrar").click(function(){
        getDistribuidoresTableList();
        updateDistribuidoresTable();
        ReporteTranscripcionesPorDistribuidores();
    });
    $("body").on("click",".VerSemanal",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDistribuidor = ObjectDiv.attr("id");
        var Date = $("select[name='Mes']").val();
        var Template = $("#ReporteSemanalTemplate").html();
        bootbox.dialog({
            title: "REPORTE SEMANAL",
            message: Template,
            buttons: {
                close: {
                    label: "Cerrar",
                    callback: function() {
                        
                    }
                }
            },
            size: "large"
        }).off("shown.bs.modal");
        setTimeout(function(){
            $.ajax({
                type: "POST",
                url: "../includes/speech/GetReporteSemanalByDistribuidor.php",
                dataType: "html",
                data: {  
                    Date: Date,
                    idDistribuidor: idDistribuidor
                },
                async: false,
                beforeSend: function(){
                    $("#ReporteSemanal").html("");
                },
                success: function(data){
                    if(isJson(data)){
                        var Json = JSON.parse(data);
                        Morris.Bar({
                            element: 'ReporteSemanal',
                            data: Json,
                            xkey: 'Semana',
                            ykeys: ['Cantidad'],
                            labels: ['Cantidad'],
                        });
                    }
                },
                error: function(){
                }
            });
        },200);        
    });
    $("body").on("click",".VerDetalle",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDistribuidor = ObjectDiv.attr("id");
        var Date = $("select[name='Mes']").val();
        var Template = $("#VerDetalleTemplate").html();
        bootbox.dialog({
            title: "REPORTE DETALLADO POR DISTRIBUIDOR",
            message: Template,
            buttons: {
                close: {
                    label: "Cerrar",
                    callback: function() {
                        
                    }
                }
            },
            size: "large"
        }).off("shown.bs.modal");
        getTranscripcionesTableList(idDistribuidor);
        updateTranscripcionesTable();
    });
    $("body").on("click",".VerTranscripcion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idTranscripcion = ObjectDiv.attr("id");
        var Template = $("#TranscripcionTemplate").html();

        Transcripcion = getTranscripcion(idTranscripcion);

        Template = Template.replace("{RECORD}","<audio src='"+Transcripcion.RUTA+"' preload='auto' controls></audio>")

        bootbox.dialog({
            title: "TRANSCRIPCION",
            message: Template,
            buttons: {
                close: {
                    label: "Cerrar",
                    callback: function() {
                        
                    }
                }
            },
            size: "large"
        }).off("shown.bs.modal");

        $("#TranscripcionText").html(Transcripcion.Transcripcion);
    });

    function getMonthsFromTranscriptions(){
        $.ajax({
            type: "POST",
            url: "../includes/speech/getMonthsFromTranscriptions.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                $("select[name='Mes']").html(data);
                $("select[name='Mes']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getDistribuidoresTableList(Modal = true){
        var Date = $("select[name='Mes']").val();
        $.ajax({
            type: "POST",
            url: "../includes/speech/getDistribuidoresTableList.php",
            dataType: "html",
            data: {  
                Date: Date
            },
            async: false,
            beforeSend: function(){
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                
                DistribuidoresArray = [];
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    DistribuidoresArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateDistribuidoresTable(){
        DistribuidoresTable = $('#DistribuidioresTable').DataTable({
            data: DistribuidoresArray,
            "bDestroy": true,
            columns: [
                { data: 'Distribuidor' },
                { data: 'CantTranscripciones' },
                { data: 'Semanal' },
                { data: 'Transcripciones' }
            ],
            "columnDefs": [
                {"className": "dt-center", "targets": "_all"},
                {
                    "targets": 2,
                    "searchable": false,
                    "data": "Semanal",
                    "render": function( data, type, row ) {
                        return "<div id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-bar-chart icon-lg VerSemanal'></i></div>";
                    }
                },
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Transcripciones",
                    "render": function( data, type, row ) {
                        return "<div id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-list-alt icon-lg VerDetalle'></i></div>";
                    }
                },
            ]
        });
    }
    function getTranscripcionesTableList(idDistribuidor,Modal = true){
        var Date = $("select[name='Mes']").val();
        $.ajax({
            type: "POST",
            url: "../includes/speech/getTranscripcionesTableList.php",
            dataType: "html",
            data: {  
                Date: Date,
                idDistribuidor: idDistribuidor
            },
            async: false,
            beforeSend: function(){
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                
                TranscripcionesArray = [];
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    TranscripcionesArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateTranscripcionesTable(){
        TranscripcionesTable = $('#TranscripcionesTable').DataTable({
            data: TranscripcionesArray,
            "bDestroy": true,
            columns: [
                { data: 'Ver', width: '10%' },
                { data: 'FechaHora', width: '20%' },
                { data: 'PalabrasClaves', width: '40%' },
                { data: 'PalabrasEncontradas', width: '10%' },
                { data: 'PorcentajeCumplimiento', width: '10%' },
                { data: 'Cumplimiento', width: '10%' },
            ],
            "columnDefs": [
                {"className": "dt-center", "targets": "_all"},
                {
                    "targets": 0,
                    "searchable": false,
                    "data": "Ver",
                    "render": function( data, type, row ) {
                        return "<div id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='fa fa-eye icon-lg VerTranscripcion'></i></div>";
                    }
                }
            ]
        });
    }
    function getTranscripcion(idTranscripcion){
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/speech/getTranscripcion.php",
            dataType: "html",
            data: {
                idTranscripcion: idTranscripcion
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function ReporteTranscripcionesPorDistribuidores(){
        var Date = $("select[name='Mes']").val();
        $.ajax({
            type: "POST",
            url: "../includes/speech/GetReporteTranscripcionesPorDistribuidores.php",
            dataType: "html",
            data: {  
                Date: Date,
            },
            async: false,
            beforeSend: function(){
                $("#ReportePorDistribuidor").html("");
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var Json = JSON.parse(data);
                    console.log(Json);
                    Morris.Bar({
                        element: 'ReportePorDistribuidor',
                        data: Json,
                        xkey: 'Distribuidor',
                        ykeys: ['Transcripciones'],
                        labels: ['Transcripciones'],
                        ymin: 0,
                        ymax: 100
                    });
                }
            },
            error: function(){
            }
        });
    }
});
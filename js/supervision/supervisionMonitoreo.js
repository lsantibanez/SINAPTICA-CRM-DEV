$(document).ready(function(){

    var CampoArray = [];
    var ArrayColorEstado = [];
    var AgentesTable;
    var EstrategiasColasTable;
    var PuestosTable;
    var AgentesTable;
    var PuestosTrabajoTable;
    var ModalEstrategias;
    var ModalAgentes;
    var ColaSelect = "";
    var titulo = "";
    
    var conectados;
    var hablando;
    var disponibles;
    var pausados;
    var dead;

    var contador;
    var interval;
    var countDownDate;

    var myChart;
    var chart = document.getElementById("descartadasChart");

    actualizarMonitoreo();

    function actualizarMonitoreo(){
        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        });
        interval = $("#interval").val();
        contador = 0;
        var queue;
        var cedente = $('#Id_Cedente').val()

        if(ColaSelect !== ""){
            queue = ColaSelect;
        }else{
            queue    = (ColaSelect) ? ColaSelect : "";
            ColaSelect = queue;
        }

        var post = "queue="+queue+"&cedente="+cedente;

        console.log(post);

        getHoy(post);
        getEnCola(post);
        getDescartados(post);
        getEstatusAgentes(post);
        getRatiosMonitoreo(post);
        showPuestos(post);

        if(queue === ""){
            $("#tituloEstrategias").text("ESTÁS VIENDO TODAS LAS CAMPAÑAS");
        }else{
            $("#tituloEstrategias").text("ESTÁS VIENDO LA CAMPAÑA " + titulo);
        }
        $('#Cargando').modal('hide')
    }

    function getEstatusAgentes(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getEstatusAgentes.php",
            data: post,
            async: false,
            success: function(data){
                console.log(data);
                var cant = JSON.parse(data);

                dead        = cant[0].dead;
                pausados    = cant[0].pausados;
                conectados  = cant[0].conectados;
                disponibles = cant[0].disponible;
                hablando = cant[0].hablando;

                $("#dead").text(" " + dead + " ");
                $("#pausados").text(" " + pausados + " ");
                $("#agentesDisponibles").text(disponibles);
                $("#conectados").text(" " + conectados + " ");
                $("#disponible").text(" " + disponibles + " ");

                $("#hablando").text(" " + hablando + " ");
                $("#hablandoConectados").text(hablando + "/" + conectados);

                //getHablando(post, conectados);
            }
        });
    }

    function getHablando(post, conectados){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getHablando.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                var cant = JSON.parse(data);
                hablando = cant[0].hablando;
                $("#hablando").text(" " + hablando + " ");
                $("#hablandoConectados").text(hablando + "/" + conectados);
            }
        });
    }

    function getEnCola(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getEnCola.php",
            data: post,
            async: false,
            success: function(data){
                console.log(data);
                var cant = JSON.parse(data);
                var encola = cant[0].enCola;

                $("#spanCola").text(" " + encola + " ");
            }
        });
    }

    function getDescartados(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getDescartados.php",
            data: post,
            async: false,
            success: function(data){
                $("#spanDes").text(" " + data + " ");
            }
        });
    }

    function getHoy(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getHoy.php",
            data: post,
            async: false,
            success: function(data){
                console.log(data);
                var cant = JSON.parse(data);
                if (cant[0].hoy){
                    var hoy = cant[0].hoy;
                }else{
                    var hoy = 0;
                }

                $("#spanHoy").text(" " + hoy + " ");
            }
        });
    }

    function getRatiosMonitoreo(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getRatiosMonitoreo.php",
            data: post,
            async: false,
            success: function(data){
                console.log(data);
                var cant = JSON.parse(data);
                console.log(data);
                var penetracion = cant.penetracion;
                var contactabilidad = cant.contactabilidad;

                $("#spanPen").text(" " + penetracion + " ");
                $("#spanCont").text(" " + contactabilidad + " ");
            }
        });
    }

    $(".estrategias").click(function(){
        estrategiaDialog("FILTRAR COLAS");

        $("select[name='Colas']").selectpicker('render');
        var cedente = $('#Id_Cedente').val()

        fillColas(cedente);
    });

    $(".configuracion").click(function(){
        configuracionDialog("CONFIGURACIÓN");

        $("select[name='Tiempo']").selectpicker('render');
    });

    $("body").on("click",".spyCall",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var Agente = ObjectMe.attr("id");

        
        DiscadorSocket.emit('spyCall', { Anexo: "SIP/"+GlobalData.anexo, Agente: Agente } )
    });

    function fillColas(cedente){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillColas.php",
            data: { 
                cedente: cedente
            },
            dataType: "html",
            success: function(data){
                console.log(data);
                $("select[name='Colas']").html(data);
                $("select[name='Colas']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    function showPuestos(post){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getAgentesEstatus.php",
            data: post,
            async: false,
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_puestos_trabajo' ) ) {
                    PuestosTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    PuestosTable = $('#mostrar_puestos_trabajo').DataTable({
                        data: dataSet,
                        pageLength: 100,
                        columns: [
                            { data: 'anexo', width: "10%" },
                            { data: 'ejecutivo', width: "20%" },
                            { data: 'estatus', width: "10%" },
                            { data: 'pausa', width: "5%" },
                            { data: 'tiempo', width: "10%" },
                            { data: 'cedente', width: "10%" },
                            { data: 'Cola', width: "10%" },
                            { data: '', width: "5%" },
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": 0,
                            },
                            {
                                className: "dt-center",
                                "targets": 1,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: left;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 2,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center; font-weight: bold;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": [3, 4],
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;'>" + data + "</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": [5, 6],
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;' data-title='" + data + "' data-trigger='hover' class='add-popover'>Ver</div>";
                                    //return "<div style='text-align: center;' data-content='45%' data-trigger='hover' class='add-popover'>" + data + "</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 7,
                                "render": function( data, type, row ) {
                                    return "<i style='cursor: pointer;margin-right: 10px;' class='fa fa-headphones spyCall' id='"+row.anexo+"'></i>"+
                                    "<i style='cursor: pointer;' class='fa fa-trash delete' id='"+row.anexo+"'></i>"
                                    //return "<i style='cursor: pointer;' class='fa fa-trash btn btn-danger btn-icon icon-lg delete' id='" + row.anexo + "'></i>"
                                }
                            }
                        ],
                        destroy: true,
                        "order": [[ 2, "asc" ]],
                        "createdRow": function( row, data, dataIndex){
                            var a = data.tiempo.split(':'); // split it at the colons
                            var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
                            console.log(seconds);
                            if(data.estatus == "DISPONIBLE"){
                                if(seconds <= 60){
                                    $(row).addClass('hablando1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('hablando2');
                                }else if (seconds > 300){
                                    $(row).addClass('hablando3');
                                }
                            }else if(data.estatus == "EN LLAMADA"){
                                if(seconds <= 60){
                                    $(row).addClass('disponible1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('disponible2');
                                }else if (seconds > 300){
                                    $(row).addClass('disponible3');
                                }
                            }else if(data.estatus == "PAUSADO"){
                                if(seconds <= 60){
                                    $(row).addClass('pausado1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('pausado2');
                                }else if (seconds > 300){
                                    $(row).addClass('pausado3');
                                }
                            }else if(data.estatus == "MUERTO"){
                                $(row).addClass('black');
                            }
                        }
                    });
                    var popover = $('.add-popover');
                    if (popover.length) popover.popover();
                }
            },
            error: function(){
            }
        });
    }

    function estrategiaDialog(label){
        var ModalEstrategias = $("#modalEstrategias").html();
        bootbox.dialog({
            title: label,
            message: ModalEstrategias,
            buttons: {
                confirm:{
                    label: "Buscar",
                    className: "btn-success",
                    callback: function() {
                        ColaSelect = $("select[name='Colas']").val();
                        titulo = $("#Colas option:selected").text();
                        if(ColaSelect == ""){
                            $("#divContacta").hide();
                        }else{
                            $("#divContacta").show();
                        }
                        actualizarMonitoreo();
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
    }

    function configuracionDialog(label){
        var ModalConfiguracion = $("#modalConfiguracion").html();
        bootbox.dialog({
            title: label,
            message: ModalConfiguracion,
            buttons: {
                confirm:{
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function() {
                        actualizarInterval();
                        actualizarMonitoreo();
                    }
                },
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
    }

    function chartDialog(label, dataSet){
        var ModalEstrategias = $("#modalDescartadas").html();

        bootbox.dialog({
            title: label,
            message: ModalEstrategias,
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

    $("body").on("click",".iconos",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var estatus = ObjectMe.attr("id");

        console.log("ESTATUS SELECCIONADO: " + estatus);
        var queue    = ColaSelect;
        
        var puestos = "queue="+queue;

        agentesDialog(estatus);
        fillDialog(puestos, estatus);
    });

    function agentesDialog(label){
        var ModalAgentes = $("#modalAgentes").html();
        bootbox.dialog({
            title: label,
            message: ModalAgentes,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            className: "modalEstrategias"
        });
    }

    function fillDialog(puestos, estatus){
        puestos = puestos+"&estatus="+estatus;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getAgentesEstatus.php",
            data: puestos,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#agentesPorEstatus' ) ) {
                    AgentesTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    AgentesTable = $('#agentesPorEstatus').DataTable({
                        data: dataSet,
                        columns: [
                            { data: 'anexo', width: "10%" },
                            { data: 'ejecutivo', width: "20%" },
                            { data: 'estatus', width: "10%" },
                            { data: 'pausa', width: "5%" },
                            { data: 'tiempo', width: "10%" },
                            { data: '', width: "10%" },
                            { data: '', width: "10%" },
                            { data: '', width: "10%" },
                            { data: '', width: "10%" },
                            { data: '', width: "5%" },
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": 0,
                            },
                            {
                                className: "dt-center",
                                "targets": 1,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: left;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 2,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center; font-weight: bold;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": [3,4],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": [5,6,7,8],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'></div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 9,
                                "render": function( data, type, row ) {
                                    return "<button class='fa fa-trash btn btn-danger btn-icon icon-lg delete' id='"+row.anexo+"'></button>";
                                }
                            }
                        ],
                        destroy: true,
                        "createdRow": function( row, data, dataIndex){
                            var a = data.tiempo.split(':'); // split it at the colons
                            var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
                            console.log(seconds);
                            if(data.estatus == "DISPONIBLE"){
                                if(seconds <= 60){
                                    $(row).addClass('hablando1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('hablando2');
                                }else if (seconds > 300){
                                    $(row).addClass('hablando3');
                                }
                            }else if(data.estatus == "EN LLAMADA"){
                                if(seconds <= 60){
                                    $(row).addClass('disponible1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('disponible2');
                                }else if (seconds > 300){
                                    $(row).addClass('disponible3');
                                }
                            }else if(data.estatus == "PAUSADO"){
                                if(seconds <= 60){
                                    $(row).addClass('pausado1');
                                }else if ((seconds > 60) && (seconds <= 300)){
                                    $(row).addClass('pausado2');
                                }else if (seconds > 300){
                                    $(row).addClass('pausado3');
                                }
                            }else if(data.estatus == "MUERTO"){
                                $(row).addClass('black');
                            }
                        }
                    });
                }
            },
            error: function(){
            }
        });
    }

    $("body").on("click",".verContactabilidad",function(){
        var queue    = ColaSelect;
        var post = "idcola="+queue+"&label=";

        $.ajax({
            type: "POST",
            url: "../includes/supervision/verGestion.php",
            data: post,
            dataType: "html",
            success: function(data){
               window.location = '../supervision/supervisionGestion.php';
            }
        });
    });

    $(".descartadas").click(function(){
        var post = "queue="+ColaSelect;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getDetalleDescartadas.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                var dataSet = JSON.parse(data);
                chartDialog("DESCARTADAS");
                setTimeout(function(){
                    grafico(dataSet);
                }, 1000);
            }
        });
    });

    function grafico(data){
        var ArrayCauseText = [];
        var ArrayCauseCantidad = [];

        for (var i in data){
            var value = data[i];

            var leyenda = value.label;
            ArrayCauseText.push(leyenda);

            var causeCont = Number(value.cantidad);
            ArrayCauseCantidad.push(causeCont);
        }

        var idChart = document.getElementById("descartadasChart");

        myChart = new Chart(idChart, {
            type: "pie",
            data: {
                labels: ArrayCauseText,
                datasets: [{
                    data: ArrayCauseCantidad,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(120, 40, 31, 0.5)', //  255, 206, 86
                        'rgba(183, 149, 11, 0.5)',
                        'rgba(153, 102, 255, 0.5)', 
                        'rgba(255, 159, 64, 0.5)', 
                        'rgba(34, 153, 84, 0.5)',
                        'rgba(66, 73, 73, 0.5)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(120, 40, 31, 1)',
                        'rgba(183, 149, 11, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(34, 153, 84, 1)',
                        'rgba(66, 73, 73, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                    reverse: 'true',
                    fullWidth: false,
                    legendCallback: function(chart) {
                        return "fdfdfdf";
                    }
                }
            }
        });
        return myChart;
    }

    Chart.plugins.register({
        afterDraw: function(chart) {
            //console.log(chart.data.datasets[0].data.length);
            if (chart.data.datasets[0].data.length === 0) {
                // No data is present
                var ctx = chart.chart.ctx;
                var width = chart.chart.width;
                var height = chart.chart.height
                chart.clear();
                
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = "48px normal 'Helvetica Nueue'";
                ctx.fillText('No data to display', width / 2, height / 2);
                ctx.restore();
            }
        }
    });

    $(document).on("click",".delete",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var id = ObjectMe.attr("id");

        var idPuesto = "puesto="+id;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/deletePuestos.php",
            data: idPuesto,
            dataType: "html",
            success: function(data){
                console.log(data);
                actualizarMonitoreo();
            },
            error: function(data){
                console.log("Error: " + data);
            }
        });
    });

    function actualizarInterval(){
        var tiempo = $("select[name='Tiempo']").val();
        
        $("#interval").val(tiempo * 1000);
        //interval = (tiempo * 1000);
    }

    setInterval(function(){
        countDownDate = interval;
        contador = contador + 1000;

        var distance = countDownDate - contador;
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        minutes = minutes < 10 ? "0"+minutes : minutes;
        seconds = seconds < 10 ? "0"+seconds : seconds;

        $("div#countDown").text("TIEMPO RESTANTE PARA REFRESCO: " + minutes + ":" + seconds);

        if(distance == 0){
            actualizarMonitoreo();
        }
    }, 1000);

    $("body").on("change", "#estadistica-switch", function () {
        if ($(this).is(":checked")) {
            $("#estadistica").hide();
        } else {
            $("#estadistica").show();
        }
    });
    $(document).on("change", "#Id_Mandante", function () {
        ID = $(this).val()
        $('#Id_Cedente').empty();
        $('#Id_Cedente').append('<option value="">Todos</option>');
        if(ID != ''){
            $.ajax({
                type: "POST",
                url: "../includes/admin/GetListarCedentesMandantes.php",
                data: { idMandante: ID },
                dataType: "json",
                async: false,
                success: function (data) {
                    $.each(data, function (index, array) {
                        $('#Id_Cedente').append('<option value="' + array.idCedente + '">' + array.NombreCedente + '</option>');
                    });
                }
            });
        }
        $('#Id_Cedente').selectpicker('refresh')
        actualizarMonitoreo();
    });
    $(document).on("change", "#Id_Cedente", function () {
        actualizarMonitoreo();
    });
});
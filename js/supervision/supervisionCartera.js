$(document).ready(function(){

    var CampoArray = [];
    var ArrayColorEstado = [];
    var AgentesTable;
    var EstrategiasColasTable;
    var PuestosTable;
    var PuestosTrabajoTable;
    var myChart;
    var chart        = document.getElementById("myChart");

    fillMandantes();

    function fillMandantes(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillMandantes.php",
            data: { },
            dataType: "html",
            success: function(data){
                $("select[name='Mandante']").html(data);
                $("select[name='Mandante']").selectpicker('refresh');
                cargaBusqueda();
                cargarEstrategia();
            },
            error: function(){
            }
        });
    }

    $("select[name='Mandante']").change(function(){
        var ObjectMe = $(this);
        var id = ObjectMe.val();

        $("select[name='Cedente']").val("");
        fillCedentes(id);
        limpiarEstrategias();
    });

    function fillCedentes(mandante){

        var data = "mandante="+mandante;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillCedentes.php",
            data: data,
            dataType: "html",
            success: function(data){
                $("select[name='Cedente']").html(data);
                $("select[name='Cedente']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $("select[name='Cedente']").change(function(){
        var cedente = $("select[name='Cedente']").val();
        limpiarEstrategias();
        if(cedente != ""){
            fillEstrategia(cedente);
        }
    });

    function fillEstrategia(cedente){

        var post = "cedente="+cedente;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillEstrategias.php",
            data: post,
            dataType: "html",
            success: function(data){
                $("select[name='Estrategia']").html(data);
                $("select[name='Estrategia']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $("select[name='Estrategia']").change(function(){
        var estrategia = $("select[name='Estrategia']").val();
        limpiarAsignaciones();
        if(estrategia != ""){
            fillQueue(estrategia);
        }
    });

    function fillQueue(estrategia){

        var post = "estrategia="+estrategia;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillQueues.php",
            data: post,
            dataType: "html",
            success: function(data){
                $("select[name='Queue']").html(data);
                $("select[name='Queue']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $("select[name='Queue']").change(function(){
        var cola = $("select[name='Queue']").val();
        fillAsignacion(cola);
    });

    function fillAsignacion(cola){

        var post = "cola="+cola;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillAsignacion.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                $("select[name='Asignacion']").html(data);
                $("select[name='Asignacion']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    function limpiarAsignaciones(){
        fillQueue("");
        fillAsignacion("");
    }

    function limpiarEstrategias(){
        fillEstrategia("");
        fillQueue("");
        fillAsignacion("");
    }

    function cargaBusqueda(){
        var man = $("select[name='Mandante']").val();
        var ced = $("select[name='Cedente']").val();
        var est = $("select[name='Estrategia']").val();
        var que = $("select[name='Queue']").val();
        var asi = $("select[name='Asignacion']").val();

        var mandante    = (man) ? man : "";
        var cedente     = (ced) ? ced : "";
        var estrategia  = (est) ? est : "";
        var queue       = (que) ? que : "";
        var asignacion  = (asi) ? asi : "";

        console.log("mandante = " + mandante + " cedente= " + cedente);

        $("#resultDiscadorPredictivo").show();
        showAgentes(mandante, cedente, estrategia, queue, asignacion, true);
        showPuestos(mandante, cedente, estrategia, queue, asignacion, true);
    }

    function cargarEstrategia(){
        var man = $("select[name='Mandante']").val();
        var ced = $("select[name='Cedente']").val();
        var est = $("select[name='Estrategia']").val();
        var que = $("select[name='Queue']").val();
        var asi = $("select[name='Asignacion']").val();

        var mandante    = (man) ? man : "";
        var cedente     = (ced) ? ced : "";
        var estrategia  = (est) ? est : "";
        var cola       = (que) ? que : "";
        var asignacion  = (asi) ? asi : "";

        var post = "mandante="+mandante+"&cedente="+cedente+"&estrategia="+estrategia+"&cola="+cola+"&asignacion="+asignacion;

        console.log(post);

        $.ajax({
            type: "POST",
            url: "../includes/supervision/getEstrategiasColas.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_estrategias_colas' ) ) {
                    EstrategiasColasTable.destroy();
                }
            },
            success: function(data){
                console.log(data);

                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    EstrategiasColasTable = $('#mostrar_estrategias_colas').DataTable({
                        data: dataSet,
                        columns: [
                            { data: 'mandante' },
                            { data: 'cedente' },
                            { data: 'estrategia' },
                            { data: 'cola' },
                            { data: 'nombre', width: "13%" },
                            { data: 'marcacion' },
                            { data: '' },
                            { data: 'casos' },
                            { data: 'barridos' },
                            { data: '' },
                            { data: 'contactabilidad' },
                            { data: 'penetracion' }
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": [0,1,2,3],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": [4,5,7,8],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>" + data + "</div>";
                                }
                            },{
                                className: "dt-center",
                                "targets": [10,11],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>" + data + "%</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 6,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'></div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 9,
                                "render": function( data, type, row ) {
                                    var pctj;
                                    if(Number(row.casos) !== 0){
                                        var div = ((Number(row.barridos) * 100) / Number(row.casos)).toFixed(2);
                                        pctj = Math.ceil(div);
                                    }else{
                                        pctj = 0;
                                    }
                                    return "<div style='text-align: center;'>" + pctj + "%</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 12,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;' id='" + row.idcola + "," + row.cola +"'><i class='fa fa-pie-chart btn btn-primary btn-icon icon-lg verGestion' style='cursor: pointer;'></i></div>";
                                }
                            }
                        ]
                    });
                }
            },
            error: function(){
            }
        });        
    }

    $(document).on('click', '.verGestion', function(){
        var ObjectI = $(this);
        var ObjectDIV = ObjectI.closest("div");
        var colaID = ObjectDIV.attr("id");

        var arreglo = colaID.split(",");
        var post = "idcola="+arreglo[0]+"&label="+arreglo[1];

        console.log(post);

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

    $(document).on('click', '#buscar_agentes_puestos', function(){
        cargaBusqueda();
        cargarEstrategia();
    });

    function showAgentes(mandante, cedente, estrategia, queue, asignacion, Modal){
        var cartera = "mandante="+mandante+"&cedente="+cedente+"&estrategia="+estrategia+"&queue="+queue+"&asignacion="+asignacion;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/getAgentes.php",
            data: cartera,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_agentes_estado' ) ) {
                    AgentesTable.destroy();
                    myChart.destroy();
                }

                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    grafico(dataSet);

                    AgentesTable = $('#mostrar_agentes_estado').DataTable({
                        data: dataSet,
                        "searching": false,
                        "paging": false,
                        "bInfo" : false,
                        columns: [
                            { data: 'estatus' },
                            { data: 'cantidad', width: "30%" }
                        ],
                        "columnDefs": [ 
                            {
                                "targets": 0,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center; vertical-align: middle'><span style='float: left; inline-size: auto'><button id='"+row.estatus+"' class='btn iconos'><i class='"+row.icono+"' style='background-color: "+row.color+"; width: 40px; height: 30px; text-align: center; padding: 5px 0px; font-size: 20px; color: #FFFFFF;'></i></button>"+data+"</span></div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 1,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            }
                        ]
                    });
                    $("#mostrar_agentes_estado").trigger('update');
                }
            },
            error: function(){
            }
        });
        
    }

    $("body").on("click",".iconos",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var estatus = ObjectMe.attr("id");

        var man = $("select[name='Mandante']").val();
        var ced = $("select[name='Cedente']").val();
        var est = $("select[name='Estrategia']").val();
        var que = $("select[name='Queue']").val();
        var asi = $("select[name='Asignacion']").val();

        var mandante    = (man) ? man : "";
        var cedente     = (ced) ? ced : "";
        var estrategia  = (est) ? est : "";
        var queue       = (que) ? que : "";
        var asignacion  = (asi) ? asi : "";
        
        var puestos = "mandante="+mandante+"&estatus="+estatus+"&cedente="+cedente+"&estrategia="+estrategia+"&queue="+queue+"&asignacion="+asignacion;

        createDialog(estatus);
        fillDialog(puestos);
    });

    function showPuestos(mandante, cedente, estrategia, queue, asignacion, Modal){
        var cartera = "mandante="+mandante+"&cedente="+cedente+"&estrategia="+estrategia+"&queue="+queue+"&asignacion="+asignacion;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/getPuestos.php",
            data: cartera,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_puestos_trabajo' ) ) {
                    PuestosTable.destroy();
                }

                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    PuestosTable = $('#mostrar_puestos_trabajo').DataTable({
                        data: dataSet,
                        bInfo: false,
                        columns: [
                            { data: 'anexo', width: "10%" },
                            { data: 'ejecutivo', width: "30%" },
                            { data: 'estatus', width: "20%" },
                            { data: 'pausa', width: "10%" },
                            { data: 'tiempo', width: "20%" },
                            { data: '', width: "10%" },
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
                                    return "<div style='text-align: center; color: #FFFFFF; font-weight: bold; background-color: "+ArrayColorEstado[data]+"'>"+data+"</div>";
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
                                "targets": 5,
                                "render": function( data, type, row ) {
                                    return "<button class='fa fa-trash btn btn-danger btn-icon icon-lg delete' id='"+row.anexo+"'></button>";
                                }
                            }
                        ]
                    });
                }
            },
            error: function(){
            }
        });
    }
    
    $("body").on("update","#mostrar_agentes_estado",function(){
        UpdateTotalFoot();
    });

    function UpdateTotalFoot(){
        var cont = 0;
        AgentesTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            cont += Number(data.cantidad);
        });
        $("#totalAgentes").html(cont);
    }

    $("body").on("click",".delete",function(){
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
                cargaBusqueda();
            },
            error: function(data){
                console.log("Error: " + data);
            }
        });
    });

    function grafico(data){

        var ArrayEstadoAgente = [];
        var ArrayCantidadAgentes = [];
        var ArrayColoresPie = [];
        var ArrayHColoresPie = [];

        for (var i in data){
            var value = data[i];

            var leyenda = value.estatus;
            ArrayEstadoAgente.push(leyenda);

            var cantidadAgentes = Number(value.cantidad);
            ArrayCantidadAgentes.push(cantidadAgentes);

            var colorPie = value.color;
            ArrayColoresPie.push(colorPie);

            var hColorPie = value.hcolor;
            ArrayHColoresPie.push(hColorPie);

            ArrayColorEstado[leyenda] = colorPie;
        }

        console.log(ArrayHColoresPie);
        console.log(ArrayColorEstado);
        var idChart = document.getElementById("myChart");

        myChart = new Chart(idChart, {
            type: "pie",
            data: {
                labels: ArrayEstadoAgente,
                datasets: [{
                    data: ArrayCantidadAgentes,
                    backgroundColor: ArrayColoresPie,
                    hoverBackgroundColor: ArrayHColoresPie,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    position: 'left',
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

    chart.onclick = function(evt) {
      var activePoints = myChart.getElementsAtEvent(evt);
      if (activePoints[0]) {
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];

        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];

        console.log(label + " - " + value);

        var man = $("select[name='Mandante']").val();
        var ced = $("select[name='Cedente']").val();
        var est = $("select[name='Estrategia']").val();
        var que = $("select[name='Queue']").val();
        var asi = $("select[name='Asignacion']").val();

        var mandante    = (man) ? man : "";
        var cedente     = (ced) ? ced : "";
        var estrategia  = (est) ? est : "";
        var queue       = (que) ? que : "";
        var asignacion  = (asi) ? asi : "";
        
        var puestos = "mandante="+mandante+"&estatus="+label+"&cedente="+cedente+"&estrategia="+estrategia+"&queue="+queue+"&asignacion="+asignacion;

        createDialog(label);
        fillDialog(puestos);
      }
    }

    function createDialog(label){
        var ModalPuestosTrabajo = $("#modalPuestosTrabajo").html();
        bootbox.dialog({
            title: label,
            message: ModalPuestosTrabajo,
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

    function fillDialog(puestos){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getPuestosTrabajo.php",
            data: puestos,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#puestosTrabajo' ) ) {
                    PuestosTrabajoTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    PuestosTrabajoTable = $('#puestosTrabajo').DataTable({
                        data: dataSet,
                        searching: false,
                        paging: false,
                        bInfo: false,
                        columns: [
                            { data: 'anexo', width: "20%" },
                            { data: 'ejecutivo', width: "40%" },
                            { data: 'tiempo', width: "20%" },
                            { data: 'pausa', width: "20%" },
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
                                "targets": [2,3],
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>"+data+"</div>";
                                }
                            }
                        ]
                    });
                }
            },
            error: function(){
            }
        });
    }

    setInterval(function(){
        if($("select[name='Mandante']").val() != ""){
            cargaBusqueda();
            cargarEstrategia();
        }
    }, 30000);
});
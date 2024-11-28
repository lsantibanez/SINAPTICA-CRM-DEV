$(document).ready(function(){

    var GestionTable;
    var myChartGestion;
    var myChartNivel2;
    var myChartNivel3;

    var chartGestion = document.getElementById("myChartNivel1");
    var chartGestionNivel2 = document.getElementById("myChartNivel2");

    fillNivel1();

    function fillNivel1(){
        var cola = $('#cola').val();
        console.log("ID " + cola);
        llenarGestionNivel1(cola);
    }

    function llenarGestionNivel1(cola){
        var post = "cola="+cola;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getGestionCola.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    if (typeof data !== 'undefined' && dataSet.length > 0) {
                        $('#div_gestiones').show();
                        graficoGestion(dataSet);
                    }else{
                        $('#no_data').show();
                    }
                }
            }
        });
    }

    function graficoGestion(data){
        var ArrayCantidades = [];
        var ArrayLabels = [];

        for (var i in data){
            var value = data[i];

            var cantidadAgentes = Number(value.cantidad);
            ArrayCantidades.push(cantidadAgentes);

            var leyenda = value.label;
            ArrayLabels.push(leyenda);
        }

        console.log(ArrayLabels);
        var idChart = document.getElementById("myChartNivel1");
        idChart.height = 380;

        myChartGestion = new Chart(idChart, {
            type: "pie",
            data: {
                labels: ArrayLabels,
                datasets: [{
                    data: ArrayCantidades,
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
        return myChartGestion;
    }

    function llenarGestionNivel2(cola, nivel1){
        $('#nivel1').val(nivel1);
        var post = "cola="+cola+"&nivel1="+nivel1;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getGestionColaNivel2.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    graficoGestionNivel2(dataSet);
                }
            }
        });
    }

    function graficoGestionNivel2(data){
        var ArrayCantidades = [];
        var ArrayLabels = [];

        for (var i in data){
            var value = data[i];

            var cantidadAgentes = Number(value.cantidad);
            ArrayCantidades.push(cantidadAgentes);

            var leyenda = value.label;
            ArrayLabels.push(leyenda);
        }

        console.log(ArrayLabels);
        var idChart = document.getElementById("myChartNivel2");
        idChart.height = 380;

        myChartNivel2 = new Chart(idChart, {
            type: "pie",
            data: {
                labels: ArrayLabels,
                datasets: [{
                    data: ArrayCantidades,
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.5)', 
                        'rgba(255, 159, 64, 0.5)', 
                        'rgba(34, 153, 84, 0.5)',
                        'rgba(66, 73, 73, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(120, 40, 31, 0.5)', //  255, 206, 86
                        'rgba(183, 149, 11, 0.5)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(34, 153, 84, 1)',
                        'rgba(66, 73, 73, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(120, 40, 31, 1)',
                        'rgba(183, 149, 11, 1)',
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
        return myChartNivel2;
    }

    function llenarGestionNivel3(cola, nivel2){
        var nivel1 = $('#nivel1').val();
        $('#nivel2').val(nivel2);

        var post = "cola="+cola+"&nivel1="+nivel1+"&nivel2="+nivel2;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getGestionColaNivel3.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    graficoGestionNivel3(dataSet);
                }
            }
        });
    }

    function graficoGestionNivel3(data){
        var ArrayCantidades = [];
        var ArrayLabels = [];

        for (var i in data){
            var value = data[i];

            var cantidadAgentes = Number(value.cantidad);
            ArrayCantidades.push(cantidadAgentes);

            var leyenda = value.label;
            ArrayLabels.push(leyenda);
        }

        console.log(ArrayLabels);
        var idChart = document.getElementById("myChartNivel3");
        idChart.height = 380;

        myChartNivel3 = new Chart(idChart, {
            type: "pie",
            data: {
                labels: ArrayLabels,
                datasets: [{
                    data: ArrayCantidades,
                    backgroundColor: [
                        'rgba(183, 149, 11, 0.5)',
                        'rgba(255, 159, 64, 0.5)', 
                        'rgba(34, 153, 84, 0.5)',
                        'rgba(66, 73, 73, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(120, 40, 31, 0.5)', //  255, 206, 86
                        'rgba(153, 102, 255, 0.5)' 
                    ],
                    hoverBackgroundColor: [
                        'rgba(183, 149, 11, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(34, 153, 84, 1)',
                        'rgba(66, 73, 73, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(120, 40, 31, 1)',
                        'rgba(153, 102, 255, 1)'
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
        return myChartNivel3;
    }

    chartGestion.onclick = function(evt) {
      var activePoints = myChartGestion.getElementsAtEvent(evt);
      if (activePoints[0]) {
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];

        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var cola = $('#cola').val();

        console.log(label + " - " + value + " - " + cola);

        if(myChartNivel2){
            myChartNivel2.destroy();
            $('#nivel2').val("");
            if(myChartNivel3){
                myChartNivel3.destroy();
            }
        }
        $('#span_Nivel2').hide();
        llenarGestionNivel2(cola, label);
        actualizaTablaGestion();
      }
    }

    chartGestionNivel2.onclick = function(evt) {
      var activePoints = myChartNivel2.getElementsAtEvent(evt);
      if (activePoints[0]) {
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];

        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var cola = $('#cola').val();

        console.log(label + " - " + value + " - " + cola);

        if(myChartNivel3){
            myChartNivel3.destroy();
        }
        $('#span_Nivel3').hide();
        llenarGestionNivel3(cola, label);
        actualizaTablaGestion();
      }
    }

    function actualizaTablaGestion(){
        var cola = $('#cola').val();
        var nivel1 = $('#nivel1').val();
        var nivel2 = $('#nivel2').val();

        var post = "cola="+cola+"&nivel1="+nivel1+"&nivel2="+nivel2;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/getGestiones.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#gestionExport' ) ) {
                    GestionTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    GestionTable = $('#gestionExport').DataTable({
                        data: dataSet,
                        columns: [
                            { data: 'cola', width: "15%" },
                            { data: 'rut', width: "10%" },
                            { data: 'nivel1', width: "20%" },
                            { data: 'nivel2', width: "20%" },
                            { data: 'nivel3', width: "20%" },
                            { data: 'fecha', width: "10%" },
                            { data: '', width: "5%" }
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
                                    return "<div style='text-align: center;'><i class='fa fa-file-excel-o fa-lg downloadGestion' style='cursor: pointer;'></i></div>";
                                }
                            }
                        ]
                    });

                    $('#gestionTablePanel').show();
                }
            }
        });
    }

    $(document).on('click', '.downloadGestion', function(){
        downloadReporteGestion();
    });

    function downloadReporteGestion(){
        var cola = $('#cola').val();
        var nivel1 = $('#nivel1').val();
        var nivel2 = $('#nivel2').val();

        var post = "cola="+cola+"&nivel1="+nivel1+"&nivel2="+nivel2;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/downloadReporteGestion.php",
            dataType: "html",
            data: post,
            success: function(data){
                console.log(data);
                var json = JSON.parse(data);
                var $a = $("<a id='AnclaTemp'>");
                $a.attr("href",json.file);
                $a.attr("download",json.filename+".xlsx");
                $("body").append($a);
                $("#AnclaTemp")[0].click();
                $("#AnclaTemp").remove();
            },
            error: function(){
            }
        });
    }
});
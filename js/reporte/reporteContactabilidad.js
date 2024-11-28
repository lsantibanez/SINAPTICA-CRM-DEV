$(document).ready(function(){
    
    var myChart;
    var myChartContacto;
    var myChartSegmento;
    var formatNumber = {
        separador: ".",
        sepDecimal: ',',
        formatear:function (num){
        num +='';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft +splitRight;
        },
        new:function(num, simbol){
            this.simbol = simbol ||'';
            return this.formatear(num);
        }
    }

    $("#titulos").hide();

    mostrarCampos();

    $("#Campo").change(function(){
        var Campo = $(this).val();
        if(Campo != ""){
            mostrarValoresCampos(Campo);
        }
    });
    $("#ver").click(function(){
        var segmento = $("#segmento").val();
        var Campo = $("#Campo").val();
        $("#titulos").show();
        mostrarSegmento(segmento,Campo);
        mostrarCasos(segmento,Campo);   
    });
    function grafico(ArrayNombres,ArrayCantidades,ArrayId,idChart,tipoGrafico){
        //var ctx = document.getElementById("myChart");
        var ctx = document.getElementById(idChart);
        var Width = $("#"+idChart).closest("div").width();
        //alert(Width);
        myChart = new Chart(ctx, {
            type: tipoGrafico,           
            data: {
                //labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                labels: ArrayNombres,
                otro: ArrayCantidades,
                id : ArrayId,
                datasets: [{
                    label: ArrayNombres,
                    porcentajes : ArrayId,
                    //data: [12, 19, 3, 5, 2, 3],
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
                legend: {
                    display: false,
                    position: 'left',
                    reverse: 'true',
                    fullWidth: false,
                    labels: {
                        //fontColor: 'rgb(255, 70, 132)'
                    },
                    legendCallback: function(chart) {
                        return "fdfdfdf";
                    }
                }
            }
        });
        return myChart;
    }
    function mostrarLeyenda(grafico,Leyenda,numerico){
        $("#"+Leyenda).find(".list-group").html('');
        console.log(grafico);
        var config = grafico['config'];
        config = config['data']; 
        config = config['datasets'];
        config = config[0];
        var label = config['label'];
        var datos = config['data'];
        var color = config['backgroundColor'];
        var porcentaje = config['porcentajes'];
        for (var indice in label){
            var Value = label[indice];
            var valor = datos[indice];
            if (numerico == 1){
                var valor = formatNumber.new(datos[indice], "$");
            }
            var valor = formatNumber.new(datos[indice]);
            $("#"+Leyenda).find(".list-group").append("<a class='list-group-item' style='background-color: "+color[indice]+"' href='#'>"+label[indice]+": "+valor+"<br><p style='background-color: #FFFF66; display: inline-block; padding: 2px 5px'> % "+porcentaje[indice]+"</p></a>");
        } 
    }
    function mostrarSegmento(segmento,Campo){
        data = {segmento:segmento, Campo: Campo};
        $.ajax({
            type:"POST",
            data: data,
            url: "../includes/reporteria/GetTotalCompromiso.php",
            async: false,
            success:function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    var ArrayNombreTipoGestion = [];
                    var ArrayMontoGestion = [];
                    var ArrayPorcentajes = [];
                    for (var indice in data){
                        var Value = data[indice];
                        var datosLegen = Value.nombre;
                        ArrayNombreTipoGestion.push(datosLegen);
                        var valor = formatNumber.new(Value.monto);
                        ArrayMontoGestion.push(Value.monto);
                        ArrayPorcentajes.push(Value.porcentaje);
                    }
                    if (typeof myChartSegmento != "undefined"){
                        myChartSegmento.destroy();
                    }
                    myChartSegmento = grafico(ArrayNombreTipoGestion,ArrayMontoGestion,ArrayPorcentajes,"myChart5","pie");
                    mostrarLeyenda(myChartSegmento,"leyendaMontos",1);
                }
            },
            error:function(){
            }
            
        });   

          
    }
    function mostrarCasos(segmento,Campo){
        data = {segmento:segmento, Campo: Campo}; 
        $.ajax({
            type:"POST",
            data: data,
            url: "../includes/reporteria/GetTotaCasosCompromiso.php",
            success:function(data){
                data = JSON.parse(data);
                var ArrayNombreTipoGestion = [];
                var ArrayCantidadGestion = [];
                var ArrayPorcentajes = [];
                for (var indice in data){
                    var Value = data[indice];
                    ArrayNombreTipoGestion.push(Value.nombre);
                    ArrayCantidadGestion.push(Value.cantidad);
                    ArrayPorcentajes.push(Value.porcentaje);
                }
                if(typeof myChartContacto != "undefined"){
                    myChartContacto.destroy();
                } 
                myChartContacto = grafico(ArrayNombreTipoGestion,ArrayCantidadGestion,ArrayPorcentajes,"myChart","pie");
                mostrarLeyenda(myChartContacto,"leyendaMontos2",2);
            },
            error:function(){
            }
        });
    }
    function mostrarCampos(){
        $.ajax({
            type:"POST",
            dataType: "html",
            url: "../includes/reporteria/getCamposDeuda.php",
            async: false,
            success:function(data){
                $("#Campo").html(data);
                $("#Campo").selectpicker("refresh");
            },
            error:function(){
            }
        });
    }
    function mostrarValoresCampos(Campo){
        $.ajax({
            type:"POST",
            data:{
                Campo: Campo
            },
            dataType: "html",
            url: "../includes/reporteria/getValoresCampo.php",
            async: false,
            success:function(data){
                $("#segmento").html(data);
                $("#segmento").selectpicker("refresh");
            },
            error:function(){
            }
        });
    }
});
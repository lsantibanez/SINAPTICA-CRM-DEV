$(document).ready(function() {
    var ctx = document.getElementById("Grafico").getContext('2d');    
    var myChart = new Chart(ctx, {});
    var varMandante = '';
    var varIdAgente = '';
    function selectMandante(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/supervisionCartera/selectMandante.php",
            data: "",
            success: function(response){
                $('#divMandante').html(response);
                $('#selectMandante').selectpicker();
            }	
        }); 
    }

    function agentesCall(varMandante){
        if(varMandante==''){
            $('#Tabla').html("");
        }else{
            $.ajax({
                type: "POST",
                url: "../includes/reporte/supervisionCartera/mostrarTabla.php",
                data: "varMandante="+varMandante,
                success: function(response){
                    console.log(response);
                    if(response==0){
                        $("#ocultar").hide();
                        
                        $.niftyNoty({
                            type: 'danger',
                            icon : 'fa fa-close',
                            message : "No hay Agentes para este Mandante" ,
                            container : 'floating',
                            timer : 2000
                        });
                    }else{
                        $("#divLoading1").hide();
                        $('#Tabla').html(response);
                        graficoAgentes(varMandante);                        
                    }
                }	
            }); 
        }
    }
    
    function puestosTrabajo(varIdAgente,varMandante){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/supervisionCartera/puestosTrabajo.php",
            data: "varIdAgente="+varIdAgente+"&varMandante="+varMandante,
            success: function(response){
                if(response==0){
                    $("#divLoading2").hide();
                    
                }else{
                    $("#divLoading2").hide();
                    $('#Tabla2').html(response);                    
                }
            }	
        }); 
    }

    function campanasTabla(varMandante){
        var data = "varMandante="+varMandante;
        $.ajax({
            type: "POST",
            url: "../includes/reporte/supervisionCartera/campanasTabla.php",
            data: data,
            success: function(response){
                $('#Tabla3').html(response);
            }	
        }); 
    }

    
    function graficoAgentes(varMandante){
        $('#Grafico').empty().append('<canvas></canvas>');
        var arrayData = [];
        var arrayCantidad = [];
        var arrayLabels = [];
        var arrayColor  = [];
        arrayColor['danger']='rgba(215, 44, 44, 0.9)';
        arrayColor['warning']='rgba(196, 145, 50, 1)';
        arrayColor['primary']='rgba(93, 188, 210, 1)';
        arrayColor['success']='rgba(70, 163, 94, 1)';
        arrayColor['dead']='rgba(16, 2, 0, 1)';
        var arrayColores = [];
        $.ajax({
            type: "POST",
            url: "../includes/reporte/supervisionCartera/mostrarTablaGrafico.php",
            data: "varMandante="+varMandante,
            success: function(response){
                arrayData = jQuery.parseJSON(response);
                console.log(response);
                console.log(arrayData);
                $.each(arrayData,function(index,contenido){
                    console.log(index);
                    arrayLabels.push(index);
                    arrayCantidad.push(contenido);
                    switch(index) {
                        case 'DISPONIBLE':
                            arrayColores.push(arrayColor['danger']);
                            break;
                        case 'INCALL':
                            arrayColores.push(arrayColor['success']);
                            break;
                        case 'PAUSED':
                        arrayColores.push(arrayColor['warning']);                        
                            break;
                            
                        case 'DEAD':
                        arrayColores.push(arrayColor['dead']);                        
                            break;

                    } 
                    
                });
                console.log(arrayColores);
                
                var ctx = document.getElementById("Grafico").getContext('2d');
                
                myChart.destroy();
                
                myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: arrayLabels,
                        datasets: [{
                            label: '# of Votes',
                            data: arrayCantidad,
                            backgroundColor: arrayColores,
                            borderColor: arrayColores,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                    }
                });
                
            }	
        }); 
    }

    $(document).on("change","#selectMandante" , function(){
        varMandante =  $('#selectMandante').val();
        campanasTabla(varMandante);
        
        if(varMandante==0){
            $("#ocultar").hide();
            $("#ocultarPuestos").hide(1000);
        }else{
            $("#ocultarPuestos").hide(1000);            
            $("#divLoading1").show();
            setTimeout(function(){
                agentesCall(varMandante);           
            }, 500);
            graficoAgentes();
            $("#ocultar").fadeIn(1000);
            $('#Tabla').html("");
            $('#tablaCampana').DataTable({
                "scrollX": true,
                "bSort": false
            });

        }
    });

    $(document).on("click",".linkAgente" , function(){
        $("#ocultarPuestos").fadeIn(1000);
        
        var varIdAgente = $(this).closest('tr').attr('id');
        $('#Tabla2').html("");                    
        
        $("#divLoading2").show();
        setTimeout(function(){
            puestosTrabajo(varIdAgente,varMandante);            
        }, 500);
    });
  
    selectMandante();
    agentesCall(varMandante);
    puestosTrabajo(varIdAgente,varMandante);
});

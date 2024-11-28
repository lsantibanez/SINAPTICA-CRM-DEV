$(document).ready(function(){
    getMandantes();
    //funcionMostrarInforme();
    setInterval(function(){
        funcionMostrarInforme();
    },4000);

    $("body").on("change","select[name='Mandante']",function(){
        var Mandante = $(this).val();
        getCedentes(Mandante);
        funcionMostrarInforme();
    });
    $("body").on("change","select[name='Cedente']",function(){
        funcionMostrarInforme();
    });

    function funcionMostrarInforme(){
        var Mandante = $('select[name="Mandante"]').val();
        var Cedente = $('select[name="Cedente"]').val();
        $.ajax({            
            type: "POST",
            url: "../includes/crm/mostrarInforme.php",
            data:{
                Mandante: Mandante,
                Cedente: Cedente
            },
            async: false,
            success: function(response){
                $('#mostrarInforme').html(response);
            },
            error: function(response){
            }
        });
    }
    function getMandantes(){
        $.ajax({            
            type: "POST",
            url: "../includes/predictivo/listaMandantes.php",
            async: false,
            success: function(response){
                $('select[name="Mandante"]').html(response);
                $('select[name="Mandante"]').selectpicker("refresh");
            },
            error: function(response){
            }
        });
    }
    function getCedentes(Mandante){
        $.ajax({            
            type: "POST",
            url: "../includes/predictivo/listaCedentes.php",
            data:{
                Mandante: Mandante
            },
            async: false,
            success: function(response){
                $('select[name="Cedente"]').html(response);
                $('select[name="Cedente"]').selectpicker("refresh");
            },
            error: function(response){
            }
        });
    }
});
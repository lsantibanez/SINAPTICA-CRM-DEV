$(document).ready(function($){
    let counter = 10;
    let contador = null;
    const countdown = () => {
        if (counter > 0) {
            $("#countdown").html("Actualizar en: <strong>" + counter + "</strong> segundos");
            counter--;
        } else {
            //fetchAndUpdateTable();
            clearInterval(contador);
            contador = null;
            $("#countdown").text("Actualizando...");
            getAgentsStatus();
            setTimeout(() => {
                counter = 10;
                contador = setInterval(countdown, 1000);
            }, 2000);            
        }
    };
    contador = setInterval(countdown, 1000);    
});

function getAgentsStatus()
{
    $.ajax({
        type:"GET",
        url:"../monitor/agents.php?update=true",
        dataType: 'html',
        success: function(datos) {
            $('table#agentsLists > tbody').html(datos);
        },
        error: function () {
            console.error('Se ha presentado un error.');
        }
    });
}
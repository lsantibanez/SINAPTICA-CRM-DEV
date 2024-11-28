$(document).ready(function($){
    let counter = 15;
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
                counter = 15;
                contador = setInterval(countdown, 1000);
            }, 2000);            
        }
    };
    contador = setInterval(countdown, 1000);  
    
    $('button#btnFind').click(function (e) {
        e.preventDefault();
        getAgentsStatus();
    });
});

function getAgentsStatus()
{
    const fecha = $('input#fechaGestion').val();
    $.ajax({
        type: 'GET',
        url: '/monitor/agent-campaign.php?update=true&fecha='+fecha,
        dataType: 'html',
        success: function(datos) {
            $('table#agentsLists > tbody').html(datos);
        },
        error: function () {
            console.error('Se ha presentado un error.');
        }
    });
}
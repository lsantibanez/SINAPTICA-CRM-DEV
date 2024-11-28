$(document).ready(function(){
    $("#carterizacionFinal").click(function(){
         
        descargaCarterizacionMes();

    });
    function descargaCarterizacionMes(){
        window.location = "../includes/tareas/decargaCarterizacionFinal.php";
    }
});
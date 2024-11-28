$(document).ready(function(){
    $("#Download").click(function(){
        descargarAsignacion();
    });
    function descargarAsignacion(){
        window.location = "../includes/tareas/descargarAsignacion.php";
    }
});
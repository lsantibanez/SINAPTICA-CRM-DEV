
$(document).ready(function() {

    var idUsuario = $("#idUsuario").val();
    var nombreLogo = "CRM Sinaptica";


    getAsignacion(idUsuario);
    $('#tabla_clientes').DataTable({});
    $('#tabla_gestiones').DataTable({
        
        "order": [[ 0, "desc" ]],
        "autoWidth": true,
        "columnDefs": [{ "width": "20%", "targets": 7 }], 
        fixedHeader: {
            header: true,
            footer: true
        }
    });
    

    function getAsignacion(idUsuario){
        $.ajax({
            type: "POST",
            url: "../includes/clientes/getAsignacion.php",
            data:"idUsuario="+idUsuario,
            success: function(response){
                $('#table-asignacion').html(response)
                $('#tabla_asignacion').DataTable({
                    "order": [[ 3, "desc" ]],
                    fixedHeader: {
                        header: true,
                        footer: true
                    }
                });
            } 
        });
    }
    
});

$(document).ready(function(){

    var OficinasTable;
    var dataSet = {};
    
    getOficinas();

    function getOficinas(){
        $.ajax({
            type: "POST",
            url: "../includes/derivaciones/getOficinas.php",
            data: {
                idMandante: GlobalData.id_mandante
            },
            async: false,
            success: function (data) {
                if(isJson(data)){
                    dataSet = JSON.parse(data);
                    console.log(dataSet);
                    showOficinas();
                }
            },
            error: function (data) {
            }
        });
    }
    function showOficinas(){
        OficinasTable = $('#tablaOficinas').DataTable({
            data: dataSet,
            bDestroy: true,
            columns: [
                { data: 'cod' },
                { data: 'Nombre' },
                { data: 'JefeZonal' },
                { data: 'Direccion' },
                { data: 'Zona' },
                { data: 'EjecutivoNormalizacion' },
                { data: 'Correo' },
                { data: 'Telefono' },
                { data: 'AgenteSucursal' },
                { data: 'CorreoAgente' },
                { data: 'TelefonoAgente' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 11,
                    "data": 'Accion',
                    "visible": false,//Modificar DESPUES
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+row.id+"'><i style='cursor: pointer;' class='fa fa-pencil AddAfirmaciones'></i></div>";
                    }
                },
            ]
        });
    }
});
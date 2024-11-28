$(document).ready(function () {
    GetProcedimientos();

    function GetProcedimientos(){
        $.ajax({
            type: "POST",
            url: "../includes/procedimientos/GetProcedimientos.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    dataSet = JSON.parse(data);
                    ProcedimientosTable = $('#ProcedimientosTable').DataTable({
                        data: dataSet,
                        destroy: true,
                        columns: [
                            { data: 'EVENT_NAME' },
                            { data: 'LAST_EXECUTED' },
                            { data: 'USER_LAST_EXECUTED' },
                            { data: 'STATUS' },
                            { data: 'ID' }
                        ],
                        "createdRow": function (row, data, index) {
                            if (data.STATUS) {
                                color = 'red'
                            } else {
                                color = 'green'
                            }
                            $(row).find('td:eq(3)').css('background-color', color);
                        },
                        "columnDefs": [
                            {
                                "targets": 3,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    
                                    return "<div style='text-align: center;></div>";
                                }
                            }, {
                                "targets": 4,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;'><i id=" + data + " style='font-size: 15px; cursor:pointer' class='fa fa-play'></i></div>";
                                }
                            }
                        ]
                    });
                }
            }
        });
    }
    $(document).on('click', '.fa-play', function () {
        ID = $(this).attr('id')
        bootbox.confirm("¿Esta seguro que desea ejecutar este procedimiento?", function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "../includes/procedimientos/RunProcedimiento.php",
                    data: {
                        ID: ID
                    },
                    dataType: "json",
                    beforeSend: function () {
                        $('#Cargando').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    },
                    success: function (data) {
                        $('#Cargando').modal('hide');
                        setTimeout(() => {
                            if (data.result) {
                                bootbox.alert("Se ha ejecutado exitosamente el procedimiento almacenado.");
                                GetProcedimientos();
                            } else {
                                bootbox.alert("Este procedimiento almacenado ya esta siendo ejecutado actualmente por " + data.usuario);
                            }
                        }, 500);
                    }
                });
            }
        });       
    });
});
$(document).ready(function () {
    $.ajax({
        type: "POST",
        url: "../includes/reporteria/GetReportesOperativoCartera.php",
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
                Table = $('#Table').DataTable({
                    data: dataSet,
                    "bDestroy": true,
                    columns: [
                        { data: 'nombre' },
                        { data: 'url' }
                    ],
                    "columnDefs": [
                        {
                            "targets": 0,
                            "searchable": false,
                            "data": "Accion",
                            "render": function (data, type, row) {
                                return "<div style='text-align: center;'>"+data+"</div>";
                            }
                        },
                        {
                            "targets": 1,
                            "searchable": false,
                            "data": "Accion",
                            "render": function (data, type, row) {
                                return "<div style='text-align: center;'><a href='" + data +"' target='_blank'><i style='font-size: 15px' class='fa fa-download'></i></a></div>";
                            }
                        },
                    ]
                });
            }
        }
    });
});
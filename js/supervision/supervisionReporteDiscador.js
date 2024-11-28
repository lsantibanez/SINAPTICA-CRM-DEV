$(document).ready(function(){

    var MembersTable;
    var CallersTable;
    var QueueAnexoTable;

    fillAnexos();
    fillListas();

    function fillAnexos(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillAnexos.php",
            data: { },
            dataType: "html",
            success: function(data){
                $("select[name='Anexo']").html(data);
                $("select[name='Anexo']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $(document).on('click', '#buscar_anexo', function(){

        var anexo = $("select[name='Anexo']").val();
        var post = "anexo="+anexo;
        
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getAnexos.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                var anx = JSON.parse(data);

                $("#status").val(anx.Status);
                $("#useragent").val(anx.Useragent);
                $("#regContact").val(anx["Reg. Contact"]);
                findQueues(anexo);
            },
            error: function(){
            }
        });
    });

    function findQueues(anexo){
        var post = "anexo="+anexo;
        $.ajax({
            type: "POST",
            url: "../includes/supervision/findQueues.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_queue_anexo' ) ) {
                    QueueAnexoTable.destroy();
                }
            },
            success: function(data){
                console.log(data);

                var queues = JSON.parse(data);

                QueueAnexoTable = $('#mostrar_queue_anexo').DataTable({
                    data: queues,
                    bInfo: false,
                    columns: [
                        { data: 'queue' },
                        { data: '' },
                        { data: '' },
                    ],
                    "columnDefs": [
                        {
                            className: "dt-center",
                            "targets": 0,
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;'>"+data+"</div>";
                            }
                        }, 
                        {
                            className: "dt-center",
                            "targets": 1,
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;'>"+anexo+"</div>";
                            }
                        },
                        {
                            className: "dt-center",
                            "targets": 2,
                            "render": function( data, type, row ) {
                                return "<i id='"+row.queue+":"+anexo+"' style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg delete'></i>"
                                // return "<button class='btn btn-danger btn-xs delete' id='"+row.queue+":"+anexo+"'><i class='fa fa-minus-square fa-lg'></i></button>";
                            }
                        }
                    ]
                });
            },
            error: function(){
            }
        });
    }

    function fillListas(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillListas.php",
            data: {},
            dataType: "html",
            success: function(data){
                $("select[name='Lista']").html(data);
                $("select[name='Lista']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $(document).on('click', '#buscar_lista', function(){

        var lista = $("select[name='Lista']").val();
        var post = "lista="+lista;

        $.ajax({
            type: "POST",
            url: "../includes/supervision/getListas.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#mostrar_miembros' ) ) {
                    MembersTable.destroy();
                }

                if ( $.fn.dataTable.isDataTable( '#mostrar_llamadas' ) ) {
                    CallersTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                var lst = JSON.parse(data);

                MembersTable = $('#mostrar_miembros').DataTable({
                    data: lst.membersArray,
                    bInfo: false,
                    columns: [
                        { data: 'member' },
                    ]
                });

                CallersTable = $('#mostrar_llamadas').DataTable({
                    data: lst.callersArray,
                    bInfo: false,
                    columns: [
                        { data: 'caller' },
                    ]
                });
            },
            error: function(){
            }
        });
    });

    $("body").on("click",".delete",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var id = ObjectMe.attr("id");
        var res = id.split(":");
        var post = "puesto="+res[1]+"&queue="+res[0];

        console.log(post);

        $.ajax({
            type: "POST",
            url: "../includes/supervision/deletePuestoByQueue.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                findQueues(res[1]);
            },
            error: function(data){
                console.log("Error: " + data);
            }
        });
    });
});
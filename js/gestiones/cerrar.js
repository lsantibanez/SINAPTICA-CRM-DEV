
$(document).ready(function(){
    var RecordTable;
    var dataSet = [];
    BuscarGrabaciones();
    $('.derivar').hide();

    $('[data-toggle="tooltip"]').tooltip();
    
    $("body").on("click",".changeStatus",function(){
        var data = {
            id : $(this).data('ref'),
            observacion : $(this).parent().parent().find('.observacion').val(),
            rut : $(this).parent().parent().find('.rut').html(),
            nivel1: 'CORREO',
            nivel2: 'CORREO ENVIADO',
            nivel3: 'CON COMPROMISO',
            r1: 14,
            r2: 117,
            r3: 269
        }
        
       $.ajax({
            type: "POST",
            url: "../includes/gestiones/cambiar_estado.php",
            async: false,
            dataType: "html",
            data: data,
            success: function(res){
                //console.log(res);
                BuscarGrabaciones();
            },
            error: function(){
            }
        });
    });

    $("body").on("change",".derivacion",function(){
        let aprobar =  $(this).parent().parent().find('.aprobar');
        let rechazar =  $(this).parent().parent().find('.rechazar');
        let derivar =  $(this).parent().parent().find('.derivar');
        if($(this).val() != ''){
            aprobar.hide();
            rechazar.hide();
            derivar.show();
        }else{
            aprobar.show();
            rechazar.show();
            derivar.hide();
        }
    });
    function BuscarGrabaciones(){
        $.ajax({
            type: "POST",
            url: "../includes/gestiones/buscar.php",
            async: false,
            dataType: "html",
            success: function(data){
                if(isJson(data)){
                    dataSet = JSON.parse(data);
                    //console.log(data);
                    //RecordTable.destroy();
                    UpdateRecords();
                }
            },
            error: function(){
            }
        });
    }

    function UpdateRecords(){
        RecordTable = $('#Records').DataTable({
            data: dataSet,
            "bDestroy": true,
            columns: [
                { data: 'id_gestion', visible: false},
                { data: 'rut_cliente', className: 'rut' },
                { data: 'fecha_gestion' },
                { data: 'nombre_ejecutivo' },
                { data: 'nombre_supervisor' },
                { data: false },
                { data: false},
                { data: 'file_url' },
                { data: false },
                { data: false }
            ],
            
            "columnDefs": [
                {
                    "targets": 5,
                    "data": false,
                    "render": function( data, type, row ) {
                        return "<input type='text' class='form-control observacion'>";
                    }
                },
                {
                    "targets": 6,
                    "data": 'data',
                    "render": function( data, type, row ) {
                        return  " <form action='../crm/index' method='POST' target='_blank'>"+
                                    "<input value='"+row.rut_cliente+"' name='rut_a_consultar' type='hidden'>"+
                                    "<button class='btn btn-success btn-md'> Ver Caso </button>"+
                                " </form>";
                    }
                },
                {
                    "targets": 7,
                    "data": 'file_url',
                    "render": function( data, type, row ) {
                        if(data!='' && data!=null ){
                            return "<a href='"+data+"' target='_blank' class='btn btn-info btn-md'> Ver Documento </a>";
                        }else{
                            return '';
                        }
                    }
                },
                {
                    "targets": 8,
                    "render": function( data, type, row ) {
                        return `
                            <select name="n1" class="form-control">
                                <option></option>
                                <option>Tipificacion</option>
                            </select>
                        `;
                    }
                },
                {
                    "targets": 9,
                    "render": function( data, type, row ) {
                        return `
                            <select name="derivacion" class="form-control derivacion">
                                <option></option>
                                <option value="SERGIO HERRERA">SERGIO HERRERA</option>
                                <option value="JOSE PAVEZ">JOSE PAVEZ</option>
                                <option value="MARIO BRIONES">MARIO BRIONES</option>
                                <option value="CRISTOBAL BERTELSEN">CRISTOBAL BERTELSEN</option>
                            </select>
                        `;
                    }
                },
                {
                    "targets": 10,
                    "data": 'id_gestion',
                    "render": function( data, type, row ) {
                        //return "<audio src='"+data+"' preload='auto' controls></audio>";
                        return `
                            <button data-ref='${data}' class='btn btn-success btn-md aprobar' data-toggle="tooltip" title="Aprobar">
                                <i class="fa fa-check" aria-hidden="true"></i> 
                            </button>
                            <button data-ref='${data}' class='btn btn-danger btn-md rechazar'data-toggle="tooltip" title="Rechazar"> 
                                <i class="fa fa-close" aria-hidden="true"></i> 
                            </button>
                            <button data-ref='${data}' class='btn btn-info btn-md derivar' data-toggle="tooltip" title="Derivar"> 
                                <i class="fa fa-code-fork" aria-hidden="true"></i> 
                            </button>
                        `;
                    }
                }
            ]
            
        });
    }
});
$(document).ready(function() {
    var idCedente
    var TablaPeriodo =  $('#listaPeriodo').DataTable({
        paging: false,
    });
    // listarTablas();
    /*
        *  Lista periodos (por cedente)
    */
    function listarTablas(idCedente){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/GetListar_periodo.php",
            data: "idCedente="+idCedente,
            dataType: "json",
            success: function(data){ 
                // si no tengo un cedente seleccionado desactivo el boton --- si el cedente ya tiene un periodo desactivo el boton  
                if((((idCedente == "")) || ((data.length != 0) && (idCedente != "")))){
                    $('#AddPeriodo').attr('disabled', 'disabled');
                }else{
                    $('#AddPeriodo').removeAttr("disabled");
                }  

                TablaPeriodo.clear().draw();

                $.each(data, function( index, array ) {
                    var rowNode = TablaPeriodo.row.add([
                        ''+array.fechaInicio+'',
                        ''+array.fechaTermino+'',
                        ''+array.Descripcion+'',
                        ''+"<div style='text-align: center;' id='"+array.Actions+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminar fa fa-trash btn-danger btn-icon icon-lg'></i></div>"+'',
                    ]).draw(false).node();

                    $(rowNode)
                        .attr('id',data.Actions)
                });
                
            },
            error: function(){
                alert('Error');
            }
        });
    }


    $('body').on( 'click', '#AddPeriodo', function () {
        bootbox.dialog({
            title: "Registro de periodo por Cedente",
            message: $("#RegistrarPeriodo").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var fechaInicio = $('#start').val();
                        var fechaFin = $('#end').val();  
                        var Descripcion = $('#descripcion').val(); 
                        addPeriodo(fechaInicio,fechaFin,Descripcion,idCedente);                    
                        $('#AddPeriodo').attr('disabled', 'disabled');
                    }
                }                
            }
       }).off("shown.bs.modal");
       $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
            weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
    });


    /*
        * Registrar nueva tabla (por cedente)
    */

    function addPeriodo(fechaInicio,fechaTermino,Descripcion,idCedente){        
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/crear_periodo.php",
            dataType: "json",
            data: { fechaInicio: fechaInicio, fechaTermino: fechaTermino, Descripcion: Descripcion, idCedente: idCedente },
            success: function(data){
                if(data){
                    var rowNode = TablaPeriodo.row.add([
                        ''+fechaInicio+'',
                        ''+fechaTermino+'',
                        ''+Descripcion+'',
                        ''+"<div style='text-align: center;' id='"+idCedente+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminar fa fa-trash btn-danger btn-icon icon-lg'></i></div>"+'',
                    ]).draw(false).node();
                        
                    $(rowNode)
                        .attr('id',idCedente) 
                }else{
                    alert('Error');
                }          
            },
            error: function(){
                alert('Error');
            }
        });
    }

    /*
        * Pop up Eliminar tabla al cedente
    */
    $("body").on("click",".eliminar", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar el periodo?", function(result) {
            if (result) {
                eliminarPeriodo(ObjectTR, ID, "");
            }
        });
    }); 

    /*
        ** tipo=Foco o ''
    */
    function eliminarPeriodo(TableRow, ID, tipo){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/eliminar_periodoGestion.php",
            dataType: "json",
            data: {
                tipo: tipo, idPeriodo: ID
            },
            success: function(data){
                if(data){
                    TablaPeriodo.row(TableRow).remove().draw();
                    if(idCedente != ""){
                        $('#AddPeriodo').removeAttr("disabled");
                    }else{
                        $('#AddPeriodo').attr('disabled', 'disabled');
                    }  
                }else{
                    alert('Error');
                }           
            },
            error: function(){
                alert('Error');
            }
        });
    }

    $('body').on( 'click', '#seleccionarCedente', function () {
        bootbox.dialog({
            title: "Seleccionar Mandante y Cedente",
            message: $("#templateMandanteCedente").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        idCedente = $('#cedenteAdmin').val();
                        listarTablas(idCedente);

                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker();
        mandantes();
    });

    function mandantes(){
        $.ajax({
            type: "POST",
            url: "../includes/global/GetMandante.php",
            success: function(data){
                $("select[name='mandanteAdmin']").html(data);
                $("select[name='mandanteAdmin']").selectpicker('refresh');
            },
            error: function(){
                alert('Error');
            }
        });
    }

    function cedentesMandante(idMandante){
        $.ajax({
            type: "POST",
            url: "../includes/global/GetCedentesMandante.php",
            data: {mandante: idMandante},
            success: function(data){
                $("select[name='cedenteAdmin']").html(data);
                $("select[name='cedenteAdmin']").selectpicker('refresh');
            },
            error: function(){

            }
        });
    }

    $("body").on("change","#mandanteAdmin",function(){
        var idMandante = $('#mandanteAdmin').val();
        if ((idMandante != 0) || (idMandante != "")){
          cedentesMandante(idMandante);
        }
    });
});
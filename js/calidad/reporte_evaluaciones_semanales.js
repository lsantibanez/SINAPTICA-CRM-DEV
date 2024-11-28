$(document).ready(function(){
    
    getPeriodosEvaluacionesSemanales();

    $("body").on("change","select[name='Periodo']",function(){
        var Periodo = $(this).val();
        getPeriodoEvaluacionesSemanales(Periodo);
    });

    function getPeriodosEvaluacionesSemanales(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getPeriodosEvaluacionesSemanales.php",
            data: { },
            beforeSend: function() {
                deleteModalBackdrop();
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                $("select[name='Periodo']").html(data);
                $("select[name='Periodo']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getPeriodoEvaluacionesSemanales(Periodo){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getPeriodoEvaluacionesSemanales.php",
            data: { 
                Periodo: Periodo
            },
            beforeSend: function() {
                deleteModalBackdrop();
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    data = JSON.parse(data);
                    $('#TablaEvaluadores thead tr th').remove();
                    $.each(data.Header, function(i, item) {
                        $('#TablaEvaluadores thead tr').append("<th>"+item+"</th>");
                    })
                    $('#TablaEvaluadores').DataTable({
                        data: data.Data,
                        bDestroy: true,
                        columns: data.Columns,
                        responsive: true
                    });
                }
            },
            error: function(){
            }
        });
    }
    function deleteModalBackdrop(){
        $(".modal-backdrop").remove();
    }
});
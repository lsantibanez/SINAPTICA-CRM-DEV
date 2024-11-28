$(document).ready(function(){
    
    $("#periodo_inicio").on('keyup blur change', function(e) {    
            if($(this).val() > 31 || $(this).val() < 1){
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: '<h4>Ingrese día inicio entre 1 y 31</h4>',
                    container: 'floating',
                    timer: 4000
                });
              $(this).val('1');
              return false;
            }
          });

          $("#periodo_fin").on('keyup blur change', function(e) {
                if($(this).val() > 31 || $(this).val() < 1){
                    $.niftyNoty({
                        type: 'danger',
                        icon: 'fa fa-check',
                        message: '<h4>Ingrese día fin entre 1 y 31</h4>',
                        container: 'floating',
                        timer: 4000
                    });
                  $(this).val('1');
                  return false;
                }
              });

              $('#btnregistrar').click(function(e){
                e.preventDefault();
                if($('#periodo_inicio').val() == ''){
                    $.niftyNoty({
                        type: 'danger',
                        icon: 'fa fa-check',
                        message: '<h4>Ingrese un valor día inicio entre 1 y 31</h4>',
                        container: 'floating',
                        timer: 4000
                    });
                    return false;
                }
                if($('#periodo_fin').val() == ''){
                    $.niftyNoty({
                        type: 'danger',
                        icon: 'fa fa-check',
                        message: '<h4>Ingrese un valor día fin entre 1 y 31</h4>',
                        container: 'floating',
                        timer: 4000
                    });
                    return false;
                }else {
                    var datos = $('#formulario').serialize();
                    $.ajax({
                        type:"POST",
                        url:"../includes/periodo/insertPeriodoDias.php",
                        data: datos,
                        success: function(data){
                            if(data){
                               $('#formulario')[0].reset();
                               $.niftyNoty({
                                type: 'success',
                                icon: 'fa fa-check',
                                message: '<h4>Periodo Creado</h4>',
                                container: 'floating',
                                timer: 4000
                              });
                            } else {
                                $.niftyNoty({
                                    type: 'danger',
                                    icon: 'fa fa-check',
                                    message: '<h4>Error al crear el Periodo</h4>',
                                    container: 'floating',
                                    timer: 4000
                                  });
                                  return false;
                            }
                        }
                    });
                }
            });

        //funcion para ver Cendete
        function getCedente(){
            $.ajax({
            url:"../includes/periodo/getCedente.php",
            method:"POST",
            success:function(data){
                    $('#cedente').html(data);
                    $('#cedente').selectpicker('refresh');
                }
            });
        }
        getCedente();
});
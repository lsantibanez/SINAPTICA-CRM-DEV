$(document).ready(function(){
  
    $('#time-start').timepicker({
         
        minuteStep: 1,
        secondStep: 1,
        timeFormat: 'H:i:s',
        showSeconds: true,
        showMeridian: false
         });
   
    $('#time-end').timepicker({ 
        minuteStep: 1,
        secondStep: 1,
        timeFormat: 'H:i:s',
        showSeconds: true,
        showMeridian: false
     });

    $("#longitud_telefono").on('keyup blur change', function(e) {
        
            if($(this).val() > 15 || $(this).val() < 1){
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: '<h4>Ingrese una longitud entre 1 y 15</h4>',
                    container: 'floating',
                    timer: 4000
                });
              $(this).val('1');
              return false;
            }
          });

    $('#btnregistrar').click(function(e){
        e.preventDefault();
        
        if($('#longitud_telefono').val() == ''){
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese una longitud para el télefono entre 1 y 15</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if($('#simbolo').val() == ''){
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese un simbolo</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }else {
            var datos = $('#formulario').serialize();
            console.log(datos);
            $.ajax({
                type:"POST",
                url:"../includes/admin/crear_config_global.php",
                data: datos,
                success: function(data){
                    if(data){
                       $('#formulario')[0].reset();
                       $.niftyNoty({
                        type: 'success',
                        icon: 'fa fa-check',
                        message: '<h4>Configuración Global Creada</h4>',
                        container: 'floating',
                        timer: 4000
                      });
                    } else {
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: '<h4>Error al crear la Configuración Global</h4>',
                            container: 'floating',
                            timer: 4000
                          });
                          return false;
                    }
                }
            });
        }
    });
});
$(document).ready(function(){
    //$('#fechaReporteHora').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
    
  $('#grupotipo').on('change', function(){
      seleccion = this.value;
      if(seleccion == ''){
          if($("#vergrupoexistente").css("display","block"))
              $("#vergrupoexistente").css("display","none");
          if($("#creagruponuevo").css("display","block"))
              $("#creagruponuevo").css("display","none");
        return false;
      }
      if(seleccion == 'gruponuevo'){
        if($("#vergrupoexistente").css("display","block"))
            $("#vergrupoexistente").css("display","none");              
        $("#creagruponuevo").show("slow");
      }
      if(seleccion == 'grupoexistente'){
        if($("#creagruponuevo").css("display", "block"))
            $("#creagruponuevo").css("display", "none");
        $("#vergrupoexistente").show("slow");
      }
  })

  //seleccionar los ejecutivos en el  grupo existente
  $('#editGrupo').on('change', function(){
    idgrupo = this.value;
    $.ajax({
        type: "POST",
        url:"../includes/reporteria/reporteEjecutivoHora/getEjecutivosSelected.php",
        data: "&idgrupo="+idgrupo, // envio al post de php
        success: function(data){
            data = JSON.parse(data)
            $('#editEjecutivos').selectpicker('val', data); //selecciono solo los id en el grupo que vienen en data
        }
    })
})


//  insertar grupo
          $('#btnregistrar').click(function(e){
            e.preventDefault();
            var datos = $('#forminsertGrupo').serialize();
//          alert($('#newEjecutivos').val());
             
            $.ajax({
                type:"POST",
                url:"../includes/reporteria/reporteEjecutivoHora/insertGrupo.php",
                data: datos,
                success: function(data){
                    if(data){
                        // document.getElementById("myForm").reset(); 
                        $('#forminsertGrupo')[0].reset();  
                        $('.selectpicker').selectpicker('deselectAll');        
                        $('#modalcreagrupo').modal('hide');
                        $.niftyNoty({
                          type: 'success',
                          icon: 'fa fa-check',
                          message: 'Grupo creado',
                          container: 'floating',
                          timer: 4000
                        });
                        getGrupos();
                    }else{
                        $.niftyNoty({
                          type: 'danger',
                          icon: 'fa fa-check',
                          message: 'Error',
                          container: 'floating',
                          timer: 4000
                        });
                    }

                }
              
            });
            return false;
        });

//   editar grupo
          $('#btneditar').click(function(e){
            e.preventDefault();
            var datos = $('#formEditGrupo').serialize();
//            alert($('#newEjecutivos').val());
            // var a = $('#formEditGrupo').serializeArray();
            
            // alert(a);
            // return 0;

            $.ajax({
                type:"POST",
                url:"../includes/reporteria/reporteEjecutivoHora/editarGrupo.php",
                data: datos,
                success: function(data){
                  
                    if(data){
                        $('#modalcreagrupo').modal('hide');
                        $.niftyNoty({
                          type: 'success',
                          icon: 'fa fa-check',
                          message: 'Grupo Actualizado',
                          container: 'floating',
                          timer: 4000
                        });
                        
                    }else{
                        $.niftyNoty({
                          type: 'danger',
                          icon: 'fa fa-check',
                          message: 'Error',
                          container: 'floating',
                          timer: 4000
                        });
                    }

                }
              
            });
            return false;
        });
 getEjecutivos();




//funcion para ver grupos actualizados
function getGrupos(){
  $.ajax({
   url:"../includes/reporteria/reporteEjecutivoHora/getGrupos.php",
   method:"POST",
    success:function(data){
        $('#mostrargrupos').html(data);
        $('#mostrargrupos').selectpicker('refresh');
        $('#editGrupo').html(data);
        $('#editGrupo').selectpicker('refresh');
    }
  });
 }
 getGrupos();

//función para ver ejecutivos
function getEjecutivos(){
  $.ajax({
    url:"../includes/reporteria/reporteEjecutivoHora/getEjecutivos.php",
    method:"POST",
    success:function(data){
        console.log(data);
        $('#editEjecutivos').html(data);
        $('#editEjecutivos').selectpicker('refresh');
        $('#newEjecutivos').html(data);
        $('#newEjecutivos').selectpicker('refresh');
      }
    });
  }


function mostrarTipoGrupo() {
    $.ajax({
      type: "POST",
      url: "../includes/reporteria/GetEjecutivoHora.php",
      async: false,
      data: {},
      success: function(data) {
        $("select[name='GrupoTipo']").html(data);
        $("select[name='GrupoTipo']").selectpicker('refresh');
      },
      error: function(){
        alert('Error al mostrar grupo');
      }
    });
}

function CustomAlert(Message){
      bootbox.alert(Message,function(){
          AddClassModalOpen();
      });
 }

    $('#verReporte').click(function(){
        
        var fechaReporte = $('#fechaReporteHora').val();
        if(fechaReporte==''){
            $.niftyNoty({
                type: 'danger',
                icon : 'fa fa-close',
                message : 'Debe Seleccionar Fecha' ,
                container : 'floating',
                timer : 2000
            });

        }else{
            var idGrupo = $('#mostrargrupos').val();
            var data = "fechaReporte="+fechaReporte+"&idGrupo="+idGrupo;
            $.ajax({
                url:"../includes/reporteria/reporteEjecutivoHora/getReporte.php",
                method:"POST",
                data: data,
                success:function(data){
                    $('#VerReporteOculto').show();
                    $("#Mostrar").html(data);
                    console.log(data);
                    
                }
            });
        }
        
    });

});

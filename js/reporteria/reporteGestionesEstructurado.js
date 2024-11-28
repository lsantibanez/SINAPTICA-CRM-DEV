$(document).ready(function(){

    getCedentes();
    
    $("body").on("click","#Download",function(){
        var Desde = $("input[name='start']").val();
        var Hasta = $("input[name='end']").val();
        var idCedente = $("select[name='Cedente']").val();

        if(idCedente != ""){
            //CrearReporteDeGestionEstructurado(Desde,Hasta,idCedente);
            window.location = "../includes/reporte/gestionEstructurado/CrearReporteDeGestionEstructurado.php?Desde="+Desde+"&Hasta="+Hasta+"&idCedente="+idCedente+"";
        }else{
            bootbox.alert("Debe seleccionar un Cedente");
        }
    });

    function getCedentes(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getCedentes.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                $("select[name='Cedente']").html(data);
                $("select[name='Cedente']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function CrearReporteDeGestionEstructurado(Desde,Hasta,idCedente){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/gestionEstructurado/CrearReporteDeGestionEstructurado.php",
            dataType: "html",
            data: {
                Desde: Desde,
                Hasta: Hasta,
                idCedente: idCedente,
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                //$("#lala").html(data);
                //console.log(data);
                if(isJson(data)){
                    var item = JSON.parse(data);
                    //console.log(item.text);
                    $("#Texto").html(item.text);
                    var $a = $("<a>");
                    $a.addClass("list-group-item");
                    $a.attr("href",item.file);
                    $a.attr("download",item.fileName+".txt");
                    $a.html(item.fileName);
                    //$a[0].click();
                    //$("#lala").append($a);
                }
                
            },
            error: function(){
            }
        });
    }
});
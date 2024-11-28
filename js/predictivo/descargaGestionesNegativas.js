$(document).ready(function(){

    var DialProvider;
    var DialProviderConfigured = false;

    GetServerStatus();

    $("#Download").click(function(){
        if(DialProviderConfigured){
            var Fecha = $("input[name='Fecha']").val();
            var Post = {};
            if(Fecha != ""){
                Post = {Fecha: Fecha};
            }
            $.ajax({
                type: "POST",
                url: "../includes/predictivo/CRON_downloadGestionesNegativas.php",
                data:Post,
                async: false,
                beforeSend: function() {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(response){
                    $('#Cargando').modal('hide');
                    if(isJson(response)){
                        var Return = JSON.parse(response);
                        if(Return.result){
                            bootbox.alert(Return.message);
                        }else{
                            bootbox.alert(Return.message);
                        }
                    }
                },
                error: function(response){
                }
            });
        }else{
            bootbox.alert("Servidor de Discado automatico no configurado");
        }
    });
    function GetServerStatus(){
        if(GlobalData.focoConfig.IpServidorDiscado != ""){
            $.ajax({
                type: "POST",
                url: "../includes/admin/GetServerStatus.php",
                data:{
                    codigoFoco: GlobalData.focoConfig.CodigoFoco,
                },
                async: false,
                beforeSend: function() {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    if(isJson(data)){
                        var json = JSON.parse(data);
                        console.log(json);
                        if(json.result){
                            DialProvider = json.Proveedor;
                            if(DialProvider != ""){
                                DialProviderConfigured = true;
                            }
                        }
                    }                
                }
            });
        }
    }
});
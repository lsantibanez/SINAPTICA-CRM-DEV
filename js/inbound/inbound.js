$(document).ready(function(){
    var DialProviderConfigured = false;
    GetServerStatus();
    getColasInbound();

    if(!DialProviderConfigured){
        $("button#btnConectar").prop("disabled",true);
    }else{
        //var DiscadorSocket = io.connect("http://localhost:65530");
    }

    $("button#btnConectar").click(function(){
        var Queue = $("select[name='ColaInbound']").val();
        if(Queue != ""){
            if (DialProviderConfigured) {
                DiscadorSocket.emit('createAnexoInbound', { Anexo: GlobalData.anexo, Queue: Queue });
            }
        }else{
            bootbox.alert("Debe seleccionar una cola.");
        }
    });
    
    function getColasInbound(){
        $.ajax({
            type: "POST",
            url: "../includes/inbound/getColasInbound.php",
            data: {
                idMandante: GlobalData.id_mandante
            },
            async: false,
            success: function (data) {
                $("select[name='ColaInbound']").html(data);
                $("select[name='ColaInbound']").selectpicker("refresh");
            },
            error: function (data) {
            }
        });
    }
    function GetServerStatus(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetServerStatus.php",
            data: {
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
            },
            async: false,
            success: function (data) {
                if (isJson(data)) {
                    var json = JSON.parse(data);
                    console.log(json);
                    if (json.result) {
                        DialProvider = json.Proveedor;
                        if (DialProvider != "") {
                            DialProviderConfigured = true;
                        }
                    }
                }
            }
        });
    }
});
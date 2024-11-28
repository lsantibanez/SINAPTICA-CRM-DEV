$(document).ready(function(){

    if(GlobalData.isEjecutivo){
        getMailConfig("2");
    }

    $("select[name='tipoModulo']").change(function(){
        var Value = $(this).val();
        cleanScreen();
        getMailConfig(Value);
    });

    function getMailConfig(tipoModulo){
        $.ajax({
			type: "POST",
			url: "../includes/email/getMailConfig.php",
			data: {
                tipoModulo: tipoModulo
            },
            async: false,
            beforeSend: function(){
			},
			success: function(result){
                if(isJson(result)){
                    var Config = JSON.parse(result);
                    
                    //$(".Protocol input[value='"+Config.protocolo+"']").attr({"checked":true}).prop({"checked":true});
                    //$(".Protocol input[value='"+Config.protocolo+"']").closest("label").addClass("active");

                    $(".Secure input[value='"+Config.secure+"']").attr({"checked":true}).prop({"checked":true});
                    $(".Secure input[value='"+Config.secure+"']").closest("label").addClass("active");

                    $("#host").val(Config.Host);
                    $("#port").val(Config.Port);
                    $("#email").val(Config.Email);
                    $("#pass").val(Config.Pass);
                    $("#from").val(Config.FromEmail);
                    $("#fromname").val(Config.FromName);
                    $("#ConfirmReadingTo").val(Config.ConfirmReadingTo);
                }
			},
			error: function(){
			}
		});
    }

    function cleanScreen(){
        /*$(".Protocol").each(function(){
            var ObjectMe = $(this);
            ObjectMe.find("input").attr({"checked":false}).prop({"checked":false});
            ObjectMe.find("input").closest("label").removeClass("active");
        });*/
        $(".Secure").each(function(){
            var ObjectMe = $(this);
            ObjectMe.find("input").attr({"checked":false}).prop({"checked":false});
            ObjectMe.find("input").closest("label").removeClass("active");
        });
        $("#host").val("");
        $("#port").val("");
        $("#email").val("");
        $("#pass").val("");
        $("#from").val("");
        $("#fromname").val("");
    }

});
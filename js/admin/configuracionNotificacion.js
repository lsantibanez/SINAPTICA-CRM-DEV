$(document).ready(function(){

    $.ajax({
        type: "POST",
        url: "../includes/admin/get-config-notificacion.php",
        data: {},
        dataType: "html",
        success: function(result){	
            console.log(result);
            var data = JSON.parse(result);
            if(data != ""){
                //$(".Protocol input[value='"+data[0].protocolo+"']").attr({"checked":true}).prop({"checked":true});
                //$(".Protocol input[value='"+data[0].protocolo+"']").closest("label").addClass("active");

                $(".Secure input[value='"+data[0].secure+"']").attr({"checked":true}).prop({"checked":true});
                $(".Secure input[value='"+data[0].secure+"']").closest("label").addClass("active");

                $("#host").val(data[0].host);
                $("#port").val(data[0].puerto);
                $("#email").val(data[0].email);
                $("#pass").val("");
                $("#from").val(data[0].from_email);
                $("#fromname").val(data[0].from_name);
            }
        },
        error: function(){
			niftyDanger("Error al guardar cambios");
        }
    });

	$(".save-conf-not").on('click', function(){
		var protocolo = 1;
		var secure = $("input:radio[name=secure]:checked").val();
		var host = $("#host").val();
		var puerto = $("#port").val();
		var email = $("#email").val();
		var password = $("#pass").val();
		var from = $("#from").val();
		var fromname = $("#fromname").val();

		$.ajax({
			type: "POST",
			url: "../includes/admin/save-config-notificacion.php",
			data: { prot:protocolo,secure:secure,host:host,port:puerto,email:email,pass:password,from:from,fromname:fromname },
			dataType: "html",
			beforeSend: function(){
				$("#message").css("display","block");;
			},
			success: function(result){	
				console.log(result);
				if(result == "true"){
					niftySuccess("Configuración guardada exitosamente.");
				}
			},
			error: function(){
				niftyDanger("Error al guardar cambios");
			}
		});
	});

	function niftyWarning(mensaje){
		$.niftyNoty({
			type: 'warning',
			icon: 'fa fa-exclamation',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
	}

	function niftyDanger(mensaje){
		$.niftyNoty({
			type: 'danger',
			icon: 'fa fa-times-circle',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
	}

	function niftySuccess(mensaje){
		$.niftyNoty({
			type: 'success',
			icon: 'fa fa-check',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
	}
});
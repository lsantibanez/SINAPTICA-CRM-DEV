$(document).ready(function(){

	var sonidoNotificaciones;

    $.ajax({
        type: "POST",
        url: "../includes/admin/getFocoConfiguracion.php",
        data: {},
        dataType: "html",
        success: function(result){	
            console.log(result);
            var data = JSON.parse(result);
            if(data != ""){
				$("#codigoFoco").prop("disabled", true);
				$("#sav").prop("disabled", true);
				$("#sav2").prop("disabled", true);
				$("#codigoFoco").val(data[0].CodigoFoco);
				$("#tipoSistema").val(data[0].tipoSistema);
				$("#tipoMenu").val(data[0].tipoMenu);
				$("#ipServidor").val(data[0].IpServidorDiscado);
				$("#mandantes").val(data[0].cantidadMaxMandantes);
				$("#cedentes").val(data[0].cantidadMaxCedentes);
				$("#evaluacion").val(data[0].NotaMaximaEvaluacion);
				$("#correos").val(data[0].cantidadCorreos);
				if(data[0].sonidoNotificaciones){
					$('#sonidoNotificacionesSwitch').prop('checked',true)
					sonidoNotificaciones = 1;
				}else{
					sonidoNotificaciones = 0;
				}
			}
        },
        error: function(){
			niftyDanger("Error al cargar la configuración.");
        }
	});

	$("#sonidoNotificacionesSwitch").on("change", function () {
		if($(this).is(':checked')){
			sonidoNotificaciones = 1;
		} else {
			sonidoNotificaciones = 0;
		}
	});
	
	$(".guardar").on("click", function(){
		var cor = $("#correos").val();
		var ced = $("#cedentes").val();
		var men = $("#tipoMenu").val();
		var man = $("#mandantes").val();
		var eva = $("#evaluacion").val();
		var ser = $("#ipServidor").val();
		var cod = $("#codigoFoco").val();
		var sis = $("#tipoSistema").val();

		var post = "cod=" + cod + "&sis=" + sis + "&men=" + men + "&ser=" + ser + "&man=" + man + "&ced=" + ced + "&eva=" + eva + "&cor=" + cor + "&son=" + sonidoNotificaciones;
		guardarConfiguracion(post);
	});

	function guardarConfiguracion(post){
		$.ajax({
			type: "POST",
			url: "../includes/admin/guardarConfiguracion.php",
			data: post,
			dataType: "html",
			success: function(result){	
				if(result == "true"){
					niftySuccess("Configuración guardada exitosamente.");
				}
			},
			error: function(){
				niftyDanger("Error al guardar cambios");
			}
		});
	}

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
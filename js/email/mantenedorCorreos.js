$(document).ready(function(){

    actualizarMantenedor();

    function actualizarMantenedor(){
        $.ajax({
            type: "POST",
            url: "../includes/email/getHorasCorreo.php",
            data: {},
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    if (dataSet.length > 0){
                        timeInicio(dataSet[0].horaInicio);
                        timeFin(dataSet[0].horaFin);
                        $("#sav").prop("disabled", true);
                        $("#act").prop("disabled", false);
                    }else{
                        timeInicio("0:00:00");
                        timeFin("0:00:00");
                        $("#act").prop("disabled", true);
                        $("#sav").prop("disabled", false);
                    }
                }
            }
        });
    }

    function timeInicio(hora){
        $('#time-start').val(hora);
        $('#time-start').timepicker({
            minuteStep: 1,
            secondStep: 1,
            timeFormat: 'H:i:s',
            showSeconds: true,
            showMeridian: false
        });
    }

    function timeFin(hora){
        $('#time-end').val(hora);
        $('#time-end').timepicker({
            minuteStep: 1,
            secondStep: 1,
            timeFormat: 'H:i:s',
            showSeconds: true,
            showMeridian: false
        });
    }

    $(".guardar").on("click", function(){
        var inicio = $('#time-start').val();
        var fin = $('#time-end').val();
        var post="inicio="+inicio+"&fin="+fin;
        guardarMantenedor(post);
    });

    function guardarMantenedor(post){
        $.ajax({
            type: "POST",
            url: "../includes/email/guardarMantenedorCorreo.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(data == "2"){
                    niftyDanger("No existe configuración válida de correos para Foco.");
                }else if (data == "true"){
                    actualizarMantenedor();
                    niftySuccess("Solicitud procesada con éxito.");
                }else{
                    niftyDanger("Ocurrió un error al momento de guardar la configuración del mantenedor");
                }
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
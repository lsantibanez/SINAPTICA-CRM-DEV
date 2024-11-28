$(document).ready(function(){

    actualizarMantenedor();
    actualizarAPISMS();

    function actualizarMantenedor(){
        $.ajax({
            type: "POST",
            url: "../includes/email/getHorasSMS.php",
            data: {},
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    if (dataSet.length > 0){
                        timeInicio(dataSet[0].horaInicio);
                        timeFin(dataSet[0].horaFin);
                        $("#cantidadSMS").val(dataSet[0].cantidad);
                        $("#costoSMS").val(dataSet[0].costoSMS);
                        $("#sav").prop("disabled", true);
                        $("#act").prop("disabled", false);
                    }else{
                        timeInicio("0:00:00");
                        timeFin("0:00:00");
                        $("#cantidadSMS").val(0);
                        $("#costoSMS").val(0);
                        $("#act").prop("disabled", true);
                        $("#sav").prop("disabled", false);
                    }
                }
            }
        });
    }

    function actualizarAPISMS(){
        $.ajax({
            type: "POST",
            url: "../includes/email/getCredencialesSMS.php",
            data: {},
            dataType: "html",
            success: function(data){
                //console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    $("#urlEnvio").val(dataSet.urlEnvio);
                    $("#urlConsulta").val(dataSet.urlConsulta);
                    $("#urlSaldo").val(dataSet.urlSaldo);
                    $("#user").val(dataSet.usuario);
                    $("#pwd").val(dataSet.contrasena);

                    $("#savURL").prop("disabled", true);
                    $("#actURL").prop("disabled", false);
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
        var cost = $('#costoSMS').val();

        var post="inicio="+inicio+"&fin="+fin+"&cost="+cost;
        guardarMantenedor(post);
    });

    $(".guardarURL").on("click", function(){
        var pwd     = $('#pwd').val();
        var user    = $('#user').val();
        var saldo   = $('#urlSaldo').val();
        var envio   = $('#urlEnvio').val();
        var consult = $('#urlConsulta').val();

        if((saldo == "") || (envio == "") || (consult == "") || (user == "")){
            niftyWarning("Los campos de URL y Usuario no pueden estar vacíos.");
            return false;
        }

        var post="pwd="+pwd+"&user="+user+"&envio="+envio+"&consult="+consult+"&saldo="+saldo;
        guardarURLSMS(post);
    });

    function guardarMantenedor(post){
        $.ajax({
            type: "POST",
            url: "../includes/email/guardarMantenedor.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                actualizarMantenedor();
                niftySuccess("Solicitud procesada con éxito.");
            }
        });
    }

    function guardarURLSMS(post){
        $.ajax({
            type: "POST",
            url: "../includes/email/guardarURLSMS.php",
            data: post,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(data == "true"){
                    actualizarAPISMS();
                    niftySuccess("Operación realizada con éxito.");
                }else{
                    niftyDanger("¡Alerta! Ocurrió un error al tratar de guardar los datos.");
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
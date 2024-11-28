$(document).ready(function(){

    verificaCronActivo();
    listaColasPendientes();
    getCorreosRestantes();

    // Enviar Email Masivo
    $('#enviar-mail').on('click', function () {
        var asunto = $('#asunto').val();
        var est = $('#asignacion').val();
        var cant = $('#cantidad-emails').val();

        var template = $('#template').val();

        if ($('#facturas').prop('checked')) {
            var adjuntar = 1;
        } else {
            var adjuntar = 0;
        }

        if (cant > 0) {
            $('#enviar-mail').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "../includes/email/enviar-correo.php",
                data: { est: est, cant: cant, asunto: asunto, adjuntar, template: template },
                dataType: "html",
                async: false,
                success: function (result) {
                    listaColasPendientes();
                    if (result == 1) {
                        niftyWarning("Error, Ya existe un envío programado para la asignación: " + est);
                    } else if ((result == 2) || (result == 6)) {
                        niftySuccess("Se ha programado exitosamente el envío de email.");
                        $.ajax({
                            type: "POST",
                            url: "../includes/email/cron-email-masivo.php",
                        });
                    } else if (result == 3) {
                        niftySuccess("El envío de email ha terminado exitosamente.");
                    } else {
                        niftyDanger("Error al intentar envío de correos.");
                    }
                }
            });
        } else {
            niftyWarning("Error, La estrategia debe tener al menos un email válido para proceder con el envío.");
        }
    });

    setTimeout(() => {
        $('#summernote').summernote('disable');
    }, 200);
    
	function verificaCronActivo(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/verificaCron.php",
            async: false,
            success: function(data){
            	if (data == 1){
					$("#enviar-mail").removeAttr("disabled");
                    $('#continuarEnvio').prop('disabled', true); // true inactivo
			  	}else{
					$('#enviar-mail').prop('disabled', true);
                    verificaAlertaEnvio();
			  	}
            }
        });		
	}

    function verificaAlertaEnvio(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/verificaAlertaEnvio.php",
            async: false,
            success: function(data){
                data = JSON.parse(data);
               
                if (data.length > 0){
                   var usuarios = "";
                   $.each(data, function (indice, elemento){
                    usuarios+=elemento+", ";
                   });
                    $(".alert").addClass("alert-warning");
                    $('#continuarEnvio').prop('disabled', false); // true inactivo
				    $(".alert").html("Alerta, Ya existen envíos programados, comuníquese con el usuario(s) "+usuarios+" para eliminar o continuar con los envios");
                }else{
                    $(".alert").addClass("alert-warning");
				    $(".alert").html("Alerta, El envio de correo solo sera permitido en el horario de 09:00 am - 20:00 pm");
                }
            }
        });		
    }
    
    function getCorreosRestantes() {
        $.ajax({
            type: "POST",
            url: "../includes/email/getCorreosRestantes.php",
            dataType: "json",
            async: false,
            success: function (data) {
                $('#correos_restantes').text(data)
                if (typeof getCorreosRestantesInterval === 'undefined') {
                    getCorreosRestantesInterval = setInterval(function () {
                        getCorreosRestantes();
                    }, 10000);
                }
            }
        });
    }

    function listaColasPendientes(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/GetListarColas.php",
            async: false,
            success: function(data){
                $('#continuarEnvio').prop('disabled', true);
                $('#pausarEnvio').prop('disabled', true);
                $('#cancelarEnvio').prop('disabled', true);
                $("select[name='colaPendiente']").html(data);
                $("select[name='colaPendiente']").selectpicker('refresh');
            }
        });
	}

    $('#colaPendiente').on('change', function(){
        var id_envio = $("#colaPendiente").val();
        if(id_envio != 0){
            $.ajax({
                type: "POST",
                url: "../includes/email/estadoEnvio.php",
                data: { id_envio: id_envio},
                async: false,
                success: function(data){
                    if(data == 'Activa'){
                        $('#pausarEnvio').prop('disabled',false);
                        $('#continuarEnvio').prop('disabled', true);
                    }else{
                        $('#pausarEnvio').prop('disabled', true);
                        $('#continuarEnvio').prop('disabled', false);
                    }
                    $('#cancelarEnvio').prop('disabled', false);
                    $('#estadoEnvio').text(data);
                }
            });
        }else{
            $('#estadoEnvio').text('');
            $('#continuarEnvio').prop('disabled', true);
            $('#pausarEnvio').prop('disabled', true);
            $('#cancelarEnvio').prop('disabled', true);
        }
    });

    $("body").on("click", "#cancelarEnvio", function(){
        var idCola = $('#colaPendiente').val();
        if (idCola == 0){
            CustomAlert("Debe seleccionar una cola para proceder con la eliminación.");
        }else{
            bootbox.confirm("¿Está seguro que desea eliminar el envío de esta cola?", function(result) {
                if (result) {
                   cancelarCola(idCola);                
                }
            });
        }
    });

    $("body").on("click", "#pausarEnvio", function(){
        var idCola = $('#colaPendiente').val();
        if (idCola == 0){
            CustomAlert("Debe seleccionar una cola para proceder con el pausado.");
        }else{
            bootbox.confirm("¿Está seguro que desea pausar el envío de esta cola?", function(result) {
                if (result) {
                   pausarCola(idCola);                
                }
            });
        }
    });

    $("body").on("click", "#continuarEnvio", function(){
        var idCola = $('#colaPendiente').val();
        if (idCola == 0){
             CustomAlert("Debe seleccionar una cola");
        }else{
            bootbox.confirm("¿Esta seguro que desea continuar con el envio?", function(result) {
                if (result) {
                    continuarCola(idCola);                
                }
            });
        }
    });

    function cancelarCola(ID){
        $.ajax({
            type: "POST",
            url: "../includes/email/cancelarCola.php",
            dataType: "html",
            data: { ID: ID },
            async: false,
            success: function(data){
                console.log(data);
                if(data){
                    niftySuccess("Se ha cancelado el envío de correos.");
                    $('#estadoEnvio').text('');
                    listaColasPendientes();
                }else{
                    niftyDanger("Se produjo un error al intentar cancelar la cola.");
                }
            }
        });
    }

    function pausarCola(ID){
        $.ajax({
            type: "POST",
            url: "../includes/email/pausarCola.php",
            dataType: "html",
            data: { ID: ID },
            async: false,
            success: function(data){
                console.log(data);
                if(data){
                    niftySuccess("Se ha pausado el envío de correos de la cola seleccionada.");
                    $('#continuarEnvio').prop('disabled', false);
                    $('#pausarEnvio').prop('disabled', true);
                    $('#estadoEnvio').text('Pausada');
                    // listaColasPendientes();
                }else{
                    niftyDanger("Se produjo un error al intentar pausar la cola.");
                }
            }
        });
    }

    function continuarCola(ID){
        $.ajax({
            type: "POST",
            url: "../includes/email/continuarCola.php",
            dataType: "html",
            data: { ID: ID },
            async: false,
            success: function(data){
                if(data){
                    $('#continuarEnvio').prop('disabled', true);
                    $('#pausarEnvio').prop('disabled', false);
                    CustomAlert("El envío se activo satisfactoriamente");
                    $('#estadoEnvio').text('Activa');
                }
                // location.reload();              
            }
        });
    }

    function CustomAlert(Message){
        bootbox.alert(Message,function(){
            AddClassModalOpen();
        });
    } 

    function AddClassModalOpen(){
        setTimeout(function(){
            if($("body").hasClass("modal-open")){
                $("body").removeClass("modal-open");
            }
        }, 500);
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

    $("body").on("click", "#Download", function () {
        var Codigo = $("select[name='codigo']").val();
        if (Codigo != "") {
            window.open("../includes/email/descargarEstadistica.php?Codigo=" + Codigo + "", "_blank");
        } else {
            bootbox.alert("Debe seleccionar una Fecha");
        }
    });
});
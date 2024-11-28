$(document).ready(function(){
	var text_max = 160;
	mensajesEnviados();

	EnviadosTable = $('#sms_enviados').DataTable()

	$.ajax({
		type: "POST",
		url: "../includes/email/verificarEnvioSMS.php",
		async: false,
		success: function(data){
			console.log(data);
			var result = JSON.parse(data);
			console.log(result);
			if(result.respuesta == "1"){
				$("#enviar-sms").removeAttr("disabled");
				$("#SMSDisponibles").val(result.SMSDisponibles);
			}else if(result.respuesta == "2"){
				$('#enviar-sms').prop('disabled', true);
				niftyWarning("¡Alerta! Debe ingresar entre las " + result.horaInicio + " y las " + result.horaFin + " para poder enviar SMS.");
			}else if(result.respuesta == "3"){
				$('#enviar-sms').prop('disabled', true);
				niftyWarning("¡Alerta! El cedente no tiene configuraciones establecidas.");
			}else if(result.respuesta == "4"){
				$('#enviar-sms').prop('disabled', true);
				niftyWarning("¡Alerta! El envío de SMS no tiene las credenciales establecidas.");
			}else if(result.respuesta == "5"){
				$('#enviar-sms').prop('disabled', true);
				niftyWarning("¡Alerta! No tiene SMS disponibles para enviar en el día.");
			}else if(result.respuesta == "6"){
				$('#enviar-sms').prop('disabled', true);
				niftyDanger("¡Alerta! Ya superó el límite diario de SMS establecidos.");
			}else{
				$('#enviar-sms').prop('disabled', true);
				niftyDanger("Error. Intente ingresar más tarde.");
			}
		},
		error: function(){
		}
	});

	$("select[name='estrategia']").change(function(){
        var ObjectMe = $(this);
        var id = ObjectMe.val();

		$("select[name='queue']").val("");
        fillQueues(id);
	});
	
	function fillQueues(id){
		var data = "estrategia="+id;

        $.ajax({
            type: "POST",
            url: "../includes/email/fillQueues.php",
            data: data,
			dataType: "html",
			async: false,
            success: function(data){
				console.log(data);
                $("select[name='queue']").html(data);
                $("select[name='queue']").selectpicker('refresh');
            }
        });
	}

    $('#enviar-sms').on('click', function(){
		var sms 		= $('#SMS').val();
		var queue 		= $('#queue').val();
		var colores 	= $('#colores').val();
		var template 	= $('#template').val();
		var cant 		= $('#cantidad-fonos').val();

        if((cant > 0) && (sms !== "")){
			$.ajax({
				type: "POST",
				url: "../includes/email/enviar-sms.php",
				data: { cant:cant, sms:sms, colores:colores, queue:queue, template:template },
				dataType: "html",
				async: false,
				success: function(result){
					console.log(result);
					niftySuccess("Envío de SMS en proceso");
				}
			});
		} else {
			niftyDanger("¡Error! La estrategia debe tener al menos un FONO o MENSAJE válido para proceder con el envío.");
			return false;
		}
	});

    // USAR TEMPLATE
    $('#template').on('change', function(){
		var id = $('#template').val();
		var canal = "SMS";
		$.ajax({
			type: "POST",
			url: "../includes/email/select-template.php",
			data: { id:id, canal:canal},
			dataType: "html",
			async: false,
			success: function(result){
				var data = JSON.parse(result);
				$("#SMS").val(data[0]);
				$("#SMS").trigger("keyup");
			}
		});
	});

	$('#colores').on('change', function(){
		var queue 		= $('#queue').val();
		var colores 	= $('#colores').val();
		$.ajax({
			type: "POST",
			url: "../includes/email/info-estrategia-sms.php",
			data: { colores:colores, queue:queue },
			dataType: "html",
			async: false,
			success: function(result){
				var data = JSON.parse(result);
				$('#cantidad-rut').val(data[0]);
				$('#cantidad-fonos').val(data[1]);
			}
		});
	});

	// Buscar correos
    $('#queue').on('change', function(){
		var queue 		= $('#queue').val();
		var colores 	= $('#colores').val();

		$.ajax({
			type: "POST",
			url: "../includes/email/info-estrategia-sms.php",
			data: { colores:colores, queue:queue },
			dataType: "html",
			async: false,
			success: function(result){
				console.log(result);
				var data = JSON.parse(result);
				$('#cantidad-rut').val(data[0]);
				$('#cantidad-fonos').val(data[1]);
			}
		});
    });

	$('#clean-temp-sms').on('click', function(){
	   limpiarTemplateSMS();
	});

	function limpiarTemplateSMS(){
		$("#nombre-template").val("");
		$("#SMS").val("");
	}

	function getTemplates(){
		$.ajax({
			type: "POST",
			url: "../includes/email/getTemplates.php",
			dataType: "html",
			async: false,
			success: function(result){
				$("#templates").html(result);
			},
			error: function(){
			}
		});
	}

	function getTemplatesSMS(){
		$.ajax({
			type: "POST",
			url: "../includes/email/getTemplatesSMS.php",
			dataType: "html",
			async: false,
			success: function(result){
				$("#templates-sms").html(result);
			},
			error: function(){
			}
		});
	}

	$('#count_message').html(text_max);

	$('#SMS').keyup(function(event) {
		var al = $('#SMS').val();
		var cleared = clearSMS(al);
		$('#SMS').val(cleared);

		var variables = cleared.split(/]/).length;
		var char = ((variables - 1) * 20);

		var text_length = $('#SMS').val().length;
		var text_remaining = text_max - text_length - char;
		var max_length = text_max - char;

		$("#SMS").attr("maxlength", max_length);		
		$('#count_message').html(text_remaining);
	});

	function clearSMS(text){
      text = text.replace(/[áàäâå]/, 'a');
      text = text.replace(/[éèëê]/, 'e');
      text = text.replace(/[íìïî]/, 'i');
      text = text.replace(/[óòöô]/, 'o');
      text = text.replace(/[úùüû]/, 'u');
      text = text.replace(/[ýÿ]/, 'y');
      text = text.replace(/[ñ]/, 'n');
	  text = text.replace(/[ç]/, 'c');
	  text = text.replace(/[ÁÀÄÂ]/, 'A');
      text = text.replace(/[ÉÈËÊ]/, 'E');
      text = text.replace(/[ÍÌÏÎ]/, 'I');
      text = text.replace(/[ÓÒÖÔ]/, 'O');
      text = text.replace(/[ÚÙÜÛ]/, 'U');
      text = text.replace(/[Ý]/, 'Y');
      text = text.replace(/[Ñ]/, 'N');
	  text = text.replace(/[Ç]/, 'C');
	  text = text.replace(/[%]/, '');

      return text;
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
	function mensajesEnviados() {
		$.ajax({
			type: "POST",
			url: "../includes/email/mensajesEnviados.php",
			async: false,
			success: function (data) {
				$("#mensajes_enviados").text(data);
			}
		});
	}
	$("select[name='estrategia_estadistica']").change(function () {
		var ObjectMe = $(this);
		var id = ObjectMe.val();
		fillQueuesEstadistica(id);
	});

	function fillQueuesEstadistica(id) {
		var data = "estrategia=" + id;

		$.ajax({
			type: "POST",
			url: "../includes/email/fillQueues.php",
			data: data,
			dataType: "html",
			async: false,
			success: function (data) {
				console.log(data);
				$("select[name='asignacion_estadistica']").html(data);
				$("select[name='asignacion_estadistica']").selectpicker('refresh');
			}
		});
	}
	$("select[name='asignacion_estadistica']").change(function () {
		var ObjectMe = $(this);
		var id = ObjectMe.val();
		fillFechas(id);
	});
	function fillFechas(id) {
		var data = "estrategia=" + id;

		$.ajax({
			type: "POST",
			url: "../includes/email/fillFechas.php",
			data: data,
			dataType: "html",
			async: false,
			success: function (data) {
				console.log(data);
				$("select[name='id_envio']").html(data);
				$("select[name='id_envio']").selectpicker('refresh');
			}
		});
	}
	$("select[name='id_envio']").change(function () {
		var id_envio = $(this).val();
		var Estrategia = $('#asignacion_estadistica').val()
		if(id_envio){
			getEstadistica(id_envio, Estrategia)
		}
	});
	function getEstadistica(id_envio) {
		$('#Cargando').modal({
			backdrop: 'static',
			keyboard: false
		})
		setTimeout(() => {
			$.ajax({
				type: "POST",
				url: "../includes/email/getSMSEstadistica.php",
				data: {
					id_envio: id_envio
				},
				async: false,
				success: function (response) {
					var response = JSON.parse(response);
					$('#enviados').text(response.Enviados)
					$('#entregados').text(response.Entregado)
					$('#no_entregados').text(response.No_Entregado)
					$('#rechazados').text(response.Rechazado)
					$('#pendientes').text(response.Pendiente)
					$('#error_proveedor').text(response.Error_Proveedor)
					console.log(response.dataSet)
					EnviadosTable = $('#sms_enviados').DataTable({
						data: response.dataSet,
						destroy: true,
						columns: [
							{ data: 'Rut' },
							{ data: 'Nombre' },
							{ data: 'Fono' },
							{ data: 'Estado' }
						],
						"columnDefs": [
							{
								className: "dt-center",
								"targets": [0, 1, 2, 3],
								"render": function (data, type, row) {
									return "<div style='text-align: center;'>" + data + "</div>";
								}
							}
						]
					});
					$('#Cargando').modal('hide')
				}
			});
		}, 1000);
	}
	$(".filtrarEstadistica").click(function () {
		var id_envio = $("select[name='id_envio']").val();
		if (id_envio) {
			var Estado = $(this).attr('id');
			if (Estado == 'ENVIADO') {
				Estado = '';
			}
			EnviadosTable
				.columns(3)
				.search(Estado)
				.draw();
		}
	});
	$("body").on("click", "#Download", function () {
		var id_envio = $("select[name='id_envio']").val();
		if (id_envio != "") {
			window.open("../includes/email/descargarSMSEstadistica.php?id_envio=" + id_envio + "", "_blank");
		} else {
			bootbox.alert("Debe seleccionar una Fecha");
		}
	});
});
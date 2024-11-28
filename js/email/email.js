$(document).ready(function(){
	var text_max = 160;
	//EnviadosTable = $('#email_enviados').DataTable();

	$.ajax({
		type: "POST",
		url: "../includes/email/verificarEnvioCorreo.php",
		async: false,
		success: function(data){
			console.log(data);
			var result = JSON.parse(data);
			console.log(result);
			if(result.respuesta == "1"){
				$("#enviar-mail").removeAttr("disabled");
			}else if(result.respuesta == "2"){
				$('#enviar-mail').prop('disabled', true);
				niftyWarning("¡Alerta! Debe ingresar entre las " + result.horaInicio + " y las " + result.horaFin + " para poder enviar Correos.");
			}else if(result.respuesta == "3"){
				$('#enviar-mail').prop('disabled', true);
				niftyWarning("¡Alerta! El cedente no tiene configuraciones establecidas para el envío de correo.");
			}else if(result.respuesta == "4"){
				$('#enviar-mail').prop('disabled', true);
				niftyWarning("¡Alerta! No existe configuración para realizar el envío de correo.");
			}else if(result.respuesta == "5"){
				$('#enviar-mail').prop('disabled', true);
				niftyWarning("¡Alerta! No está habilitado para realizar envío de correos en el día.");
			}else{
				$('#enviar-mail').prop('disabled', true);
				niftyDanger("Error. Intente ingresar más tarde.");
			}
		}
	});

	// Ejecutar Cron
    $('#ejecutar-cron').on('click', function(){
        $.ajax({
			type: "POST",
			url: "cron-email-masivo.php",
			async: false,
			beforeSend: function(){
				$("#message").css("display","block");
			},
			success: function(result){
				if(result==1){
					$(".alert").addClass("alert-info");
					$(".alert").html("No existen registros de envío de email programados");

				}else if(result==2) {
					$(".alert").addClass("alert-info");
					$(".alert").html("No ha transcurrido el tiempo de espera para el próximo envío.");
				}else if(result==3) {
					$(".alert").addClass("alert-info");
					$(".alert").html("Envío programado procesado, el próximo envío se ejecutará en 30 min.");
				}
			}
		});
		setTimeout(function() { 
			$("#message").fadeOut(1000);
			$(".alert").html(""); 
		}, 7000);
    });

    // USAR TEMPLATE
    $('#template').on('change', function(){
        var id = $('#template').val();
		$.ajax({
			type: "POST",
			url: "../includes/email/select-template.php",
			data: { id:id},
			dataType: "html",
			async: false,
			success: function(result){
				var data = JSON.parse(result);
				$('#summernote').summernote('code',data[0]);
				$('#asunto').val(data[3]);
			}
		});
	});

    // Buscar correos
    $('#asignacion').on('change', function(){
        var table = $('#asignacion').val();
		$.ajax({
			type: "POST",
			url: "../includes/email/info-estrategia.php",
			data: {table:table},
			dataType: "html",
			success: function(result){
				console.log(result);
				var data = JSON.parse(result);
				$('#cantidad-rut').val(data[0]);
				$('#cantidad-emails').val(data[1]);
			}
		});
    });

	$("#enviar-prueba").on('click', function(){
		var asunto 	= $("#asunto").val();
		var to 		= $("#email-prueba").val();
		var html 	= $('#summernote').summernote('code');
		var cedente = $("#cedente").val();

		if(to){
			$.ajax({
				type: "POST",
				url: "../includes/email/enviar-prueba.php",
				data: {to:to, html:html, asunto: asunto, cedente: cedente},
				dataType: "html",
				async: false,
				success: function(result){
					niftySuccess("Email enviado con éxito");
				},
				error: function(){
					niftyDanger("Error al enviar email");
				}
			});
		} else {
			niftyWarning("Debe indicar al menos un correo electrónico.");
		}
	});

	// SUMMERNOTE CLICK TO EDIT
    $('#clean-temp').on('click', function(){
       limpiarCamposTemplate();
	});

	$('#clean-temp-sms').on('click', function(){
	   limpiarTemplateSMS();
	});

	function limpiarCamposTemplate(){
		$("#nombre-template").val('');
		$("#asunto-template").val('');
		$('#summernote').summernote('code','');
		$("#save-temp").removeAttr("disabled");
		$('#update-temp').attr('disabled', 'disabled');
	}

	function limpiarTemplateSMS(){
		$("#SMS").val("");
		$("#nombre-template").val("");
		$("#save-temp-sms").removeAttr("disabled");
		$('#update-temp-sms').attr('disabled', 'disabled');
	}

    $('#save-temp').on('click', function(){	
		var tname = $("#nombre-template").val();
		var tasunto = $("#asunto-template").val();
		var template = $('#summernote').summernote('code');
		$("#message").css("display","block");
		if ((template == '<p><br></p>') || (template == "") || (tname == "") || (tasunto == "")){
			niftyWarning("Debe ingresar todos los datos");
            return false;
		}
		$.ajax({
			type: "POST",
			url: "../includes/email/save-template.php",
			data: { tname:tname, template:template, tasunto:tasunto},
			dataType: "html",
			async: false,
			success: function(result){
				if(result == 1){
					niftySuccess("Template guardado con éxito");
					getTemplates();
				} else if(result == 3){
					niftyWarning("¡Alerta! Ya existe un Template con ese nombre.");
				} else {
					niftyDanger("Error al guardar template");
				}
			},
			error: function(){
				niftyDanger("Error al guardar template");
			}
		}); 
	});
	
	$('#save-temp-sms').on('click', function(){	
		var tname = $("#nombre-template").val();
		var template = $('#SMS').val();

		if ((template == "") || (tname == "")){
			niftyWarning("Debe ingresar todos los datos");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "../includes/email/save-template.php",
			data: { tname:tname, template:template },
			dataType: "html",
			async: false,
			success: function(result){
				if(result == 1){
					niftySuccess("Template guardado con éxito");
					getTemplatesSMS();
				} else if(result == 3){
					niftyWarning("¡Alerta! Ya existe un Template con ese nombre.");
				} else {
					niftyDanger("Error al guardar template");
				}
			},
			error: function(){
				niftyDanger("Error al guardar template");
			}
		}); 
	});

    //UPDATE TEMPLATE (EDITAR)
	$("#update-temp").on('click', function(){
		var id 		= $('#current-template').val();
		var tname 	= $("#nombre-template").val();
		var tasunto = $("#asunto-template").val();
		var template = $('#summernote').summernote('code');

		if(id){
			$.ajax({
				type: "POST",
				url: "../includes/email/update-template.php",
				data: { tname:tname, template:template, templateid:id, tasunto:tasunto},
				dataType: "html",
				async: false,
				success: function(result){
					if(result == 1){
						niftySuccess("Template actualizado con éxito");
						$('#update-temp').attr('disabled', 'disabled');
						$("#save-temp").removeAttr("disabled");
						limpiarCamposTemplate();
						getTemplates();
					} else if(result == 3){
						niftyWarning("¡Alerta! Ya existe un Template con el nombre.");
					} else {
						niftyDanger("Error al actualizar template");
					}
				},
				error: function(){
					niftyDanger("Error al actualizar template");
				}
			});
		}
	});

	$("#update-temp-sms").on('click', function(){
		var template = $('#SMS').val();
		var tname 	= $("#nombre-template").val();
		var id 		= $('#current-template-sms').val();
		if(id){
			$.ajax({
				type: "POST",
				url: "../includes/email/update-template.php",
				data: { tname:tname, template:template, templateid:id },
				dataType: "html",
				async: false,
				success: function(result){
					if(result == 1){
						niftySuccess("Template actualizado con éxito");
						$('#update-temp-sms').attr('disabled', 'disabled');
						$("#save-temp-sms").removeAttr("disabled");
						limpiarTemplateSMS();
						getTemplatesSMS();
					} else if(result == 3){
						niftyWarning("¡Alerta! Ya existe un Template con el nombre.");
					} else {
						niftyDanger("Error al actualizar template");
					}
				},
				error: function(){
					niftyDanger("Error al actualizar template");
				}
			});
		}
	});

	// ELIMINAR TEMPLATE
	$("body").on('click',".delete-template", function(){
		var tid = $(this).data('id');
		var ObjectButton = $(this);
		var ObjectTD = ObjectButton.closest("td");
		var canal = ObjectTD.attr("id");

		$.ajax({
			type: "POST",
			url: "../includes/email/delete-templates.php",
			data: { templateid:tid, canal:canal },
			dataType: "html",
			async: false,
			success: function(result){
				if(result == 2){
					niftyDanger("Error al eliminar template");
				} else {
					$('tr[data-id="'+tid+'"]').remove();
					niftySuccess("Template eliminado exitosamente.");
					if(canal == "sms"){
						getTemplatesSMS();
					}else{
						getTemplates();
					}
				}
			},
			error:function(){
				niftyDanger("Error al eliminar template");
			}
		});
	});

	//USAR TEMPLATE
	$('body').on('click', '.use-template', function(){
		var tid = $(this).data('id');

		$.ajax({
			type: "POST",
			url: "../includes/email/select-template.php",
			data: { id:tid },
			dataType: "html",
			async: false,
			beforeSend: function(){
				$("#message").css("display","block");
			},
			success: function(result){		
				var data = JSON.parse(result);
				$('#summernote').summernote('code',data[0]);
				$("#current-template").val(data[2]);
				$("#nombre-template").val(data[1]);
				$("#asunto-template").val(data[3]);

				niftySuccess("Template seleccionado.");

				$("#update-temp").removeAttr("disabled");
				$('#save-temp').attr('disabled', 'disabled');
			}
		});
	});

	$('body').on('click', '.use-template-sms', function(){
		var tid = $(this).data('id');

		$.ajax({
			type: "POST",
			url: "../includes/email/select-template.php",
			data: { id:tid, canal:"sms" },
			dataType: "html",
			async: false,
			beforeSend: function(){
				$("#message").css("display","block");
			},
			success: function(result){
				console.log(result);
				var data = JSON.parse(result);
				$('#SMS').val(data[0]);
				$("#SMS").trigger("keyup");

				$("#current-template-sms").val(data[2]);
				$("#nombre-template").val(data[1]);

				niftySuccess("Template seleccionado.");

				$("#update-temp-sms").removeAttr("disabled");
				$('#save-temp-sms').attr('disabled', 'disabled');
			}
		});
	});

	//USAR TEMPLATE
	$(".save-conf").on('click', function(){
		var tipoModulo = $("select[name='tipoModulo']").val();
		//var protocolo = $("input:radio[name=protocol]:checked").val();
		var protocolo = 1;
		var secure = $("input:radio[name=secure]:checked").val();
		var host = $("#host").val();
		var puerto = $("#port").val();
		var email = $("#email").val();
		var password = $("#pass").val();
		var from = $("#from").val();
		var fromname = $("#fromname").val();
		var ConfirmReadingTo = $("#ConfirmReadingTo").val();

		$.ajax({
			type: "POST",
			url: "../includes/email/save-config.php",
			data: { tipoModulo: tipoModulo, prot: protocolo, secure: secure, host: host, port: puerto, email: email, pass: password, from: from, fromname: fromname, ConfirmReadingTo: ConfirmReadingTo },
			dataType: "html",
			async: false,
			beforeSend: function(){
				$("#message").css("display","block");;
			},
			success: function(result){	
				console.log(result);
				if(result == 1){
					niftySuccess("Configuración guardada exitosamente.");
				}
			},
			error: function(){
				niftyDanger("Error al guardar cambios");
			}
		});
	});

	// Reenviar
	$(this).on('click','.reenviar', function(){
		var id = $(this).data('id');

		$.ajax({
			type: "POST",
			url: "../includes/email/reenviar-correo.php",
			data: { id:id },
			dataType: "html",
			async: false,
			beforeSend: function(){
				$("#message").css("display","block");
			},
			success: function(result){
				alert(result);
				if(result == 1){
					$('tr[data-id="'+id+'"] button').addClass("disabled");
					$(".alert").addClass("alert-success");
					$(".alert").html("Email reenviado exitósamente.");

				} else {		
					$(".alert").addClass("alert-danger");
					$(".alert").html("Error en el envío, por favor intente de nuevo más tarde.");				
				}
			},
			error:function(){
				alert('error');
			}
		});
		setTimeout(function() { 
			$("#message").fadeOut(1000); 
			$(".alert").html("");
			$(".alert").removeClass("alert-danger");
			$(".alert").removeClass("alert-success"); 
		}, 7000);

	});

	$("body").on("change",".inputCheckFactura",function(){
		var ObjectMe = $(this);
		var ObjectTR = ObjectMe.closest("tr");
		var ID = ObjectTR.attr("data-id");
		var ObjectTable = ObjectMe.closest("table");
		var Check = "1";
		if(!ObjectMe.find("input[type='checkbox']").is(":checked")){
			Check = "0";
		}
		ObjectTable.find(".inputCheckFactura").removeClass("active");
		if(Check == "1"){
			ObjectMe.addClass("active");
		}
		ChangeFacturaTemplate(ID,Check);
	});

	function ChangeFacturaTemplate(idTemplate,Check){
		$.ajax({
			type: "POST",
			url: "../includes/email/ChangeFacturaTemplate.php",
			data: { idTemplate:idTemplate, Check: Check },
			dataType: "html",
			async: false,
			success: function(result){
			}
		});
	}

	function getTemplates(){
		$.ajax({
			type: "POST",
			url: "../includes/email/getTemplates.php",
			dataType: "html",
			async: false,
			success: function(result){
				$("#templates").html(result);
			}
		});
	}

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
                $("select[name='asignacion']").html(data);
                $("select[name='asignacion']").selectpicker('refresh');
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
			}
		});
	}

	$("#canal").on("change", function(){
		var servicio = $("#canal").val();
		console.log(servicio);
		if(servicio == 0){
			$("#title-template").text("Template EMAIL");
			$(".template-email").css("display", "block");
			$(".template-sms").css("display", "none");
			$(".asunto-template").css("display", "block");
			limpiarCamposTemplate();
		}else if(servicio == 1){
			$("#title-template").text("Template SMS");
			$(".template-sms").css("display", "block");
			$(".template-email").css("display", "none");
			$(".asunto-template").css("display", "none");
			limpiarTemplateSMS();
		}
		$(".panel-template").css("display", "block");
	});

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

	function niftyWait(mensaje){
		$.niftyNoty({
			type: 'info',
			icon: 'fa fa-clock-o',
			message: mensaje,
			container: 'floating',
			timer: 5000
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
		fillCodigos(id);
	});
	function fillCodigos(id) {
		var data = "estrategia=" + id;

		$.ajax({
			type: "POST",
			url: "../includes/email/fillCodigos.php",
			data: data,
			dataType: "html",
			async: false,
			success: function (data) {
				console.log(data);
				$("select[name='codigo']").html(data);
				$("select[name='codigo']").selectpicker('refresh');
			}
		});
	}
	$("select[name='codigo']").change(function () {
		var Codigo = $(this).val();
		var Estrategia = $('#asignacion_estadistica').val()
		getEstadistica(Codigo,Estrategia)
	});
	function getEstadistica(Codigo, Estrategia){
		$('#Cargando').modal({
			backdrop: 'static',
			keyboard: false
		})
		setTimeout(() => {
			$.ajax({
				type: "POST",
				url: "../includes/email/getCorreosEstadistica.php",
				data: {
					Codigo: Codigo,
					Estrategia: Estrategia
				},
				async: false,
				success: function (response) {
					var response = JSON.parse(response);
					$('#enviados').text(response.Enviados)
					$('#recibidos').text(response.Recibidos)
					$('#abiertos').text(response.Abiertos)
					$('#rebotados').text(response.Rebotados)
					$('#pendientes').text(response.Pendientes)
					$('#totales').text(response.Totales)
					EnviadosTable = $('#email_enviados').DataTable({
						data: response.dataSet,
						destroy: true,
						columns: [
							{ data: 'Rut' },
							{ data: 'Nombre' },
							{ data: 'Correo' },
							{ data: 'Estado' },
							{ data: 'Accion' }
						],
						"columnDefs": [
							{
								className: "dt-center",
								"targets": [0, 1, 2, 3],
								"render": function (data, type, row) {
									return "<div style='text-align: center;'>" + data + "</div>";
								}
							},
							{
								"targets": 4,
								"searchable": false,
								"data": "Accion",
								"render": function (data, type, row) {
									if (data != '') {
										return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
									} else {
										return "<div style='text-align: center;'></div>";
									}
								}
							},
						]
					});
					$('#Cargando').modal('hide')
				}
			});
		}, 1000);
	}
	$(".filtrarEstadistica").click(function () {
		var Codigo = $("select[name='codigo']").val();
		if(Codigo){
			var Estado = $(this).attr('id');
			if(Estado == 'ENVIADO'){
				Estado = '';
			}
			EnviadosTable
				.columns(3)
				.search(Estado)
				.draw(); 
		}
	});
	$("body").on("click", ".Delete", function () {
		var ObjectMe = $(this);
		var ObjectDiv = ObjectMe.closest("div");
		var email = ObjectDiv.attr("id");

		bootbox.confirm({
			message: "¿Esta seguro de eliminar el email seleccionado?",
			buttons: {
				confirm: {
					label: 'Si',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					deleteEmail(email);
				}
			}
		});
	});
	function deleteEmail(email) {
		$.ajax({
			type: "POST",
			url: "../includes/email/deleteEmail.php",
			dataType: "html",
			data: {
				email: email
			},
			async: false,
			success: function (data) {
				var Codigo = $('#codigo').val();
				var Estrategia = $('#asignacion_estadistica').val()
				getEstadistica(Codigo, Estrategia)
			}
		});
	}
});
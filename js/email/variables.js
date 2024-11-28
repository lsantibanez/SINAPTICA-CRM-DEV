$(document).ready(function(){

	$('#tipo').on('change', function(){
		var tipo = $('#tipo').val();
		console.log(tipo);
		if(tipo == 'tabla'){
			$('#agregar').css('display', 'block');
			$('#Alias').css('display', 'block');
			$('#ContainerOrdenamiento').css('display', 'block');
			$('#previsualizar').css('display', 'block');
			$('#operacion-wrapper').css('display', 'none');
		} else if(tipo == 'operacion'){
			$('#agregar').css('display', 'none');
			$('#Alias').css('display', 'none');
			$('#ContainerOrdenamiento').css('display', 'none');
			$('#operacion-wrapper').css('display', 'block');
			$('#previsualizar').css('display', 'none');
		} else {
			$('#agregar').css('display', 'none');
			$('#Alias').css('display', 'none');
			$('#ContainerOrdenamiento').css('display', 'none');
			$('#operacion-wrapper').css('display', 'none');
			$('#previsualizar').css('display', 'none');
		}
	});

	$('#tabla').on('change', function(){
		var tabla = $('#tabla').val();	
		switch(tabla){
			case "Persona":
				$('#campos-deuda').css("display","none");
				$('#campos-persona').css("display","block");
				$('#campos-direcciones').css("display","none");
				$('#campos-mail').css("display","none");
			break;
			case "Deuda":
				$('#campos-persona').css("display","none");
				$('#campos-deuda').css("display","block");
				$('#campos-direcciones').css("display","none");
				$('#campos-mail').css("display","none");
			break;
			case "Direcciones":
				$('#campos-persona').css("display","none");
				$('#campos-deuda').css("display","none");
				$('#campos-direcciones').css("display","block");
				$('#campos-mail').css("display","none");
			break;
			case "Mail":
				$('#campos-persona').css("display","none");
				$('#campos-deuda').css("display","none");
				$('#campos-direcciones').css("display","none");
				$('#campos-mail').css("display","block");
			break;
		}
	});

	$('#agregar').on('click', function(){
        var tabla = $('#tabla').val();	
        var selector = tabla.toLowerCase();
        var campo = $('#campos-'+selector+' select').val();
		var Alias = $("#Alias").val();

		if(campo != ""){
			if(Alias != ""){
				var fields = $("#fields").val();
				var FieldsArray = fields.split(",");
				var CanAdd = true;
				for(var i=0; i<=FieldsArray.length - 1; i++){
					var CampoTmp = FieldsArray[i];
					var CampoTmpArray = CampoTmp.split("|");
					if(campo == CampoTmpArray[0]){
						CanAdd = false;
					}
				}
				if(CanAdd){
					if(fields == ''){
						$("#fields").val(campo+"|"+Alias);
					} else{
						$("#fields").val(fields+','+campo+"|"+Alias);
					}
					$("#previsualizar table thead tr").append('<th><span><strong class="field">'+campo+'</strong><br>'+Alias+'</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th>');
					var Fields = $("#fields").val();
					var FieldsArray = Fields.split(",");
					var Options = "";
					for(var i=0; i<=FieldsArray.length - 1; i++){
						var CampoTmpArray = FieldsArray[i].split("|");
						Options += "<option value='"+CampoTmpArray[0]+"'>"+CampoTmpArray[0]+"</option>";
					}
					$("select[name='Ordenamiento']").html(Options);
					$("select[name='Ordenamiento']").selectpicker("refresh");
				}else{
					bootbox.alert("El campo "+campo+" ya fue agregado");
				}
			}else{
				bootbox.alert("Debe indicar un alias");
			}
		}else{
			bootbox.alert("Debe seleccionar un campo");
		}
	}); 

	$("body").on("click",".deleteCol",function(){
		var ObjectMe = $(this);
		var ObjectTh = ObjectMe.closest("th");
		var ObjectSpan = ObjectTh.find("span");
		//var Column = ObjectSpan.html();
		var Column = ObjectSpan.find(".field").html();
		var Fields = $("#fields").val();
		var FieldsArray = Fields.split(",");
		var FieldsArrayTmp = [];
		for(var i=0; i<=FieldsArray.length - 1; i++){
			var CampoTmp = FieldsArray[i];
			var CampoTmpArray = CampoTmp.split("|");
			if(Column != CampoTmpArray[0]){
				FieldsArrayTmp.push(FieldsArray[i]);
			}
		}
		ObjectTh.remove();
		$("#fields").val(FieldsArrayTmp.join(","));
	});

	$("#guardar-variable").on('click', function(){
		/***********************
		 * Tipo Envío 0: EMAIL *
		 * Tipo Envío 1: SMS   *
		 ***********************/
		var tipo 		= $("#tipo").val();
		var tabla 		= $("#tabla").val();
		var tipoEnvio 	= $("#canal").val();
		var nombre 		= $("#nombre").val();

		var Ordenamiento = $("select[name='Ordenamiento']").val();

		if(tipo == 'tabla'){
			var campos = $("#fields").val()+"$&$"+Ordenamiento;
		} else{
	        var selector = tabla.toLowerCase();
	        var campos = $('#campos-'+selector+' select').val();
		}

		var operacion = $("#operacion").val();

		if(nombre !== ''){
			$.ajax({
				type: "POST",
				url: "../includes/email/guardar-variable.php",
				data: { nombre: nombre, tabla:tabla, tipo:tipo, campos:campos, operacion:operacion, tipoEnvio:tipoEnvio},
				dataType: "html",
				success: function(result){
					if(result == 1){
						niftySuccess("Variable guardada con éxito");
						if(tipoEnvio == 0){
							getVariables();
						}else{
							getVariablesSMS();
						}
					} else if(result == 3){
						niftyWarning("Variable ya existe. Por favor ingrese un nombre diferente.");
					} else {
						niftyDanger("Error al guardar variable.");
					}
				},
				error: function(){
					niftyDanger("Error al guardar variable.");
				}
			}); 
		} else{
			niftyWarning("Debe ingresar un nombre de variable.");
		}
	});

	$('#clean').on('click', function(){
		var canal = $('select[name=canal]').val();
		if(canal != 1){
			$('select[name=tipo]').val("");
		}
		$("#nombre").val('');
		$('select[name=tabla]').val("");
		$('select[name=campos]').val("");
		$('.selectpicker').selectpicker('refresh');
		$("#previsualizar table thead tr").html('');
		$("#guardar-variable").attr('disabled', false);
		$("#actualizar-variable").css('display', 'none');
	});

	$(".edit-var").each(function(){
		$(this).on('click', function(){
			$(".panelVariables").css("display", "block");
			var vid = $(this).data('id');
			var ObjectButton = $(this);
			var ObjectTD = ObjectButton.closest("td");
			var tipoEnvio = ObjectTD.attr("id");
			$("#Alias").hide();
			$("#ContainerOrdenamiento").hide();
			$.ajax({
				type: "POST",
				url: "../includes/email/select-var.php",
				data: { id:vid, tipoEnvio:tipoEnvio },
				dataType: "html",
				beforeSend: function(){
					$("#message").css("display","block");
				},
				success: function(result){
					console.log(result);
					var data = JSON.parse(result);
					$("#current-var").val(data[0]);
					$("#nombre").val(data[1]);	
					$("#tipo").val(data[2]);
					$("#tabla").val(data[3]);	
					$("#operacion").val(data[5]);
					$('#campos-persona').css("display","none");
					$('#campos-deuda').css("display","none");
        			var selector = data[3].toLowerCase();
					if(data[2] == 'valor'){
						$("#campos-"+selector).css('display','block');
						$("#campos-"+selector+" select").val(data[4]);
						$("#previsualizar").css('display','none');
						$("#operacion-wrapper").css('display','none');
						if(tipoEnvio == "sms"){
							$("#titleVariable").text("Variable SMS");
							$('select[name=canal]').val(1);
							$('select[name=tipo]').attr('disabled',true);
						}else{
							$("#titleVariable").text("Variable EMAIL");
							$('select[name=canal]').val(0);
							$('select[name=tipo]').attr('disabled',false);
						}
					}else if(data[2] == 'tabla'){
						$("#agregar").show();
						$("#Alias").show();
						$("#ContainerOrdenamiento").show();
						$('select[name=canal]').val(0);
						$("#campos-"+selector).css('display','block');
						var Campos = data[4];
						var CamposArray = Campos.split("$&$");
						var FieldList = CamposArray[0];
						var FieldOrder = CamposArray.length <= 1 ? "" : CamposArray[1];
						
						var FieldsArray = FieldList.split(",");
						var Options = "";
						for(var i=0; i<=FieldsArray.length - 1; i++){
							var CampoTmpArray = FieldsArray[i].split("|");
							var Selected = "";
							if(FieldOrder == CampoTmpArray[0]){
								Selected = "selected='selected'";
							}
							Options += "<option "+Selected+" value='"+CampoTmpArray[0]+"'>"+CampoTmpArray[0]+"</option>";
						}
						$("select[name='Ordenamiento']").html(Options);
						$("select[name='Ordenamiento']").selectpicker("refresh");

						$("#fields").val(FieldList);
						
						$("#previsualizar").css('display','block');
						$("#previsualizar table thead tr").html(data[6]);
						$("#operacion-wrapper").css('display','none');
					} else {
						$('select[name=canal]').val(0);
						$("#campos-"+selector).css('display','block');
						$("#campos-"+selector+" select").val(data[4]);
						$("#operacion-wrapper").css('display','block');
						$("#previsualizar").css('display','none');
					}
					$('.selectpicker').selectpicker('refresh')
					$("#actualizar-variable").css('display','inline-block');
					$("#guardar-variable").attr('disabled', true);

					niftySuccess("Variable Seleccionada");
				}
			});
		});
	});

	$("body").on('click','.delete-var', function(){
		var vid = $(this).data('id');
		var ObjectButton = $(this);
		var ObjectTD = ObjectButton.closest("td");
		var tipoEnvio = ObjectTD.attr("id");

		$.ajax({
			type: "POST",
			url: "../includes/email/delete-var.php",
			data: { vid:vid, tipoEnvio:tipoEnvio },
			dataType: "html",
			success: function(result){	
				if(result == 2){
					niftyDanger("Se produjo un error al eliminar variable.");
				} else {
					$('tr[data-id="'+vid+'"]').remove();
					niftySuccess("Variable eliminada exitosamente.");
				}
			},
			error:function(){
				niftyDanger("Se produjo un error al eliminar variable.");
			}
		});
	});

	$("#actualizar-variable").on('click', function(){
		var tipo 	= $("#tipo").val();
		var tabla 	= $("#tabla").val();
		var nombre 	= $("#nombre").val();
		var id 		= $("#current-var").val();
		var tipoEnvio = $("#canal").val();

		var Ordenamiento = $("select[name='Ordenamiento']").val();

		if(tipo == 'tabla'){
			var campos = $("#fields").val()+"$&$"+Ordenamiento;;
		} else{
	        var selector = tabla.toLowerCase();
	        var campos = $('#campos-'+selector+' select').val();
		}
		var operacion = $("#operacion").val();

		if(nombre !== ''){
			$.ajax({
				type: "POST",
				url: "../includes/email/actualizar-variable.php",
				data: { id:id, nombre: nombre, tabla:tabla, tipo:tipo, campos:campos, operacion:operacion, tipoEnvio:tipoEnvio},
				dataType: "html",
				success: function(result){
					if(result == 1){
						niftySuccess("Cambios guardados con éxito.");
						if(tipoEnvio == 0){
							getVariables();
						}else{
							getVariablesSMS();
						}
					} else if(result == 3){
						niftyWarning("Variable ya existe, por favor ingrese otro nombre.");
					} else {
						niftyDanger("Error al guardar cambios.");
					}
				},
				error: function(){
					niftyDanger("Error al guardar cambios.");
				}
			}); 
		} else{
			niftyWarning("Ingrese un nombre de variable.");
		}
	});

	function getVariables(){
		$.ajax({
			type: "POST",
			url: "../includes/email/getVariables.php",
			data: { },
			dataType: "html",
			success: function(result){
				$("#templates").html(result);
			},
			error: function(){
			}
		});
	}

	function getVariablesSMS(){
		$.ajax({
			type: "POST",
			url: "../includes/email/getVariablesSMS.php",
			data: { },
			dataType: "html",
			success: function(result){
				$("#templatesSMS").html(result);
			},
			error: function(){
			}
		});
	}

	$("#canal").on("change", function(){
		var servicio = $("#canal").val();
		console.log(servicio);
		if(servicio == 0){
			$("#titleVariable").text("Variable EMAIL");
			$("select[name=tipo]").removeAttr("disabled");
			$('select[name=tipo]').val("");
			$('select[name=tabla]').val("");
			$('select[name=campos]').val("");
			$('.selectpicker').selectpicker('refresh');
		}else if(servicio == 1){
			$("#titleVariable").text("Variable SMS");
			$('select[name=tipo]').val("valor");
			$('select[name=tipo]').attr('disabled',true);
			$('select[name=tabla]').val("");
			$('select[name=campos]').val("");
			$('.selectpicker').selectpicker('refresh');
			$( "#tipo" ).trigger("change");
		}

		$(".panelVariables").css("display", "block");
		$("#guardar-variable").attr('disabled', false);
		$("#actualizar-variable").css('display', 'none');
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
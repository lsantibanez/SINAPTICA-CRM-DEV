$(document).ready(function(){
    $('#contenido').hide();
	var id_dial = $('#id_dial').val();
	var rut_dial = $('#rut_ultimo').val();
	var cedente_dial = $('#IdCedente').val();
	var cedente= $('#IdCedente').val();
	var fono_dial = $('#fono_dial').val();
	var usuario_dial = $('#usuario_dial').val();
	var dateInicioLlamada;
	var idFilaFono;
	var rutEstrategia;
	var CallLlamadaPredictivo;
	var sonidoHangup = false;
	var intervaloEsperandoLlamada;
	var intervaloHangup;
	var pausaEjec; // 1 = noPausa --- 2 = pausa
	var color;
	var texto;
	var icono;
	var nombrePausa;
	var sonido;
	var tiempo = {
        hora: 0,
        minuto: 0,
        segundo: 0
    };
	var tiempo_corriendo = null;
	var id;

	var canales = "";
	var prioridades = "";

	var DatosCedente;
	//var DialProviderConfigured = false;
	var Queue = "";

	getDatosCedente();
	mostrarColaDiscador();
	GetServerStatus();

	if(DialProviderConfigured){
		var DiscadorSocket = io.connect("http://" + GlobalData.focoConfig.IpServidorDiscadoAux + ":65530");
	}else{
		$("#entrar").prop("disabled",true);
		bootbox.alert("Servidor de discado no configurado. Comuniquese con soporte tecnico.");
	}

	function funcionNivel1(ce) {
		$('#Cargando').modal({
			backdrop: 'static',
			keyboard: false
		})
		setTimeout(function () {
			var busqueda = $('#seleccione_tipo_busqueda').val();
			$.ajax({
				type: "POST",
				url: "../includes/crm/nivel_1.php",
				data: { cedente: ce, busqueda: busqueda },
				success: function (response) {
					$('.nivel_1_ocultar').hide();
					$('.nivel_1_mostrar').html(response);
					$('.selectpicker').selectpicker('refresh')
					$('#Cargando').modal('hide')
				}
			});
		}, 1000);
	}

	function funcionNivelRapido(ce) {
		$.ajax({
			type: "POST",
			url: "../includes/crm/nivel_rapido.php",
			data: ce,
			success: function (response) {
				$('#respuesta_rapida').html(response);
				$('.selectpicker').selectpicker('refresh')
				$('#respuesta_rapida_ocultar').hide();
			}
		});
	}

	function ejecutarSonido(sonido){
		var rutaSonido = sonido; 
		var sonido = new Audio();
		sonido.addEventListener('play', function () {}, false);
		sonido.addEventListener('ended', function () {}, false);
		sonido.src = rutaSonido;
		sonido.play();
	}
	$(document).on('click', '.Break', function() {
		var desactivado = $(this).closest('i').attr('disabled');
		if (desactivado != 'disabled'){
			if (pausaEjec == 1){
				id = $(this).closest('i').attr('id');
				pausaEjecutivo(id);
			}else{
				if (pausaEjec == 2){
					unPauseEjecutivo();
				}	
			}
		}
	});
	function desactivarBotonera(id){
		var idBoton;
		$("#Botonera i").each(function(){
			idBoton = $(this).attr('id');
			if ((idBoton) != (id)){
				$(this).attr('disabled', 'disabled');
			}        	   
		});
	}
	function activarBotonera(){
		$("#Botonera i").each(function(){
			$(this).removeAttr('disabled');
		});
	}
	function accionesBotonera(id){
		switch (id){
			case 'bano':
				color = 'danger';
				texto = 'Estoy en el Baño';
				icono = 'ion-waterdrop';
				nombrePausa = 'Bano';
			break;
			case 'descanso':
				color = 'info';
				texto = 'Tomando café';
				icono = 'ion-coffee';
				nombrePausa = 'Cafe';
			break;
			case 'soporte':
				color = 'warning';
				texto = 'Soporte';
				icono = 'ion-settings';
				nombrePausa = 'Soporte';
			break;
			case 'office':
				color = 'mint';
				texto = 'Office';
				icono = 'ion-edit';
				nombrePausa = 'Office';
			break;
			case 'capacitacion':
				color = 'purple';
				texto = 'Estoy en Capacitación';
				icono = 'ion-help-buoy';
				nombrePausa = 'Capacitacion';
			break;
			case 'reunion':
				color = 'success';
				texto = 'Estoy en reunión';
				icono = 'ion-person-stalker';
				nombrePausa = 'Reunion';
			break;
		}
		
	}
	function pausaEjecutivo(id){
		desactivarBotonera(id);
		pausaEjec = 2;
		$(".alert-wrap").remove();
		ShowNotification = false;
		accionesBotonera(id);
		//nuevoEstatus('PAUSED',nombrePausa);
		nuevoEstatus('PAUSADO',nombrePausa);
		intervalo_EsperandoLlamada(texto,color,icono);
		pausePredictivo();
		
	}
	function unPauseEjecutivo(){
		var cedente = $('#IdCedente').val();
		rutEstrategia = 1;
		var rut_buscado;
		var fono;
		var idCola = $('#seleccione_tipo_busqueda').val();	
		$(".alert-wrap").remove();
		activarBotonera();
		pausaEjec = 1;
		nuevoEstatus('DISPONIBLE','');
		CallLlamadaPredictivo = true;
		$("#CanalGrabacion").val("");
		console.log("CallLlamadaPredictivo es TRUE");
		unPausePredictivo();
	}
	function tiempollamadaInicio(){
		dateInicioLlamada = new Date();
	}
	function transcurrido(){
		var dateFinLlamada = new Date();
		var diferencia = (dateFinLlamada-dateInicioLlamada);
		segLlamadaTranscurrido = Math.floor(diferencia / 1000);
		return segLlamadaTranscurrido;
	}
	function limpiarSesion(){
		$.ajax({
			type: "POST",
			url: "../includes/crm/limpiar_sesion.php",
			data: 'a=1',
			success: function(){
			}
		});
	}
	if(id_dial==1){
		var data_fono = 'rut='+rut_dial;
		var data_deudas = 'rut='+rut_dial+"&cedente="+cedente_dial;
	}else{
		var nombre_usuario_foco = $('#nombre_usuario_foco').val();
		$(document).on('keyup','.solo-numero',function (){
			this.value = (this.value + '').replace(/[^0-9]/g, '');
		});
		function validarNuevoCorreo(){
			var sw1 = 0;
			var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#nombre").val() == "" ){
				$("#nombre").focus().after("<span class='errorL'>Ingrese un nombre</span>");
			    sw1 = 1;
		    }
		    if( $("#correo_nuevo").val() == "" || !emailreg.test($("#correo_nuevo").val()) ){
	        	$("#correo_nuevo").focus().after("<span class='errorL'>Ingrese un email correcto</span>");
				sw1 = 1;
	     	}
	     	if( $("#cargo").val() == "0"  ){
	        	$("#cargo").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
	     	if(  $("#uso").val() == null || $("#uso").val() == "0" ){
	        	$("#uso").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0){
		    	return false;
		    }else{
		    	return true;
		    }
		};
		function validarNuevoCorreocc(){
			var sw1 = 0;
			var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#nombre_cc").val() == "" ){
				$("#nombre_cc").focus().after("<span class='errorL'>Ingrese un nombre</span>");
			    sw1 = 1;
		    }
		    if( $("#correo_nuevo_cc").val() == "" || !emailreg.test($("#correo_nuevo_cc").val()) ){
	        	$("#correo_nuevo_cc").focus().after("<span class='errorL'>Ingrese un email correcto</span>");
				sw1 = 1;
	     	}
	     	if( $("#cargo_cc").val() == "0"  ){
	        	$("#cargo_cc").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
	     	if(  $("#uso_cc").val() == null || $("#uso_cc").val() == "0" ){
	        	$("#uso_cc").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0){
		    	return false;
		    }else{
		    	return true;
		    }
		};

		function validarNuevaDireccion(){
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#direccion_nuevo").val().trim() == "" ){
				$("#direccion_nuevo").focus().after("<span class='errorL'>Ingrese una dirección</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0){
		    	return false;
		    }else{
		    	return true;
		    }
		};
		function validarNuevoTelefono(){
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#fono_discado_nuevo").val().trim() == "" ){
				$("#fono_discado_nuevo").focus().after("<span class='errorL'>Ingrese un telefono</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0){
		    	return false;
		    }else{
		    	return true;
		    }
		};
		var user_dial = $('#usuario_usuario_foco').val();
		$(document).on('click', '#AddCorreoN', function() {
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreo();
			if (resValidacion == true){
				return false;
			}
			var correo_nuevo = $('#correo_nuevo').val();
			var rut_correo = $('#rut_ultimo').val();
			var cargo = $('#cargo').val();
			var uso = $('#uso').val();
			var nombre = $('#nombre').val();
			var data_correo_nuevo = "rut=" + rut_correo + "&correo_nuevo=" + correo_nuevo + "&cargo=" + cargo + "&uso=" + uso + "&nombre=" + nombre + "&Queue=" + Queue;
			$.ajax({
				type: "POST",
				url: "../includes/crm/insertar_correo.php",
				data:data_correo_nuevo,
				success: function(response){
					limpiarSesion();
					$('#mostrar_correo').html(response);
					$('#AggCorreoModal').modal('hide');
					$('#correo_nuevo').val("") ;
					$('#cargo').prop('selectedIndex',0);
					$('#uso').val("");
					$('.selectpicker').selectpicker('refresh')
				}
			});
			$.niftyNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : "Registro Guardado",
				container : 'floating',
				timer : 4000
			});
		});
		$(document).on('click', '#AddCorreoNcc', function() {
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreocc();
			if (resValidacion == true){
				return false;
			}
			var correo_nuevo = $('#correo_nuevo_cc').val();
			var rut_correo = $('#rut_ultimo_cc').val();
			var cargo = $('#cargo_cc').val();
			var uso = $('#uso_cc').val();
			var nombre = $('#nombre_cc').val();
			var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso+"&nombre="+nombre;
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertar_correo_cc.php",
				data:data_correo_nuevo,
				success: function(response){
					limpiarSesion();
					$('#mostrar_correo_cc').html(response);
					$('#AggCorreoModalcc').modal('hide');
					$('#correo_nuevo_cc').val("") ;
					$('#cargo_cc').prop('selectedIndex',0);
					$('#uso_cc').val("").selectpicker('refresh');
				}
			});
			$.niftyNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : "Registro Guardado",
				container : 'floating',
				timer : 4000
			});
		});
		function fonosLlamando($tipoGestion){
			// if (($tipoGestion == 1) || ($tipoGestion == 5)){
			// 	nextRut();
			// }else{
				$("#llamado"+idFilaFono).prop('checked',true);
				var CantFilas = 0;
				var CantMarcados = 1; // style="background-color:#CCFFFF"
				$("#mostrar_fonos table tr").each(function(indexTR){
					var ObjectTR = $(this);
					if(indexTR > 0){
						ObjectTR.find("td").each(function(indexTD){
							var ObjectTD = $(this);
							switch(indexTD){
								case 4:
								var Checkbox = ObjectTD.find("input[type='checkbox']");
								if(Checkbox.is(":checked")){
									CantMarcados++;
								}
								break;
							}
						});
						CantFilas++;
					}
				});
				// if(CantMarcados >= CantFilas){			
				// 	nextRut();
				// }
			}
		}
		function funcionMostrarFonos(data_fono){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_fonos_rut.php",
				data:data_fono,
				success: function(response){
					$('#mostrar_fonos').html(response);
					$('#mostrar_fonos_ocultar').hide();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
					$('#nuevo_correo_cc').prop("disabled",false);
					$('#script_cobranza_mostrar').show();
					$('#script_cobranza_ocultar').hide();
					$('#botones_modal').show();
				}
			});
		}
        function funcionMostrarFono(rut, fon){	
            $.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_fono.php",
				data: {rut: rut, fono: fon},
				success: function(response){
					console.log("MOSTRAR FONO");
					console.log(response);
					console.log("FIN MOSTRAR FONO");
					$('#mostrar_fonos').html(response);
					$('#mostrar_fonos_ocultar').hide();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
					$('#nuevo_correo_cc').prop("disabled",false);
					$('#script_cobranza_mostrar').show();
					$('#script_cobranza_ocultar').hide();
					$('#botones_modal').show();
				}
			});
		}
		function funcionMostrarDireccion(data_direccion){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_direccion_rut_predictivo.php",
				data:data_direccion,
				success: function(response){
					$('#mostrar_direccion').html(response);
					$('#mostrar_direccion_ocultar').hide();
				}
			});
		}
		function funcionMostrarDeudas(data_deudas){
			$.ajax({
				type: "POST",
				url: "../includes/crm/deudas_predictivo.php",
				data:data_deudas,
				success: function(response){
					if (isJson(response)){
						var json = JSON.parse(response);
						$('#mostrar_deudas table').empty();
						$('#mostrar_deudas').html(json.Table.trim());
						if (json.result) {
							$('#mostrar_deudas table').DataTable();
						}
					}

					funcionMostrar();
					funcionOcultar();
				},
				error: function(){
				}
			});
		}
		function functionMostrarUltimaGestion(data){
			$.ajax({
				type: "POST",
				url: "../includes/crm/ultimaGestion_predictivo.php",
				data:data,
				success: function(response){
					$('#mostrar_ultimagestion_ocultar').html(response);
					$('#mostrar_ultimagestion').hide();
				},
				error: function(){
				}
			});
		}
		function functionMostrarMejorGestion(data){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mejorGestion_predictivo.php",
				data:data,
				success: function(response){
					$('#mostrar_mejorgestion_ocultar').html(response);
					$('#mostrar_mejorgestion').hide();
				},
				error: function(){
				}
			});
		}
		function funcionMostrarRegistros(data_reg){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_reg.php",
				data:data_reg,
				success: function(response){
					$('#cantidad').html(response);
				}
			});
		}
        function funcionMostrarNombreCliente(rut,Queue){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_nombreRut.php",
				data:{rut:rut,Queue:Queue},
				success: function(response){
			        $('.nombre_cliente').html(response);
				}
			});
		}
		function funcionMostrarCorreo(data_correo){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_correo_rut_predictivo.php",
				data:data_correo,
				success: function(response){
					$('#mostrar_correo').html(response);
					$('#mostrar_correo_ocultar').hide();

				}
			});
		}
		function funcionMostrarCorreocc(data_correo){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_correo_rut_cc.php",
				data:data_correo,
				success: function(response){
					$('#mostrar_correo_cc').html(response);
					$('#mostrar_correo_ocultar_cc').hide();

				}
			});
		}
		function funcionMostrarGestion(data_gestion){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_gestion_rut.php",
				data:data_gestion,
				success: function(response){
					$('#mostrar_gestion').html(response);
					$('#mostrar_gestion_ocultar').hide();
					$('#mostrar_gestion').show();
				}
			});
		}
		function funcionMostrarGestionTotal(data_gestion_total){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_gestion_total_rut.php",
				data:data_gestion_total,
				success: function(response){
					
					$('#mostrar_gestion_total').html(response);
					$('#mostrar_gestion_total_ocultar').hide();
					$('#mostrar_gestion_total').show();
				}
			});
		}
		function funcionMostrarGestionDiaria(data_gestion_diaria){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_gestion_diaria_rut.php",
				data:data_gestion_diaria,
				success: function(response){
					$('#mostrar_gestion_diaria').html(response);
					$('#mostrar_gestion_diaria_ocultar').hide();
					$('#mostrar_gestion_diaria').show();
				}
			});
		}
		function funcionMostrarPagos(data_pagos){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_pagos_rut.php",
				data:data_pagos,
				success: function(response){
					$('#mostrar_pagos').html(response);
					$('#mostrar_pagos_ocultar').hide();
				}
			});
		}
		function funcionMostrarAgrupacion(cedente,prefijo,rut,Queue){
			var rut = rut;
			var data1 = "rut="+rut+"&Queue="+Queue;
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			var data3 = "rut="+rut+"&cedente="+cedente+"&Queue="+Queue;
			var data4 = "cedente="+cedente;
			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3)
			funcionMostrarCorreo(data1);
			functionMostrarUltimaGestion(data1);
			functionMostrarMejorGestion(data1);
				//funcionMostrarGestion(data1);
				//funcionMostrarGestionTotal(data1);
				//funcionMostrarGestionDiaria(data1);
				//funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
			mostrarScriptCompleto();
			mostrarPoliticas();
			mostrarMediosPago();
			limpiarSesion();
		}
		function unPausePredictivo(){

			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
			setTimeout(function(){
				$.ajax({
					type: "POST",
					url: "../includes/crm/unPausePredictivo.php",
					async: false,
					success: function(response){
						funcionLimpiar();
						ShowNotification = false;
						intervalo_EsperandoLlamada('Esperando LLamada','danger','pli-old-telephone');
						nuevoEstatus('DISPONIBLE','');
						$('#Cargando').modal('hide')
					}
				});
			},1000)
		}
		function pausePredictivo(){
			$.ajax({
				type: "POST",
				url: "../includes/crm/pausaPredictivo.php",
				//data:ce,
				async: false,
				success: function(response){
				}
			});
		}
		function funcionMostrarAgrupacionNoFono(cedente,prefijo,rut){
			var rut = rut;
			var data1 = "rut="+rut;
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			var data3 = "rut="+rut+"&cedente="+cedente;
			var data4 = "cedente="+cedente;
			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3)
			funcionMostrarRegistros(data2);
			funcionMostrarCorreo(data1);
			funcionMostrarCorreocc(data1)
			funcionMostrarGestion(data1);
			funcionMostrarGestionTotal(data1);
			funcionMostrarGestionDiaria(data1);
			funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
			limpiarSesion();
		}
		function validarCampos(campos) {
			var valor = 1;
			$.each(campos, function (index, contenido) {
				if (typeof contenido != "undefined") {
					if (contenido == '') {
						$('#' + index + '_').addClass('has-error');
						valor = 0;
					} else {
						$('#' + index + '_').removeClass('has-error');
					}
				}
			});
			return valor;
		}

		function validarCamposDinamicos() {
			var ArrayCampos = [];
			ContError = 0;
			$(".DinamicField").each(function (index) {
				var ObjectMe = $(this);
				if ($(ObjectMe).hasClass('RequiredField')) {

					if (!$(ObjectMe).hasClass("bootstrap-select")) {
						var Value = $(ObjectMe).val();
					} else {
						var Value = $(ObjectMe).find("option:selected").val();
					}
					if (Value != "") {
						$(ObjectMe).closest('.form-group').removeClass('has-error');
					} else {
						ContError++;
						$(ObjectMe).closest('.form-group').addClass('has-error');
					}
				}
				if ($(ObjectMe).is("input") || $(ObjectMe).is("textarea") || $(ObjectMe).is("select")) {
					var Value = $(ObjectMe).val();
					var Codigo = $(ObjectMe).attr("id");
					ArrayCampos.push(
						{
							"Codigo": Codigo,
							"Valor": Value
						}
					);
				}
			});
			if (ContError == 0) {
				return ArrayCampos
			} else {
				return false;
			}
		}

		$(document).on('change', '#seleccione_nivel1', function () {
			$('#tipo_gestion').val('');
			$('.nivel_2_ocultar').hide();
			$('.nivel_2_mostrar').show();
			var nivel2 = $('#seleccione_nivel1').val();
			var nivel2 = "nivel2=" + nivel2;
			$.ajax(
				{
					type: "POST",
					url: "../includes/crm/nivel_2.php",
					data: nivel2,
					async: false,
					success: function (response) {
						$('.nivel_2_mostrar').html(response);
						$(".selectpicker").selectpicker("refresh");

						$('.datetimepicker').datetimepicker({
							format: 'YYYY-MM-DD HH:mm:ss',
							locale: 'es'
						});
					}
				});
		});

		$(document).on('change', '#seleccione_nivel2', function () {
			$('.nivel_3_ocultar').hide();
			$('.nivel_3_mostrar').show();
			var nivel3 = $('#seleccione_nivel2').val();
			var nivel3 = "nivel3=" + nivel3;
			console.log(nivel3);
			$.ajax(
				{
					type: "POST",
					url: "../includes/crm/nivel_3.php",
					data: nivel3,
					async: false,
					success: function (response) {
						$('.nivel_3_mostrar').html(response);
						var tipo_gestion = $('#tipo_gestion').val();
						if (tipo_gestion == 5) {
							$('#seleccione_nivel3').html("<select class='selectpicker' id='seleccione_nivel3' name='seleccione_nivel3'><option value='0'>Seleccione</option><option value='0'>COMPROMISO</option></select>");
						}
						else {
							$('.nivel_3_mostrar').html(response);
						}

						$(".selectpicker").selectpicker("refresh");
						$('.datetimepicker').datetimepicker({
							format: 'YYYY-MM-DD HH:mm:ss',
							locale: 'es'
						});
					}
				});
		});

		$(document).on('change', '#seleccione_nivel3', function () {
			var nombreNivel1 = $("#seleccione_nivel1 option:selected").html();
			var bandera = 1; // discado
			var mensa;
			if ($('#ultimo_fono').val() == 0) {
				$.niftyNoty({
					type: 'danger',
					icon: 'fa fa-close',
					message: "Debe finalizar la Gestión para poder guardar una nueva gestión!",
					container: 'floating',
					timer: 4000
				});
			}

			if (bandera == 0) {
				$.niftyNoty({
					type: 'danger',
					icon: 'fa fa-close',
					message: mensa,
					container: 'floating',
					timer: 4000
				});
				$('#seleccione_nivel3').prop('selectedIndex', 0);
				$('.selectpicker').selectpicker('refresh');
			}else {
				$('#grupo1').show();
				fono_discado = $('#ultimo_fono').val();
				var rut_ultimo = $('#rut_ultimo').val();
				var nivel4 = "nivel_3="+$('#seleccione_nivel3').val()+"&cortar_valor="+cortar_valor+"&rut="+$("input[name='RutNumber']").val();
				$.ajax({
					type: "POST",
					url: "../includes/crm/nivel_4.php",
					data: nivel4,
					async: false,
					success: function (response) {
						$('#grupo1_ocultar').hide();
						$('#grupo1').html(response);
						$('.selectpicker').selectpicker('refresh')
					}
				});

				$(".selectpicker").selectpicker("refresh");
				$('.datetimepicker').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:ss',
					locale: 'es'
				});
			}
		});
		
		$('body').on('click','#guardar',function(){																
			var tiempoLlamada = transcurrido();
			var i = 1;
			while(i<=10){
				$('#call'+i).prop("disabled",false);
				i++;
			}
			$('#next_rut').prop("disabled",false);
			$('#prev_rut').prop("disabled",false);
			var tipo_gestion_final = $('#tipo_gestion_final').val();
			var comentario = $('#comentario').val();
			var camposForm = {
				comentario: comentario,
			};
			if (DatosCedente.compromiso == 1 && tipo_gestion_final == 5) {
				var fecha_compromiso = $('#fecha_compromiso').val();
				var monto_compromiso = $('#monto_compromiso').val();
				camposForm.fecha_compromiso = fecha_compromiso
				camposForm.monto_compromiso = monto_compromiso
			} else {
				var fecha_compromiso = '';
				var monto_compromiso = 0;
			}
			if (DatosCedente.agendamiento == 1 && tipo_gestion_final != 5) {
				var fechaAgendamiento = $('#fecha_agendamiento').val();
				if (DatosCedente.agendamiento_obligatorio == 1) {
					camposForm.fechaAgendamiento = fechaAgendamiento
				}
			} else {
				var fechaAgendamiento = '';
			}

			var retorno = validarCampos(camposForm);
			var ArrayCampos = validarCamposDinamicos();

			if (retorno == 0) {
				bootbox.alert('Debe Completar todos los campos!');
				return 0;
			}

			if (!ArrayCampos) {
				bootbox.alert('Debe Completar todos los campos!');
				return 0;
			}
			if (DatosCedente.facturas == 1) {
				var facturas = $('#facturas').val();
				if ((facturas == 0) || (facturas == "") || (facturas == null)) {
					bootbox.alert('Debe seleccionar minimo una Factura');
					return 0;
				}
			} else {
				facturas = '';
			}

			var cedente = $('#IdCedente').val();

			var nivel1 = $('#seleccione_nivel1').val();
			var nivel2 = $('#seleccione_nivel2').val();
			var nivel3 = $('#seleccione_nivel3').val();
			var asignacion = $('#prefijo').val();
			var rut_ultimo = $('#rut_ultimo').val();
			var numero_cola = $('#numero_cola').val();
			var NombreGrabacion = $('#NombreGrabacion').val();
			var UrlGrabacion = $('#UrlGrabacion').val();
			var origen = 1;
			var Hablar = $("#Hablar").val();
			omnicanalidad();
			var insertar1 = "nivel1=" + nivel1 + "&nivel2=" + nivel2 + "&nivel3=" + nivel3 + "&comentario=" + comentario + "&rut=" + rut_ultimo + "&fono_discado=" + fono_discado + "&tipo_gestion=" + tipo_gestion_final + "&cedente=" + cedente + "&usuario_foco=" + nombre_usuario_foco + "&lista=" + numero_cola + "&fecha_compromiso=" + fecha_compromiso + "&monto_compromiso=" + monto_compromiso + "&tiempoLlamada=" + tiempoLlamada + "&NombreGrabacion=" + NombreGrabacion + "&asignacion=" + asignacion + "&origen=" + origen + "&facturas=" + facturas + "&fechaAgendamiento=" + fechaAgendamiento + "&Hablar=" + Hablar + "&UrlGrabacion=" + UrlGrabacion + "&canales=" + canales + "&prioridades=" + prioridades + "&ArrayCampos=" + JSON.stringify(ArrayCampos);
			eliminarAnexoBridge();
			setTimeout(function(){
				$.ajax({
					type: "POST",
					url: "../includes/crm/insertar1.php",
					data:insertar1,
					async: false,
					success: function(response){
						insertarNivelCola();

						$('#seleccione_nivel1').prop('selectedIndex',0);
						$('#seleccione_nivel2').prop('selectedIndex',0);
						$('#seleccione_nivel3').prop('selectedIndex',0);
						$('#ultimo_fono').val('0');
						$("textarea").val("");
						$('#respuesta').prop('selectedIndex',0);
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : 'Respuesta Integral Guardada' ,
							container : 'floating',
							timer : 2000
						});
						$('#grupo1').hide();
						$('.nivel_2_mostrar').hide();
						$('.nivel_3_mostrar').hide();
						$('.nivel_2_ocultar').show();
						$('.nivel_3_ocultar').show();
						setTimeout(function(){
							CallLlamadaPredictivo = true;
							$("#CanalGrabacion").val("");
							console.log("CallLlamadaPredictivo es TRUE");
							if (rutEstrategia == 2){
								fonosLlamando(tipo_gestion_final);
							}
							unPausePredictivo();
							activarBotonera();
						},1000);
					}
				});
			},1000);
		});
														
		function insertarNivelCola(){
			var nivel1 = "";
			var nivel2 = "";

			if( $('#respuesta').val() != 0){
				var nivel3 	= $('#respuesta').val();
				var rut 	= $('#rut_ultimo').val();
				var cola 	= $('#seleccione_tipo_busqueda').val();
			}else{
				var nivel1 = $('#seleccione_nivel1').val();
				var nivel2 = $('#seleccione_nivel2').val();
				var nivel3 = $('#seleccione_nivel3').val();
				var rut = $('#rut_ultimo').val();
				var cola = $('#seleccione_tipo_busqueda').val();
			}

			var post = "cola="+cola+"&rut="+rut+"&nivel1="+nivel1+"&nivel2="+nivel2+"&nivel3="+nivel3;

			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertarNivelColaPredictivo.php",
				data:post,
				success: function(response)
				{
					console.log(response);
				}
			});
		}
		function funcionLimpiar(){
	  		$('#seleccione_nivel1').prop('selectedIndex',0);
			$('#seleccione_nivel2').prop('selectedIndex',0);
			$('#seleccione_nivel3').prop('selectedIndex',0);
			$("textarea").val("");
			$("#fecha_compromiso").val("");
			$("#monto_compromiso").val("");
			$('#respuesta').prop('selectedIndex',0);
			$('#seleccione_cedente2').prop('selectedIndex',0);
			$('#mostrar_deudas_ocultar').show();
			$('#mostrar_deudas').hide();
			$('#mostrar_fonos_ocultar').show();
			$('#mostrar_fonos').hide();
			$('#mostrar_ultimagestion_ocultar').show();
			$('#mostrar_ultimagestion').hide();
			$('#mostrar_mejorgestion_ocultar').show();
			$('#mostrar_mejorgestion').hide();
			$('#mostrar_direccion_ocultar').show();
			$('#mostrar_direccion').hide();
			$('#mostrar_correo_ocultar').show();
			$('#mostrar_correo').hide();
			$('#mostrar_correo_ocultar_cc').show();
			$('#mostrar_correo_cc').hide();
			$('#rut_buscado').val('');
			$('#busqueda_estrategia').hide();
			$('#busqueda_rut').hide();
			$('#seleccione_tipo_busqueda').show();
			$("#nuevo_telefono").prop("disabled",true);
			$("#nuevo_direccion").prop("disabled",true);
			$("#nuevo_correo").prop("disabled",true);
			$('#script_cobranza_mostrar').hide();
			$('#script_cobranza_ocultar').show();
			$('#botones_modal').hide();
			// $('.nombre_cliente').html('');
			$("#ContainerRutNumber input[name='RutNumber']").val("");
			$("#ContainerRutNumber input[name='nombre_cliente']").val("");
			$('#NombreGrabacion').val("");
			$('#UrlGrabacion').val("");
		}
		function funcionMostrar(){
			$('#mostrar_deudas').show();
			$('#mostrar_fonos').show();
			$('#mostrar_direccion').show();
			$('#mostrar_correo').show();
			$('#mostrar_correo_cc').show();
		
	  	}
	  	function funcionOcultar(){
			$('#mostrar_deudas_ocultar').hide();
			$('#mostrar_fonos_ocultar').hide();
			$('#mostrar_direccion_ocultar').hide();
			$('#mostrar_correo_ocultar').hide();
			$('#mostrar_correo_ocultar_cc').hide();
	  	}
		$(document).on('click', '.adjuntar', function(){
			var clase = '#l'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');
			if ($(clase).is(':checked')){
	        	$('#enviar_factura').prop("disabled",false);
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }else{
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
		});
		$(document).on('click', '.adjuntar_cc', function(){
			var clase = '#l_cc'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');
			if ($(clase).is(':checked')){
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }else{
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
		});
		$(document).on('click', '#enviar_factura', function(){
			var cedente = $('#IdCedente').val();
			var rut = $('#rut_ultimo').val();
			var data = "cedente="+cedente+"&rut="+rut;
			$.ajax({
				type: "POST",
				url: "../includes/crm/enviar_mail.php",
				data:data,
				success: function(response){
					if(response==2){
						var msg = "No has seleccionado un Email de Envio!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}else if(response==3){
						var msg = "No has adjuntado Factura!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else if(response==1){
						var msg = "No se puede enviar Mail , Cedente no tiene Template Cargado en la Base de Datos , Consulte con el Administrador.";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}else{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				}
			});
		});
		$(document).on('click', '.fono_gestion', function(){
			id = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var telefono = '#'+'telefono'+id;
			var valor_telefono = $(telefono).val();
			$('#ultimo_fono').val(valor_telefono);
		});
		$(document).on('click', '.ckhsel', function(){
			var id_deuda = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var rut_factura = $('#rut_ultimo').val();
			var cedente = $('#IdCedente').val();
			if($(clase).is(':checked')){
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=1";
				$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				});
			}else{
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=0";
				$.ajax({
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response){
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				});
			}
		});
		$(document).on('change', '.correo_cambiar', function(){
			id = $(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');
			var data1 = '#'+'correo'+id;
			var data2 = '#'+'nombre'+id;
			var data3 = '#'+'cargo'+id;
			var data4 = '#'+'obs'+id;
			var mail = $(data1).val();
			var nombre = $(data2).val();
			var cargo = $(data3).val();
			var obs = $(data4).val();
			var idmail = "id_mail="+id_mail+"&mail="+mail+"&nombre="+nombre+"&cargo="+cargo+"&obs="+obs;
	    	$.ajax({
				type: "POST",
				url: "../includes/crm/actualizar_mail.php",
				data:idmail,
				success: function(response){
					$.niftyNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : 'Datos Actualizados!',
						container : 'floating',
						timer : 1000
					});
				}
			});
		});
		$(document).on('click', '#nuevo_telefono', function() {
			bootbox.dialog({
				title: "Ingrese Nuevo Telefono",
				message:'<div class="row"> ' +
						'<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-4 control-label" for="name">Nuevo Telefonos</label> ' +
						'<div class="col-md-4"> ' +
						'<input id="fono_discado_nuevo" name="name" type="number" placeholder="" class="form-control input-md solo-numero"> ' +
						' </div> ' +
						'</div> ' + '<div class="form-group"> ' +
						'' +
						'<div class="col-md-8"> <div class="form-block"> ' +
						'' +
						'</div>' +
						'</div> </div>' + '</form> </div> </div><script></script>',
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-primary",
						callback: function() {
							var resValidacion = validarNuevoTelefono();
							if (resValidacion == true){
								return false;
							}
							var fono_discado_nuevo = $('#fono_discado_nuevo').val();
							if(fono_discado_nuevo.length < 9){
								$.niftyNoty({
									type: 'danger',
									icon : 'fa fa-close',
									message : "Registro no Cumple con el Formato ",
									container : 'floating',
									timer : 4000
								});
							}else{
								var rut_fono = $('#rut_ultimo').val();
								var data_fono_nuevo = "rut="+rut_fono+"&fono_discado_nuevo="+fono_discado_nuevo;
								var idCola = $('#seleccione_tipo_busqueda').val();
								$.ajax({
									type: "POST",
									url: "../includes/crm/insertar_fonos.php",
									data:data_fono_nuevo,
									success: function(response){
										var fecha = new Date();
										var fechaCarga = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
										var fila = $('#tablaTelefonos >tbody >tr').length + 1;										
										$('#tablaTelefonos tr:last').after('<tr id="'+fila+'"><td class="text-sm"><center><i class="fa fa-flag fa-lg icon-lg" style="color:#ff0080"></i> </center></td><td class="text-sm">Nuevo Fono</td><td class="text-sm"><input type="hidden" id="telefono'+fila+'" value="'+fono_discado_nuevo+'" name="telefono'+fila+'">'+fono_discado_nuevo+'</td><td class="text-sm">'+fechaCarga+'</td><td><center><input type="checkbox" disabled  class="fono_gestion" name="llamado'+fila+'" value="llamado'+fila+'" id="llamado'+fila+'" ></center></td><td><center></center></td></tr>');
									}
								});
								$.niftyNoty({
									type: 'success',
									icon : 'fa fa-check',
									message : "Registro Guardado",
									container : 'floating',
									timer : 4000
								});
							}
						}
					}
				}
			});
		});
		function retiroDocumentos(){
			var Template = '';
			bootbox.dialog({
				title: "Planilla Retiro Documentos",
				message:'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> ' +
								'<div class="form-group"> ' +
									'<label class="col-md-4 control-label" for="name">Responsable</label> ' +
									'<div class="col-md-6"> ' +
									'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
									'</div> ' +
								'</div> ' +
								'<div class="form-group"> ' +
									'<label class="col-md-4 control-label" for="name">Clienten</label> ' +
									'<div class="col-md-6"> ' +
									'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
									'</div> ' +
								'</div> ' +

								'</form>'+
							'</div>'+
						' </div>',
				buttons:{
					success:{
						label: "Guardar",
						className: "btn-primary",
						callback: function(){
							var direccion_nuevo = $('#direccion_nuevo').val();
							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax({
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response){
									$('#mostrar_direccion').html(response);
									$('#mostrar_direccion_ocultar').hide();
								}
							});
							$.niftyNoty({
								type: 'success',
								icon : 'fa fa-check',
								message : "Registro Guardado",
								container : 'floating',
								timer : 4000
							});
						}
					}
				}
			});
		}
		$(document).on('click', '#nuevo_direccion', function() {
			bootbox.dialog({
				title: "Ingrese Nueva Direccion",
				message:'<div class="row"> ' + '<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-4 control-label" for="name">Nueva Direccion</label> ' +
						'<div class="col-md-4"> ' +
						'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
						' </div> ' +
						'</div> ' + '<div class="form-group"> ' +
						'' +
						'<div class="col-md-8"> <div class="form-block"> ' +
						'' +
						'</div>' +
						'</div> </div>' + '</form> </div> </div><script></script>',
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-primary",
						callback: function() {
							var resValidacion = validarNuevaDireccion();
							if (resValidacion == true){
								return false;
							}
							var direccion_nuevo = $('#direccion_nuevo').val();
							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax({
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response)
								{
									$('#mostrar_direccion').html(response);
									$('#mostrar_direccion_ocultar').hide();
								}
							});
							$.niftyNoty({
								type: 'success',
								icon : 'fa fa-check',
								message : "Registro Guardado",
								container : 'floating',
								timer : 4000
							});
						}
					}
				}
			});
		});
		$(document).on('click', '#nuevo_correo2 ', function() {
			var resp = '';
			var data = 'id=1';
			$.ajax({
				type: "POST",
				url: "../includes/crm/ver_cargo.php",
				data:data,
				success: function(response){
					resp = response;
					var data_modal = resp;
					bootbox.dialog({
						title: "Ingrese Nuevo Correo",
						message:data_modal,
						buttons: {
							success: {
								label: "Guardar",
								className: "btn-primary",
								callback: function() {
									var correo_nuevo = $('#correo_nuevo').val();
									var rut_correo = $('#rut_ultimo').val();
									var cargo = $('#cargo').val();
									var uso = $('#uso').val();
									var data_correo_nuevo = "rut=" + rut_correo + "&correo_nuevo=" + correo_nuevo + "&cargo=" + cargo + "&uso=" + uso + "&Queue=" + Queue;
									$.ajax({
										type: "POST",
										url: "../includes/crm/insertar_correo.php",
										data:data_correo_nuevo,
										success: function(response)
										{
											$('#mostrar_correo').html(response);
											$('#mostrar_correo_ocultar').hide();
										}
									});
									$.niftyNoty({
										type: 'success',
										icon : 'fa fa-check',
										message : "Registro Guardado",
										container : 'floating',
										timer : 4000
									});
								}
							}
						}
					});
				}
			});
		});

		function nuevoEstatus(estatus,pausa){
            $.ajax({
				type: "POST",
				url: "../includes/crm/nuevoEstatus.php",
				data: { estatus: estatus, pausa: pausa },
				//dataType: 'json',
				success: function(response){
                    //alert(response);                    
                }
            });
        }
        function datosAnexo(idCola){
            $.ajax({
				type: "POST",
				url: "../includes/crm/datosCola.php",
				data:{idCola:idCola},
				success: function(response){
                    if (response == 1){
                        $.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : " No existe anexo para el usuario logueado!",
							container : 'floating',
							timer : 4000
						});
                    }
                }
            });
        }
		$(document).on('click', '.entrar', function(){
			var idCola = $('#seleccione_tipo_busqueda').val();
			activarBotonera();
			if (idCola == 0) {
				$.niftyNoty({
					type: 'danger',
					icon: 'fa fa-close',
					message: "Debe Seleccionar la cola!",
					container: 'floating',
					timer: 4000
				});
			} else {
				if (DialProviderConfigured) {
					DiscadorSocket.emit('createAnexo', { Anexo: GlobalData.anexo, idDiscador: idCola })
				}
				rutEstrategia = 1; // para indicar que se encuentra en buscar
				funcionLimpiar();
				var rut_buscado;
				var fono;
				pausaEjec = 1;
				$('#Botonera').show();
				nuevoEstatus('DISPONIBLE', '');
				$("#entrar").val('Salir');
				$("#ContainerRutNumber").show();
				$("#ContainerRutNumber input[name='anexoFoco']").val(GlobalData.anexo);
				$('#contenido').show();
				$('#seleccione_tipo_busqueda').prop("disabled", true);
				$("#entrar").removeClass("entrar btn-primary");
				$("#entrar").addClass("salir btn-danger");
				unPausePredictivo();
				CallLlamadaPredictivo = true;
				$("#CanalGrabacion").val("");
				console.log("CallLlamadaPredictivo es TRUE");
			}
		});
		$(document).on('click', '.salir', function () {
			if (DialProviderConfigured) {
				DiscadorSocket.emit('dropAnexo', { Anexo: GlobalData.anexo })
			}
			pausaEjec = 1;
			nuevoEstatus('','');				
			activarBotonera();
			clearInterval(intervaloHangup);
			$(".alert-wrap").remove();
			ShowNotification = false;
			$('#Botonera').hide();
			$('#contenido').hide();
            $('#seleccione_tipo_busqueda').prop("disabled",false);
            $("#entrar").removeClass("salir btn-danger");
		    $("#entrar").addClass("entrar btn-primary");
            $("#entrar").val('Entrar');
			$("#ContainerRutNumber").hide();
			var idCola = $('#seleccione_tipo_busqueda').val();
            mostrarColaDiscador();
		});
		if (DialProviderConfigured) {
			DiscadorSocket.on('bridgePredictivo', function(Response){
				console.log(Response);
				if (CallLlamadaPredictivo){
					CallLlamadaPredictivo = false;
					console.log("CallLlamadaPredictivo es FALSE");
					console.log(Response);
					llamadaPredictivo(Response.Data.AccountCode, Response.urlGrabacion, Response.nombreGrabacion, Response.Data.DestChannel);
				}
			})
			DiscadorSocket.on('capturaHangup', function(Response){
				if (sonidoHangup){
					ejecutarSonido('../sonidos/hangup.wav');
					nuevoEstatus('MUERTO', '');
					sonidoHangup = false;
				}
			})
		}
		var ShowNotification = false;
		function intervalo_EsperandoLlamada(texto,color,icono){
			intervaloEsperandoLlamada = setInterval(function(){
				if(!ShowNotification){
					$.niftyNoty({
						type: color,
						icon : icono,
						message : "<span id='CallNotification' style='font-weight: bold;font-size: 16px;'>"+texto+"<span>",
						container : 'floating',
						timer : 0,
						closeBtn: false
					});
					ShowNotification = true;
				}else{
					clearInterval(intervaloEsperandoLlamada);
				}
			},0);
		}
		function llamadaPredictivo(data, urlGrabacion, nombreGrabacion, CanalGrabacion){
			$.ajax({
				type: "POST",
				url: "../includes/crm/predictivoRut.php",
				data:{Datos: data+"&"+GlobalData.username},
				//dataType: 'json',
				success: function(response){
					//console.log(response);
					if(isJson(response)){
						response = JSON.parse(response);
						console.log(response);
						//console.log("aca");
						CallLlamadaPredictivo = false;
						console.log("CallLlamadaPredictivo es FALSE");
						sonidoHangup = true;
						$(".alert-wrap").remove();
						ejecutarSonido('../sonidos/answer.wav');
						var rut_buscado = response.uno;
						var fono = response.dos;
						Queue = response.Queue;
						var nombre_cliente = response.nombre_cliente;
						// var NombreGrabacion = response.Nombre_Grabacion;
						// var UrlGrabacion = response.UrlGrabacion;
						//console.log(UrlGrabacion);
						desactivarBotonera('');
						$('#rut_buscado').val(rut_buscado);
						$('#rut_ultimo').val(rut_buscado);
						$('#ultimo_fono').val(fono);
						$("#ContainerRutNumber input[name='RutNumber']").val(rut_buscado);
						$("#ContainerRutNumber input[name='nombre_cliente']").val(nombre_cliente);
						$('#NombreGrabacion').val(nombreGrabacion);
						$('#UrlGrabacion').val(urlGrabacion);
						$("#CanalGrabacion").val(CanalGrabacion);
						mostrarScriptCobranzaCedente();
						funcionMostrarNombreCliente(rut_buscado, Queue);
						funcionMostrarAgrupacion(cedente,'1',rut_buscado,Queue);
						funcionMostrarFono(rut_buscado,fono);
						//nuevoEstatus('INCALL','');
						nuevoEstatus('EN LLAMADA','');
					}
				}
			});	
		}
		function eliminarAnexoBridge(){
			var Anexo = $('#Anexo').val();
			var data = "Anexo="+Anexo;
			$.ajax({
				type: "POST",
				url: "../discador/cortar.php",
				data:data, 
				success: function(response){
					$.niftyNoty({
						type: 'danger',
						icon : 'fa fa-phone',
						message : 'Llamada Cortada por Asterisk' ,
						container : 'floating',
						timer : 1000
					});
				}
			});	  
		}
		$(document).on('change', '#respuesta', function(){
			var tiempoLlamada = transcurrido();
			var fono_discado = $('#ultimo_fono').val();
			var i = 1;
			while(i<=7){
				$('#call'+i).prop("disabled",false);
				i++;
			}
			var cedente = $('#IdCedente').val();
			var resp = $('#respuesta').val();
			var tipo_gestion2 = 3;

			var rut_ultimo = $('#rut_ultimo').val();
			var duracion_llamada2 = $('#duracion_llamada').val();
			var numero_cola = $('#numero_cola').val();
			var NombreGrabacion = $('#NombreGrabacion').val();
			var UrlGrabacion = $('#UrlGrabacion').val();
			var asignacion = $('#prefijo').val();
			var origen = 1;
			var insertar3 = "nivel1=" + resp + "&rut=" + rut_ultimo + "&fono_discado=" + fono_discado + "&tipo_gestion=" + tipo_gestion2 + "&cedente=" + cedente + "&duracion_llamada=" + duracion_llamada2 + "&usuario_foco=" + nombre_usuario_foco + "&lista=" + numero_cola + "&tiempoLlamada=" + tiempoLlamada + "&NombreGrabacion=" + NombreGrabacion + "&asignacion=" + asignacion + "&origen=" + origen + "&UrlGrabacion=" + UrlGrabacion;
			eliminarAnexoBridge();
			setTimeout(function(){
				$.ajax({
					type: "POST",
					url: "../includes/crm/insertar3.php",
					data:insertar3,
					success: function(response){
						insertarNivelCola();

						$('#seleccione_nivel1').prop('selectedIndex',0);
						$('#seleccione_nivel2').prop('selectedIndex',0);
						$('#seleccione_nivel3').prop('selectedIndex',0);
						$("textarea").val("");
						$("#fecha_compromiso").val("");
						$("#monto_compromiso").val("");
						$('#respuesta').prop('selectedIndex',0);
						funcionNivelRapido(cedente);
						$('#respuesta').prop('selectedIndex',0);
						if (rutEstrategia == 2){
							fonosLlamando(tipo_gestion2);
						}
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : 'Respuesta Rapida Guardada' ,
							container : 'floating',
							timer : 2000
						});
						$('#ultimo_fono').val('0');
						$('#grupo1').hide();
						$('.nivel_2_mostrar').hide();
						$('.nivel_3_mostrar').hide();
						$('.nivel_2_ocultar').show();
						$('.nivel_3_ocultar').show();

						setTimeout(function(){
							CallLlamadaPredictivo = true;
							$("#CanalGrabacion").val("");
							console.log("CallLlamadaPredictivo es TRUE");
							unPausePredictivo();
							activarBotonera();
						},1000);
					}
				});
			},1000);
		});
		$(document).on('change', '#seleccione_cedente2', function(){
	    	var IdCedente = $('#seleccione_cedente2').val();
	    	$('#IdCedente').val(IdCedente);
	    	limpiarSesion();
	    });
		$(document).on('change', '#seleccione_estrategia', function(){
			limpiarSesion();			
			var idq = $('#seleccione_estrategia').val();
			var data = "id="+idq;
			rutEstrategia = 2; // para indicar que se encuentra en estrategia
			$.ajax({
				type: "POST",
				url: "../includes/crm/seleccione_cola.php",
				data:data,
				success: function(response)
				{
					$('#grupo').html(response);
					$('.selectpicker').selectpicker('refresh')
				}
			});
		});
		function mostrarScriptCobranzaCedente(){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_script_cobranza.php",
				data:{idCedente: GlobalData.id_cedente},
				success: function(response){
					if (response == "") {
						$('#script_cobranza_ocultar').hide();
						$('#script_cobranza_mostrar').html('Buenos días/Tardes, necesito comunicarme con el encargado de pagos a proveedores');
					} else {
						$('#script_cobranza_ocultar').hide();
						$('#script_cobranza_mostrar').html(response);
					}
				}
			});
		}
		function mostrarScriptCompleto() {
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_script_completo.php",
				data: {
					idCedente: GlobalData.id_cedente
				},
				success: function (response) {
					if (response == "") {
						$('#mostrar_script').hide();
					} else {
						$('#mostrar_script').show();
						$('#script').html(response);
					}
				}
			});
		}
		function mostrarPoliticas() {
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_politicas.php",
				data: {
					idCedente: GlobalData.id_cedente
				},
				dataType: "json",
				success: function (response) {
					if (response == "") {
						$('#mostrar_politicas').hide();
					} else {
						$('#mostrar_politicas').show();
						$('#politica').html(response);
					}
				}
			});
		}
		function mostrarMediosPago() {
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_medios_pago.php",
				data: {
					idCedente: GlobalData.id_cedente
				},
				dataType: "json",
				success: function (response) {
					if (response == "") {
						$('#mostrar_medios_pago').hide();
					} else {
						$('#mostrar_medios_pago').show();
						$('#medio_pago').html(response);
					}
				}
			});
		}
		// function nextRut(){
		// 	limpiarSesion();
		// 	var rut = $('#next_rut').val();
		// 	var prefijo = $('#prefijo').val();
		// 	var data = "rut=" + rut + "&prefijo=" + prefijo + "&tipo=" + 1
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "../includes/crm/next_rut.php",
		// 		data:data, 
		// 		dataType: 'json',
		// 		success: function(response){	
		// 			//alert(response);		
		// 			$('#mostrar_rut').html(response.uno);
		// 			$('#mostrar_rut2').html(response.cinco);
		// 			$('#next_rut').val(response.dos);
		// 			$('#prev_rut').val(response.dos);
		// 			$('#rut_ultimo').val(response.dos);
		// 			$('.nombre_cliente').html(response.tres);
		// 			$('#prefijo').val(response.cuatro);
		// 			var cedente = $('#IdCedente').val();
		// 			$('#cantidadRut').html(response.siete);	
		// 			funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
		// 		},
		// 		error: function(){
		// 			alert('error');
		// 		}
		// 	});	
		// }
	// 	$(document).on('click', '#prev_rut', function(){
	// 		limpiarSesion();
	// 		var rut = $('#prev_rut').val();
	// 		var prefijo = $('#prefijo').val();
	// 		var data = "rut=" + rut + "&prefijo=" + prefijo + "&tipo=" + 2;
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "../includes/crm/prev_rut.php",
	// 			data:data, 
	// 			dataType: 'json',
	// 			success: function(response){	
	// 				$('#mostrar_rut').html(response.uno);
	// 				$('#mostrar_rut2').html(response.cinco);
	// 				$('#next_rut').val(response.dos);
	// 				$('#prev_rut').val(response.dos);
	// 				$('#rut_ultimo').val(response.dos);
	// 				$('.nombre_cliente').html(response.tres);
	// 				$('#prefijo').val(response.cuatro);
	// 				$('#cantidadRut').html(response.siete);
	// 				var cedente = $('#IdCedente').val();	
	// 				funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
	// 			}
	// 		});		
	// 	});		
	// }

	$(document).on('click', '.CortarPredictivo', function(){
		console.log("Cortar Presictivo");
		clearInterval(tiempo_corriendo);
		id = $(this).closest('tr').attr('id');
		var Anexo = $('#Anexo').val();
		var data = "Anexo="+Anexo;
		var i = 1;
		while(i<=10){
			$('#fono'+i).prop("disabled",true);
			i++;
		}
		$.ajax({
			type: "POST",
			url: "../discador/cortar.php",
			data:data, 
			success: function(response){
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-phone',
					message : 'Llamada Cortada' ,
					container : 'floating',
					timer : 2000
				});
				eliminarAnexoBridge();
			}
		});	
	});

	function getDatosCedente()   
    {
    	idCedente = $('#IdCedente').val();

	    $.ajax({
	        type:"POST",
	        data: {idCedente: idCedente},
	        url:"../includes/admin/GetMostrarCedente.php",
	        async: false,
	        success:function(data){  
	            data = JSON.parse(data);
				DatosCedente = data[0];
	        },
            error: function(){             
                alert('error');
            }          
	    });
	 }


    function mostrarColaDiscador(){

    	$('#Cargando').modal({
			backdrop: 'static',
			keyboard: false
		})

		setTimeout(function(){
		    $.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_colaDiscador.php",
				success: function(response){
					$('#colaDiscador').html(response);
					var ce1 = $('#IdCedente').val();
					var ce = ce1;
					funcionNivel1(ce);
					funcionNivelRapido(ce);
					$('#Cargando').modal('hide')
	            }
        	});
        },1000)
    }

	function GetServerStatus(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetServerStatus.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
			},
			async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    console.log(json);
                    if(json.result){
						DialProvider = json.Proveedor;
						if(DialProvider != ""){
							DialProviderConfigured = true;
						}
                    }else{
                        $('#TableProveedores').hide()
                        $('#newProveedor').hide()
                    }
                }                
            }
        });
	}

	function omnicanalidad() {
		if (DatosCedente.omnicanalidad == 1) {

			var channels = [];

			$.each($("input[name='omnicanal[]']:checked"), function () {
				channels.push($(this).val());
			});

			var priorities = [];

			channels.forEach(function (canal) {
				var pri = "prioridad_" + canal;
				var x = $('#' + pri + ' option[disabled]:selected').val();
				priorities.push(x);
			});
			canales = channels.join(",");
			prioridades = priorities.join(",");
			console.log("Canales: " + canales);
			console.log("Prioridades: " + prioridades);
		} else {
			canales = '';
			prioridades = '';
		}
	}

	function omnicanalidad(){
		var channels = [];
		$.each($("input[name='omnicanal[]']:checked"), function(){            
			channels.push($(this).val());
		});

		var priorities = [];

		channels.forEach(function(canal){
			
			var pri = "prioridad_"+canal;
			var x =	$('#'+pri+' option[disabled]:selected').val();
			priorities.push(x);
		});
		canales 	= channels.join(",");
		prioridades = priorities.join(",");
		console.log("Canales: " + canales);
		console.log("Prioridades: " + prioridades);
	}
	$(document).on('click', '#mostrar_script', function () {
		$('#modalScript').modal('show')
	});
	$(document).on('click', '#mostrar_politicas', function () {
		$('#modalPolitica').modal('show')
	});
	$(document).on('click', '#mostrar_medios_pago', function () {
		$('#modalMedioPago').modal('show')
	});
	$(document).on("change","select[name='NumeroTransferencia']",function(){
		var CanalActivo = $("#CanalGrabacion").val();
		var Number = $(this).val();
		if(Number != ""){
			if(CanalActivo != ""){
				if (DialProviderConfigured) {
					DiscadorSocket.emit('transferCall', { Canal: $("#CanalGrabacion").val(), Provider: DialProvider, Number: Number })
				}else{
					bootbox.alert("Error al realizar transferencia de llamada, Proveedor no configurado.")
				}
			}else{
				bootbox.alert("Debe estar en una llamada para poder realizar la transferencia.")
			}
		}else{
			bootbox.alert("Numero a transferir se encuentra vacio, favor consultar con el administrador.");
		}
	});
});

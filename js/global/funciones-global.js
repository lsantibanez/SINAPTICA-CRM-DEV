var GlobalData;
var FocoSocket;
var DiscadorSocket;
var DialProvider;
var DialProviderConfigured = false;
var activeUserID = "";
var FocoSocketURL;
var DiscadorSocketURL;
if ($('.add-popover').length) popover.popover();
$(document).ready(function($){
	// return false;
	//Llamada a Variables Globales
	getGlobalData();

	if (GlobalData.serverNode !== null) {
		if (location.hostname !== "localhost" && location.hostname !== "127.0.0.1"){
			FocoSocketURL = "http://" + GlobalData.serverNode + ":" + GlobalData.portNode;
			DiscadorSocketURL = "http://" + GlobalData.focoConfig.IpServidorDiscadoAux + ":65530";
			GetNotificaciones();
			GetNotificacionesCalidad();
			PredictivoNotification();
			JavaProcessNotification();
			verificarSession();
		} else {
			FocoSocketURL = "http://127.0.0.1:40005";
			DiscadorSocketURL = "http://127.0.0.1:65530";
		}
		FocoSocket = io.connect(FocoSocketURL);
		FocoSocket.on('connect', () => {
			console.log(FocoSocket.id); // 'G5p5...'
			if (GlobalData.focoConfig.IpServidorDiscado){
				GetServerStatus();
			}
			if(DialProviderConfigured){
				DiscadorSocket = io.connect(DiscadorSocketURL);
	
				$("#dropdown_alertas_inbounbd").show();
				getInboundDataExtension();
	
				$(".btnStopInbound").click(function () {
					DiscadorSocket.emit('dropAnexoInbound', { Anexo: GlobalData.anexo })
				});
				$(".btnHangUpInbound").click(function () {
					DiscadorSocket.emit('hangUpInbound', { Channel: $(".btnHangUpInbound").attr("channel") })
				});
				$(".btnPauseInbound").click(function () {
					var ObjectMe = $(this);
					var Pausa = "";
					if(ObjectMe.hasClass("fa-play")){
						Pausa = 0;
					}else{
						Pausa = 1;
					}
					pauseInbound(Pausa);
				});
				$(".btnTransferCallInbound").click(function(){
					DiscadorSocket.emit('transferCallToQueueInbound', { Channel: $(".btnHangUpInbound").attr("channel1"), Queue: "5000" })
				});
				$("body").on("click","button[name='buscarRutInbound']",function(){
					var Rut = $("input[name='rutInbound']").val();
					window.location = "../crm/index?rutInbound="+Rut;
				});
				function insertPersonaInbound(data,Rut,Telefono){
					$.ajax({
						type: "POST",
						url: "../includes/inbound/insertPersonaInbound.php",
						data: data,
						beforeSend: function () {
							$('#Cargando').modal({
								backdrop: 'static',
								keyboard: false
							});
						},
						success: function (data) {
							window.location = "../crm/index?rutInbound=" + Rut + "&telefonoInbound=" + Telefono;
						}
					});
				}
				DiscadorSocket.on('connect', () => {
					LoginSocket();
					/**
					 * NODE DISCADOR SERVER FUNCTIONS
					 */
						DiscadorSocket.on("inboundDataExtension", function (Data) {
							console.log(Data);
							getInboundDataExtension();
						});
						DiscadorSocket.on("bridgeInbound", function (Data) {
							console.log(Data);
	
							getInboundDataExtension();
							showInboundModal(1)
						});
						DiscadorSocket.on("closeInboundModal", function (Data) {
							$("#InboundCallModal").closest(".modal").modal("hide");
						});
					/**
					 * END NODE DISCADOR SERVER FUNCTIONS
					 */
				});
			}else{
				LoginSocket();
			}
			function showInboundModal(isInbound){
				var Template = $("#InboundCallModalTemplate").html();
				bootbox.dialog({
					title: "MODAL DE LLAMADA INBOUND",
					message: Template,
					closeButton: false,
					buttons: {
						confirm: {
							label: "Guardar",
							className: "btn-purple",
							callback: function () {
								var tipoInbound = $("#tipoInbound").val();
								if(tipoInbound == 1){
									var origen = $("#origen").val();
									var nombreIn = $("#nombreIn").val();
									var clienteIn = $("#clienteIn").val();
									var observacionIn = $("#observacionIn").val();
									var tipificacionIn = $("#tipificacionIn").val();
									var data = "origen="+origen+"&nombreIn="+nombreIn+"&clienteIn="+clienteIn+"&observacionIn="+observacionIn+"&tipificacionIn="+tipificacionIn;
									$.ajax({
										type: "POST",
										url: "../includes/crm/insertOperaciones.php",
										data:data,
										success: function(){
											window.location = "../crm/gestiones";
										}
									});
	
								}else{
									var Rut = $("#RutInsertInbound").val();
									var Nombre = $('#NombreInsertInbound').val();
									var Telefono = $('#TelefonoInsertInbound').val();
									var Correo = $('#CorreoInsertInbound').val();
									var Direccion = $('#DireccionInsertInbound').val();
									var Comuna = $('#ComunaInsertInbound').val();
									var Ciudad = $('#CiudadInsertInbound').val();
									var CanAdd = false;
									if (Rut != "") {
										if (Nombre != "") {
											if (Telefono != "") {
												if (Telefono.length == 9) {
													var emailRegex = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
													if (Correo == "" || emailRegex.test(Correo)) {
														CanAdd = true;
													} else {
														bootbox.alert("Correo no valido");
													}
												} else {
													bootbox.alert("Teléfono no Cumple con el Formato (9 Digitos)");
												}
											} else {
												bootbox.alert("Debe ingresar un Telefono");
											}
										} else {
											bootbox.alert("Debe ingresar un Nombre");
										}
									} else {
										bootbox.alert("Debe ingresar un Rut");
									}
									if (CanAdd) {
										data = {
											Rut: Rut,
											Nombre: Nombre,
											Telefono: Telefono,
											Correo: Correo,
											Direccion: Direccion,
											Comuna: Comuna,
											Ciudad: Ciudad
										}
										insertPersonaInbound(data, Rut, Telefono);
									} else {
										return false;
									}
								}
							}
						},
						cancel: {
							label: "Cancelar",
							className: "btn-danger",
							callback: function () {
								if(isInbound){
									DiscadorSocket.emit('difusionCloseInboundModal', { Anexo: GlobalData.anexo });
								}else{
									bootbox.hideAll()
								}
							}
						}
					},
					size: 'medium'
				}).off("shown.bs.modal");
			}
			$(document).on('click', '#storePersonaCrm', function () {
				showInboundModal(0)
			});
	
			
	
			/**
			 * NODE SERVER FUNCTIONS
			 */
				function LoginSocket() {
					console.log(FocoSocket)
					FocoSocket.emit('createLogin', { idUsuario: GlobalData.idUsuario, idCedente: GlobalData.id_cedente, idMandante: GlobalData.id_mandante })
				}
				FocoSocket.on("loginResponse", function (Data) {
					console.log(Data);
					activeUserID = Data.insertID;
					console.log(DiscadorSocket);
					if (DialProviderConfigured) {
						DiscadorSocket.emit('inboundConnection', { activeUserID: activeUserID + "_" + GlobalData.anexo });
					}
				});
				FocoSocket.on("calidadNotifications", function (Data) {
					console.log(Data);
					GetNotificacionesCalidad();
				});
			/**
			 * END NODE SERVER FUNCTIONS
			 */
		});
	} else {
		console.info('Sin socket')
	}
	
	// $("title").text(GlobalData.nombreLogo); 
	$("title").text("CRM Sinaptica"); 
	$("head").append('<link rel="shortcut icon" href="../img/favicon/'+GlobalData.logo+'.ico">');

	$(document).on("change", "#mandanteSeleccionado", function () {
		ID = $(this).val()
		$.ajax({
			type: "POST",
			url: "../includes/admin/GetListarCedentesMandantes.php",
			data: { idMandante: ID },
			dataType: "json",
			async: false,
			beforeSend: function () {
				$('#Cargando').modal({
					backdrop: 'static',
					keyboard: false
				});
			},
			success: function (data) {
				$('#cedenteSeleccionado').empty();
				$.each(data, function (index, array) {
					$('#cedenteSeleccionado').append('<option value="' + array.idCedente + '">' + array.NombreCedente + '</option>');
				});
				$('.selectpicker').selectpicker('refresh')
				$('#Cargando').modal('hide');
			}
		});
	});

	$(document).on('click', '#idSeleccionarCedente', function() {
		$.ajax({
			type: "POST",
			url: "../includes/global/seleccionar_cedente.php",
			data:{
				cedente: GlobalData.id_cedente,
				mandante: GlobalData.id_mandante
			},
			async: false,
			success: function(response)
			{
				bootbox.dialog({
					title: "Seleccione",
					message: response,
					buttons: {
						success: {
							label: "Enviar",
							className: "btn-primary",
							callback: function() {
								if(!$('#cedenteSeleccionado').val()){
									$("#cedenteSeleccionado").focus().after("<span class='error'>Seleccione una opción</span>");
									return false;
								}
								var mandante = $('#mandanteSeleccionado').val();
								var cedente = $('#cedenteSeleccionado').val();
								var data = "mandante=" + mandante+"&cedente="+cedente;
								$.ajax({
									type: "POST",
									url: "../sesion_cedente_cambiar.php",
									data: data,
									async: false,
									success: function() {
										if(DialProviderConfigured) {
											DiscadorSocket.emit('dropAnexoInbound', { Anexo: GlobalData.anexo })
										}
										location.reload();
									}
								});
							}
						}
					}
				});
				setTimeout(function () {
					$(".selectpicker").selectpicker("refresh");
				}, 100);
			}
		});
	});

	function PredictivoNotification(){
		setInterval(function(){
			$.ajax({
				type: "POST",
				url: "../includes/tareas/FindFinishedQueues.php",
				async: false,
				success: function(response)
				{
					if(response != ""){
						var json = JSON.parse(response);
						$.each(json,function(index, value){
							Push.create('Discador Predictivo',{
								icon: "../img/I-Manager.png",
								body: "La cola "+value.Queue+" asignada al grupo: "+value.Cola+" ha culminado. Click aqui para ir a configuracion de colas.",
								onClick: function () {
									window.open('../tareas/configuracionPredictivo.php','_blank');
									this.close();
								}
							});
						});
					}
				}
			});
		},60000);
	}

	function JavaProcessNotification(){
		setInterval(function(){
			$.ajax({
				type: "POST",
				url: "../carga/ajax/FindFinishedJavaProcess.php",
				async: false,
				success: function(response){
					if(response != ""){
						var result = JSON.parse(response);
						//$.each(json,function(index, value){
						$.each(result, function(){
							var proc = this.process;
							switch(proc) {
								case "1":
									Push.create('Carga Automatica',{
										icon: "../img/I-Manager.png",
										body: "El archivo: "+this.filename+" fue procesado satisfactoriamente.\nCarga realizada por: "+this.usuario
									});
								break;
								case "2":
									Push.create('Envío SMS',{
										icon: "../img/I-Manager.png",
										body: "El Envío de SMS realizado por: "+this.usuario+" \nquedó con el estado: "+this.comment
									});
								break;
								case "3":
									Push.create('Envío Email', {
										icon: "../img/I-Manager.png",
										body: "El Envío de Email realizado por: " + this.usuario + " \nquedó con el estado: " + this.comment
									});
								break;
							}
						});
					}
				}
			});
		},60000);
	}

	// BOOTSTRAP DATEPICKER
	// =================================================================
	// Require Bootstrap Datepicker
	// http://eternicode.github.io/bootstrap-datepicker/
	// =================================================================
	//$('#demo-dp-txtinput input').datepicker();-----------------------------------------------------------------------

	//$('#demo-dp-txtinput input').datepicker({format: "dd-mm-yyyy"});



	// BOOTSTRAP DATEPICKER WITH AUTO CLOSE
	// =================================================================
	// Require Bootstrap Datepicker
	// http://eternicode.github.io/bootstrap-datepicker/
	// =================================================================

	//$('#demo-dp-component .input-group.date').datepicker({autoclose:true});-------------------------------------------

	if(($('#demo-dp-component .input-group.date').size() > 0) || ($('.input-daterange').size() > 0)){
		$.fn.datepicker.dates['es'] = {
			days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
			daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			today: "Hoy"
		};

		$('#demo-dp-component .input-group.date').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});

		$('.input-daterange').datepicker({
				format: "yyyy/mm/dd",
				weekStart: 1,
				todayBtn: "linked",
				autoclose: true,
				todayHighlight: true,
				language: 'es'
		});
	}

	$("body").on("keyup",".SoloNumeros",function (){
		this.value = (this.value + '').replace(/[^0-9]/g, '');
	});

	if(typeof $.fn.dataTable != 'undefined'){
		$.extend( true, $.fn.dataTable.defaults, {
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "Palabra no encontrada.",
				"info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
				"infoEmpty": "No se encontraron registros",
				"search": "Buscar:",
				"paginate": {
					"first":      "Primera",
					"last":       "Última",
					"next":       "Siguiente",
					"previous":   "Anterior"
				},
				"infoFiltered": "(encontrados de _MAX_ records)"
			}
		});
	}

	//Formaro

	// PARA FORMATO DE NUMEROS CON SEPARADORES DECIMALES
	// =================================================================
	//$('#number_container').slideDown('fast');

	// $('#price').on('change',function(){
	// 	console.log('Change event.');
	// 	var val = $('#price').val();
	// 	$('#the_number').text( val !== '' ? val : '(empty)' );
	// });

	// $('#price').change(function(){
	// 	console.log('Second change event...');
	// });

	// //$('#price').number( true, 2 );
	// $('#price').number( true, 2,',','.' );
	// // Get the value of the number for the demo.
	// $('#get_number').on('click',function(){

	// 	var val = $('#price').val();

	// 	$('#the_number').text( val !== '' ? val : '(empty)' );

	//Funcion de Variables Globales
	function getGlobalData() {
		var ToReturn = false; 
		$.ajax({
			type: "POST",
			url: "../includes/global/GetGlobalData.php",
			dataType: "html",
			async: false,
			success: function(data){
				GlobalData = JSON.parse(data);
				ToReturn = true;
				if (typeof GlobalData.id_cedente == "undefined"){
					$("#idSeleccionarCedente").remove();
				}
			},
			error: function(data){
				console.log(data);
			}
		});
		return ToReturn;
	}
	
	var prioridad_alta = 0;
	var prioridad_media = 0;
	var prioridad_baja = 0;
	var recorrido = 0;

	function GetNotificaciones() {
		$.ajax({
			type: "POST",
			url: "../includes/global/GetNotificaciones.php",
			dataType: "json",
			async: false,
			success: function (data) {
				$('#ul_prioridad_baja').empty()
				$('#ul_prioridad_media').empty()
				$('#ul_prioridad_alta').empty()
				var nuevas_prioridad_baja = 0;
				var nuevas_prioridad_media = 0;
				var nuevas_prioridad_alta = 0;
				Notificaciones = data.Notificaciones
				$.each(Notificaciones, function (index, Notificacion){
					List = ''
					List += '<li>'
					List += '<a class="media Notificacion" href="#">'
					List += '<div class="media-left">'
					List += '<i class="demo-pli-add-user-plus-star icon-2x"></i>'
					List +='</div>'
					List +='<div class="media-body">'
					List +='<div class="text-nowrap">'+Notificacion.Titulo+'</div>'
					List += '<small class="text-muted">Rut: <span class="Rut">' + Notificacion.Rut + '</span> - ' + Notificacion.Fecha +'</small>'
					List += '</div>'
					List += '</a>'
					List += '</li>'
					if (Notificacion.Tipo_Notificacion == 3){
						$('#ul_prioridad_baja').append(List)
						nuevas_prioridad_baja++
					} else if (Notificacion.Tipo_Notificacion == 2) {
						$('#ul_prioridad_media').append(List)
						nuevas_prioridad_media++
					}else{
						$('#ul_prioridad_alta').append(List)
						nuevas_prioridad_alta++
					}
				})

				$('#p_prioridad_baja').text('Tienes ' + nuevas_prioridad_baja+' Notificaciones.');
				$('#span_prioridad_baja').text(nuevas_prioridad_baja)
				$('#p_prioridad_media').text('Tienes ' + nuevas_prioridad_media + ' Notificaciones.');
				$('#span_prioridad_media').text(nuevas_prioridad_media)
				$('#p_prioridad_alta').text('Tienes ' + nuevas_prioridad_alta + ' Notificaciones.');
				$('#span_prioridad_alta').text(nuevas_prioridad_alta)

				if (GlobalData.focoConfig.sonidoNotificaciones == 1 && recorrido > 1) {
					Sonido = false
					if (nuevas_prioridad_baja > prioridad_baja){
						Sonido = true;
					} else if (nuevas_prioridad_media > prioridad_media) {
						Sonido = true;
					} else if (nuevas_prioridad_alta > prioridad_alta) {
						Sonido = true;
					}

					if(Sonido){
						ejecutarSonido('../sonidos/notification.wav');
					}
				}

				prioridad_baja = nuevas_prioridad_baja
				prioridad_media = nuevas_prioridad_media
				prioridad_alta = nuevas_prioridad_alta
				recorrido++;
				
				if (typeof GetNotificacionesInterval === 'undefined') {
					GetNotificacionesInterval = setInterval(function () {
						GetNotificaciones();
					}, 30000);
				}
			}
		});
	}

	$(document).on('click', '.Notificacion', function(e){
		e.preventDefault();
		Rut = $(this).find('.Rut').text();
		$.ajax({
			type: "POST",
			url: "../includes/bienvenida/accesoDirectoRut.php",
			data: {
				Rut: Rut
			},
			async: false,
			success: function (data) {
				window.location = '../crm/index';
			}
		});
	})

	function ejecutarSonido(rutaSonido) {
		var sonido = new Audio();
		sonido.addEventListener('play', function () { }, false);
		sonido.addEventListener('ended', function () { }, false);
		sonido.src = rutaSonido;
		sonido.play();
	}

	$(document).on('hidden.bs.modal', '.modal', function () {
		$('.modal:visible').length && $(document.body).addClass('modal-open');
	});
	
	//verificaSeleccionCedente();
	/*
	** Funcion que verifica si en el modulo donde estoy parada necesita seleccionar cedente
	** esto solo para el rol administrador
	*/
	function verificaSeleccionCedente(){
		// necesito variable del menu para luego ver si el necesita cedente esto ultimo lo busco en bd
		var data = "idMenu="+GlobalData.idMenu;
		$.ajax({
			type: "POST",
			url: "../includes/global/GetAdminCedente.php",
			dataType: "html",
			async: false,
			data:data,
			success: function(data){
				if (data == 1){
					seleccionMandanteCedente();
				}
			}
		});
	}

	function seleccionMandanteCedente(){
		 if (typeof nombreVariable == "undefined")
		 bootbox.dialog({
			title: "Seleccionar Mandante y Cedente",
			message: templeteMandanteCedente,
			buttons: {
				success: {
					label: "Guardar",
					className: "btn-primary",
					callback: function() {
						/*var idTabla = $('#tablaBD').val();
						var idCampos = $('#camposTabla').val();
						var nombreTabla = $("#tablaBD option:selected").html();
						if ((idTabla == 0) || (idTabla == ""))
						{
							CustomAlert("Debe seleccionar una tabla");
							return false;
						}
						if ((idCampos == 0) || (idCampos == "") || (idCampos == null))
						{
							CustomAlert("Debe seleccionar minimo un campo");
							return false;
						}
						addTabla(idTabla,idCampos,GlobalData.id_cedente,nombreTabla);*/
						var idCedenteAdmin = $('#cedenteAdmin').val();
						var idMandanteAdmin = $('#mandanteAdmin').val();
						registraSessionCedente(idCedenteAdmin, idMandanteAdmin);
						location.reload();

					}
				}
			}
		}).off("shown.bs.modal");
		$(".selectpicker").selectpicker();
		mandantes();
		//FiltrarTablas(GlobalData.id_cedente);
		//resetearCombo();
		//AddClassModalOpen();
	}
 
	function registraSessionCedente(idCedenteAdmin, idMandanteAdmin){
		$.ajax({
			type: "POST",
			url: "../includes/global/cedenteSessionAdmin.php",
			dataType: "html",
			data: { idCedenteMandante: idCedenteAdmin, idMandanteAdmin: idMandanteAdmin },
			async: false,
			success: function(data){

			}
		});
	}

	function mandantes(){
		$.ajax({
			type: "POST",
			url: "../includes/global/GetMandante.php",
			async: false,
			success: function(data){
				$("select[name='mandanteAdmin']").html(data);
				$("select[name='mandanteAdmin']").selectpicker('refresh');
			}
		});
	}

	function cedentesMandante(idMandante){
		$.ajax({
			type: "POST",
			url: "../includes/global/GetCedentesMandante.php",
			//dataType: "html",
			data: {mandante: idMandante},
			async: false,
			success: function(data){
				$("select[name='cedenteAdmin']").html(data);
				$("select[name='cedenteAdmin']").selectpicker('refresh');
			}
		});
	}

	$("body").on("change","#mandanteAdmin",function(){
		var idMandante = $('#mandanteAdmin').val();
		if ((idMandante != 0) || (idMandante != ""))
		{
			cedentesMandante(idMandante);
		}else {
			//resetearCombos();
		}
	});

	$('body').on( 'click', '#cambiaMandante', function () {
		seleccionMandanteCedente();
	});

	var templeteMandanteCedente = ''+
	'<div class="row">'+
		'<div class="col-md-12">'+
			'<form class="form-horizontal">'+
				'<div class="row">'+
					'<div class="col-md-12">'+
						'<div class="form-group">'+
							'<div class="col-md-3">'+
								'<label>Mandante</label>'+
							'</div> '+
							'<div class="col-md-8">'+
								'<select class="selectpicker" title="Seleccione" id="mandanteAdmin" name="mandanteAdmin" data-live-search="true" data-width="100%"></select>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
				'<div class="row">'+
					'<div class="col-md-12">'+
						'<div class="form-group">'+
							'<div class="col-md-3">'+
								'<label>Cedente</label>'+
							'</div>'+
							'<div class="col-md-8">'+
								'<select class="selectpicker" title="Seleccione" id="cedenteAdmin" name="cedenteAdmin" data-live-search="true" data-width="100%"></select>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>    '+
			'</form>'+
		'</div>'+
	'</div>';
	
	$('body').on('click', '.toggle-switch-label', function () {
		toggle_switch = $(this).siblings('.toggle-switch');
		$(toggle_switch).click();
    });
	
	/*
    $.ajax({
        type: "POST",
        url: "../includes/global/RutSearcher/RutSearcherTemplate.php",
        async: false,
        success: function(response){
            $("body").append(response);
            var RutSearcherBody = $('#RutSearcher');
            var RutSearcherIcon = $('#RutSearcher-icon');
            var RutSearcherBtnGo = $('#RutSearcher-btngo');
            $('body').on('click','#RutSearcher-btn',function(e){
                e.stopPropagation();
                RutSearcherBody.toggleClass('open');
            });
            $('body').on('click','#RutSearcherFind',function (){
                $.ajax({
                    type: "POST",
                    url: "../includes/global/RutSearcher/FindRutData.php",
                    data:{
                        Rut: $("#RutSearcherRutText").val()
                    },
                    async: false,
					success: function(response){//console.log(response);
						if(isJson(response)){
							var json = JSON.parse(response);
							$.each(json,function(i,val){
								var Tabla = i;
								// console.log(val);
								var Campos = val.Campos;
								var Data = val.Data;
								$("#TableTabD"+Tabla+" thead tr th").remove();
								Campos.forEach(function(element, index, array){
									// console.log(element);
									$("#TableTabD"+Tabla+" thead tr").append("<th>"+element.data+"</th>");
								});
								if(Campos.length > 0){
									$("#TableTabD"+Tabla).DataTable({
										data: Data,
										columns: Campos,
										"bDestroy": true
									});
								}
							});
						}
                    }
                });
            });
        }
	});*/

	function GetNotificacionesCalidad(){
		$.ajax({
			type: "POST",
			url: "../includes/global/GetNotificacionesCalidad.php",
			async: false,
			success: function (data) {
				if(isJson(data)){
					$('#ul_alerta_calidad').empty();
					var Cant = 0;
					Notificaciones = JSON.parse(data);
					var ContVistos = 0;
					$.each(Notificaciones, function (index, Notificacion) {
						var Color = "";
						if(Notificacion.visto == "0"){
							Color = "#FFFFAA";
							ContVistos++;
						}
						List = ''
						List += '<li id="' + Notificacion.idObjecion + '_' + Notificacion.idEvaluacion +'" style="background-color: ' + Color + '; position: relative;">'
							List += '<a class="media" href="#">'
								List += '<div class="media-left">'
									List += '<i class="demo-pli-add-user-plus-star icon-2x"></i>'
								List += '</div>'
								List += '<div class="media-body NotificacionCalidad">'
									List += '<div class="text-nowrap" style="font-weight: bold;">' + Notificacion.Tipo + '</div>'
									List += '<div class="text-nowrap">' + Notificacion.Usuario + '</div>'
								List += '</div>'
								List += '<i class="fa fa-times skipObjecionNotificacion" style="position: absolute;top: 10px;right: 10px;font-size: 20px;"></i>'
							List += '</a>'
						List += '</li>';
						$('#ul_alerta_calidad').append(List);
						Cant++;
					});

					$('#p_alerta_calidad').text('Tienes ' + Cant + ' Notificaciones.');
					$('#span_alerta_calidad').text(Cant);

					if (ContVistos > 0) {
						ejecutarSonido('../sonidos/notification.wav');
					}
				}
			}
		});
	}
	$(document).on('click', '.NotificacionCalidad', function (e) {
		e.preventDefault();
		var ObjectMe = $(this);
		var ObjectLI = ObjectMe.closest("li");
		var ID = ObjectLI.attr("id");
		var IDArray = ID.split("_");
		var idObjecion = IDArray[0];
		var idEvaluacion = IDArray[1];
		console.log(ID);
		$.ajax({
			type: "POST",
			url: "../includes/calidad/verObjecion_Notificacion.php",
			data: {
				idObjecion: idObjecion
			},
			async: false,
			success: function (data) {
				if(isJson(data)){
					window.location = '../calidad/calidad?e='+idEvaluacion;
				}
			}
		});
	});
	$(document).on('click', '.skipObjecionNotificacion', function (e) {
		e.preventDefault();
		var ObjectMe = $(this);
		var ObjectLI = ObjectMe.closest("li");
		var ID = ObjectLI.attr("id");
		var IDArray = ID.split("_");
		var idObjecion = IDArray[0];
		$.ajax({
			type: "POST",
			url: "../includes/calidad/deleteObjecion_Notificacion.php",
			data: {
				idObjecion: idObjecion
			},
			async: false,
			success: function (data) {
				if(isJson(data)){
					data = JSON.parse(data);
					if(data.result){
						GetNotificacionesCalidad();
					}
				}
			}
		});
	});

	if (DialProviderConfigured) {
		
	}
});


var TimeOutCloseSession;
/*var ValidSession = function(){
	var moviendo= false;
	document.onmousemove = function(){
	moviendo= true;
	};
	setInterval (function() {
		if (!moviendo) {
			alertCloseSession();
			TimeCloseSession();
		} else {
			moviendo=false;
			closeModal();
		}
	}, 30000);
}*/

function verificarSession(){
	setInterval (function() {
		$.post('../class/verificarSession.php', function(data){
			if (data =='true'){
					var modal= '<div style="position: absolute !important; z-index:99999999" class="modal fade" tabindex="-1" role="dialog" id="alertSession2">';
					modal+= '<div class="modal-dialog" role="document">';
					modal+= '<div class="modal-content">';
					modal+= '<div class="modal-header">';
					modal+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					modal+= '<h4 class="modal-title"><b>Alerta de session </b></h4>';
					modal+= '</div>';
					modal+= '<div class="modal-body">';
					modal+= '<div class="row">';
					modal+= '<div class="col-md-offset-2 col-md-8">';
					modal+= '<h3 class="text-center">La sesion se cerrara en 10 segundos</h3>';
					modal+= '</div>';
					modal+= '</div>';
					modal+= '</div>';
					modal+= '</div><!-- /.modal-content -->';
					modal+= '</div><!-- /.modal-dialog -->';
					modal+= '</div>';
					$('body').append(modal)
					$('#alertSession2').modal({
						backdrop: 'static',
						keyboard: false
					});
					TimeCloseSession();
			}
		});
	}, 30000);
}

var alertCloseSession = function(){
	var modal= '<div class="modal fade" tabindex="-1" role="dialog" id="alertSession">';
	modal+= '<div class="modal-dialog" role="document">';
	modal+= '<div class="modal-content">';
	modal+= '<div class="modal-header">';
	modal+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	modal+= '<h4 class="modal-title"><b>Alerta no se detecto movimiento</b></h4>';
	modal+= '</div>';
	modal+= '<div class="modal-body">';
	modal+= '<div class="row">';
	modal+= '<div class="col-md-offset-2 col-md-8">';
	modal+= '<h2 class="text-center">La sesion se cerrara en 10 segundos</h2>';
	modal+= '</div>';
	modal+= '</div>';
	modal+= '</div>';
	modal+= '<div class="modal-footer">';
	modal+= '<button type="button" class="btn btn-primary noCloseSession" data-dismiss="modal">No</button>';
	modal+= '</div>';
	modal+= '</div><!-- /.modal-content -->';
	modal+= '</div><!-- /.modal-dialog -->';
	modal+= '</div>';
	$('body').append(modal)
	$('#alertSession').modal({
		backdrop: 'static',
		keyboard: false
	});
}

var closeModal = function(){
	$('#alertSession').modal('hide');
	$('#alertSession').on('hidden.bs.modal', function (e) {
		$('#alertSession').remove();
	})
}

var TimeCloseSession = function(){
	TimeOutCloseSession = setTimeout(function() {
		$.post('../class/closeSession.php',function(){
			window.location = "../index.php";
		});
	}, 10000);
}

$(document).on('click', '.noCloseSession', function(){
	clearTimeout(TimeOutCloseSession);
});

function formatDollar(num) {
	var p = num.toFixed(2).split(".");
	return "$" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
			return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
	}, "") + "." + p[1];
}
//ValidSession();
function isJson(Value){
	var ToReturn = true;
	try{
		var json = $.parseJSON(Value);
	}
	catch(err){
		ToReturn = false;
	}
	return ToReturn;
}
function GetServerStatus() {
	$.ajax({
		type: "POST",
		url: "../includes/admin/GetServerStatus.php",
		data: {
			codigoFoco: GlobalData.focoConfig.CodigoFoco,
		},
		async: false,
		success: function (data) {
			if (isJson(data)) {
				var json = JSON.parse(data);
				console.log(json);
				if (json.result) {
					DialProvider = json.Proveedor;
					if (DialProvider != "") {
						DialProviderConfigured = true;
					}
				} else {
					$('#TableProveedores').hide()
					$('#newProveedor').hide()
				}
			}
		}
	});
}
function getInboundDataExtension(){
	$.ajax({
		type: "POST",
		url: "../includes/inbound/getInboundDataExtension.php",
		data: {
			Anexo: GlobalData.anexo,
		},
		async: false,
		success: function (data){
			if(isJson(data)){
				var json = JSON.parse(data);
				$("#span_alerta_inbounbd").removeClass("badge-success");
				$("#span_alerta_inbounbd").removeClass("badge-danger");
				$("#span_alerta_inbounbd").removeClass("badge-warning");

				$(".btnPauseInbound").removeClass("btn-danger");
				$(".btnPauseInbound").removeClass("btn-success");

				$(".btnPauseInbound").removeClass("fa-play");
				$(".btnPauseInbound").removeClass("fa-pause");

				$("#p_alerta_inbounbd").html(json.Data.Cola);

				$(".btnHangUpInbound").hide();
				$(".btnTransferCallInbound").hide();
				$(".btnStopInbound").hide();
				if(json.result){
					if(json.Data.isTalking){
						$("#span_alerta_inbounbd").removeClass("badge-primary");
						//$(".btnTransferCallInbound").show();
						$(".btnHangUpInbound").show();
						$(".btnHangUpInbound").attr("channel", json.Data.Channel);
						$(".btnHangUpInbound").attr("channel2", json.Data.DestChannel);
					}else{
						if (json.Data.Pausa == "1") {
							$("#span_alerta_inbounbd").addClass("badge-warning");
							$(".btnPauseInbound").addClass("fa-play");
							$(".btnPauseInbound").addClass("btn-success");
							$(".btnPauseInbound").show();
						} else {
							$("#span_alerta_inbounbd").addClass("badge-success");
							$(".btnPauseInbound").addClass("fa-pause");
							$(".btnPauseInbound").addClass("btn-danger");
							$(".btnPauseInbound").show();
						}
						$(".btnStopInbound").show();
					}
				}else{
					$("#span_alerta_inbounbd").addClass("badge-danger");
					$(".btnPauseInbound").hide();
					$(".btnStopInbound").hide();
				}
			}
		}
	});
}
function pauseInbound(PausaParam){
	$.ajax({
		type: "POST",
		url: "../includes/inbound/pauseInbound.php",
		data: {
			Anexo: GlobalData.anexo,
			Pausa: PausaParam
		},
		async: false,
		success: function (data){
			console.log(data);
			if(isJson(data)){
				var json = JSON.parse(data);
				if(json.result){
					var Pausa = false;
					if(PausaParam == "1"){
						Pausa = true;
					}else{
						Pausa = false;
					}
					DiscadorSocket.emit('pauseInbound', { Anexo: GlobalData.anexo, Pausa: Pausa})
				}
			}
		}
	});
}
$(document).ready(function(){	
	var id_dial = $('#id_dial').val();
	var rut_dial = $('#rut_ultimo').val();
	var cedente_dial = $('#IdCedente').val();
	var cedente= $('#IdCedente').val();
	var fono_dial = $('#fono_dial').val();
	var usuario_dial = $('#usuario_dial').val();
	var dateInicioLlamada;
	var idFilaFono;
	var rutEstrategia;
	var ordenRut;

	var colaAD = $("#cola").val();
	var estrategiaAD = $("#est").val();
	var asignacionAD = $("#asig").val();
	var AccesoDirectoRut = $("#AccesoDirectoRut").val();

	var canales;
	var prioridades;

	var GestionDiariaTable;
	var GestionTotalTable;
	var GestionContactoTable;
	var GestionCorreoTable;
	var GestionPagosTable;
	var GestionFacturasTable;
	var GestionSMSTable;
	var GestionIVRTable;
	var GestionesExternasTable;
    $('#busqueda_rut4').hide();

	var DialProvider;
	var DialProviderConfigured = false;
	var typeMessage = "danger"
    var message = "Hubo un problema con el proveedor de discado, consulte con el administrador"
    getGestiones();
    
    function getGestiones(){
        $.ajax({
            type: "POST",
            url: "../includes/crm/getGestion.php",
            data:"",
            success: function(response){
                $('#tabla-mis-gestiones').html(response)
                $('#tabla_mis_gestiones').DataTable({});
            } 
        });
    }

    

	if (GlobalData.planDiscado == 1){
		$('#reloj').hide();
	}else{
		$('#reloj').show();
	}
	
	if(colaAD !== ""){
		accesoDirectoCola();
	}

	if(AccesoDirectoRut !== ""){
		$("#rut_buscado").val(AccesoDirectoRut);
		$(window).on('load', function() {
			$("#buscar_rut").trigger("click");
			$("#seleccione_tipo_busqueda").val("2").trigger("change");
		});
	}

	var Parametros = window.location.search.substr(1);
	if (Parametros != "") {
		var ParametrosArray = Parametros.split("&");
		for (var i = 0; i < ParametrosArray.length; i++) {
			var Parametro = ParametrosArray[i];
			var ParametroArray = Parametro.split("=");
			switch (ParametroArray[0]) {
				case "rutInbound":
					$("#rut_buscado").val(ParametroArray[1]);
					$(window).on('load', function () {
						$("#buscar_rut").trigger("click");
						$("#seleccione_tipo_busqueda").val("2").trigger("change");
					});
					break;
				case "telefonoInbound":
					$("#ultimo_fono").val(ParametroArray[1]);
					break;
			}
		}
	}

	var tiempo = {
        hora: 0,
        minuto: 0,
        segundo: 0
    };

	var tiempo_corriendo = null;
	var id;

	var myDropzone;
	Dropzone.autoDiscover = false;

	$("#file-up").dropzone({
		url: 'a',
		maxFiles: 1000,
		uploadMultiple: true,
		parallelUploads: 1000,
		maxFilesize: 2048,
		autoProcessQueue: false,
		init: function () {
			myDropzone = this;
		},

		error: function (file, response) {
			console.log(response);
			if (response == "You can't upload files of this type.") {
				$('#alertFile').modal({
					backdrop: 'static',
					keyboard: false
				});
			}
			myDropzone.removeAllFiles();
		},

		processing: function () {
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
		}
	});

	var DatosCedente

	getDatosCedente();
	getTabs();
	GetServerStatus();
	mostrarScriptCobranzaCedente();
	mostrarScriptCompleto();
	mostrarPoliticas();
	mostrarMediosPago();

	function mostrarScriptCobranzaCedente() {
		$.ajax({
			type: "POST",
			url: "../includes/crm/mostrar_script_cobranza.php",
			data: {
				idCedente: GlobalData.id_cedente
			},
			success: function (response) {
				if (response == "") {
					$('#script_cobranza_ocultar').hide();
					$('#script_cobranza_mostrar').html('Buenos días/Tardes, necesito comunicarme con el encargado de pagos a proveedores');
				} else {
					$('#script_cobranza_ocultar').hide();
					$('#script_cobranza_mostrar').html(response);
				}
				$('#botones_modal').show();
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

	function getDatosCedente() {
		idCedente = $('#IdCedente').val();

		$.ajax({
			type: "POST",
			data: { idCedente: idCedente },
			url: "../includes/admin/GetMostrarCedente.php",
			async: false,
			success: function (data) {
				data = JSON.parse(data);
				DatosCedente = data[0]

			},
			error: function () {
				alert('error');
			}
		});
	}

	$.ajax({
		type: "POST",
		url: "../includes/crm/getTemplates.php",
		success: function (response) {

			response = JSON.parse(response);

			$.each(response, function (index, array) {
				$('#idTemplate').append('<option value="' + array.Id + '">' + array.Nombre + '</option>');
			});

			$('#idTemplate').append('<option value="s">Sin Template</option>');
			$('#idTemplate').selectpicker('refresh');
		}
	});

	$.ajax({
		type: "POST",
		url: "../includes/crm/getTemplatesSMS.php",
		success: function (response) {

			response = JSON.parse(response);

			$.each(response, function (index, array) {
				$('#idTemplateSMS').append('<option value="' + array.id + '">' + array.Nombre + '</option>');
			});

			$('#idTemplateSMS').append('<option value="s">Sin Template</option>');
			$('#idTemplateSMS').selectpicker('refresh');
		}
	});

	function tiempollamadaInicio(){
		dateInicioLlamada = new Date();
	}

	function transcurrido(){
		var dateFinLlamada = new Date();
	    //La diferencia se da en milisegundos así que se debe dividir entre 1000
	    var diferencia = (dateFinLlamada-dateInicioLlamada);
		segLlamadaTranscurrido = Math.floor(diferencia / 1000);
		return segLlamadaTranscurrido;
	}

	function limpiarSesion()
	{
		$.ajax(
	    {
			type: "POST",
			url: "../includes/crm/limpiar_sesion.php",
			data: 'a=1',
			success: function()
			{
				console.log('Limpliar sesion');
			}
		});
	}
	if(id_dial==1)
	{
		var data_fono = 'rut='+rut_dial;
		var data_deudas = 'rut='+rut_dial+"&cedente="+cedente_dial;

	}
	else
	{

		var nombre_usuario_foco = $('#nombre_usuario_foco').val();

		// Para que el campo acepte solo numeros
		$(document).on('keyup','.solo-numero',function (){
			console.log("Si paso ");
            this.value = (this.value + '').replace(/[^0-9]/g, '');
        });

		function validarNuevoCorreo()
		{
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
	     	}console.log("paso aqui: " + $("#uso").val());
	     	if(  $("#uso").val() == null || $("#uso").val() == "0" ){
	        	$("#uso").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};
		function validarNuevoCorreocc()
		{
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
	     	}console.log("paso aqui: " + $("#uso_cc").val());
	     	if(  $("#uso_cc").val() == null || $("#uso_cc").val() == "0" ){
	        	$("#uso_cc").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};

		function validarNuevaDireccion()
		{
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#direccion_nuevo").val().trim() == "" ){
				$("#direccion_nuevo").focus().after("<span class='errorL'>Ingrese una dirección</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};

		function validarNuevoTelefono()
		{
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#fono_discado_nuevo").val().trim() == "" ){
				$("#fono_discado_nuevo").focus().after("<span class='errorL'>Ingrese un telefono</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};


		var user_dial = $('#usuario_usuario_foco').val();
		$(document).on('click', '#AddCorreoN', function() {
			console.log("paso aqui");
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreo();
			if (resValidacion == true)
			{
				return false;
			}
			var correo_nuevo = $('#correo_nuevo').val();
			var rut_correo = $('#rut_ultimo').val();
			var cargo = $('#cargo').val();
			var uso = $('#uso').val();
			var nombre = $('#nombre').val();
			var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso+"&nombre="+nombre;
			console.log(data_correo_nuevo);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertar_correo.php",
				data:data_correo_nuevo,
				success: function(response)
				{
					limpiarSesion();
					$('#mostrar_correo').html(response);
					console.log(response);
					$('#AggCorreoModal').modal('hide');
					$('#correo_nuevo').val("") ;
					$('#cargo').prop('selectedIndex',0);
					$('#uso').val("").selectpicker('refresh');
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
			console.log("paso aqui");
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreocc();
			if (resValidacion == true)
			{
				return false;
			}
			var correo_nuevo = $('#correo_nuevo_cc').val();
			var rut_correo = $('#rut_ultimo_cc').val();
			var cargo = $('#cargo_cc').val();
			var uso = $('#uso_cc').val();
			var nombre = $('#nombre_cc').val();
			var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso+"&nombre="+nombre;
			console.log(data_correo_nuevo);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertar_correo_cc.php",
				data:data_correo_nuevo,
				success: function(response)
				{
					limpiarSesion();
					$('#mostrar_correo_cc').html(response);
					console.log(response);
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
			// verifico si la gestion es 1 y 5 y paso al siguiente Rut
			if (($tipoGestion == 1) || ($tipoGestion == 5)){
				nextRut();
			}else{
				// si la gestion es diferente a 1 y 5 marco el que acabo de llamar

				//alert(idFilaFono);
				$("#llamado"+idFilaFono).prop('checked',true);
				//$('#idFilaFono').attr('style', 'background-color:#CCFFFF');
				//background-color: #F3F781
				// verfico si tiene mas telefonos para Llamar
				var CantFilas = 0;
				var CantMarcados = 0; // style="background-color:#CCFFFF"
				//var CantMarcados = 1; // style="background-color:#CCFFFF"
				$("#mostrar_fonos table tr").each(function(indexTR){
					var ObjectTR = $(this);
					if(indexTR > 0){
						ObjectTR.find("td").each(function(indexTD){
							var ObjectTD = $(this);
							switch(indexTD){
								//case 7:
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
				//alert(CantFilas);
				//alert(CantMarcados);
				if(CantMarcados >= CantFilas){
					//alert('entro')
					nextRut();
				}else{
					var rut_ultimo = $('#rut_ultimo').val();
					var cedente = $('#IdCedente').val();
					funcionMostrarAgrupacionNoFono(cedente,'1',rut_ultimo);
				}
			}
		}
		function funcionMostrarFonos(data_fono)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_fonos_rut.php",
				data:data_fono,
				async: false,
				success: function(response)
				{
					$('#mostrar_fonos').html(response);
					$('#mostrar_fonos_ocultar').hide();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
					$('#nuevo_correo_cc').prop("disabled",false);
					$('#script_cobranza_mostrar').show();
					$('#script_cobranza_ocultar').hide();
					$('#botones_modal').show();
					/*if(!DialProviderConfigured){
						$("#tablaTelefonos .Llamar").prop("disabled",true);
					}else{
						$("#tablaTelefonos .Llamar").prop("disabled",false);
					}*/
				}
			});
		}
		function funcionMostrarDireccion(data_direccion)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_direccion_rut.php",
				data:data_direccion,
				success: function(response)
				{
					$('#mostrar_direccion').html(response);
					$('#mostrar_direccion_ocultar').hide();

				}
			});
		}
		function funcionMostrarDeudas(data_deudas)
		{
			$.ajax({
				type: "POST",
				url: "../includes/crm/deudas.php",
				data:data_deudas,
				success: function(response){
					console.log(response);
					if(isJson(response)){
						var json = JSON.parse(response);
						//response = response.replace("\n","");
						//response = response.replace("\r","");
						$('#mostrar_deudas table').empty();
						$('#mostrar_deudas').html(json.Table);
						if(json.result){
							$('#mostrar_deudas table').DataTable();
								$('#mostrar_deudas').prepend('<button id="crearConvenio" class="btn btn-success " style="margin-bottom:20px">Crear convenio</button>');

						}
					}
	
					funcionMostrar();
					funcionOcultar();
				},
				error: function(response){
					console.log(response);
					alert(response);
				}
			});
		}

	$('#mostrar_deudas').prepend('<button id="crearConvenio" class="btn btn-success " style="margin-bottom:20px">Crear convenio</button>');

	$(document).on('click', '#crearConvenio', function () {
					$('#modalConvenio').modal('show')
		});
	 $("#hoyConvenio").val( moment().format('DD/MM/YYYY'));

	 $(document).on('change', '#diasConvenio', function () {
			dia = $(this).val();
			dias = ( moment().add(dia, 'days') );
	 		$("#vencimientoConvenio").val(dias.format('DD/MM/YYYY'));
		});  

	 $(document).on('change', '#cuotasConvenio', function () {
	 		if($('#CalculoConvenio').val()!= ''){
	 			total = ($('#CalculoConvenio').val() / $(this).val() ).toFixed(2);
	 			$('#ValorCuotas').val(total);
	 		}else{
	 			$('#ValorCuotas').val('');

	 		}
		}); 
 	
 	$(document).on('click', '#GuardarConvenio', function () {
	 		if($('#ValorCuotas').val()!= '' && $('#ValorCuotas').val()!= ''){

	 			montoConvenio = $('#montoConvenio').val();
				DescuentoConvenio = $('#DescuentoConvenio').val();
				CalculoConvenio = $('#CalculoConvenio').val();
				hoyConvenio= $('#hoyConvenio').val();
				diasConvenio = $('#diasConvenio').val();
				vencimientoConvenio = $('#vencimientoConvenio').val();
				cuotasConvenio = $('#cuotasConvenio').val();
				ValorCuotas = $('#ValorCuotas').val();
	 			$.ajax({
					type: "POST",
					url: "../includes/crm/guardarConvenio.php",
					data:
					{	montoConvenio : montoConvenio,
						DescuentoConvenio : DescuentoConvenio,
						CalculoConvenio : CalculoConvenio,
						hoyConvenio : hoyConvenio,
						diasConvenio : diasConvenio,
						vencimientoConvenio : vencimientoConvenio,
						cuotasConvenio : cuotasConvenio,
						ValorCuotas : ValorCuotas
					},
					dataType: "json",
					success: function(response)
					{
						last = response[0].id_convenio;
						$('.body_convenio').prepend('<button id="excelConvenio" data-id="'+last+'" class="btn btn-success " style="margin-bottom:20px">Generar Excel</button>');

						$('#modalConvenio').modal('hide');
						$('#modalListadoConvenio').modal('show');
						$('#modalConvenio').modal('hide');
						$('#tablaCuotas').DataTable ({
					        "data" : response,
					        "columns" : [
					            { "data" : "cuota" },
					            { "data" : "fecha_vencimiento" },
					            { "data" : "valor" },
					        ]
					    });
						//$('#cantidad').html(response);
					}
				});
	 		}else{
	 			alert("favor llenar todos los dato");

	 		}
		}); 


 		 $(document).on('click', '#excelConvenio', function () {
 		 	id = $(this).attr('data-id');
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/generarExcelConvenio.php",
				data:{id:id},
				success: function(response)
				{
					window.open("../file/convenio-detalle.csv");
				}
			});


			});

	 $(document).on('change', '#montoConvenio, #DescuentoConvenio', function () {
			if($('#montoConvenio').val()!= '' && $('#DescuentoConvenio').val()!= ''){
				console.log("si");
				decimal = (100 - $('#DescuentoConvenio').val() ) /100 ;		
				montoCalculado = (decimal  * $('#montoConvenio').val()).toFixed(2);
				$('#CalculoConvenio').val(montoCalculado);
			}else{
				console.log("no");
				$('#CalculoConvenio').val('');
			}
		});
	 $("#hoyConvenio").val( moment().format('DD/MM/YYYY') );



	$('input.numberinput').bind('keypress', function (e) {
        return !(e.which != 8 && e.which != 0 &&
                (e.which < 48 || e.which > 57) && e.which != 46);
    });
		function funcionMostrarRegistros(data_reg)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_reg.php",
				data:data_reg,
				success: function(response)
				{
					$('#cantidad').html(response);
				}
			});
		}
		function funcionMostrarCorreo(data_correo)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_correo_rut.php",
				data:data_correo,
				success: function(response)
				{
					$('#mostrar_correo').html(response);
					$('#mostrar_correo_ocultar').hide();

				}
			});
		}
		function funcionMostrarGestion(data_gestion)
		{	//Gestiones con Contacto
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_rut.php",
				data:data_gestion,
				beforeSend: function(){
					if ( $.fn.dataTable.isDataTable( '#mostrar_gestion_contacto_dt' ) ) {
						GestionContactoTable.destroy();
					}
				},
				success: function(response)
				{
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						GestionContactoTable = $('#mostrar_gestion_contacto_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'fono_discado' },
								{ data: 'n1'},
								{ data: 'n2' },
								{ data: 'n3' },
								{ data: 'compromiso' },
								{ data: 'monto' },
								{ data: 'observacion' },
								{ data: 'canales' },
								{ data: '' }
							],
							columnDefs: [ 
								{
									className: "dt-center",
									"targets": 10,
									visible: !GlobalData.isEjecutivo,
									"render": function( data, type, row ) {
										return "<div style='text-align: center;'><i class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteGestion' id='"+row.gestion+"' style='font-size: 20px;cursor: pointer;'></i></div>";
									}
								}
							],
							language: {
								emptyTable: 'RUT no registra gestiones por contacto'
								},
							destroy: true
						});
					}
					$('#mostrar_gestion_contacto_ocultar').hide();
				}
			});
		}

		function funcionMostrarGestionTotal(data_gestion_total)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_total_rut.php",
				data:data_gestion_total,
				beforeSend: function(){
					if ( $.fn.dataTable.isDataTable( '#mostrar_gestion_total_dt' ) ) {
						GestionTotalTable.destroy();
					}
				},
				success: function(response)
				{
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						GestionTotalTable = $('#mostrar_gestion_total_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'fono_discado' },
								{ data: 'status_name' },
								{ data: 'n1'},
								{ data: 'n2' },
								{ data: 'n3' },
								{ data: 'compromiso' },
								{ data: 'monto' },
								{ data: 'factura' },
								{ data: 'observacion' },
								{ data: '' }
							],
							columnDefs: [ 
								{
									className: "dt-center",
									"targets": 11,
									visible: !GlobalData.isEjecutivo,
									"render": function( data, type, row ) {
										return "<div style='text-align: center;'><i class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteGestion' id='"+row.gestion+"' style='font-size: 20px;cursor: pointer;'></i></div>";
									}
								}
							],
							language: {
								emptyTable: 'RUT no registra gestiones totales'
								},
							destroy: true
						});
					}
					$('#mostrar_gestion_total_ocultar').hide();
				}
			});
		}
		function funcionMostrarGestionCorreo(data_gestion_total)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_correo.php",
				data:data_gestion_total,
				beforeSend: function(){
					if ( $.fn.dataTable.isDataTable( '#mostrar_gestion_correo_dt' ) ) {
						GestionCorreoTable.destroy();
					}
				},
				success: function(response)
				{
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						GestionCorreoTable = $('#mostrar_gestion_correo_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'hora_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'correo' },
								{ data: 'factura' },
								{ data: 'estado' }
							],
							language: {
								emptyTable: 'RUT no registra gestiones por correo'
								},
							destroy: true
						});
					}
					$('#mostrar_gestion_correo_ocultar').hide();
				}
			});
		}
		function funcionMostrarGestionSMS(data_gestion_total) {
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_sms.php",
				data: data_gestion_total,
				beforeSend: function () {
					if ($.fn.dataTable.isDataTable('#mostrar_gestion_sms_dt')) {
						GestionSMSTable.destroy();
					}
				},
				success: function (response) {
					console.log(response);
					if (isJson(response)) {
						var dataSet = JSON.parse(response);

						GestionSMSTable = $('#mostrar_gestion_sms_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'hora_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'fono' },
								{ data: 'estado' }
							],
							language: {
								emptyTable: 'RUT no registra gestiones por SMS'
							},
							destroy: true
						});
					}
					$('#mostrar_gestion_sms_ocultar').hide();
				}
			});
		}

		function funcionMostrarGestionIVR(data_gestion_total) {
			$.ajax(
				{
					type: "POST",
					url: "../includes/crm/mostrar_gestion_ivr.php",
					data: data_gestion_total,
					beforeSend: function () {
						if ($.fn.dataTable.isDataTable('#mostrar_gestion_ivr_dt')) {
							GestionIVRTable.destroy();
						}
					},
					success: function (response) {
						console.log(response);
						if (isJson(response)) {
							var dataSet = JSON.parse(response);

							GestionIVRTable = $('#mostrar_gestion_ivr_dt').DataTable({
								data: dataSet,
								columns: [
									{ data: 'fecha_gestion' },
									{ data: 'hora_gestion' },
									{ data: 'fono' },
									{ data: 'duracion' },
									{ data: 'estado' }
								],
								language: {
									emptyTable: 'RUT no registra gestiones por IVR'
								},
								destroy: true
							});
						}
						$('#mostrar_gestion_ivr_ocultar').hide();
					}
				});
		}

		function funcionMostrarGestionesExternas(data_gestiones_externas) {
			$.ajax(
				{
					type: "POST",
					url: "../includes/crm/mostrar_gestiones_externas.php",
					data: data_gestiones_externas,
					beforeSend: function () {
						if ($.fn.dataTable.isDataTable('#mostrar_gestiones_externas_dt')) {
							GestionesExternasTable.destroy();
						}
					},
					success: function (response) {
						console.log(response);
						if (isJson(response)) {
							var dataSet = JSON.parse(response);
							if(dataSet.Gestiones.length > 0){
								GestionesExternasTable = $('#mostrar_gestiones_externas_dt').DataTable({
									data: dataSet.Gestiones,
									columns: dataSet.Columnas,
									language: {
										emptyTable: 'RUT no registra gestiones externas'
									},
									destroy: true
								});
							}
						}
						$('#mostrar_gestiones_externas_ocultar').hide();
					}
				});
		}

		function funcionMostrarGestionDiaria(data_gestion_diaria)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_diaria_rut.php",
				data:data_gestion_diaria,
				beforeSend: function(){
					if ( $.fn.dataTable.isDataTable( '#mostrar_gestion_diaria_dt' ) ) {
						GestionDiariaTable.destroy();
					}
				},
				success: function(response)
				{
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						GestionDiariaTable = $('#mostrar_gestion_diaria_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'fono_discado' },
								{ data: 'status_name' },
								{ data: 'n1'},
								{ data: 'n2' },
								{ data: 'n3' },
								{ data: 'compromiso' },
								{ data: 'monto' },
								{ data: 'factura' },
								{ data: 'observacion' },
								{ data: 'canales' },
								{ data: '' }
							],
							columnDefs: [ 
								{
									className: "dt-center",
									"targets": 12,
									visible: !GlobalData.isEjecutivo,
									"render": function( data, type, row ) {
										return "<div style='text-align: center;'><i class='btn fa fa-trash btn-danger btn-icon icon-lg DeleteGestion' id='"+row.gestion+"' style='font-size: 20px;cursor: pointer;'></i></div>";
									}
								}
							],
							language: {
								emptyTable: 'RUT no registra gestiones diarias'
								},
							destroy: true
						});
					}
					$('#mostrar_gestion_diaria_ocultar').hide();
				}
			});
		}
		function funcionMostrarPagos(data_pagos)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_pagos_rut.php",
				data:data_pagos,
				beforeSend: function(){
					if ( $.fn.dataTable.isDataTable( '#mostrar_gestion_pagos_dt' ) ) {
						GestionPagosTable.destroy();
					}
				},
				success: function(response)
				{
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						GestionPagosTable = $('#mostrar_gestion_pagos_dt').DataTable({
							data: dataSet,
							columns: [
								{ data: 'rut' },
								{ data: 'fecha' },
								{ data: 'monto' },
								{ data: 'operacion' },
							],
							language: {
								emptyTable: 'RUT no registra pagos'
								},
							destroy: true
						});
					}
					$('#mostrar_gestion_pagos_ocultar').hide();
				}
			});
		}
		function funcionMostrarAgrupacion(cedente,prefijo,rut)
		{
			var TipoEstrategia = $('#seleccione_tipo_busqueda').val();
			
			var dataColor = "";
			switch(TipoEstrategia){
				case "1":
					var idCola = $('#seleccione_cola').val();
					dataColor = {rut:rut, prefijo:prefijo, cola:idCola};
				break;
				case "2":
                    dataColor = {rut:rut, prefijo:prefijo};
                case "3":
                    dataColor = {rut:rut, prefijo:prefijo};
                case "4":
                    dataColor = {rut:rut, prefijo:prefijo};
            
				break;
			}
			
			//var dataColor = {rut:rut, prefijo:prefijo, cola:idCola};
			var data1 = "rut="+rut;
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			var data3 = "rut="+rut+"&cedente="+cedente;
			var data4 = "cedente="+cedente;


			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3);
			funcionMostrarRegistros(data2);
			funcionMostrarFonos(dataColor);
			funcionMostrarCorreo(data1);
			funcionMostrarGestion(data1);
			funcionMostrarGestionTotal(data1);
			funcionMostrarGestionCorreo(data1);
			funcionMostrarGestionSMS(data1);
			funcionMostrarGestionIVR(data1);
			funcionMostrarGestionesExternas(data1);
			funcionMostrarGestionDiaria(data1);
			funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
			getTabsContent(data1);
			limpiarSesion();
		}
		function funcionMostrarAgrupacionNoFono(cedente,prefijo,rut)
		{
			var data1 = "rut="+rut;
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			var data3 = "rut="+rut+"&cedente="+cedente;
			var data4 = "cedente="+cedente;
			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3)
			funcionMostrarRegistros(data2);
			//funcionMostrarFonos(data2);
			funcionMostrarCorreo(data1);
			funcionMostrarGestion(data1);
			funcionMostrarGestionTotal(data1);
			funcionMostrarGestionCorreo(data1);
			funcionMostrarGestionSMS(data1);
			funcionMostrarGestionIVR(data1);
			funcionMostrarGestionesExternas(data1);
			funcionMostrarGestionDiaria(data1);
			funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
			getTabsContent(data1);
			limpiarSesion();
		}
		function funcionNivelRapido(ce)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/nivel_rapido.php",
				data:ce,
				success: function(response)
				{
					$('#respuesta_rapida').html(response);
					$('.selectpicker').selectpicker('refresh')
					$('#respuesta_rapida_ocultar').hide();
				}
			});
		}
		$(document).on('click', '.VerFono', function () {
			var id_fono = $(this).closest('tr').attr('class');
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_detalle_fono.php",
				data: {id_fono:id_fono},
				async: false,
				success: function (response) {
					$('#mostrar_detalle_fono').html(response);
					$('#modalFono').modal('show')
				}
			});
		});

		function validarCampos(campos){
			var valor = 1;
			$.each(campos,function(index,contenido){
				if (typeof contenido != "undefined"){
					if(contenido ==''){
						$('#'+index+'_').addClass('has-error');
						valor = 0;
					}else{
						$('#'+index+'_').removeClass('has-error');
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

					if (!$(ObjectMe).hasClass("bootstrap-select")){
						var Value = $(ObjectMe).val();
					}else{
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

		function funcionNivel1(ce)
		{
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
			setTimeout(function(){
				var busqueda = $('#seleccione_tipo_busqueda').val();
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/nivel_1.php",
					data:{ cedente:ce,busqueda:busqueda },
					success: function(response)
					{
						$('.nivel_1_ocultar').hide();
						$('.nivel_1_mostrar').html(response);
						$('.selectpicker').selectpicker('refresh')
						$('#Cargando').modal('hide')
					}
				});
			},1000);
		}

		$(document).on('change', '#seleccione_nivel1', function() 
		{
			$('#tipo_gestion').val('');
			$('.nivel_2_ocultar').hide();
			$('.nivel_2_mostrar').show();
			var nivel2 = $('#seleccione_nivel1').val();
			var nivel2 = "nivel2="+nivel2;
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/nivel_2.php",
				data:nivel2,
				async: false,
				success: function(response)
				{
					$('.nivel_2_mostrar').html(response);
					$('.selectpicker').selectpicker('refresh')

					$('.datetimepicker').datetimepicker({
						format: 'YYYY-MM-DD HH:mm:ss',
						locale: 'es'
					});
				}
			});
		});

		$(document).on('change', '#seleccione_nivel2', function() 
		{
			$('.nivel_3_ocultar').hide();
			$('.nivel_3_mostrar').show();
			var nivel3 = $('#seleccione_nivel2').val();
			var nivel3 = "nivel3="+nivel3;
			console.log(nivel3);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/nivel_3.php",
				data:nivel3,
				async: false,
				success: function(response)
				{
					$('.nivel_3_mostrar').html(response);
					var tipo_gestion = $('#tipo_gestion').val();
					if (tipo_gestion == 5)
					{
						$('#seleccione_nivel3').html("<select class='selectpicker' id='seleccione_nivel3' name='seleccione_nivel3'><option value='0'>Seleccione</option><option value='0'>COMPROMISO</option></select>");
					}
					else
					{
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

		$(document).on('change', '#seleccione_nivel3', function() 
		{
			var nombreNivel1 = $("#seleccione_nivel1 option:selected").html();
			var bandera = 1; // discado
			var mensa;
			if (nombreNivel1 != 'INBOUND'){
				// valido telefono
				if($('#ultimo_fono').val()==0){
					bandera = 0; // sin discar
					mensa = "Debe seleccionar un telefono para continuar con la gestión";
				}
			}else{
				// si entra aca es INBOUND
				// verifico que tenga un rut seleccionado
				if ($('#rut_buscado').val() == ''){
					bandera = 0;
					mensa = "Debe buscar un Rut para poder continuar con la gestión";
				}
			}

			if(bandera==0){
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-close',
					message : mensa,
					container : 'floating',
					timer : 4000
				});
				$('#seleccione_nivel3').prop('selectedIndex',0);
				$('.selectpicker').selectpicker('refresh');
			}else{
				$('#grupo1').show();
				fono_discado = $('#ultimo_fono').val();
				var rut_ultimo = $('#rut_ultimo').val();
				var nivel4 = "nivel_3="+$('#seleccione_nivel3').val()+"&cortar_valor="+cortar_valor+"&rut="+rut_ultimo;
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/nivel_4.php",
					data:nivel4,
					async: false,
					success: function(response)
					{
						$('#tipo_gestion_final').val(response.tipo_gestion_final)
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

		$(document).on('click', '#guardar', function() 
		{
			var tiempoLlamada = transcurrido();
			var i = 1;
			while(i<=10)
			{
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
			}else{
				var fecha_compromiso = '';
				var monto_compromiso = 0;
			}
			if (DatosCedente.agendamiento == 1 && tipo_gestion_final != 5) {
				var fechaAgendamiento = $('#fecha_agendamiento').val();
				if(DatosCedente.agendamiento_obligatorio == 1){
					camposForm.fecha_agendamiento = fechaAgendamiento
				}
			}else{
				var fechaAgendamiento = '';
			}

			var retorno = validarCampos(camposForm);
			var ArrayCampos = validarCamposDinamicos();

			if (retorno == 0){
				bootbox.alert('Debe Completar todos los campos!');
				return 0;
			}

			if (!ArrayCampos) {
				bootbox.alert('Debe Completar todos los campos!');
				return 0;
			}
			if (DatosCedente.facturas == 1) {
				var facturas = $('#facturas').val();
				if ((facturas == 0) || (facturas == "") || (facturas == null)){
					bootbox.alert('Debe seleccionar minimo una Factura');
					return 0;
				}
			}else{
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
			var origen = 0;
			var Hablar  = $("#Hablar").val();
			omnicanalidad();
			var insertar1 = "nivel1=" + nivel1 + "&nivel2=" + nivel2 + "&nivel3=" + nivel3 + "&comentario=" + comentario + "&rut=" + rut_ultimo + "&fono_discado=" + fono_discado + "&tipo_gestion=" + tipo_gestion_final + "&cedente=" + cedente + "&usuario_foco=" + nombre_usuario_foco + "&lista=" + numero_cola + "&fecha_compromiso=" + fecha_compromiso + "&monto_compromiso=" + monto_compromiso + "&tiempoLlamada=" + tiempoLlamada + "&NombreGrabacion=" + NombreGrabacion + "&asignacion=" + asignacion + "&origen=" + origen + "&facturas=" + facturas + "&fechaAgendamiento=" + fechaAgendamiento + "&Hablar=" + Hablar + "&UrlGrabacion=" + UrlGrabacion + "&canales=" + canales + "&prioridades=" + prioridades + "&ArrayCampos=" + JSON.stringify(ArrayCampos);
			
			CortarLlamada();
			setTimeout(function(){
				$.ajax({
					type: "POST",
					url: "../includes/crm/insertar1.php",
					data:insertar1,
					async: false,
					success: function(response)
					{
						console.log(response);
						progressBar(response);

						if($('#seleccione_tipo_busqueda').val() == 1){
							insertarNivelCola();
						}

						$('#seleccione_nivel1').prop('selectedIndex',0);
						$('#seleccione_nivel2').prop('selectedIndex',0);
						$('#seleccione_nivel3').prop('selectedIndex',0);
						$('#ultimo_fono').val('0');
						$("textarea").val("");
						$("#Hablar").val("");
						$('#respuesta').prop('selectedIndex',0);
						if ((rutEstrategia == 2) && (GlobalData.tipoSistema == 2)){
							fonosLlamando(tipo_gestion_final);
						}else{
							funcionMostrarAgrupacionNoFono(cedente,'1',rut_ultimo);
						}

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
				var cola 	= $('#seleccione_cola').val();
			}else{
				var nivel1 = $('#seleccione_nivel1').val();
				var nivel2 = $('#seleccione_nivel2').val();
				var nivel3 = $('#seleccione_nivel3').val();
				var rut = $('#rut_ultimo').val();
				var cola 	= $('#seleccione_cola').val();
			}

			var post = "cola="+cola+"&rut="+rut+"&nivel1="+nivel1+"&nivel2="+nivel2+"&nivel3="+nivel3;

			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertarNivelCola.php",
				data:post,
				success: function(response)
				{
					console.log(response);
				}
			});
		}
		function funcionLimpiar()
		{
	  		$('#seleccione_nivel1').prop('selectedIndex',0);
			$('#seleccione_nivel2').prop('selectedIndex',0);
			$('#seleccione_nivel3').prop('selectedIndex',0);
			$("textarea").val("");
			$("#fecha_compromiso").val("");
			$("#monto_compromiso").val("");
			$('#respuesta').prop('selectedIndex',0);
			$('#seleccione_cedente2').prop('selectedIndex',0);
			$('#seleccione_tipo_busqueda').prop('selectedIndex',0);
			//$("#respuesta").prop("disabled",true);
			$('#mostrar_deudas_ocultar').show();
			$('#mostrar_deudas').hide();
			$('#mostrar_fonos_ocultar').show();
			$('#mostrar_fonos').hide();
			$('#mostrar_gestion_ocultar').show();
			$('#mostrar_gestion').hide();
			$('#mostrar_gestion_total_ocultar').show();
			$('#mostrar_gestion_total').hide();
			$('#mostrar_gestion_diaria_ocultar').show();
			$('#mostrar_gestion_diaria').hide();
			$('#mostrar_gestion_correo_ocultar').show();
			$('#mostrar_gestion_correo').hide();
			$('#mostrar_gestion_pagos_ocultar').show();
			$('#mostrar_gestion_pagos').hide();
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
			$('.nombre_cliente').html('');
		}
		function funcionMostrar()
		{
			$('#mostrar_deudas').show();
			$('#mostrar_fonos').show();
			$('#mostrar_direccion').show();
			$('#mostrar_correo').show();
			$('#mostrar_correo_cc').show();
	  	}
	  	function funcionOcultar()
		{
			$('#mostrar_deudas_ocultar').hide();
			$('#mostrar_fonos_ocultar').hide();
			$('#mostrar_direccion_ocultar').hide();
			$('#mostrar_correo_ocultar').hide();
			$('#mostrar_correo_ocultar_cc').hide();
	  	}
		$(document).on('click', '.adjuntar', function()
		{
			var clase = '#l'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');

			if ($(clase).is(':checked'))
	        {
	        	$('#enviar_factura').prop("disabled",false);
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response)
					{
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
	        else
	        {
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response)
					{
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

		$(document).on('click', '.adjuntar_cc', function()
		{
			var clase = '#l_cc'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');

			if ($(clase).is(':checked'))
	        {
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response)
					{
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
	        else
	        {
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response)
					{
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

		$(document).on('click', '#enviar_factura', function()
		{
			var cedente = $('#IdCedente').val();
			var rut = $('#rut_ultimo').val();
			var data = "cedente="+cedente+"&rut="+rut;
			console.log(data);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/enviar_mail.php",
				data:data,
				success: function(response)
				{
					console.log(response);
					if(response==2)
					{
						var msg = "No has seleccionado un Email de Envio!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else if(response==3)
					{
						var msg = "No has adjuntado Factura!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else if(response==1)
					{
						var msg = "No se puede enviar Mail , Cedente no tiene Template Cargado en la Base de Datos , Consulte con el Administrador.";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else
					{
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
		$(document).on('click', '.fono_gestion', function()
		{
			id = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var telefono = '#'+'telefono'+id;
			var valor_telefono = $(telefono).val();
			$('#ultimo_fono').val(valor_telefono);
		});
		$(document).on('click', '.ckhsel', function()
		{
			var id_deuda = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var rut_factura = $('#rut_ultimo').val();
			var cedente = $('#IdCedente').val();
			console.log(clase);
			if ($(clase).is(':checked'))
	        {
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=1";

				console.log(var_factura);
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response)
					{
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
			else
			{
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=0";
				console.log(var_factura);
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response)
					{
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
			console.log(id_mail);
			var data1 = '#'+'correo'+id;
			var data2 = '#'+'nombre'+id;
			var data3 = '#'+'cargo'+id;
			var data4 = '#'+'obs'+id;
			var mail = $(data1).val();
			var nombre = $(data2).val();
			var cargo = $(data3).val();
			var obs = $(data4).val();
			console.log(cargo);
			var idmail = "id_mail="+id_mail+"&mail="+mail+"&nombre="+nombre+"&cargo="+cargo+"&obs="+obs;
	    	$.ajax(
			{
				type: "POST",
				url: "../includes/crm/actualizar_mail.php",
				data:idmail,
				success: function(response)
				{
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

		$(document).on('change', '.direccion_cambiar', function(){
			var ObjectMe = $(this);
			var ObjectTR = ObjectMe.closest('tr');
			id = ObjectTR.attr('id');
			var id_direccion = ObjectTR.attr('class');
			var Direccion = ObjectTR.find(".direccion").val();
			var Comuna = ObjectTR.find(".comuna").val();
			var iddireccion = "id_direccion="+id_direccion+"&direccion="+Direccion+"&comuna="+Comuna;
	    	$.ajax({
				type: "POST",
				url: "../includes/crm/actualizar_direccion.php",
				data:iddireccion,
				success: function(response){
					if(isJson){
						var Json = JSON.parse(response);
						if(Json.result){
							$.niftyNoty({
								type: 'success',
								icon : 'fa fa-check',
								message : 'Datos Actualizados!',
								container : 'floating',
								timer : 1000
							});
						}
					}
					console.log(response);
				}
			});
		});
		$(document).on('change', '.telefono_cambiar', function(){
			var ObjectMe = $(this);
			var ObjectTR = ObjectMe.closest('tr');
			var ObjectTD = ObjectMe.closest("td");
			id = ObjectTR.attr('id');
			var id_reg = ObjectTR.attr('class');
			var Telefono = ObjectTR.find(".telefono").val();
			var Nombre = ObjectTR.find(".nombre").val();
			var Cargo = ObjectTR.find(".cargo").val();
			var Observacion = ObjectTR.find(".observacion").val();
			var idtelefono = "id_reg="+id_reg+"&telefono="+Telefono+"&nombre="+Nombre+"&cargo="+Cargo+"&observacion="+Observacion;

			var CanCall = CanCallFono(Telefono);
			if(!CanCall){
				bootbox.alert("Teléfono no Cumple con el Formato ("+GlobalData.FonoPrefix.Length+" Digitos)");
				var telefono_viejo = ObjectTD.find("input[type='hidden']").val();
				ObjectTR.find(".telefono").val(telefono_viejo);
			}
			else{
				$.ajax({
					type: "POST",
					url: "../includes/crm/actualizar_telefono.php",
					data:idtelefono,
					success: function(response){
						if(isJson){
							var Json = JSON.parse(response);
							if(Json.result){
								$.niftyNoty({
									type: 'success',
									icon : 'fa fa-check',
									message : 'Datos Actualizados!',
									container : 'floating',
									timer : 1000
								});
								var Hidden = ObjectTD.find("input[type='hidden']").attr("value",Telefono);
								$('#tablaTelefonos tr.' + id_reg).find('.telefono').val(Telefono);
								$('#tablaDetalleTelefono tr.' + id_reg).find('.telefono').val(Telefono);
							}
						}
					}
				});
			}
		});

		$(document).on('click', '#nuevo_telefono', function() {
			bootbox.dialog({
				title: "Ingrese Nuevo Telefono",
				message:'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> '+
									'<div class="form-group"> '+
										'<label class="col-md-4 control-label" for="name">Nuevo Telefonos</label> ' +
										'<div class="col-md-4"> ' +
											'<input id="fono_discado_nuevo" name="name" type="number" placeholder="" class="form-control input-md solo-numero"> ' +
										'</div> ' +
									'</div> ' +
								'</form>'+
							'</div>'+
						'</div>'+
						
						'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> '+
									'<div class="form-group"> '+
										'<label class="col-md-4 control-label" for="name">Nombre</label> ' +
										'<div class="col-md-4"> ' +
											'<input id="Nombre_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
										'</div> ' +
									'</div> ' +
								'</form>'+
							'</div>'+
						'</div>'+
						
						'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> '+
									'<div class="form-group"> '+
										'<label class="col-md-4 control-label" for="name">Cargo</label> ' +
										'<div class="col-md-4"> ' +
											'<input id="Cargo_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
										'</div> ' +
									'</div> ' +
								'</form>'+
							'</div>'+
						'</div>'+
						
						'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> '+
									'<div class="form-group"> '+
										'<label class="col-md-4 control-label" for="name">Observacion</label> ' +
										'<div class="col-md-4"> ' +
											'<textarea id="Observacion_nuevo" name="name" class="form-control input-md"></textarea> ' +
										'</div> ' +
									'</div> ' +
								'</form>'+
							'</div>'+
						'</div>',
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-primary",
						callback: function() {
							var resValidacion = validarNuevoTelefono();
							if (resValidacion == true)
							{
								return false;
							}
							var fono_discado_nuevo = $('#fono_discado_nuevo').val();
							var Nombre = $("#Nombre_nuevo").val();
							var Cargo = $("#Cargo_nuevo").val();
							var Observacion = $("#Observacion_nuevo").val();
							if(fono_discado_nuevo.length != 9)
							{
								$.niftyNoty({
									type: 'danger',
									icon : 'fa fa-close',
									message : "Registro no Cumple con el Formato",
									container : 'floating',
									timer : 4000
								});
								return false;
							}
							else
							{
								var TipoEstrategia = $('#seleccione_tipo_busqueda').val();
								switch (TipoEstrategia) {
									case "1":
										var cola = $('#seleccione_cola').val();
										break;
									case "2":
										var cola = '';
										break;
								}
								var rut_fono = $('#rut_ultimo').val();
								var i = $('#tablaTelefonos >tbody >tr').length + 1;
								var data_fono_nuevo = "rut=" + rut_fono + "&fono_discado_nuevo=" + fono_discado_nuevo + "&Nombre_nuevo=" + Nombre + "&Cargo_nuevo=" + Cargo + "&Observacion_nuevo=" + Observacion + "&cola=" + cola + "&i=" + i;
								console.log(data_fono_nuevo);
								//Ok
								$.ajax(
								{
									type: "POST",
									url: "../includes/crm/insertar_fonos.php",
									data:data_fono_nuevo,
									success: function(response)
									{
										console.log(response);
										// $('#mostrar_fonos').html(response);
										$('#tablaTelefonos tr:last').after(response);
										$('#mostrar_fonos_ocultar').hide();
										// if(isJson(response)){
											// var dataSet = JSON.parse(response);
											// var fecha = new Date();
											// var fechaCarga = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
											// var fila = $('#tablaTelefonos >tbody >tr').length + 1;
											// $('#tablaTelefonos tr:last').after('<tr id="'+fila+'" class="'+dataSet[0].id_fono+'"><td class="text-sm" style="padding-top: 15px;"><center><i class="fa fa-flag fa-lg icon-lg" style="color:#ff0080"></i> </center></td><td class="text-sm" style="padding-top: 15px;">Nuevo Fono</td><td class="text-sm"><input type="hidden" id="telefono'+fila+'" value="'+fono_discado_nuevo+'" name="telefono'+fila+'"><input type="text" class="telefono_cambiar text6 telefono SoloNumeros" value="'+fono_discado_nuevo+'"></td><td class="text-sm"><center><button id="fono'+fila+'" class="btn btn-success btn-icon icon-lg fa fa-phone Llamar"  value="Llamar"></button></center></td><td class="text-sm"><center><input type="checkbox" disabled  class="fono_gestion" name="llamado'+fila+'" value="llamado'+fila+'" id="llamado'+fila+'" ></center></td><td class="text-sm"><input type="hidden" id="Nombre'+fila+'" value="'+Nombre+'" name="Nombre'+fila+'"><input type="text" class="telefono_cambiar text6 nombre" value="'+Nombre+'"></td><td class="text-sm"><input type="hidden" id="Cargo'+fila+'" value="'+Cargo+'" name="Cargo'+fila+'"><input type="text" class="telefono_cambiar text6 cargo" value="'+Cargo+'"></td><td class="text-sm"><input type="hidden" id="Observacion'+fila+'" value="'+Observacion+'" name="Observacion'+fila+'"><input type="text" class="telefono_cambiar text6 observacion" value="'+Observacion+'"></td><td class="text-sm">0</td></tr>');
										// }
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

		function retiroDocumentos()
		{
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
				buttons:
				{
					success:
					{
						label: "Guardar",
						className: "btn-primary",
						callback: function()
						{
							var direccion_nuevo = $('#direccion_nuevo').val();

							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax(
							{
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response)
								{
									$('#mostrar_direccion').html(response);
									console.log(response);
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
							if (resValidacion == true)
							{
								return false;
							}
							var direccion_nuevo = $('#direccion_nuevo').val();

							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax(
							{
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response)
								{
									$('#mostrar_direccion').html(response);
									console.log(response);
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
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/ver_cargo.php",
				data:data,
				success: function(response)
				{
					resp = response;
					console.log(resp);
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
									var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso;
									console.log(data_correo_nuevo);
									$.ajax(
									{
										type: "POST",
										url: "../includes/crm/insertar_correo.php",
										data:data_correo_nuevo,
										success: function(response)
										{
											$('#mostrar_correo').html(response);
											console.log(response);
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
		$(document).on('change', '#seleccione_tipo_busqueda', function()
		{	
			var ce = $('#IdCedente').val();
			funcionNivel1(ce);
			if($('#seleccione_tipo_busqueda').val() == 1)
			{
				limpiarSesion();
				id = $('#IdCedente').val();
				var data = "id="+id;
				$.ajax({
					type: "POST",
					url: "../includes/crm/seleccione_cedente.php",
					data:data,
					success: function(response){
						$('#busqueda_rut').hide();
						$('#busqueda_estrategia').show();
						$('#colas2').hide();
						$('#colas_mostrar2').show();
						$('#colas_mostrar2').html(response);
						$('.selectpicker').selectpicker('refresh')
						$('.nivel_1_ocultar').hide();
						if(estrategiaAD !== ""){
							$("#seleccione_estrategia").val(estrategiaAD).trigger("change");
						}
					}
				});
			}
			else if($('#seleccione_tipo_busqueda').val() == 2)
			{
                //$('#tipoBusqueda').val(1);
                $('#busqueda_rut').show();
                $('#busqueda_rut4').hide();
                $('#busqueda_rut3').hide();
				$('#busqueda_estrategia').hide();
				limpiarSesion();
			}else if($('#seleccione_tipo_busqueda').val() == 3){
                $('#busqueda_rut3').show();
                $('#busqueda_rut4').hide();
                $('#busqueda_rut').hide();
				$('#busqueda_estrategia').hide();
				limpiarSesion();
            }else{
                $('#busqueda_rut4').show();
                $('#busqueda_rut').hide();
                $('#busqueda_rut3').hide();
				$('#busqueda_estrategia').hide();
				limpiarSesion();

            }
		});
		$(document).on('click', '#buscar_rut', function()
		{
			var rut_buscado = $('#rut_buscado').val();
			var cedente = $('#IdCedente').val();
			rutEstrategia = 1; // para indicar que se encuentra en buscar
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
            })
            var data = "prefijo=" + rut_buscado + "&estrategia=" + 0 + "&orden=" + 0 + "&tipo=3";
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_rut.php",
				data: data,
				dataType: 'json',
				success: function(response){
					$('#Cargando').modal('hide')
					if(response){
						console.log('por aca');
						var rut_ultimo = $('#rut_ultimo').val(rut_buscado);
						$('#script_cobranza_mostrar').show();
						funcionMostrarAgrupacion(cedente,'1',rut_buscado);
					}else{
						$.niftyNoty({
							type: 'danger',
							icon: 'fa fa-close',
							message: " No existe Rut para el Cedente Seleccionado!",
							container: 'floating',
							timer: 4000
						});
					}
				}
			});
        });
        $(document).on('click', '#buscar_rut3', function()
		{
			var rut_buscado = $('#rut_buscado3').val();
            var cedente = $('#IdCedente').val();
			rutEstrategia = 1; // para indicar que se encuentra en buscar
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
            });
            var data = "prefijo=" + rut_buscado + "&estrategia=" + 0 + "&orden=" + 0 + "&tipo=3";
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_rut.php",
				data: data,
				dataType: 'json',
				success: function(response){
					$('#Cargando').modal('hide')
					if(response){
						funcionMostrarAgrupacion(cedente,'1',rut_buscado);
					}else{
						$.niftyNoty({
							type: 'danger',
							icon: 'fa fa-close',
							message: " No existe Rut para el Cedente Seleccionado!",
							container: 'floating',
							timer: 4000
						});
					}
				}
			});
        });
        
       

		$(document).on('change', '#respuesta', function()
		{
			if($('#ultimo_fono').val()==0)
			{
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-check',
					message : "Debe seleccionar un telefono para guardar la Gestion!",
					container : 'floating',
					timer : 4000
				});
				$('#respuesta').prop('selectedIndex',0);
				$('#respuesta').selectpicker('refresh')
			}
			else
			{
				var tiempoLlamada = transcurrido();
				var fono_discado = $('#ultimo_fono').val();
				var i = 1;

				while(i<=7)
				{
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
				var origen = 0;
				var insertar3 = "nivel1="+resp+"&rut="+rut_ultimo+"&fono_discado="+fono_discado+"&tipo_gestion="+tipo_gestion2+"&cedente="+cedente+"&duracion_llamada="+duracion_llamada2+"&usuario_foco="+nombre_usuario_foco+"&lista="+numero_cola+"&tiempoLlamada="+tiempoLlamada+"&NombreGrabacion="+NombreGrabacion+"&asignacion="+asignacion+"&origen="+origen+"&UrlGrabacion="+UrlGrabacion;
				CortarLlamada();
				
				setTimeout(function(){
					$.ajax(
					{
						type: "POST",
						url: "../includes/crm/insertar3.php",
						data:insertar3,
						success: function(response)
						{
							console.log(response);
							progressBar(response);

							if($('#seleccione_tipo_busqueda').val() == 1){
								insertarNivelCola();
							}

							$('#seleccione_nivel1').prop('selectedIndex',0);
							$('#seleccione_nivel2').prop('selectedIndex',0);
							$('#seleccione_nivel3').prop('selectedIndex',0);
							$("textarea").val("");
							$("#fecha_compromiso").val("");
							$("#monto_compromiso").val("");
							$('#respuesta').prop('selectedIndex',0);
							funcionNivelRapido(cedente);
							$('#respuesta').prop('selectedIndex',0);

							$.niftyNoty(
							{
								type: 'success',
								icon : 'fa fa-check',
								message : 'Respuesta Rapida Guardada' ,
								container : 'floating',
								timer : 2000
							});
							$('#ultimo_fono').val('0');
							if ((rutEstrategia == 2) && (GlobalData.tipoSistema == 2)){
								fonosLlamando(tipo_gestion2);
							}else{
								funcionMostrarAgrupacionNoFono(cedente,'1',rut_ultimo);
							}

							$('#grupo1').hide();
							$('.nivel_2_mostrar').hide();
							$('.nivel_3_mostrar').hide();
							$('.nivel_2_ocultar').show();
							$('.nivel_3_ocultar').show();
						}
					});
				},1000);
			}
		});

		$(document).on('change', '#seleccione_cedente2', function()
		{
	    	id = $('#seleccione_cedente2').val();
	    	console.log(id);
	    	$('#IdCedente').val(id);
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
					if((typeof colaAD !== 'undefined') && (colaAD !== "")){
						$("#seleccione_cola").val(colaAD).trigger("change");
					}
				}
			});
		});

		function mostrarNombreCliente(rut) {
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrarNombreCliente.php",
				data: { rut: rut },
				success: function (response) {
					setTimeout(() => {
						$('.nombre_cliente').html(response);
					}, 200);
				}
			});
		}

		$(document).on('change', '#seleccione_cola', function(){
			var idCola = $('#seleccione_cola').val();
			if(idCola != ""){
				var Cola = "QR_"+$("#IdCedente").val()+"_"+idCola;
				//actualizarCola(idCola,Cola);
				$.ajax({
					type: "POST",
					url: "../includes/crm/seleccione_asignacion.php",
					data:"Cola="+Cola,
					success: function(response)
					{
						$('#asignacion').html(response);
						$('.selectpicker').selectpicker('refresh')
						if((typeof asignacionAD !== "undefined") && (asignacionAD !== "")){
							$("#seleccione_asignacion").val(asignacionAD).trigger("change");
						}
					},
					error: function(response){
  						console.log(response);
            		}
				});
			}
		});

		$(document).on('change', '#seleccione_asignacion', function(){
			var prefijo = $('#seleccione_asignacion').val();
			if (prefijo != '0') {
				$('#Cargando').modal({
					backdrop: 'static',
					keyboard: false
				})
				setTimeout(function(){
					var estrategia = $('#seleccione_estrategia').val();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
						$.ajax({
							type: "POST",
							url: "../includes/crm/mostrar_rut.php",
							data: "prefijo=" + prefijo + "&estrategia=" + estrategia + "&orden=" + 0 + "&tipo=" + 1,
							dataType: 'json',
							success: function(response){
								console.log(response);
								if(response){
									$('#ocultar_rut').hide();
									$('#mostrar_rut').show();
									$('#mostrar_rut').html(response.uno);
									$('#mostrar_rut2').html(response.cinco);
									$('#next_rut').val(response.dos);
									$('#prev_rut').val(response.dos);
									mostrarNombreCliente(response.dos);
									$('#script_cobranza_mostrar').show();
									$('#mostrar_nombre_ocultar').hide();
									$('#rut_ultimo').val(response.dos);
									$('#prefijo').val(response.cuatro);
									progressBar(response.seis);
									$('#cantidadRut').html(response.siete);
									ordenRut = 1; // 1 es el orden 1 es decir mi primer rut de la asignacion
									funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
									if (response.estado_cola == 1) {
										$.niftyNoty({
											type: 'warning',
											icon: 'fa fa-check',
											message: "Caso con agendamiento cumplido",
											container: 'floating',
											timer: 4000
										});
									}
								}
								setTimeout(() => {
									$('#Cargando').modal('hide')
									$('body').removeClass('modal-open');
									$('.modal-backdrop').remove();
								}, 500);
							},
							error: function(response){
								console.log(response);
								setTimeout(() => {
									$('#Cargando').modal('hide')
									$('body').removeClass('modal-open');
									$('.modal-backdrop').remove();
								}, 500);
							}
						});
					
				},1000);
			}
		});

		function progressBar(porcentaje){
			var Porcentaje = Number(porcentaje);
			Color = "Red";
			if(Porcentaje <= 10){
				Color = "Red"; //Danger
			}
			if((Porcentaje > 10) && (Porcentaje <= 50)){
				Color = "Naranja"; //Warning
			}
			if((Porcentaje > 50) && (Porcentaje < 100)){
				Color = "Azul"; //Primary
			}
			if(Porcentaje == 100){
				Color = "Verde"; //Success
			}
			switch(Color){
				case 'Red':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-danger");
				break;
				case 'Naranja':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-warning");
				break;
				case 'Azul':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-primary");
				break;
				case 'Verde':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").addClass("progress-bar-success");
				break;
			}
			$("#ProgressBar .progress-bar").css('width',porcentaje+"%");
			$("#ProgressBar .progress-bar").html(porcentaje+"%");
			$("#ProgressBar").show();

		}



		$(document).on('click', '#next_rutSiguiente', function(){
			nextRut();
		});

		function nextRut(){	
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
			limpiarSesion();
			var prefijo = $('#prefijo').val();
			if (prefijo) {
				var data = "prefijo=" + prefijo + "&orden=" + ordenRut + "&tipo=" + 1;
				console.log(data);
				$.ajax({
					type: "POST",
					url: "../includes/crm/mostrar_rut.php",
					data:data,
					dataType: 'json',
					success: function(response){
						prev_rut = $('#prev_rut').val();
						if (response.estado_cola == 1) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Caso con agendamiento cumplido",
								container: 'floating',
								timer: 4000
							});
						} else if (response.seis >= 100) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Cola terminada",
								container: 'floating',
								timer: 4000
							});
						} else if (prev_rut == response.dos && response.estado == 0) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Debe gestionar este Rut antes de pasar al siguiente",
								container: 'floating',
								timer: 4000
							});
						}
						console.log(response);
						$('#mostrar_rut').html(response.uno);
						$('#mostrar_rut2').html(response.cinco);
						$('#next_rut').val(response.dos);
						$('#prev_rut').val(response.dos);
						$('#rut_ultimo').val(response.dos);
						mostrarNombreCliente(response.dos);
						$('#prefijo').val(response.cuatro);
						var cedente = $('#IdCedente').val();
						$('#cantidadRut').html(response.siete);
						ordenRut = response.ocho;
						progressBar(response.seis);
						funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);		
						$('#Cargando').modal('hide')		
					}
				});
			}
		}

		$(document).on('click', '#prev_rut', function(){
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
			limpiarSesion();
			var prefijo = $('#prefijo').val();
			if(prefijo){
				var data = "prefijo=" + prefijo + "&orden=" + ordenRut + "&tipo=" + 2;
				console.log(data);
				$.ajax({
					type: "POST",
					url: "../includes/crm/mostrar_rut.php",
					data:data,
					dataType: 'json',
					success: function(response){
						prev_rut = $('#prev_rut').val();
						if (response.estado_cola == 1) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Caso con agendamiento cumplido",
								container: 'floating',
								timer: 4000
							});
						} else if (response.seis >= 100) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Cola terminada",
								container: 'floating',
								timer: 4000
							});
						} else if (prev_rut == response.dos && response.estado == 0) {
							$.niftyNoty({
								type: 'warning',
								icon: 'fa fa-check',
								message: "Debe gestionar este Rut antes de pasar al siguiente",
								container: 'floating',
								timer: 4000
							});
						}
						$('#mostrar_rut').html(response.uno);
						$('#mostrar_rut2').html(response.cinco);
						$('#next_rut').val(response.dos);
						$('#prev_rut').val(response.dos);
						$('#rut_ultimo').val(response.dos);
						mostrarNombreCliente(response.dos);
						$('#prefijo').val(response.cuatro);
						$('#cantidadRut').html(response.siete);
						var cedente = $('#IdCedente').val();
						ordenRut = response.ocho;
						progressBar(response.seis);
						funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
						$('#Cargando').modal('hide')
					}
				});
			}
		});
	}
	function actualizarCola(idCola,Cola){
		$.ajax({
			type: "POST",
			url: "../task/actualizarColas.php",
			data:{
				Cola: idCola
			},
			async:false,
			beforeSend: function(){
				$('#Cargando').modal({
					backdrop: 'static',
					keyboard: false
				})
			},
			success: function(data){
				actualizarAsignacion(Cola);
			},
			error: function(response){
				console.log(response);
				alert(response);
			}
		});
	}
	function actualizarAsignacion(Cola){
		$.ajax({
			type: "POST",
			url: "../task/actualizarAsignaciones.php",
			data:{
				Cola: Cola
			},
			sync:false,
			success: function(data){
				$('#Cargando').modal('hide');
			},
			error: function(response){
				console.log(response);
				alert(response);
			}
		});
	}

	$(document).on('click', '.Llamar', function(){
		id = $(this).closest('tr').attr('id');
		var Tel = $("#telefono"+id).val();
		var CanCall = CanCallFono(Tel);
		//if (Tel.length == 9) {
		if (CanCall) {
			$("#hour").text('00');
			$("#minute").text('00');
			$("#second").text('00');
			tiempo.segundo = 0;
			tiempo.minuto = 0;
			tiempo.hora = 0;
			tiempo_corriendo = setInterval(function(){
				tiempo.segundo++;
				if(tiempo.segundo >= 60)
				{
					tiempo.segundo = 0;
					tiempo.minuto++;
				}

				// Minutos
				if(tiempo.minuto >= 60)
				{
					tiempo.minuto = 0;
					tiempo.hora++;
				}

				$("#hour").text(tiempo.hora < 10 ? '0' + tiempo.hora : tiempo.hora);
				$("#minute").text(tiempo.minuto < 10 ? '0' + tiempo.minuto : tiempo.minuto);
				$("#second").text(tiempo.segundo < 10 ? '0' + tiempo.segundo : tiempo.segundo);
			}, 1000);

			var Tel = $("#telefono"+id).val();
			var IdCedente = $("#IdCedente").val();
			var Anexo = $('#Anexo').val();
			var UsuarioAsterisk = $('#usuario').val();
			var data = "Tel="+Tel+"&Cedente="+IdCedente+"&Anexo="+Anexo+"&User="+UsuarioAsterisk+"&Provider="+DialProvider+"&CodigoFoco="+GlobalData.focoConfig.CodigoFoco+"&FonoPrefix="+GlobalData.FonoPrefix.Prefix;
			console.log(data);
			$("#fono"+id).removeClass("Llamar btn-success ");
			$("#fono"+id).addClass("Cortar btn-danger");

			var clase = '#chk'+$(this).closest('tr').attr('class');
			var telefono = '#'+'telefono'+id;
			var valor_telefono = $(telefono).val();
			$('#ultimo_fono').val(valor_telefono);
			$("#fg"+id).prop('checked',true);
			idFilaFono = id;
			$('#next_rut').prop("disabled",true);
			$('#prev_rut').prop("disabled",true);

			var i = 1;
			while(i<=20)
			{
				if(i==id){
					$('#fono'+i).prop("disabled",false);
					i++;
				}
				else{
					$('#fono'+i).prop("disabled",true);
					i++;
				}
			}
			if (DatosCedente.planDiscado == 1){
				if(DialProviderConfigured){
					$.ajax({
						type: "POST",
						url: "../discador/discador.php",
						data:data,
						success: function(response){
							console.log(response);
							if(isJson(response)){
								response = JSON.parse(response);
								console.log(response);
								if(response.uno){
									message = "Llamada Cortada"
									typeMessage = 'danger';
									var NombreGrabacion = response.dos;
									var UrlGrabacion = response.tres;
									var CanalGrabacion = response.uno;
									$('#NombreGrabacion').val(NombreGrabacion);
									$('#UrlGrabacion').val(UrlGrabacion);
									$("#CanalGrabacion").val(CanalGrabacion);
								}else{
									message = "No se encuentra registrado en el Softphone, consulte con el administrador"
									typeMessage = 'danger';
									$('.Cortar').click();
								}
							}
						}
					});
				}else{
					message = "Hubo un problema con el proveedor de discado, consulte con el administrador"
					typeMessage = 'danger';
					$('.Cortar').click();
				}
			}else{
				message = "Has seleccionado el télefono " + Tel
				typeMessage = 'success';
				$('.Cortar').click();
			}
		}else{
			$.niftyNoty({
				type: 'danger',
				icon: 'fa fa-close',
				message: "Teléfono no Cumple con el Formato ("+GlobalData.FonoPrefix.Length+" Digitos)",
				container: 'floating',
				timer: 4000
			});
		}
	});

	$(document).on('click', '.Cortar', function(){
		clearInterval(tiempo_corriendo);
		id = $(this).closest('tr').attr('id');
		$("#fono"+id).removeClass("Cortar btn-danger ");
		$("#fono"+id).addClass("Llamar btn-success");
		var Anexo = $('#Anexo').val();
		var data = "Anexo="+Anexo;
		$('#next_rut').prop("disabled",false);
		$('#prev_rut').prop("disabled",false);
		var i = 1;
		while(i<=20)
		{
			$('#fono'+i).prop("disabled",false);
			i++;
		}

		CortarLlamada();
	});

	function CortarLlamada(){
		if(DialProviderConfigured){
			var Anexo = $('#Anexo').val();
			var data = "Anexo="+Anexo;
			$.ajax({
				type: "POST",
				url: "../discador/cortar.php",
				data:data,
				success: function(response){
					console.log(response);
				}
			});
		}
		$.niftyNoty({
			type: typeMessage,
			icon: 'fa fa-phone',
			message: message,
			container: 'floating',
			timer: 2000
		});
	}

	//CORREOS

	$('#mostrarTemplates').click(function(){

		var Cont = 0;

		$("#mostrar_correo table tr").each(function(index){
			var ObjectMe = $(this);
			var Checkbox = ObjectMe.find(".inputCheckCorreo");
			if(Checkbox.hasClass("active")){
				Cont++;
			}
		});

		if(Cont > 0){
			$('#modalTemplate').modal('show')
		}else{
			bootbox.alert("Debe seleccionar al menos un correo eléctronico");
		}
	});	

	$("#modalTemplate").on("shown.bs.modal", function () { 
		if($('#idTemplate').val() != 's'){
			$('#SinTemplate').hide()
		}else{
			$('#SinTemplate').show();
		}
	});

	$('#idTemplate').change(function(){
		if($(this).val() != 's'){
			$('#SinTemplate').hide()
		}else{
			$('#SinTemplate').show();
		}
	});	

	$("#EnviarFacturaCorreos").click(function(){
		var Correos = [];
		var Facturas = [];
		var Cont = 0;
		var Rut = "";
		var TipoBusqueda = "";
		var idTemplate = $('#idTemplate').val();
		var Queue = $('#seleccione_asignacion').val();

		if(idTemplate){

			if($("#busqueda_estrategia").css("display") == "block"){
				TipoBusqueda = "estrategia";
			}else{
				if($("#busqueda_rut").css("display") == "block"){
					TipoBusqueda = "buscador";
				}
			}
			switch(TipoBusqueda){
				case "estrategia":
					Rut = $("#mostrar_rut input[type='text']").val();
				break;
				case "buscador":
					Rut = $("#rut_buscado").val();
				break;
			}
			$("#mostrar_correo table tr").each(function(index){
				var ObjectMe = $(this);
				var Checkbox = ObjectMe.find(".inputCheckCorreo");
				if(Checkbox.hasClass("active")){
					var Correo = ObjectMe.find(".NombreCorreo").val();
					Correos.push(Correo);
					Cont++;
				}
			});
			$("#mostrar_deudas table tr").each(function(index){
				var ObjectMe = $(this);
				if(ObjectMe.find(".inputCheckFactura").length){
					var Checkbox = ObjectMe.find(".inputCheckFactura");
					if(Checkbox.hasClass("active")){
						var Factura = Checkbox.closest("td").find("span").html();
						Facturas.push(Factura);
					}
				}
			});

			if(Cont > 0){
				//var rutEnvio = $('#rut_ultimo').val();

				data = new FormData();

				if(idTemplate == 's'){

					var Nombre = $('#nombre-template').val()
					var Asunto = $('#asunto-template').val()
					var Html = $('#summernote').summernote('code');
					
					if(!Nombre || !Asunto || !Html){
						bootbox.alert("Debe llenar todos los campos");
						return;
					}

					var DropzoneFiles = myDropzone.getAcceptedFiles();
					var CantArchivos = 0;

					$.each(DropzoneFiles, function( index, Archivo ) {
						Numero = index+1;	
						data.append('Archivo_'+Numero,Archivo);
						CantArchivos++;
					});
				}else{
					var Nombre = ''
					var Asunto = ''
					var Html = ''
					var CantArchivos = 0;
				}

				
				data.append('Correos', Correos);
				data.append('Facturas', Facturas);
				data.append('Rut', Rut);
				data.append('idTemplate', idTemplate);
				data.append('Nombre', Nombre);
				data.append('Asunto', Asunto);
				data.append('Html', Html);
				data.append('CantArchivos', CantArchivos);
				data.append('Queue', Queue);

				$.ajax({
					type: "POST",
					url: "../includes/crm/envioCorreoFacturas.php",
					data: data,
					// async: false,
				    contentType: false,
				    processData: false,
					success: function(response){
						response = response;
						console.log(response);
						if(response == "3"){
							bootbox.alert("Debe Configurar una cuenta de correo electronico.");
						}else{
							if(response == "2"){
								bootbox.alert("Debe seleccionar una template de factura");
							}else{
								if(response == "0"){
									bootbox.alert("Hubo un problema al enviar el correo");
								}else{
									if(response == "1"){
										var data1 = "rut="+Rut;
										funcionMostrarGestionCorreo(data1);
										$('#modalTemplate').modal('hide')
										bootbox.alert("Correo enviado satisfactoriamente");
										myDropzone.removeAllFiles();
									}
								}
							}
						}
					}
				});
			}else{
				bootbox.alert("Debe seleccionar al menos un correo eléctronico");
			}
		}else{
			bootbox.alert("Debe seleccionar una template de factura");
		}
	});
	$('#modalTemplate').on('hidden.bs.modal', function (e) {
		$(this)
			.find("input,textarea,select")
			.val('')
			.end()
			.find("input[type=checkbox], input[type=radio]")
			.prop("checked", "")
			.end();
		$("#summernote").summernote('code', '')
		$('.selectpicker').selectpicker('refresh')
	})

	//SMS

	$('#mostrarTemplatesSMS').click(function () {

		var Cont = 0;

		$("#mostrar_fonos table tr").each(function (index) {
			var ObjectMe = $(this);
			var Checkbox = ObjectMe.find(".inputCheckFono");
			if (Checkbox.hasClass("active")) {
				Cont++;
			}
		});

		if (Cont > 0) {
			$('#modalTemplateSMS').modal('show')
		} else {
			bootbox.alert("Debe seleccionar al menos un fono");
		}
	});

	$("#modalTemplateSMS").on("shown.bs.modal", function () {
		if ($('#idTemplateSMS').val() != 's') {
			$('#SinTemplateSMS').hide()
		} else {
			$('#SinTemplateSMS').show();
		}
	});

	$('#idTemplateSMS').change(function () {
		if ($(this).val() != 's') {
			$('#SinTemplateSMS').hide()
		} else {
			$('#SinTemplateSMS').show();
		}
	});

	$("#EnviarSMS").click(function () {
		var Telefonos = [];
		var Colores = [];
		var Rut = "";
		var TipoBusqueda = "";
		var idTemplate = $('#idTemplateSMS').val();
		var Queue = $('#seleccione_asignacion').val();
		var Cantidad = 0;

		if (idTemplate) {

			if ($("#busqueda_estrategia").css("display") == "block") {
				TipoBusqueda = "estrategia";
			} else {
				if ($("#busqueda_rut").css("display") == "block") {
					TipoBusqueda = "buscador";
				}
			}
			switch (TipoBusqueda) {
				case "estrategia":
					Rut = $("#mostrar_rut input[type='text']").val();
					break;
				case "buscador":
					Rut = $("#rut_buscado").val();
					break;
			}
			$("#mostrar_fonos table tr").each(function (index) {
				var ObjectMe = $(this);
				var Checkbox = ObjectMe.find(".inputCheckFono");
				if (Checkbox.hasClass("active")) {
					var Telefono = ObjectMe.find(".telefono").val();
					var Color = ObjectMe.find(".fa-flag").attr('color');
					Telefonos.push(Telefono);
					Colores.push(Color);
					Cantidad++;
				}
			});
			if (Cantidad > 0) {

				data = new FormData();

				if (idTemplate == 's') {

					var Contenido = $('#ContenidoSMS').val();

					if (!Contenido) {
						bootbox.alert("Debe llenar todos los campos");
						return;
					}
				} else {
					var Contenido = ''
				}

				data.append('sms', Contenido);
				data.append('cant', Cantidad);
				data.append('colores', Colores);
				data.append('queue', Queue);
				data.append('template', idTemplate);
				data.append('rut', Rut);
				data.append('telefonos', Telefonos);

				$.ajax({
					type: "POST",
					url: "../includes/email/enviar-sms.php",
					data: data,
					// async: false,
					contentType: false,
					processData: false,
					success: function (response) {
						response = response;
						console.log(response);
						if (response == "3") {
							bootbox.alert("Debe Configurar la API de SMS.");
						} else {
							if (response == "2") {
								bootbox.alert("Debe seleccionar una template de SMS");
							} else {
								if (response == "0") {
									bootbox.alert("Hubo un problema al enviar el SMS");
								} else {
									if (response == "1") {
										var data1 = "rut=" + Rut;
										funcionMostrarGestionSMS(data1);
										$('#modalTemplateSMS').modal('hide')
										bootbox.alert("SMS enviado satisfactoriamente");
									}
								}
							}
						}
					}
				});
			} else {
				bootbox.alert("Debe seleccionar al menos un fono");
			}
		} else {
			bootbox.alert("Debe seleccionar una template de SMS");
		}
	});
	$('#modalTemplateSMS').on('hidden.bs.modal', function (e) {
		$(this)
			.find("input,textarea,select")
			.val('')
			.end()
			.find("input[type=checkbox], input[type=radio]")
			.prop("checked", "")
			.end();
		$('.selectpicker').selectpicker('refresh')
	})
	$("body").on("click",".DownloadFactura",function(){
		var ObjectMe = $(this);
		var File = ObjectMe.attr("href");
		var Factura = ObjectMe.attr("number");
		if(!ObjectMe.hasClass("Disabled")){
			var $a = $("<a>");
			$a.addClass("list-group-item");
			$a.attr("href","../"+File);
			$a.attr("download",Factura+".pdf");
			$a[0].click();
			$a.remove();
		}else{
			bootbox.alert("Factura no existe");
		}
	});
	$("body").on("click",".DeleteGestion",function(){
		var ObjectMe = $(this);
		var idGestion = ObjectMe.attr("id");
		var TipoBusqueda = $("#seleccione_tipo_busqueda").val();
		var Rut = $('#rut_ultimo').val();
		bootbox.confirm({
			message: "¿Esta seguro de eliminar este registro?",
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
					$.ajax({
						type: "POST",
						url: "../includes/crm/deleteGestion.php",
						data:{
							idGestion: idGestion
						},
						success: function(data){
							console.log(data);
							if(isJson(data)){
								var json = JSON.parse(data);
								console.log(json);
								if(json.result){
									var data1 = "rut="+Rut;
									funcionMostrarGestion(data1);
									funcionMostrarGestionTotal(data1);
									funcionMostrarGestionCorreo(data1);
									funcionMostrarGestionSMS(data1);
									funcionMostrarGestionIVR(data1);
									funcionMostrarGestionesExternas(dat1);
									funcionMostrarGestionDiaria(data1);
								}
								bootbox.alert(json.message);
							}                
						}
					});
				}
			}
		});
	});
	function GetServerStatus(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetServerStatus.php",
            data:{
                codigoFoco: GlobalData.focoConfig.CodigoFoco,
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    console.log(json);
                    if(json.result){
						DialProvider = json.Proveedor;
						if(DialProvider != ""){
							DialProviderConfigured = true;
						}
                    }
                }                
            }
        });
	}
	
	function accesoDirectoCola(){
		var post = "asignacion="+asignacionAD+"&cedente="+cedente;
        $.ajax({
			type: "POST",
			url: "../includes/crm/reconstruir.php",
			dataType: "html",
			data: post,
			async: false,
			success: function (data) {
				$(window).on('load', function() {
					$("#seleccione_tipo_busqueda").val("1").trigger("change");
				});
			}
		});
	}

	var previous;

	$(document).on('change', 'input:checkbox', function () {
		id = $(this).val();
		var prioridad = "#prioridad_" + id;
		if($(this).is(':checked')){
			$(prioridad).removeAttr("disabled");
		}
		else{
			$(prioridad).trigger("focus");
			$(prioridad).val("").trigger("change");
			$(prioridad).attr("disabled", "disabled");
		}
	});

	$(document).on('focus', '.prioridad', function () {
		previous = this.value;
		console.log(previous);
	});
	
	$(document).on('change', '.prioridad', function () {
		var value = $(this).val();
		id = $(this).attr("id");
		console.log("VALUE: " + $("select[name='"+id+"']").val());
		if (previous != "" && previous != null){
			$(".prioridad option[value*='" + previous + "']").prop('disabled',false);
		}
		$(".prioridad option[value*='" + value + "']").prop('disabled',true);
	});

	function omnicanalidad(){
		if (DatosCedente.omnicanalidad == 1) {

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
		}else{
			canales = '';
			prioridades = '';
		}
	}

	$(document).on("click", ".verFactura", function(){
		var factura = $(this).closest('td').attr('id');
		var rut = $("#rut_ultimo").val();
		var label = "GESTIONES DE LA FACTURA " + factura;

		createDialogDeuda(label);
		fillDialogDeuda(factura, rut);
	});

	function createDialogDeuda(label){
        var ModalDeuda = $("#modalDeuda").html();
        bootbox.dialog({
            title: label,
            message: ModalDeuda,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
			},
			className: "modalFactura"
        });
	}

	function fillDialogDeuda(factura, rut){

		var post = "rut="+rut+"&factura="+factura;
        $.ajax({
            type: "POST",
            url: "../includes/crm/getGestionesFactura.php",
            data: post,
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#gestionesFactura' ) ) {
                    GestionesFacturaTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    GestionFacturasTable = $('#gestionesFactura').DataTable({
							data: dataSet,
							columns: [
								{ data: 'fecha_gestion' },
								{ data: 'ejecutivo' },
								{ data: 'fono_discado' },
								{ data: 'n1'},
								{ data: 'n2' },
								{ data: 'n3' },
								{ data: 'compromiso' },
								{ data: 'monto' },
								{ data: 'factura' },
								{ data: 'observacion' },
							],
							language: {
								emptyTable: 'FACTURA no tiene gestiones asociadas.'
								},
							destroy: true
						});
                }
            }
        });
	}
	function getScoring(Rut) {
		$('#gauge').jqxGauge({
			ranges: [
				{ startValue: 0, endValue: 30, style: { fill: '#e53d37', stroke: '#e53d37' }, startDistance: 0, endDistance: 0 },
				{ startValue: 30, endValue: 60, style: { fill: '#fad00b', stroke: '#fad00b' }, startDistance: 0, endDistance: 0 },
				{ startValue: 60, endValue: 100, style: { fill: '#4cb848', stroke: '#4cb848' }, startDistance: 0, endDistance: 0 }
			],
			cap: { size: '5%', style: { fill: '#2e79bb', stroke: '#2e79bb' } },
			border: { style: { fill: '#8e9495', stroke: '#7b8384', 'stroke-width': 1 } },
			ticksMinor: { interval: 5, size: '5%' },
			ticksMajor: { interval: 10, size: '10%' },
			labels: { position: 'outside', interval: 10 },
			pointer: { style: { fill: '#2e79bb' }, width: 5 },
			animationDuration: 1500,
			value: 0,
			max: 100,
			width: 175, 
			height: 175
		});
		if(Rut){
			$.ajax({
				type: "POST",
				url: "../includes/admin/conf_scoring/getScoring.php",
				dataType: "html",
				async: false,
				data: {
					Rut: Rut
				},
				success: function (data) {
					$('#gauge').css('cursor', 'pointer');
					$('#gauge').attr('rut',Rut);
				}
			});
		}else{
			data = 0
		}

		$('#gauge').jqxGauge('value', data);
	}
	$(document).on('click', '#gauge', function () {
		Rut = $(this).attr('rut');
		if(Rut){
			$.ajax({
				type: "POST",
				url: "../includes/admin/conf_scoring/getDetalleScoring.php",
				dataType: "json",
				async: false,
				data: {
					Rut: Rut
				},
				success: function (data) {
					ScoringTable = $('#ScoringTable').DataTable({
						data: data,
						columns: [
							{ data: 'variable' },
							{ data: 'resultado_scoring' },
							{ data: 'resultado_porcentaje' }
						],
						language: {
							emptyTable: 'RUT no posee scoring para la fecha de hoy.'
						},
						destroy: true
					});
					$('#modalScoring').modal('show')
				}
			});
		}
	});
	function getTabs() {
		$.ajax({
			type: "POST",
			url: "../includes/crm/getTabs.php",
			async: false,
			dataType: 'json',
			success: function (data) {
				$('#nav-tabs').html(data.Header)
				$('#tab-content').html(data.Content)
			}
		});
	}
	function getTabsContent(data) {
		$.ajax({
			type: "POST",
			url: "../includes/crm/getTabsContent.php",
			data: data,
			success: function (response) {
				if(isJson(response)){
					response = JSON.parse(response)
					$.each(response, function (index, array) {
						Table = array.Tab
						$('#'+Table+'_dt').DataTable({
							data: array.dataSet,
							columns: array.Fields,
							destroy: true
						});
						$('#'+Table+'_ocultar').hide();
					});
				}
			}
		});
	}
	function CanCallFono(Fono){
		var ToReturn = false;
		var Length = Fono.length;
		switch(GlobalData.FonoPrefix.LengthOperation){
			case "1":
				if(Length == GlobalData.FonoPrefix.Length){
					ToReturn = true;
				}
			break;
			case "2":
				if(Length <= GlobalData.FonoPrefix.Length){
					ToReturn = true;
				}
			break;
			case "3":
				if(Length >= GlobalData.FonoPrefix.Length){
					ToReturn = true;
				}
			break;
		}
		return ToReturn;
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
    $(document).on('change', '#destino', function () {
        var origen = $('#destino').val();
        var data = "origen="+origen;
        $.ajax({
            type: "POST",
            url: "../includes/crm/origen.php",
            data:data,
            async: false,
            success: function(response)
            {
                if(origen == 'Clientes'){
                    $('#clienteIn2').prop("disabled",true);
                }
                else{
                    $('#clienteIn2').prop("disabled",false);

                }
                $('.origen_mostrar').html(response);
                $('.origen_ocultar').hide();
                $('.selectpicker').selectpicker('refresh')
            }
        });
            
   });
   $(document).on('change', '#origenEntrante', function(){
        var origen = $('#origenEntrante').val();
        var data = "origen="+origen;
        $.ajax({
            type: "POST",
            url: "../includes/crm/origenIn.php",
            data:data,
            async: false,
            success: function(response){
                $('.destino_mostrar').html(response);
                $('.destino_ocultar').hide();
                $('.selectpicker').selectpicker('refresh')
            }
        });

    });

    $('.guardarGestion').click(function(){

        var ori = $('#seleccione_tipo_busqueda').val();
        var origen = $('#seleccione_tipo_busqueda').val();
        var rut = 0;
        var rut2 = $('#rut_buscado').val();
        var rut3 = $('#rut_buscado3').val();
        var rut4 = $('#rut_buscado4').val();
        var clienteIn = $("#clienteIn2").val();
        var tipificacionIn = $("#tipificacionIn2").val();
        var observacionIn = $("#observacionIn2").val();

        if(rut2 != 0){
            rut = rut2;
        }else if(rut3 != 0){
            rut = rut3;
        }else if(rut4 != 0){
            rut = rut4;
        }

        if(origen == 2){
            origen = 'Tripulacion';
        }else if(origen == 3){
            origen = 'Supervisor';
        }else if(origen == 4){
            origen = 'Cliente';
        }   
        var fono = $('#ultimo_fono').val();   
        if(ori == 0){
            bootbox.alert("Seleccione un tipo de busqueda");
        }else{
            if(rut != 0){

                if(fono == 0){ 
                    bootbox.alert("Para guardar gestiones debe seleccionar un teléfono");
        
                }else{
                    if(observacionIn == ''){
                        bootbox.alert("Debe escribir una observacion");
                    }else{
                        var data = "origen="+origen+"&nombreIn=Demo01"+"&clienteIn="+clienteIn+"&observacionIn="+observacionIn+"&tipificacionIn="+tipificacionIn+"&fono="+fono+"&ori="+ori+"&rut="+rut;
                        $.ajax({
                            type: "POST",
                            url: "../includes/crm/insertOperaciones.php",
                            data:data,
                            success: function(){
                                window.location = "../crm/operaciones";
                            }
                        });
        
                    }

                   
                }
            }else{
                bootbox.alert("debe seleccionar una opcion de busqueda");
            }
        }    
    });
});

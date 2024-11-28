$(document).ready(function(){
	Dropzone.autoDiscover = false;
	estados = [];
	var Filename = "";
	var myDropzone;
	var CamposSelect;
	var MarcaData = "";
	var FilesTXT = "";
	var FechaInicioPeriodo = "";
	var FechaFinPeriodo = "";
	var CargaAdicional = "";

	var uploadAsignacionAutomaticaURL = "uploadAsignacionAutomaticaTXT";
	var TemplateAndSheets

	$('select').selectpicker();

	$("#file-up").dropzone({
		url: "../carga/ajax/"+uploadAsignacionAutomaticaURL+".php",
		acceptedFiles: ".xlsx,.xls,.txt,.csv",
		maxFiles:1000,
		uploadMultiple: true,
		parallelUploads: 1000,
		maxFilesize: 2048,
		autoProcessQueue: false,
		init: function() {
			myDropzone = this;
			myDropzone.on("addedfile", function (fileadded) {			
				if (fileadded.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || fileadded.type == 'application/vnd.ms-excel'){
					files = myDropzone.files
					if(files.length > 1){
						bootbox.alert("La Plantilla de carga tipo 'XLSX y XLS' solo permite 1 archivo por carga")
						myDropzone.removeAllFiles();
					}
				}else{
					$.each(myDropzone.files, function (index, file) {
						if(file.type != fileadded.type){
							bootbox.alert("La Plantilla de carga solo permite archivos del mismo tipo");
							myDropzone.removeAllFiles();
						}
					})
				}
			});
		},

		sending: function(file, xhr, formData) {
			formData.append("MarcaData", MarcaData);
			formData.append("FilesTXT", FilesTXT);
			formData.append("TipoCarga", $("select[name='TipoCarga']").val());
			formData.append("FechaInicioPeriodo", FechaInicioPeriodo);
			formData.append("FechaFinPeriodo", FechaFinPeriodo);
			formData.append("id_template", $('#id_template').val());
			formData.append("id_origen", $('#id_origen').val());
			formData.append("CargaAdicional", CargaAdicional);
			MarcaData = "";
			FilesTXT = "";
			FechaInicioPeriodo = "";
			FechaFinPeriodo = "";
			CargaAdicional = "";
		},

		error: function(file,response){
			console.log(response);
			if(response == "You can't upload files of this type."){
				$('#alertFile').modal({
					backdrop: 'static',
					keyboard: false
				});
			}
			myDropzone.removeAllFiles();
		},

		success: function (file, response) {
			$('#load').modal('hide');
			console.log(response);
			if(isJson(response)){
				var json = JSON.parse(response);
				if(json.result){
					console.log(json);
					Push.create('Carga Automatica',{
						icon: "../img/I-Manager.png",
						body: "El archivo: "+json.filename+" esta siendo procesado\nEstado: "+json.comment+"\nCarga realizada por: "+json.usuario
					});
				}else{
					bootbox.alert(json.Message);
				}
			}
			myDropzone.removeAllFiles();
		},
		processing: function () {
			$('#load').modal({
				backdrop: 'static',
				keyboard: false
			})
		}
	});
	$("body").on("click","#CargarArchivos",function(){
		count = myDropzone.getAcceptedFiles().length
		if(count > 0){
			showCargaModal();
		}else{
			bootbox.alert('Debe seleccionar un archivo')
		}
	});
	$("body").on("change", "select[name='id_template']", function () {
		TemplateAndSheets = TemplateData();
		getTipoCargaConfig();
	});
	$("body").on("change","select[name='TipoCarga']",function(){
		getTipoCargaConfig();
		getOrigenes();
	});
	function getTipoCargaConfig(){
		var Value = $("select[name='TipoCarga']").val();
		var CantSheets = 0;
		TemplateAndSheets.Sheets.forEach(function (Sheet) {
			if (Sheet.TipoCarga == Value) {
				CantSheets++;
			}
		});
		switch (TemplateAndSheets.Template.TipoArchivo) {
			case "xlsx":
			case "xls":
				$("#ContainerFilesTXT").hide();
				break;
			case "csv":
			case "txt":
				var Archivos = "";
				var Sheets = "";
				TemplateAndSheets.Sheets.forEach(function (Sheet) {
					if (Sheet.TipoCarga == Value) {
						Sheets += "<option value='" + Sheet.Accion + "'>" + Sheet.Nombre + "</option>";
					}
				});
				for (var i = 1; i <= CantSheets; i++) {
					name = myDropzone.files[i - 1].name
					name = name.replace('.txt', '');
					name = name.replace('.csv', '');
					name = name.replace('.xls', '');
					name = name.replace('.xlsx', '');
					Archivos += "<h3>Archivo " + i + "</h3>";
					Archivos += '<div class="row FilaFiles">' +
									'<div class="col-sm-6">' +
										'<div class="form-group">' +
											'<label class="control-label">Nombre del Archivo:</label>' +
											'<input class="form-control Filename" id="Filename' + i + '" value="' + name + '" disabled>' +
										'</div>' +
									'</div>' +
									'<div class="col-sm-6">' +
										'<div class="form-group">' +
											'<label class="control-label">Configuración:</label>' +
											'<select class="selectpicker form-control Configuracion" id="Configuracion' + i + '" title="Seleccione" data-live-search="true" data-width="100%">' + Sheets + '</select>' +
										'</div>' +
									'</div>' +
								'</div>';
				}
				$("#ContainerFilesTXT").html(Archivos);
				$("#ContainerFilesTXT").show();
				$(".selectpicker").selectpicker("refresh");
				break;
		}
		switch (Value) {
			case "carga":
			case "pagos":
			case "cargagestiones":
				switch (Value) {
					case "carga":
						$("#ContainerCarga").show();
						$("#ContainerOrigen").hide();
						break;
					case "pagos":
						$("#ContainerCarga").hide();
						$("#ContainerOrigen").hide();
						break;
					case "cargagestiones":
						$("#ContainerCarga").hide();
						$("#ContainerOrigen").show();
						break;
				}
				$("#ContainerMarca").hide();
				break;
			case "marca":
				$("#ContainerMarca").show();
				$("#ContainerCarga").hide();
				break;
		}
	}
	$("body").on("click",".addCampoMarca",function(){
		var Tabla = $("select[name='Tabla']").val();
		if(Tabla != ""){
			var Template = $("#TemplateCampoMarca").html();
			Template = Template.replace("{CAMPOS}",CamposSelect);
			$("#ContainerMarca").append(Template);
			$(".selectpicker").selectpicker("refresh");
		}else{
			bootbox.alert("Debe seleccionar una tabla primero.",function(){AddClassModalOpen();});
		}
	});
	$("body").on("click",".deleteCampoMarca",function(){
		var ObjectMe = $(this);
		var ObjectContainer = ObjectMe.closest(".CampoMarca");
		ObjectContainer.remove();
	});
	$("body").on("change","select[name='Tabla']",function(){
		var Value = $(this).val();
		$.ajax({
            type: "POST",
            url: "ajax/selectCamposTablas.php",
            dataType: "html",
            async: false,
            data: {
                tabla: Value
            },
            success: function(data){
				CamposSelect = data;
				$("select[name='CampoRelacion']").html(CamposSelect);
				$(".CampoMarca select[name='CampoMarca']").each(function(){
					var ObjectMe = $(this);
					ObjectMe.html(CamposSelect);
				});
				$(".selectpicker").selectpicker("refresh");
            },
            error: function(response){
            }
        });
	});
	$("body").on("change","input[name='checkboxFechaInicioPeriodo']",function(){
		var ObjectMe = $(this);
		if((ObjectMe).is(":checked")){
			$("input[name='FechaInicioPeriodo']").prop("disabled",false);

			$("input[name='FechaFinPeriodo']").prop("disabled",false);
		}else{
			$("input[name='FechaInicioPeriodo']").prop("disabled",true);
			$("input[name='FechaInicioPeriodo']").val("");

			$("input[name='FechaFinPeriodo']").prop("disabled",true);
			$("input[name='FechaFinPeriodo']").val("");
		}
	});
	function showCargaModal(){
		var Template = $("#TemplateCarga").html();
		bootbox.dialog({
			title: "CARGA AUTOMATICA",
			message: Template,
			closeButton: false,
			buttons: {
				confirm: {
					label: "Guardar",
					className: "btn-purple",
					callback: function() {
						var CanProcess = true;
						FilesTXT = "-";
						var TipoCarga = $("select[name='TipoCarga']").val();
						switch(TipoCarga){
							case "carga":
							case "pagos":
							case "cargagestiones":
								switch(TipoCarga){
									case "carga":
										if($("input[name='checkboxFechaInicioPeriodo']").is(":checked")){
											var FechaInicio = $("input[name='FechaInicioPeriodo']").val();
											var FechaFin = $("input[name='FechaFinPeriodo']").val();
											if((FechaInicio == "") || (FechaFin == "")){
												CanProcess = false;
											}
											if(FechaInicio == ""){
												bootbox.alert("Debe ingresar una fecha de inicio de periodo valida");
											}else{
												FechaInicioPeriodo = "1_"+FechaInicio;
											}
											if(FechaFin == ""){
												bootbox.alert("Debe ingresar una fecha de Fin de periodo valida");
											}else{
												FechaFinPeriodo = "1_"+FechaFin;
											}
										}else{
											FechaInicioPeriodo = "0_";
											FechaFinPeriodo = "0_";
										}
										if($("input[name='checkboxCargaAdicional']").is(":checked")){
											CargaAdicional = "0";
										}else{
											CargaAdicional = "1";
										}
									break;
									case "pagos":
										FechaInicioPeriodo = "0_";
										FechaFinPeriodo = "0_";
										CargaAdicional = "1";
									break;
									case "cargagestiones":
										FechaInicioPeriodo = "0_";
										FechaFinPeriodo = "0_";
										CargaAdicional = "1";
									break;
								}
								MarcaData = "-";
								switch(TemplateAndSheets.Template.TipoArchivo){
									case "txt":
									case "csv":
										FilesTXT = "";
										
										$("#ContainerFilesTXT .FilaFiles").each(function(){
											var ObjectMe = $(this);
											var Filename = ObjectMe.find(".Filename").val();
											var Configuracion = ObjectMe.find("select.Configuracion").val();
											if((Filename == "") || (Configuracion == "")){
												bootbox.alert("Debe llenar todos los campos");
												CanProcess = false;
											}
											if(FilesTXT != ""){
												FilesTXT += "&"+Filename+"|"+Configuracion;
											}else{
												FilesTXT = Filename+"|"+Configuracion;
											}
										});
										uploadAsignacionAutomaticaURL = "uploadAsignacionAutomaticaTXT";
									break;
								}
								if(CanProcess){
									myDropzone.processQueue();
								}else{
									return false;
								}
							break;
							case "marca":
								FechaInicioPeriodo = "0_";
								FechaFinPeriodo = "0_";
								CargaAdicional = "1";
								var Tabla = $("select[name='Tabla']").val();
								var CampoRelacion = $("select[name='CampoRelacion']").val();
								var CanProcess = false;
								if(Tabla != ""){
									if(CampoRelacion != ""){
										var Cont = 0;
										$(".CampoMarca select[name='CampoMarca']").each(function(){
											var Value = $(this).val();
											if(Value == ""){
												Cont++;
											}
										});
										if(Cont == 0){
											CanProcess = true;
										}else{
											bootbox.alert("Debe Seleccionar almenos un campo de base de datos por cada Campo de marca",function(){AddClassModalOpen();});
										}
									}else{
										bootbox.alert("Debe Seleccionar un campo relación",function(){AddClassModalOpen();});
									}
								}else{
									bootbox.alert("Debe Seleccionar una tabla",function(){AddClassModalOpen();});
								}
								if(CanProcess){
									MarcaData += Tabla+"|";
									MarcaData += CampoRelacion+"|";
									$(".CampoMarca select[name='CampoMarca']").each(function(){
										var Value = $(this).val();
										MarcaData += Value+",";
									});
									MarcaData = MarcaData.substring(0,MarcaData.length - 1);
									myDropzone.processQueue();
								}else{
									return false;
								}
							break;
							default:
								bootbox.alert("Debe seleccionar un tipo de carga")
								return false;
						}
					}
				},
				cancel: {
					label: "Cancelar",
					className: "btn-danger",
					callback: function() {
						myDropzone.removeAllFiles();
					}
				}
			}
		}).off("shown.bs.modal");
		getTemplatesSelect();
		$.fn.datepicker.dates['es'] = {
			days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
			daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			today: "Hoy"
	  };
	  $('#demo-dp-component .input-group.date').datepicker({autoclose:true,format: "dd-mm-yyyy", weekStart: 1, language: 'es'});


		var TablasCarga = "";
        if(GlobalData.focoConfig.tipoMenu.indexOf("foco") != -1){
            TablasCarga += "<option value='carga'>Carga</option>";
			TablasCarga += "<option value='pagos'>Pagos</option>";
			TablasCarga += "<option value='marca'>Marca</option>";
        }
        if(GlobalData.focoConfig.tipoMenu.indexOf("cal") != -1){
			TablasCarga += "<option value='cargagestiones'>Gestiones</option>";
        }
        $("select[name='TipoCarga']").html(TablasCarga);
		$(".selectpicker").selectpicker("refresh");
	}
	function getTemplatesSelect() {
		$.ajax({
			type: "POST",
			url: "ajax/getTemplatesSelect.php",
			dataType: "html",
			async: false,
			success: function (data) {
				$("select[name='id_template']").html(data);
				$("select[name='id_template']").selectpicker("refresh");
				setTimeout(() => {
					TemplateAndSheets = TemplateData();
				}, 200);
			},
		});
	}
	function TemplateData(){
		var ToReturn = [];
		$.ajax({
            type: "POST",
            url: "ajax/getTemplateData.php",
            dataType: "json",
            async: false,
            data: {
				id_template: $('#id_template').val()
			},
            success: function(data){
				ToReturn = data;	
            }
        });
		return ToReturn;
	}
	function getOrigenes() {
		$.ajax({
			type: "POST",
			url: "ajax/getOrigenes.php",
			dataType: "html",
			async: false,
			success: function (data) {
				$("select[name='id_origen']").html(data);
				$("select[name='id_origen']").selectpicker("refresh");
			},
		});
	}
	function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
    }
});
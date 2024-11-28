$(document).ready(function($){

	$('body').on('focus', ".date", function () {
		$('.date').datepicker({
			format: "dd-mm-yyyy",
			weekStart: 1,
			todayBtn: "linked",
			autoclose: true,
			todayHighlight: true,
			language: 'es',
			defaultDate: new Date()
		});
	});

	$('.InputPorcentaje').mask('000');

	var idCola;
	var EstrategiasTable;
	var ColasTable;

	$('.seleccione_tipo').on('click',function() {
    	var mivar = $(this).closest('tr').attr('id');
    	var id_cedente = $('#id_cedente').val();
		
        var data = 'id='+mivar+"&id_cedente="+id_cedente;
        console.log(data);
        var recorrer = $('.seleccione_tipo').length;
		if ($('.seleccione_tipo').is(':checked')) {
        	$.niftyNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : "Tipo Estrategia Seleccionado" ,
				container : 'floating',
				timer : 2000
			});
            for (var i=1; i<=recorrer; i++) {
            	if (mivar==i){
					$('#uno'+i).attr("disabled" , false);
				}else{
					$('#uno'+i).attr("disabled" , true);
                }
			}
            $.ajax({
				type: "POST",
				url: "../includes/tareas/seleccione_tipo.php",
				data:data, 
				success: function(response){ 
					console.log(response);
					if(isJson(response)){
						var dataSet = JSON.parse(response);

						$('#mostrar_estrategia').show();

						EstrategiasTable = $('#mostrar_estrategia_dt').DataTable({
							data: dataSet,
							destroy: true,
							order: [[2, 'asc'], [3, 'asc']],
							columns: [
								// { data: 'id' },
								{ data: 'nombre' },
								{ data: 'comentario' },
								{ data: 'fecha'},
								{ data: 'hora' },
								{ data: '' },
							],
							columnDefs: [ 
								{
									className: "dt-center",
									"targets": 0,
									"render": function (data, type, row) {
										return "<div style='text-align: center;'>"+data+"</div>";
									}
								},
								{
									className: "dt-center",
									"targets": 1,
									"render": function (data, type, row) {
										return "<div style='text-align: center;'>" + data + "</div>";
									}
								},
								{
									className: "dt-center",
									"targets": 2,
									"render": function (data, type, row) {
										return "<div style='text-align: center;'>" + data + "</div>";
									}
								},
								{
									className: "dt-center",
									"targets": 3,
									"render": function (data, type, row) {
										return "<div style='text-align: center;'>" + data + "</div>";
									}
								},
								{
									className: "dt-center",
									"targets": 4,
									"render": function( data, type, row ) {
										return "<div style='text-align: center;'><input type='checkbox' class='toggle-switch seleccione_estrategia' id='"+row.id+"'><label class='toggle-switch-label'></label ></div ></div><div style='text-align: center;'>";
									}
								}
							],
							language: {
								emptyTable: 'No existen estrategias creadas en el Tipo seleccionado.'
							}
						});
						
						$('html,body').animate({ scrollTop: $("#cambiar").offset().top }, 1000);
					
						$('body').on('click','.seleccione_estrategia',function() {
							console.log("AÑADIEDO CLASS");

							var $inputs = $('.seleccione_estrategia input:checkbox'); 
							var mivar2 = $(this).attr('id');
							var data = 'id='+mivar2;
							if($('.seleccione_estrategia').is(':checked')){
								$('.seleccione_estrategia').not(this).attr("disabled", true);

								$.ajax({
									type: "POST",
									url: "../includes/tareas/seleccione_estrategia.php",
									data:data, 
									success: function(response){
										$('body').removeClass("loading");  
										$.niftyNoty({
											type: 'success',
											icon : 'fa fa-check',
											message : "Estrategia Seleccionada" ,
											container : 'floating',
											timer : 2000
										});
										console.log(response);
										if(isJson(response)){
											var dataSet = JSON.parse(response);
											$('#mostrar_cola').show();

											ColasTable = $('#mostrar_cola_dt').DataTable({
												data: dataSet,
												destroy: true,
												columns: [
													// { data: 'id' },
													{ data: 'cola' },
													{ data: 'cantidad' },
													{ data: 'monto'},
													{ data: 'prioridad' },
													{ data: 'comentario' },
													{ data: '' },
												],
												columnDefs: [ 
													{
														className: "dt-center",
														"targets": 0,
														"render": function (data, type, row) {
															return "<div style='text-align: center;'>" + data + "</div>";
														}
													},
													{
														className: "dt-center",
														"targets": 1,
														"render": function (data, type, row) {
															return "<div style='text-align: center;'>" + data + "</div>";
														}
													},
													{
														className: "dt-center",
														"targets": 2,
														"render": function (data, type, row) {
															return "<div style='text-align: center;'>" + data + "</div>";
														}
													},
													{
														className: "dt-center",
														"targets": 3,
														"render": function (data, type, row) {
															return "<div style='text-align: center;'>" + data + "</div>";
														}
													},
													{
														className: "dt-center",
														"targets": 4,
														"render": function (data, type, row) {
															return "<div style='text-align: center;'>" + data + "</div>";
														}
													},
													{
														className: "dt-center",
														"targets": 5,
														"render": function( data, type, row ) {
															return "<div style='text-align: center;'><i class='fa fa-download btn btn-purple btn-icon icon-lg Asignar' id='"+row.id+"'></i></div>";
														}
													}
												],
												language: {
													emptyTable: 'No existen colas terminales en la estrategia seleccionada.'
												}
											});
											//$('#cambiar3').html(response);
											$('html,body').animate({scrollTop: $("#cambiar2").offset().top}, 1000);
											$('.activar_cola').on('click',function(){
												var ObjectTR = $(this).closest('tr');
												var Asignador = ObjectTR.find("td.AsignadorBtn");
												var id_var = $(this).closest('tr').attr('id');
												var id_var2 = '#k'+id_var;
												var mivar = $(this).closest('tr').attr('id');
												var data = "id="+mivar;
												console.log(id_var2);
												var prueba = $(id_var2).val();
												console.log(prueba);
												if (prueba==1) {
													prueba = $(id_var2).val("0");
													$('body').addClass("loading");
													$.ajax({
														type: "POST",
														url: "../../includes/tareas/desactivar_cola.php",
														data:data, 
														success: function(response){ 
															$('body').removeClass("loading"); 
															console.log(response);
															if(response==1){
																Asignador.find("i").addClass("Disabled");
																$.niftyNoty({
																	type: 'danger',
																	icon : 'fa fa-check',
																	message : "Cola Desactivada" ,
																	container : 'floating',
																	timer : 4000
																});
															}
														}	
													});	
												}else{
													prueba = $(id_var2).val("1");
													console.log(response);

													$('body').addClass("loading"); 
													var mivar = $(this).closest('tr').attr('id');
													var data = "id="+mivar;
													$.ajax({
														type: "POST",
														url: "../../includes/tareas/activar_cola.php",
														data:data, 
														success: function(response){
															$('body').removeClass("loading"); 
															console.log(response);
															if(response==1){
																$.niftyNoty({
																	type: 'danger',
																	icon : 'fa fa-check',
																	message : "La Cola ya existe" ,
																	container : 'floating',
																	timer : 2000
																});
															}else{
																Asignador.find("i").removeClass("Disabled");
																$.niftyNoty({
																	type: 'success',
																	icon : 'fa fa-check',
																	message : "Cola Activada , Ya se puede visualizar en el Discador!" ,
																	container : 'floating',
																	timer : 4000
																});
															}
														}
													});
												}
											});
										}
									}
								});
							}else{
								$('.seleccione_estrategia').attr("disabled", false);
							}
						});
					}
				}
			});
		}else{ 
			for (var i=1; i<=recorrer; i++){                       		
				$('#uno'+i).attr("disabled" , false);
			}
			$('#mostrar_estrategia').fadeOut( "slow", function() {

			});
			$('#mostrar_cola').fadeOut( "slow", function() {

			});
			$('html,body').animate({ scrollTop: $("#cambiar").offset().top}, 1000);
		}
	});
		
	var IDCola;
	var TablaDeAsignados;
	var ArrayEE = [];
	var ArrayPersonal = [];
	var ArrayGrupo = [];
	$("body").on("click",".Asignar", function(){
		var ObjectMe = $(this);
		var ObjectTd = ObjectMe.closest("td");
		var ObjectIcon = ObjectTd.find("i");
		//var ObjectTr = ObjectMe.closest("tr");
		//IDCola = ObjectTr.attr("id");
		IDCola = ObjectMe.attr("id");
		idCola = IDCola;
		if(!ObjectIcon.hasClass("Disabled")){
			var dataSet = [];
			$.ajax({
				type: "POST",
				url: "../includes/tareas/getAsignaciones.php",
				data:{
					idCola: IDCola
				},
				beforeSend: function() {
					$('body').addClass("loading");
				},
				success: function(response){ 
					console.log(response);
					$('body').removeClass("loading"); 
					var json = JSON.parse(response);
					console.log(json);
					dataSet = json.Asiganciones;
					$("#Downloads #Tipo2").html("<a class='list-group-item' style='position: relative;'>Descarga Archivo de Asignacion <i class='fa fa-download' style='position: absolute; right: 15px;'></i></a>");
					
					$.each(json.Archivos.Tipo2, function(i, archivos) {
						console.log(archivos);
						var $a = $("<a>");
						$a.addClass("list-group-item");
						$a.attr("Table",archivos.Table);
						$a.addClass("DownloadAsignacionTipo2");
						$a.css("cursor","pointer");
						$a.html(archivos.fileName);
						$("#Downloads").find("#Tipo2").append($a);
					});

					$("#page-content").addClass("AsignadorOculto");
					$("#AsignadorDeCasos").addClass("AsignadorOculto");
					TablaDeAsignados = $("#TablaDeAsignados").DataTable({
						data: dataSet,
						columns: [
							{ data: 'Nombre',"width": "60%" },
							{ data: 'Tipo',"width": "10%", },
							{ data: 'Porcentaje',"width": "10%", },
							{ data: 'id' },
							null,
							null,
							{ data: 'Actions',"width": "10%", }
						],
						"columnDefs": [ 
							{
								"targets": 0,
								"width": "70%"
							},
							{
								"targets": 1,
								"width": "10%",
								"render": function (data, type, row) {
									return "<div style='text-align: center;'>" + data + "</div>";
								}
							},
							{
								"targets": 2,
								"width": "10%",
								"render": function( data, type, row ) {
									return "<div style='text-align: center;'><input style='width: 100%; border: 0;' class='InputPorcentaje' type='text' value='"+data+"' /></div>";
								}
							},
							{
								"targets": 3,
								"visible": false
							},
							{
								"targets": 4,
								"width": "10%",
								"render": function (data, type, row) {
									return "<div style='text-align: center;'><input style='width: 100%;' class='date FechaInicio' type='text'/></div>";
								}
							},
							{
								"targets": 5,
								"width": "10%",
								"render": function (data, type, row) {
									return "<div style='text-align: center;'><input style='width: 100%;' class='date FechaFinal' type='text'/></div>";
								}
							},
							{
								"targets": 6,
								"width": "10%",
								"data": 'Actions',
								"render": function( data, type, row ) {
									return "<div style='text-align: center;'><i style='cursor: pointer; margin: 0 10px;' id='" + data +"' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
								}
							},
						]
					});
					$("#TablaDeAsignados").trigger('update');
				}	
			});
		}else{
			$.niftyNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : "La cola no existe" ,
				container : 'floating',
				timer : 2000
			});
		}
	});
	$("body").on("change","select[name='Entidad']",function(){
		var Entidad = $(this).val();
		var CantEntidades = 0;
		jQuery.each( Entidad, function( i, val ) {
			CantEntidades++;
		});
		if(CantEntidades > 1){
			$("#NombreGrupo").show();
		}else{
			$("#NombreGrupo").hide();
		}
	})
	$("body").on("click","#AddEntidad",function(){
		var Template = $("#TemplateAddEntidad").html();
		bootbox.dialog({
            title: "Agregue Nueva Entidad",
            message: Template,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Entidad = $("select[name='Entidad']").val();
						var NombreEntidad = $("select[name='Entidad'] option:selected").text();
						var TipoEntidad = "";
						var idEntidad = "";
						var CantEntidades = 0;
						var CanSave = true;
						jQuery.each( Entidad, function( i, val ) {
							CantEntidades++;
						});
						if(CantEntidades <= 1){
							if(CantEntidades > 0){
								Entidad = Entidad[0];
								var ArrayEntidad = Entidad.split("_");
								TipoEntidad = ArrayEntidad[0];
								idEntidad = ArrayEntidad[1];
							}
						}
						if(CantEntidades > 1){
							var NombreGrupo = $("#NombreGrupo input[name='nombreGrupo']").val();
							if(NombreGrupo == ""){
								CanSave = false;
							}
							if(CanSave){
								NombreEntidad = NombreGrupo;
								var Personas = [];
								var Empresas = [];
								jQuery.each( Entidad, function( i, val ) {
									var ArrayPersona = val.split("_");
									var Persona = ArrayPersona[1];
									switch(ArrayPersona[0]){
										case 'S':
										case 'E':
											Personas.push(Persona);
										break;
										case 'EE':
											Empresas.push(Persona);
										break;
									}
								});
								TipoEntidad = "G";
								console.log(Empresas);
								idEntidad = createGroup(NombreGrupo,Personas,Empresas);
								Entidad = "G_"+idEntidad;
								Entidad = Entidad.replace("/(?:\r\n|\r|\n)/g","");
							}
						}
						switch(TipoEntidad){
							case 'S':
								TipoEntidad = "Personal";
								ArrayPersonal.push(idEntidad);
							break;
							case 'E':
								TipoEntidad = "Personal";
								ArrayPersonal.push(idEntidad);
							break;
							case 'EE':
								TipoEntidad = "Empresa Externa";
								ArrayEE.push(idEntidad);
							break;
							case 'G':
								TipoEntidad = "Grupo";
								ArrayGrupo.push(idEntidad);
							break;
						}
						if(CanSave){
							if(CantEntidades > 0){
								TablaDeAsignados.row.add({
									Nombre: NombreEntidad,
									Tipo: TipoEntidad,
									Porcentaje: "0",
									id: Entidad,
									Actions: Entidad
								}).draw();
							}else{
								CustomAlert("Debe llenar todos los datos.");
							}
						}else{
							CustomAlert("Debe llenar todos los datos.");
						}
						/*if(CantEntidades > 0){
							TablaDeAsignados.row.add({
								Nombre: NombreEntidad,
								Tipo: TipoEntidad,
								Porcentaje: "0",
								id: Entidad,
								Foco: "0",
								Actions: Entidad
							}).draw();
						}else{
							CustomAlert("Debe llenar todos los datos.");
						}*/
                    }
                }
            }
        }).off("shown.bs.modal");
		$(".selectpicker").selectpicker("refresh");
	});
	$("body").on("change","select[name='TipoEntidad']",function(){
		var tipoEntidad = $(this).val();
		$("select[name='Entidad']").html();
		$("select[name='Entidad']").prop("disabled",false);
		$("select[name='Entidad']").selectpicker("refresh");
		var ArrayTmp = [];
		switch(tipoEntidad){
			case '1':
				ArrayTmp = ArrayEE;
			break;
			case '2':
				ArrayTmp = ArrayPersonal;
			break;
			case '3':
				ArrayTmp = ArrayGrupo;
			break;
		}
		$.ajax({
			type: "POST",
			url: "../includes/tareas/getEntidades.php",
			data:{
				tipoEntidad: tipoEntidad, ArrayIds: ArrayTmp
			},
			beforeSend: function() {
				$('body').addClass("loading");
			},
			success: function(response){ 
				$('body').removeClass("loading"); 
				$("select[name='Entidad']").html(response);
				$("select[name='Entidad']").selectpicker("refresh");
			}	
		});
	});
	$("body").on("click",".Delete", function(){
        var ObjectMe = $(this);
		var ID = ObjectMe.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar esta entidad?", function(result) {
            if (result) {
                DeleteEntidad(ObjectTR,ID);
            }
        });
    });
	$("body").on("click","#Seleccionar_Modo_Asignacion",function(){
		var Porcentaje = $("#SumPorcentaje").html();
		Porcentaje = Porcentaje.replace("%","");
		Porcentaje = Number(Porcentaje);
		if(Porcentaje == 100){
			var Template = $("#TemplateSeleccionModoAsignacion").html();
			bootbox.dialog({
				title: "SELECCIONE ALGORITMO DE ASIGNACIÓN",
				message: Template,
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-purple",
						callback: function() {
							var MetodoAsignacion = $("select[name='MetodoAsignacion']").val();
							var Rows = [];
							TablaDeAsignados.rows().eq(0).each( function ( index ) {
								var row = TablaDeAsignados.row( index );
								var data = row.data();
								var ArrayTmp = [];
								$.each(data,function(indexCol,value){
									switch(indexCol){
										case 'Nombre':
											ArrayTmp.push(value);
										break;
										case 'Porcentaje':
											ArrayTmp.push(value);
										break;
										case 'id':
											ArrayTmp.push(value);
										break;
									}
								});
								Rows.push(ArrayTmp);
							});

							switch(MetodoAsignacion){
								case '1':
									//Ruts
									$.ajax({
										type: "POST",
										url: "../includes/tareas/SeparateByRuts.php",
										data:{
											idCola: IDCola, Rows: Rows
										},
										beforeSend: function() {
											$('body').addClass("loading");
										},
										success: function(response){ 
											$('body').removeClass("loading"); 
											var json = JSON.parse(response);
											console.log(json);
											//$("#Downloads #Tipo1").html("<a class='list-group-item' style='position: relative;'>Formato Descargable Tipo 1 <i class='fa fa-download' style='position: absolute; right: 15px;'></i></a>");
											$("#Downloads #Tipo2").html("<a class='list-group-item' style='position: relative;'>Descarga Archivo de Asignacion <i class='fa fa-download' style='position: absolute; right: 15px;'></i></a>");
											//$("#Downloads #Tipo3").html("<a class='list-group-item'>TIPO 3</a>");
											/*$.each(json.Tipo1, function(i, item) {
												//console.log(item);
												var $a = $("<a>");
												$a.addClass("list-group-item");
												$a.attr("href",item.file);
												$a.attr("download",item.fileName+"_Full.xlsx");
												$a.html(item.fileName+"_Full");
												$("#Downloads").find("#Tipo1").append($a);
											});*/
											$.each(json.Tipo2, function(i, item) {
												//console.log(item);
												var $a = $("<a>");
												$a.addClass("list-group-item");
												//$a.attr("href",item.file);
												//$a.attr("download",item.fileName+"_Dial.xlsx");
												$a.attr("Table",item.Table);
												$a.addClass("DownloadAsignacionTipo2");
												$a.css("cursor","pointer");
												$a.html(item.fileName);
												$("#Downloads").find("#Tipo2").append($a);
											});
											/*$.each(json.Tipo3, function(i, item) {
												console.log(item);
												var $a = $("<a>");
												$a.addClass("list-group-item");
												$a.attr("href",item.file);
												$a.attr("download",item.fileName+".xlsx");
												$a.html(item.fileName);
												$("#Downloads").find("#Tipo3").append($a);
											});*/
										}	
									});
								break;
								case '2':
									//Deuda
									$.ajax({
										type: "POST",
										url: "../includes/tareas/SeparateByDeuda.php",
										data:{
											idCola: IDCola, Rows: Rows
										},
										beforeSend: function() {
											$('body').addClass("loading");
										},
										success: function(response){ 
											$('body').removeClass("loading"); 
											var json = JSON.parse(response);
											$("#Downloads #Tipo2").html("<a class='list-group-item' style='position: relative;'>Descarga Archivo de Asignacion <i class='fa fa-download' style='position: absolute; right: 15px;'></i></a>");
											$.each(json.Tipo2, function(i, item) {
												var $a = $("<a>");
												$a.addClass("list-group-item");
												$a.attr("Table",item.Table);
												$a.addClass("DownloadAsignacionTipo2");
												$a.css("cursor","pointer");
												$a.html(item.fileName);
												$("#Downloads").find("#Tipo2").append($a);
											});
										}	
									});
								break;
							}
						}
					}
				}
			}).off("shown.bs.modal");
			$(".selectpicker").selectpicker("refresh");
		}else{
			CustomAlert("El porcentaje total debe ser de 100%");
		}
	});
	$("body").on("click",".DownloadAsignacionTipo2",function(){
		var ObjectMe = $(this);
		var Tabla = ObjectMe.attr("table");
		window.location = "../includes/tareas/crearArchivosDeAsignacion.php?idCola="+idCola+"&Tabla="+Tabla+"&Tipo=2";
	});
	$("body").on("change","input.InputPorcentaje",function(){
		var ObjectMe = $(this);
        var Value = ObjectMe.val();
        var Row = ObjectMe.attr("row");
        var ObjectTD = ObjectMe.closest("td");
		var cell = TablaDeAsignados.cell( ObjectTD );
        cell.data( Value ).draw();
		$("#TablaDeAsignados").trigger('update');
		Porcentaje = $('#SumPorcentaje').text()
		PorcentajeSplit = Porcentaje.split('.');
		SumPorcentaje = parseInt(PorcentajeSplit[0]);
		if (SumPorcentaje > 100) {
			CustomAlert("El porcentaje total debe ser de 100%");
			cell.data(0).draw();
			$("#TablaDeAsignados").trigger('update');
		}
	});
	$("body").on("click","input.Checkbox",function(){
		var ObjectMe = $(this);
		var Value = "0";
		if(ObjectMe.is(':checked')){
			Value = "1";
		}
        var Row = ObjectMe.attr("row");
        var ObjectTD = ObjectMe.closest("td");
        var cell = TablaDeAsignados.cell( ObjectTD );
        cell.data( Value ).draw();
		$("#TablaDeAsignados").trigger('update');
	});
	$("body").on("update","#TablaDeAsignados",function(){
        UpdateEntidadSummaryFoot();
	});
	$("body").on("click",".Cautiva",function(){
		var ObjectMe = $(this);
		//var ObjectTR = ObjectMe.closest("tr");
		var ID = ObjectMe.attr("id");
		showModalCautiva(ID);
	});
	function DeleteEntidad(TableRow,ID){
		$("#Downloads").find("#Tipo2").empty()
        TablaDeAsignados.row(TableRow).remove().draw();
		var ArrayEntidad = ID.split("-");
		var TipoEntidad = ArrayEntidad[0];
		var idEntidad = ArrayEntidad[1];
		switch(TipoEntidad){
			case 'S':
				removeItem(ArrayPersonal,idEntidad);
			break;
			case 'E':
				removeItem(ArrayPersonal,idEntidad);
			break;
			case 'EE':
				removeItem(ArrayEE,idEntidad);
			break;
		}
		$("#TablaDeAsignados").trigger('update');
    }
	function removeItem(array, item){
		for(var i in array){
			if(array[i]==item){
				array.splice(i,1);
				break;
			}
		}
		console.log(array);
	}
	function UpdateEntidadSummaryFoot(){
        var SumPorcentaje = 0;
		TablaDeAsignados.rows().eq(0).each( function ( index ) {
			var row = TablaDeAsignados.row( index );
			var data = row.data();
			$.each(data,function(indexCol,value){
				switch(indexCol){
                    case 'Porcentaje':
                        SumPorcentaje += Number(value);
                    break;
                }
			});
		});
        $("#SumPorcentaje").html(SumPorcentaje.toFixed(2)+"%");
    }
	function createGroup(Nombre,Personas,Empresas){
		ToReturn = "";
		$.ajax({
			type: "POST",
			url: "../grupos/ajax/insertGrupo.php",
			data:{
				nombre: Nombre,
				personas: Personas,
				empresas: Empresas,
				cola: IDCola
			},
			async: false,
			beforeSend: function() {
				$('body').addClass("loading");
			},
			success: function(response){ 
				var json = JSON.parse(response);
				$('body').removeClass("loading"); 
				ToReturn = json.idGrupo;
			}	
		});
		return ToReturn;
	}
	function showModalCautiva(idCola){
		var Template = $("#TemplateCautivo").html();
		bootbox.dialog({
            title: "SELECCIÓN DE EJECUTIVO ",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
						var ObjectStatus = $("input[name='inputCautiva']");
						var Status = ObjectStatus.is(":checked") ? "1": "0";
						var Usuario = $("select[name='EjecutivoColaCautiva']").val();
						if(Usuario != ""){
							updateColaCautiva(idCola);
						}
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
		}).off("shown.bs.modal");
		var Usuarios = getEjecutivosActivos();
		$("select[name='EjecutivoColaCautiva']").html(Usuarios);
		$(".selectpicker").selectpicker("refersh");
		getCola(idCola);
	}
	function getCola(idCola){
		$.ajax({
			type: "POST",
			url: "../includes/tareas/getCola.php",
			data:{
				cola: idCola
			},
			async: false,
			beforeSend: function() {
				$('body').addClass("loading");
			},
			success: function(response){
				$('body').removeClass("loading"); 
				if(isJson(response)){
					var json = JSON.parse(response);
					if(json.Cautiva == "1"){
						$("input[name='inputCautiva']").prop("checked",true);
						$("input[name='inputCautiva']").closest("label").addClass("active");
						$("select[name='EjecutivoColaCautiva']").val(json.idUserCautiva).change();
						$(".selectpicker").selectpicker("refersh");
					}
				}
			}	
		});
	}
	function getEjecutivosActivos(){
		var ToReturn = "";
		$.ajax({
			type: "POST",
			url: "../includes/tareas/getEjecutivosActivos.php",
			data:{},
			async: false,
			beforeSend: function() {
				$('body').addClass("loading");
			},
			success: function(response){
				$('body').removeClass("loading"); 
				ToReturn = response;
			}	
		});
		return ToReturn;
	}
	function updateColaCautiva(idCola){
		var Ejecutivo = $("select[name='EjecutivoColaCautiva']").val();
		var ObjectStatus = $("input[name='inputCautiva']");
		var Status = ObjectStatus.is(":checked") ? "1": "0";
		$.ajax({
			type: "POST",
			url: "../includes/tareas/updateColaCautiva.php",
			data:{
				cola: idCola,
				Ejecutivo: Ejecutivo,
				Cautiva: Status
			},
			async: false,
			beforeSend: function() {
				$('body').addClass("loading");
			},
			success: function(response){
				$('body').removeClass("loading");
			}	
		});
	}
	function isString(value) {return typeof value === 'string';}
	function CustomAlert(Message){
        bootbox.alert(Message);
    }
});
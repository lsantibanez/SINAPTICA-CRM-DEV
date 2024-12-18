$.post('../includes/tickets/privilegios.php', function(data) {
	$('#page-content').load('views/'+data+'.php', function(){

		$('.selectpicker').selectpicker();

		$('[name="AsignarA"], [name="AsignarAUpdate"]').load('../includes/tickets/listUsuario.php',function(){
			$('[name="AsignarA"], [name="AsignarAUpdate"]').selectpicker();
		});
		$('[name="Prioridad"], [name="PrioridadUpdate"]').load('../includes/tickets/selectPrioridad.php',function(){
			$('[name="Prioridad"], [name="PrioridadUpdate"]').selectpicker();
		});
		$('[name="Tipo"], [name="TipoUpdate"], [name="nombreTipo"]').load('../includes/tickets/selectTipoTicket.php',function(){
			$('[name="Tipo"], [name="TipoUpdate"], [name="nombreTipo"]').selectpicker();
		});

		$('select[name="NumeroTicket"]').load('../includes/tickets/listNroTickets.php',function(){
			$('select[name="NumeroTicket"]').selectpicker();
		});


		$('select[name="NombreCliente"], [name="Cliente"], [name="ClienteUpdate"]').load('../includes/tickets/selectClientes.php',function(){
			$('select[name="NombreCliente"], [name="Cliente"], [name="ClienteUpdate"]').selectpicker();
		});

		$('.listaPrioridad').load('../includes/tickets/listPrioridad.php',function(){
			var count = $('.listaPrioridad > .tabeData tr th').length -1;
			$('.listaPrioridad > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});
		$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
			var count = $('.listaAbiertos > .tabeData tr th').length -1;
			$('.listaAbiertos > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});

		$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
			var count = $('.listaFinalizados > .tabeData tr th').length -1;
			$('.listaFinalizados > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});

		$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
			var count = $('.listaIncumplidos > .tabeData tr th').length -1;
			$('.listaIncumplidos > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});
		$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
			var count = $('.listaAsignados > .tabeData tr th').length -1;
			$('.listaAsignados > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});

		$('.listaTipoTicket').load('../includes/tickets/listTipoTicket.php',function(){
			var count = $('.listaTipoTicket > .tabeData tr th').length -1;
			$('.listaTipoTicket > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});

		$('.listaSubTipoTicket').load('../includes/tickets/listSubTipoTicket.php',function(){
			var count = $('.listaSubTipoTicket > .tabeData tr th').length -1;
			$('.listaSubTipoTicket > .tabeData').dataTable({
				"columnDefs": [{
					'orderable': false,
					'targets': [count]
				}, ]
			});
		});

		$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
		$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
		$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
		$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');

		$('.guardarTicket').click(function() {
			$.postFormValues('../includes/tickets/insertData.php','.cont-form1',function(data){
				if (Number(data) > 0){
					$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
						var count = $('.listaAbiertos > .tabeData tr th').length -1;
						$('.listaAbiertos > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
						var count = $('.listaFinalizados > .tabeData tr th').length -1;
						$('.listaFinalizados > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
						var count = $('.listaAsignados > .tabeData tr th').length -1;
						$('.listaAsignados  > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
						var count = $('.listaIncumplidos > .tabeData tr th').length -1;
						$('.listaIncumplidos > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
					$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
					$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
					$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');

					$('input, select, textarea').val('');
					bootbox.alert('<h3 class="text-center">El ticket se registro con éxito.</h3>');
					$('.cont-form1 input, .cont-form1 select, .cont-form1 textarea').value('')
					$('.cont-form1 select').selectpicker('val', '');

				}else{
					console.log(data);
					bootbox.alert('<h3 class="text-center">Se produjo un error al guardar el ticket.</h3>');
				}
			});
		});

		$('.guardarTicketInterno').click(function() {
			$.postFormValues('../includes/tickets/insertData.php','.cont-form3',function(data){
				if (Number(data) > 0){
					$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
						var count = $('.listaAbiertos > .tabeData tr th').length -1;
						$('.listaAbiertos > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
						var count = $('.listaFinalizados > .tabeData tr th').length -1;
						$('.listaFinalizados > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
						var count = $('.listaAsignados > .tabeData tr th').length -1;
						$('.listaAsignados  > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
						var count = $('.listaIncumplidos > .tabeData tr th').length -1;
						$('.listaIncumplidos > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
					$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
					$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
					$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');

					$('input, select, textarea').val('');
					bootbox.alert('<h3 class="text-center">El ticket #'+data+' se registro con éxito.</h3>');
					$('.cont-form3 input, .cont-form3 select, .cont-form3 textarea').value('')
					$('.cont-form3 select').selectpicker('render');
				}else{
					console.log(data);
					bootbox.alert('<h3 class="text-center">Se produjo un error al guardar el ticket.</h3>');
				}
			});
		});

		$('.busqueda').click(function() {
			$.postFormValues('../includes/tickets/listBuscar.php','.cont-form2',function(data){
				$('.listaBusqueda').html(data);
				var count = $('.listaBusqueda > .tabeData tr th').length -1;
				$('.listaBusqueda > .tabeData').dataTable({
					"columnDefs": [{
						'orderable': false,
						'targets': [count]
					}, ]
				});
			});
		});
		$('.guardarPrioridad').click(function() {
			if ($('[name="idUpdatePrioridad"]').val() != "") {
				$.postFormValues('../includes/tickets/updatePrioridad.php','.cont-form3',function(data){
					if (Number(data) > 0) {
						$('.listaPrioridad').load('../includes/tickets/listPrioridad.php',function(){
							var count = $('.listaPrioridad > .tabeData tr th').length -1;
							$('.listaPrioridad > .tabeData').dataTable({
								"columnDefs": [{
									'orderable': false,
									'targets': [count]
								}, ]
							});
						});
						$('[name="Prioridad"]').load('../includes/tickets/selectPrioridad.php');
						$('[name="nombre"]').val("");
						$('[name="tiempo"]').val("");
						$('[name="idUpdatePrioridad"]').val("");
						bootbox.alert('<h3 class="text-center">la prioridad se actualizo con éxito.</h3>');
					}else{
						console.log(data);
						bootbox.alert('<h3 class="text-center">Se produjo un error al guardar.</h3>');
					}
				});
			}else{
				$.postFormValues('../includes/tickets/dataPrioridad.php','.cont-form3',function(data){
					if (Number(data) > 0) {
						var count = $('.listaPrioridad > .tabeData tr th').length -1;
						$('.listaPrioridad').load('../includes/tickets/listPrioridad.php',function(){
							$('.listaPrioridad > .tabeData').dataTable({
								"columnDefs": [{
									'orderable': false,
									'targets': [count]
								}, ]
							});
						});
						$('[name="Prioridad"]').load('../includes/tickets/selectPrioridad.php');
						bootbox.alert('<h3 class="text-center">la prioridad se registro con éxito.</h3>');
					}else{
						console.log(data);
						bootbox.alert('<h3 class="text-center">Se produjo un error al guardar.</h3>');
					}
				});
			}
		});
		$(document).on('click', '.cancelarPrioridad', function() {
			$('[name="nombre"]').val("");
			$('[name="tiempo"]').val("");
			$('[name="idUpdatePrioridad"]').val("");
		});

		$('[name="Tipo"]').change(function() {
			var id = $(this).selectpicker('val');
			$.post('../includes/tickets/selectSubTipoTicket.php', {id:id}, function(data) {
				$('[name="Subtipo"]').html(data);
				$('[name="Subtipo"]').selectpicker('refresh');
			});
		});

		$('[name="TipoUpdate"]').change(function() {
			$.post('../includes/tickets/selectSubTipoTicket.php', {id:$('[name="Tipo"]').val()}, function(data) {
				$('[name="SubtipoUpdate"]').html(data);
				$('[name="SubtipoUpdate"]').selectpicker('refresh');
			});
		});
		$(document).on('click', '.delete-tickets', function() {
			var id = $(this).attr('attr');
			bootbox.confirm({
				message: "<h3 class='text-center'>Esta seguro de querer eliminar los datos</h3>",
				buttons: {
					confirm: {
						label: 'Si borrar',
						className: 'btn-success'
					},
					cancel: {
						label: 'No borrar',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result == true) {
						$.post('../includes/tickets/deleteTickets.php', {id: id}, function(data) {
							$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
								var count = $('.listaAbiertos > .tabeData tr th').length -1;
								$('.listaAbiertos > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
								var count = $('.listaFinalizados > .tabeData tr th').length -1;
								$('.listaFinalizados > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
								var count = $('.listaIncumplidos > .tabeData tr th').length -1;
								$('.listaIncumplidos > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
								var count = $('.listaAsignados > .tabeData tr th').length -1;
								$('.listaAsignados > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});

							$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
							$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
							$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
							$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');
						});
					}
				}
			});
		});

		$(document).on('click', '.delete-tiempo_prioridad', function() {
			var id = $(this).attr('attr');
			bootbox.confirm({
				message: "<h3 class='text-center'>Esta seguro de querer eliminar los datos</h3>",
				buttons: {
					confirm: {
						label: 'Si borrar',
						className: 'btn-success'
					},
					cancel: {
						label: 'No borrar',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result == true) {
						$.post('../includes/tickets/deletePrioridad.php', {id: id}, function(data) {
							$('.listaPrioridad').load('../includes/tickets/listPrioridad.php',function(){
								var count = $('.listaPrioridad > .tabeData tr th').length -1;
								$('.listaPrioridad > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('[name="Prioridad"]').load('../includes/tickets/selectPrioridad.php',function(){
								$('[name="Prioridad"]').selectpicker();
							});
						});
					}
				}
			});
		});

		$(document).on('click', '.finalizar-tickets', function() {
			var id = $(this).attr('attr');
			bootbox.confirm({
				message: "<h3 class='text-center'>Desea finalizar este ticket</h3>",
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
					if (result == true) {
						$.post('../includes/tickets/finalizarTicket.php', {id: id}, function(data) {
							$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
								var count = $('.listaAbiertos > .tabeData tr th').length -1;
								$('.listaAbiertos > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
								var count = $('.listaFinalizados > .tabeData tr th').length -1;
								$('.listaFinalizados > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
								var count = $('.listaIncumplidos > .tabeData tr th').length -1;
								$('.listaIncumplidos > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});
							$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
								var count = $('.listaAsignados > .tabeData tr th').length -1;
								$('.listaAsignados > .tabeData').dataTable({
									"columnDefs": [{
										'orderable': false,
										'targets': [count]
									}, ]
								});
							});

							$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
							$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
							$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
							$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');
						});
					}
				}
			});
		});


		$(document).on('click', '.update-tickets', function(event) {
			var id = $(this).attr('attr');
			$('#actualizarTikect').modal('show');
			$.post('../includes/tickets/dataUpdateTickets.php', {id:id}, function(data) {
				console.log(data);
				value = $.parseJSON(data);
				$('[name="idUpdateTicket"]').val(value[0]['IdTickets'])
				$('[name="ClienteUpdate"]').selectpicker('val',value[0]['IdCliente']);
				$('[name="OrigenUpdate"]').selectpicker('val',value[0]['Origen']);
				$('[name="DepartamentoUpdate"]').selectpicker('val',value[0]['Departamento']);
				$('[name="TipoUpdate"]').selectpicker('val',value[0]['Tipo']);
				$('[name="SubtipoUpdate"]').selectpicker('val',value[0]['Subtipo']);
				$('[name="PrioridadUpdate"]').selectpicker('val',value[0]['Prioridad']);
				$('[name="AsignarAUpdate"]').selectpicker('val',value[0]['AsignarA']);
				$('[name="EstadoUpdate"]').selectpicker('val',value[0]['Estado']);
				$('[name="ObservacionesUpdate"]').val(value[0]['Observacion']);
				$.post('../includes/tickets/selectSubTipoTicket.php', {id:value[0]['Tipo']}, function(data) {
					$('[name="SubtipoUpdate"]').html(data);
					$('[name="SubtipoUpdate"]').val(value[0]['Subtipo']);
				});
			});
		});

		$(document).on('click', '.updateTicket', function(){
			$.postFormValues('../includes/tickets/updateTickets.php','.cont-form4',function(data){
				$('.listaAbiertos').load('../includes/tickets/listAbiertos.php',function(){
					var count = $('.listaAbiertos > .tabeData tr th').length -1;
					$('.listaAbiertos > .tabeData').dataTable({
						"columnDefs": [{
							'orderable': false,
							'targets': [count]
						}, ]
					});
				});
				$('.listaFinalizados').load('../includes/tickets/listFinalizados.php',function(){
					var count = $('.listaFinalizados > .tabeData tr th').length -1;
					$('.listaFinalizados > .tabeData').dataTable({
						"columnDefs": [{
							'orderable': false,
							'targets': [count]
						}, ]
					});
				});
				$('.listaIncumplidos').load('../includes/tickets/listIncumplidos.php',function(){
					var count = $('.listaAbiertos > .tabeData tr th').length -1;
					$('.listaIncumplidos > .tabeData').dataTable({
						"columnDefs": [{
							'orderable': false,
							'targets': [count]
						}, ]
					});
				});
				$('.listaAsignados').load('../includes/tickets/listAsignados.php',function(){
					var count = $('.listaAsignados > .tabeData tr th').length -1;
					$('.listaAsignados > .tabeData').dataTable({
						"columnDefs": [{
							'orderable': false,
							'targets': [count]
						}, ]
					});
				});

				$('.coutAbiertos').load('../includes/tickets/countAbiertos.php');
				$('.coutnAsigados').load('../includes/tickets/countAsigados.php');
				$('.coutnIncumplidos').load('../includes/tickets/countIncumplido.php');
				$('.coutnFinalizado').load('../includes/tickets/countFinalizados.php');
				bootbox.alert('<h3 class="text-center">El ticket se actualizo con éxito.</h3>');
			});
		});

		$(document).on('click', '.update-tiempo_prioridad', function() {
			id = $(this).attr('attr');
			$.post('../includes/tickets/dataUpdatePrioridad.php', {id: id}, function(data) {
				value = $.parseJSON(data);
				$('[name="nombre"]').val(value[0][1]);
				$('[name="tiempo"]').val(value[0][2]);
				$('[name="idUpdatePrioridad"]').val(value[0][0]);
			});
		});

		$(document).on('click', '.guardarTipoTicket', function() {
			$.postFormValues('../includes/tickets/insertTipoTicket.php','.cont-form5',function(data){
				if (Number(data) > 0) {
					$('.listaTipoTicket').load('../includes/tickets/listTipoTicket.php',function(){
						var count = $('.listaTipoTicket > .tabeData tr th').length -1;
						$('.listaTipoTicket > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$('[name="Tipo"], [name="TipoUpdate"], [name="nombreTipo"]').load('../includes/tickets/selectTipoTicket.php');
					bootbox.alert('<h3 class="text-center">El tipo de ticket se registro con éxito.</h3>');
					$('.cont-form5 input, .cont-form5 select, .cont-form5 textarea').value('')
					$('.cont-form5 select').selectpicker('val', '');
				}else{
					console.log(data);
					bootbox.alert('<h3 class="text-center">Se produjo un error al guardar.</h3>');
				}
			})
		});

		$(document).on('click', '.guardarSubTipoTicket', function() {
			$.postFormValues('../includes/tickets/insertSubtipoticket.php','.cont-form6',function(data){
				if (Number(data) > 0) {
					$('.listaSubTipoTicket').load('../includes/tickets/listSubTipoTicket.php',function(){
						var count = $('.listaSubTipoTicket > .tabeData tr th').length -1;
						$('.listaSubTipoTicket > .tabeData').dataTable({
							"columnDefs": [{
								'orderable': false,
								'targets': [count]
							}, ]
						});
					});
					$.post('../includes/tickets/selectSubTipoTicket.php', {id:$('[name="Tipo"]').val()}, function(data) {
						$('[name="Subtipo"]').html(data);
					});
					$('.cont-form6 input, .cont-form6 select, .cont-form6 textarea').value('')
					$('.cont-form6 select').selectpicker('val', '');
					bootbox.alert('<h3 class="text-center">El Subtipo de ticket se registro con éxito.</h3>');
				}else{
					console.log(data);
					bootbox.alert('<h3 class="text-center">Se produjo un error al guardar.</h3>');
				}
			})
		});

		$(document).on('click', '.comentarios', function() {
			id = $(this).attr('attr');
			$('.guardarComentario').attr('attr', id)
			$.post('../includes/tickets/comentarios.php', {id:id}, function(data) {
				$('.cont-comentarios').html(data);
			});
		});

		$(document).on('click', '.guardarComentario', function() {
			id = $(this).attr('attr');
			comentario = $('.textComentario').val();
			$.post('../includes/tickets/insertComentarios.php', {idTicket:id,comentario:comentario}, function(data) {
				$.post('../includes/tickets/comentarios.php', {id:id}, function(data) {
					$('.cont-comentarios').html(data);
					$('.textComentario').val('');
				});
			});
		});

	});


	$(document).on('click', '.guardarCliente', function() {
		$.postFormValues('../includes/tickets/insertCliente.php','.container-form2',function(data){
			value = $.parseJSON(data);
			$('[name="Cliente"]').append(value[1]);
			$('[name="Cliente"]').selectpicker('refresh');
			$('[name="Cliente"]').selectpicker('val', value[2]);
			if (Number(value[0]) > 0){
				$('#modalClienteExtra').modal('hide');
				bootbox.alert('<h3 class="text-center">El cliente #'+value[0]+' se registro con éxito.</h3>');
				$('.container-form2 input, .container-form2 select, .container-form2 textarea').value('')
				$('.container-form2 select').selectpicker('val', '');
			}else{
				console.log(data);
				bootbox.alert('<h3 class="text-center">Se produjo un error al guardar el ticket.</h3>');
			}
		});
	});

});


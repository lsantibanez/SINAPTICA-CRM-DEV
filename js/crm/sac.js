$(document).ready(function(){	
    getDatosCedente();
    $('#llamadaSac').DataTable();

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
	$(document).on('click', '.Llamar', function(){
		id = $(this).closest('tr').attr('id');
        var Tel = $("#telefono"+id).val();
		var CanCall = CanCallFono(Tel);
		if (CanCall) {
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
    
    $(document).on('click', '.Cortar', function(){
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
    
    $(document).on('change', '#seleccione_tipo_busqueda', function(){
        var tipo = $('#seleccione_tipo_busqueda').val();
        $.ajax({
            type: "POST",
            url: "../sac/seleccioneTipo.php",
            data:data,
            success: function(response){
                $('#ocultar').hide();
                $('#mostrar').show();
            }
        });

    });

});

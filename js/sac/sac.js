$(document).ready(function(){	
    getDatosCedente();

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
        var data = "tipo="+tipo;
        $.ajax({
            type: "POST",
            url: "../includes/sac/seleccioneTipo.php",
            data:data,
            success: function(response){
                $('#ocultar').hide();
                $('#mostrar').show();
                $('#mostrar').html(response);
                $('#subTipo').selectpicker("render");
                $('#subTipo').selectpicker('refresh');
            }
        });

    });

    $(document).on('change', '#subTipo', function(){
        var tipo = $('#seleccione_tipo_busqueda').val();
        var subTipo = $('#subTipo').val();
        var data = "tipo="+tipo+"&subTipo="+subTipo;
        $.ajax({
            type: "POST",
            url: "../includes/sac/subTipo.php",
            data:data,
            success: function(response){
                $('#ocultar2').hide();
                $('#mostrar2').show();
                $('#mostrar2').html(response);
                $('#dato').selectpicker("render");
                $('#dato').selectpicker('refresh');
            }
        });

    });

    $(document).on('click', '#buscar', function(){
        var subTipo = $('#subTipo').val();
        var tipo = $('#seleccione_tipo_busqueda').val();
        var dato = $('#dato').val();
        var data = "tipo="+tipo+"&subTipo="+subTipo+"&dato="+dato;
        alert(data);
        if(tipo == 0 || subTipo == 0 || dato == 0){
            bootbox.alert('Debe seleccionar todos los campos!');
            $('#ocultarFono').show();
            $('#mostrarFono').hide();
            $('#ocultar').show();
            $('#mostrar').hide();
            $('#ocultar2').show();
            $('#mostrar2').hide();
            $('#ocultar3').show();
            $('#mostrar3').hide();

        }else{
            $.ajax({
                type: "POST",
                url: "../includes/sac/buscar.php",
                data:data,
                success: function(response){
                    $('#ocultarFono').hide();
                    $('#mostrarFono').show();
                    $('#mostrarFono').html(response);
                    $('#llamadaSac').DataTable();
                }
            });
            $.ajax({
                type: "POST",
                url: "../includes/sac/buscarDatos.php",
                data:data,
                success: function(response){
                    $('#ocultar3').hide();
                    $('#mostrar3').show();
                    $('#mostrar3').html(response);
                    $('#ocultar4').hide();
                    $('#mostrar4').show();
                }
            });

            $.ajax({
                type: "POST",
                url: "../includes/sac/getGestion.php",
                data:data,
                success: function(response){
                    $('#ocultarGestion').hide();
                    $('#mostrarGestion').show();
                    $('#mostrarGestion').html(response);
                    $('#gestiones').DataTable();


                }
            });
        }
    });

    $('.guardarGestion').click(function(){

        var tipo = $('#seleccione_tipo_busqueda').val();
        var subTipo = $('#subTipo').val();
        var dato = $('#dato').val();
        var tipificacion = $("#tipificacion").val();
        var observacion = $("#observacion").val();
        var fono = $('#ultimo_fono').val();   
        if(tipificacion == 0 || observacion == ''){
            bootbox.alert("Debe completar los campos de gestión!");
        }else{
            var data = "tipo="+tipo+"&subTipo="+subTipo+"&tipificacion="+tipificacion+"&observacion="+observacion+"&fono="+fono+"&dato="+dato;
            alert(data);
            $.ajax({
                type: "POST",
                url: "../includes/sac/insertGestion.php",
                data:data,
                success: function(){
                    window.location = "../sac/sac";
                }
            });    
 
        }    
    });

});

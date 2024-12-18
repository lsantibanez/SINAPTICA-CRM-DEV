$(document).ready(function() {


	$.post('ajax/dataUser.php',  function(data) {
		value = $.parseJSON(data);
		$('[name="Usuario"]').val(value[0].usuario)
		$('[name="Nombre"]').val(value[0].nombre)
		$('[name="Correo"]').val(value[0].email)
		$('.img-lg').html(value[1]);
	});


	function showPreview(coords) {
		var height = $('.imgSelect').width();
		var width = $('.imgSelect').height();
		var rx = 150 / coords.w;
		var ry = 150 / coords.h;

		$('#x1').val(coords.x);
		$('#y1').val(coords.y);
		$('#x2').val(coords.x2);
		$('#y2').val(coords.y2);
		$('#w1').val(coords.w);
		$('#h1').val(coords.h);
		$('#w2').val(width);
		$('#h2').val(height);

		$('.imgPreview').css({
			width: Math.round(rx * height) + 'px',
			height: Math.round(ry * width) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	}

	$(".adjuntar-img").on("change", function() {
		obj = this;
		var file = $(obj).val();
   		var ext = file.substring(file.lastIndexOf("."));
   		if (ext == ".jpg") {
   			Archivo = obj.files[0];
			ManejadorArchivo = new FileReader();
			ManejadorArchivo.onload = function(evento) {
				Url = evento.target.result;
				Img1 = '<img src="' + Url + '" class="img-responsive imgSelect">';
				Img2 = '<img src="' + Url + '" class="imgPreview">';
				$('.cont-preImg1').html(Img1);
				$('.cont-preImg2').html(Img2);
				var h = $('.imgSelect').width();
				var w = $('.imgSelect').height();
				var xh1 = h * 0.1;
				var xw1 = w * 0.1;
				var xh2 = h * 0.9;
				var xw2 = w * 0.9;
				if (w > h) {
					v1 =  xh1;
					v2 =  xh2;
				}else{
					v1 =  xw1;
					v2 =  xw2;
				}
				$('.imgSelect').Jcrop({
					onChange: showPreview,
					onSelect: showPreview,
					setSelect:   [ v1, v1, v2, v2 ],
					aspectRatio: 1
				});
			}
			ManejadorArchivo.readAsDataURL(Archivo);
   		}else{
			bootbox.alert('<h3 class="text-center">Disculpe la imagen debe tener el formato jpg</h3>');
			$(obj).reset();
   		}

	});

	$('.check').on('change',function(){
		if( $(this).prop('checked')) {
			$('.pass1').attr('disabled', false);
			$('.pass2').attr('disabled', false);
			$('.pass3').attr('disabled', false);
		}else{
			$('.pass1').attr('disabled', true);
			$('.pass2').attr('disabled', true);
			$('.pass3').attr('disabled', true);
		}
	});

	$('#procesar').on('click', function(){
		$('#Cargando').modal({
			backdrop: 'static',
			keyboard: false
		});
		var formValues = new FormData();
		formValues.append('file', $('.adjuntar-img')[0].files[0]);
		$('.container-form').find("input").each(function(index, elemento) {
			formValues.append($(elemento).attr('name'), $(elemento).val());
		});

		$.ajax({
			url: 'ajax/uploadUpdate.php',
			type: 'POST',
			data: formValues,
			processData: false,
			contentType: false,
			success: function(e) {
				$('#Cargando').modal('hide');
				src = $('.cont-preImg2').children('img').attr('src')
				$('#img-profile').attr('src', src)
				bootbox.alert('<h3 class="text-center">Los datos fueron actualizados con exito.</h3>');
			}
		});
	});

	$('#newPass').on('click', function(){

		if ($('[name="pass2"]').val() == $('[name="newPass"]').val()) {
			var formValues = new FormData();
			$('.container-form2').find("input").each(function(index, elemento) {
				formValues.append($(elemento).attr('name'), $(elemento).val());
			});
			$.ajax({
				url: 'ajax/cambioPass.php',
				type: 'POST',
				data: formValues,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response) {
						bootbox.alert('<h3 class="text-center">La contraseña fue cambiada con exito.</h3>');
					}else{
						bootbox.alert('<h3 class="text-center">Su contraseña anterior no coincide.</h3>');
					}
				}
			});
		}else{
			bootbox.alert('<h3 class="text-center">Los campos de Contraseña no son iguales.</h3>');
		}
	});
});
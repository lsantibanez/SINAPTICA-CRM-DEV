$("body").on("change", "#mandante", function () {
    ID = $(this).val()
    $.ajax({
        type: "POST",
        url: "includes/admin/GetListarCedentesMandantes.php",
        data: { idMandante: ID },
        dataType: "json",
        beforeSend: function () {
            $('#Cargando').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        success: function (data) {
            $('#cedente').empty();

            $.each(data, function (index, array) {
                $('#cedente').append('<option value="' + array.idCedente + '">' + array.NombreCedente + '</option>');
            });

            $('.selectpicker').selectpicker('refresh')

            $('#Cargando').modal('hide');
        },
        error: function () {
            alert('error');
        }
    });
});
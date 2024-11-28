$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "../includes/crm/nivel_1.php",
        data: {
            cedente: $('#cedente').val(),
            busqueda: 1
        },
        success: function(response) {
            $('.nivel_1_mostrar').html(response);
            $('.selectpicker').selectpicker('refresh')
            getInhabilitaciones();
        }
    });

    $(document).on('change', '#seleccione_nivel1', function() {
        Nivel1 = $('#seleccione_nivel1').val()
        getNivel2(Nivel1, 0);
    });

    function getNivel2(Nivel1, Nivel2) {
        $.ajax({
            type: "POST",
            url: "../includes/crm/nivel_2.php",
            data: {
                nivel2: Nivel1
            },
            async: false,
            success: function(response) {
                $('.nivel_2_mostrar').html(response);
                if (Nivel2) {
                    $('#seleccione_nivel2').val(Nivel2)
                }
                $('.selectpicker').selectpicker('refresh')
            }
        });
    }
    $(document).on('change', '#seleccione_nivel2', function() {
        Nivel2 = $('#seleccione_nivel2').val()
        getNivel3(Nivel2, 0);
    });

    function getNivel3(Nivel2, Nivel3) {
        $.ajax({
            type: "POST",
            url: "../includes/crm/nivel_3.php",
            data: {
                nivel3: Nivel2
            },
            async: false,
            success: function(response) {
                $('.nivel_3_mostrar').html(response);
                $("#seleccione_nivel3 option[value='0']").remove();
                if (Nivel3) {
                    $('#seleccione_nivel3').val(Nivel3)
                }
                $(".selectpicker").selectpicker("refresh");
            }
        });
    }

    function getInhabilitaciones(Nivel2, Nivel3) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_inhabilitaciones/getInhabilitaciones.php",
            async: false,
            dataType: 'json',
            success: function(response) {
                $('#seleccione_nivel1').val(response.Nivel1);
                $('.selectpicker').selectpicker('refresh')
                setTimeout(() => {
                    getNivel2(response.Nivel1, response.Nivel2);
                }, 500);
                setTimeout(() => {
                    getNivel3(response.Nivel2, response.Nivel3)
                }, 500);
            }
        });
    }

    $("#updateInhabilitaciones").on('click', function() {
        var Nivel = $("#seleccione_nivel3").val();
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_inhabilitaciones/updateInhabilitaciones.php",
            data: { Nivel: Nivel },
            async: false,
            success: function(result) {
                niftySuccess("Niveles actualizados");
            },
            error: function() {
                niftyDanger("Error al actualizar niveles");
            }
        });
    });

    function niftyWarning(mensaje) {
        $.niftyNoty({
            type: 'warning',
            icon: 'fa fa-exclamation',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }

    function niftyDanger(mensaje) {
        $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-times-circle',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }

    function niftySuccess(mensaje) {
        $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check',
            message: mensaje,
            container: 'floating',
            timer: 5000
        });
    }
});
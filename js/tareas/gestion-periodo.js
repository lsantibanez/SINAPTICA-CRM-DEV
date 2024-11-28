$(document).ready(function () {
    getCedentesMandante();

    $("#Download").click(function () {
        var startDate = $("input[name='start']").val();
        var endDate = $("input[name='end']").val();
        var Cedente = $("select[name='Cedente']").val();
        if (startDate != "") {
            if (endDate != "") {
                if (Cedente != "") {
                    descargarGestiones();
                } else {
                    bootbox.alert("Debe seleccionar un cedente");
                }
            } else {
                bootbox.alert("Debe seleccionar un rango de fecha valido");
            }
        } else {
            bootbox.alert("Debe seleccionar un rango de fecha valido");
        }
    });

    function getCedentesMandante() {
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getCedentesMandante.php",
            data: {},
            async: false,
            success: function (response) {
                $("select[name='Cedente']").html(response);
                $("select[name='Cedente']").selectpicker("refresh");
            },
        });
    }
    function descargarGestiones() {
        var startDateVal = $("input[name='start']").val();
        var endDateVal = $("input[name='end']").val();
        var CedenteVal = $("select[name='Cedente']").val();

        window.location =
            "../includes/tareas/descargarGestiones.php?startDate=" +
            startDateVal +
            "&endDate=" +
            endDateVal +
            "&Cedente=" +
            CedenteVal;
    }
});

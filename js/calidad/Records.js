
audiojs.events.ready(function() {
    audiojs.createAll();
});
$(document).ready(function(){
    var RecordTable;
    var dataSet = [];
    UpdateRecords();
    $("#FiltrarPorFecha").click(function(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        if((startDate != "") && (endDate != "")){
            FillPersonalList(startDate,endDate,GlobalData.nombre_cedente);
            $("select[name='Tipificacion']").html("");
            $("select[name='Tipificacion']").prop("disabled",true);
            $("select[name='Tipificacion']").selectpicker("refresh");
        }
    });
    $("body").on("change","select[name='Ejecutivo']",function(){
        var Val = $(this).val();
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        var Cartera = GlobalData.nombre_cedente;
        if(Val != ""){
            getTipificacion(startDate,endDate);
        }
    });
    $("#Buscar").click(function(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        var Ejecutivo = $("select[name='Ejecutivo']").val();
        var Tipificacion = $("select[name='Tipificacion']").val();
        var Telefono = $("input[name='Fono'").val();
        var Rut = $("input[name='Rut'").val();
        console.log(startDate+" _ "+endDate+" _ "+Ejecutivo+" _ "+Tipificacion+" _ "+Telefono+" _ "+Rut);
        if((startDate != "") || (endDate != "") || (Ejecutivo != "") || (Tipificacion != "") || (Telefono != "") || (Rut != "")){
            BuscarGrabaciones();
        }
    });
    $("body").on("click","#ListenRecord i",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var URL = ObjectDiv.attr("Url");
        var Template = $("#ListenRecordTemplate").html();
            Audio = "<audio src='"+URL+"' preload='auto' controls></audio>";
            Template = Template.replace("{RECORD_AUDIO}",Audio);
        bootbox.dialog({
            title: "GRABACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'medium'
        }).off("shown.bs.modal");
    });

    function FillPersonalList(startDate, endDate, Cartera){
        $.ajax({
            type: "POST",
            url: "../includes/personal/fillSelect.php",
            dataType: "html",
            data: {
                Cartera: Cartera,
                startDate: startDate,
                endDate: endDate
            },
            success: function(data){
                $("select[name='Ejecutivo'").removeAttr("disabled");
                $("select[name='Ejecutivo']").html(data);
                $("select[name='Ejecutivo']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function getTipificacion(startDate, endDate){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipificacionGrabaciones.php",
            data: { 
                Ejecutivo: $("select[name='Ejecutivo']").val(),
                startDate: startDate,
                endDate: endDate
            },
            async: false,
            dataType: "html",
            success: function(data){
                $("select[name='Tipificacion']").html(data);
                $("select[name='Tipificacion']").prop("disabled",false);
                $("select[name='Tipificacion']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function BuscarGrabaciones(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        var Ejecutivo = $("select[name='Ejecutivo']").val();
        var Tipificacion = $("select[name='Tipificacion']").val();
        var Telefono = $("input[name='Fono'").val();
        var Rut = $("input[name='Rut'").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getGrabaciones.php",
            data: { 
                startDate: startDate,
                endDate: endDate,
                Ejecutivo: Ejecutivo,
                Tipificacion: Tipificacion,
                Telefono: Telefono,
                Rut: Rut,
            },
            async: false,
            dataType: "html",
            success: function(data){
                if(isJson(data)){
                    dataSet = JSON.parse(data);
                    console.log(data);
                    RecordTable.destroy();
                    UpdateRecords();
                }
            },
            error: function(){
            }
        });
    }
    function UpdateRecords(startDate, endDate, Cartera){
        RecordTable = $('#Records').DataTable({
            data: dataSet,
            "bDestroy": true,
            columns: [
                { data: 'Cartera' },
                { data: 'Filename' },
                { data: 'Phone' },
                { data: 'Rut' },
                { data: 'Tipificacion' },
                { data: 'Listen' },
                { data: 'Date' }
            ],
            "columnDefs": [
                {
                    "targets": 5,
                    "data": 'Listen',
                    "render": function( data, type, row ) {
                        //return "<audio src='"+data+"' preload='auto' controls></audio>";
                        return "<div url='"+data+"' id='ListenRecord'><i class='fa fa-play'></i></div>";
                    }
                }
            ]
        });
    }
});
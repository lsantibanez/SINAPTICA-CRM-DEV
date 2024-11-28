$(document).ready(function(){
    EstadisticaTable = $('#bot_enviados').DataTable()
    getColas();
    getBots();
    getVoz();

    function getBots() {
        $.ajax({
            type: "POST",
            url: "../includes/bot/getBots.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $("select[name='dialplan']").html(data);
                $("select[name='dialplan']").selectpicker('refresh');
            }
        });
    }

    function getVoz() {
        $.ajax({
            type: "POST",
            url: "../includes/bot/getVoz.php",
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $("select[name='id_voz']").html(data);
                $("select[name='id_voz']").selectpicker('refresh');
            }
        });
    }

    $("select[name='estrategia']").change(function () {
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        var data = "estrategia=" + id;
        $("select[name='queue']").val("");

        $.ajax({
            type: "POST",
            url: "../includes/email/fillQueues.php",
            data: data,
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $("select[name='asignacion']").html(data);
                $("select[name='asignacion']").selectpicker('refresh');
            }
        });
    });

    $("select[name='asignacion']").change(function () {
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        console.log(id)
    });

    $('#enviar').on('click', function () {
        var Estrategia = $("#estrategia").val();
        var Queue = $('#asignacion').val();
        var canales = $('#canales').val();
        var dialplan = $('#dialplan').val();
        var id_voz = $('#id_voz').val();

        if(Estrategia != ""){
            if(Queue != ""){
                if (canales > 0) {
                    if (dialplan != "") {
                        if (id_voz != "") {
                            var Template = $("#TipoCategoriaTemplate").html();
                            bootbox.dialog({
                                title: "Crear BOT",
                                message: Template,
                                buttons: {
                                    confirm: {
                                        label: "Crear",
                                        callback: function() {
                                            var TipoTelefono = $("select[name='Categorias']").val();
                                            var TipoCategorias = $("select[name='TipoCategoria']").val();
                                            var CantTipoTelefono = 0;
                                            jQuery.each(TipoTelefono,function(i,val){
                                                CantTipoTelefono++;
                                            });
                                            if(CantTipoTelefono > 0){
                                                NomEstrategia = $("#estrategia").find("option:selected").text();
                                                NomAsignacion = $("#asignacion").find("option:selected").text();
                                                Nombre = NomEstrategia + ' ' + NomAsignacion;
                                                var data = new FormData();
                                                data.append("Queue", Queue);
                                                data.append("nombre", Nombre);
                                                data.append("canales", canales);
                                                data.append("dialplan", dialplan);
                                                data.append("id_voz", id_voz);
                                                data.append("TipoTelefono", TipoTelefono);
                                                data.append("TipoCategorias", TipoCategorias);
                                                $('#enviar').prop('disabled', true);
                                                $.ajax({
                                                    type: "POST",
                                                    url: "../includes/bot/enviar.php",
                                                    data: data,
                                                    contentType: false,
                                                    processData: false,
                                                    success: function (result) {
                                                        if (result == 1) {
                                                            niftySuccess("Se ha creado exitosamente el envío de BOT.");
                                                            getColas();
                                                        } else if(result == 2) {
                                                            niftyWarning("Error, Ya existe un envío programado para la asignación: " + Queue);
                                                        }else {
                                                            niftyDanger("Error al intentar envío de BOT.");
                                                        }
                                                        $('#enviar').prop('disabled', false);
                                                    }
                                                });
                                            }else{
                                                niftyWarning("Debe seleccionar un tipo de telefono");
                                                return false;
                                            }
                                        }
                                    }
                                }
                            }).off("shown.bs.modal");
                            $(".selectpicker").selectpicker("refresh");
                        } else {
                            niftyWarning("Error, debe seleccionar una Voz.");
                        }
                    }else{
                        niftyWarning("Error, debe seleccionar un BOT.");
                    }
                } else {
                    niftyWarning("Ingrese los canales.");
                }
            }else{
                niftyWarning("Error, debe seleccionar una asignacion.");
            }
        } else {
            niftyWarning("Error, debe seleccionar una estrategia.");
        }
    });
    $("body").on("change","select[name='TipoCategoria']",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/getCategoriasFromTipoCategoria.php",
            data:{
                Tipo: Value
            },
            success: function(response){
                $("select[name='Categorias']").html(response);
                $("select[name='Categorias']").selectpicker("refresh");
            }
        });
    });

    function getColas() {
        $.ajax({
            type: "POST",
            url: "../includes/bot/getColas.php",
            async: false,
            success: function (response) {
                $('#Cargando').modal('hide');
                console.log(response);
                Colas = JSON.parse(response);
                BotTable = $('#BotTable').DataTable({
                    data: Colas,
                    columns: [
                        { data: 'Nombre' },
                        { data: 'Canales' },
                        { data: 'Estado' },
                        { data: 'ProgresoRuts' },
                        { data: 'ProgresoFonos' },
                        { data: 'Accion' }
                    ],
                    destroy: true,
                    "columnDefs": [
                        {
                            "targets": 2,
                            "data": 'Estado',
                            "render": function (data, type, row) {
                                var SelectedPlay = "";
                                var SelectedPause = "";
                                var SelectedStop = "";
                                switch (data) {
                                    case '0':
                                        SelectedStop = " Selected";
                                        break;
                                    case '1':
                                        SelectedPlay = " Selected";
                                        break;
                                    case '2':
                                        SelectedPause = " Selected";
                                        break;
                                }
                                return "<div style='text-align: center; font-size: 25px;' id='" + row.Accion + "'>" +
                                    "<i style='padding: 0 5px;' id='1' class='fa fa-play btn-repro " + SelectedPlay + "'></i>" +
                                    "<i style='padding: 0 5px;' id='2' class='fa fa-pause btn-repro " + SelectedPause + "'></i>" +
                                    "<i style='padding: 0 5px;' id='0' class='fa fa-stop btn-repro " + SelectedStop + "'></i>" +
                                    "</div>";
                            }
                        },
                        {
                            "targets": 5,
                            "render": function (data, type, row) {
                                return "<div style='text-align: center; font-size: 15px;' id='" + data + "'><i style='cursor: pointer;' class='fa fa-check btn btn-primary btn-icon icon-lg Test'></i> <i style='cursor: pointer;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                            }
                        }
                    ]
                });
            }
        });
    }

    $("body").on("click", ".btn-repro", function () {
        var ObjectMe = $(this);
        var Estado = ObjectMe.attr("id");
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        if(Estado == 0){
           Mensaje = "¿Desea Reiniciar la cola?"
        }else if(Estado == 1){
            Mensaje = "¿Desea Iniciar la cola?";
        }else{
            Mensaje = "¿Desea Pausar la cola?"
        }
        bootbox.confirm({
            message: "<div style='font-size: 20px;'>"+Mensaje+"</div>",
            size: 'small',
            buttons: {
                confirm: {
                    label: 'SI',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'NO',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    CambiarStatusCola(id, Estado, ObjectDiv, ObjectMe);
                }
            }
        });
    });

    function CambiarStatusCola(Cola, Estado, ObjectDiv, ObjectMe) {
        $.ajax({
            type: "POST",
            url: "../includes/bot/CambiarStatusCola.php",
            data: {
                Cola: Cola,
                Estado: Estado
            },
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (response) {
                var response = JSON.parse(response);
                if(response.result){
                    location.reload();
                }else{
                    niftyDanger(response.message)
                }
                $('#Cargando').modal('hide');
                console.log(response);
            }
        });
    }

    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de eliminar la cola seleccionada?",
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
                if (result) {
                    EliminarCola(id);
                }
            }
        });
    })

    function EliminarCola(id) {
        $.ajax({
            type: "POST",
            url: "../includes/bot/EliminarCola.php",
            data: {
                Cola: id
            },
            async: false,
            success: function (response) {
                getColas();
            }
        });
    }

    $("select[name='estrategia_estadistica']").change(function () {
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        var data = "estrategia=" + id;

        $.ajax({
            type: "POST",
            url: "../includes/email/fillQueues.php",
            data: data,
            dataType: "html",
            async: false,
            success: function (data) {
                console.log(data);
                $("select[name='asignacion_estadistica']").html(data);
                $("select[name='asignacion_estadistica']").selectpicker('refresh');
            }
        });
    });
    $("select[name='asignacion_estadistica']").change(function () {
        var ObjectMe = $(this);
        var Estrategia = ObjectMe.val();
        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        })
        setTimeout(() => {
            $.ajax({
                type: "POST",
                url: "../includes/bot/getEstadistica.php",
                data: {
                    Estrategia: Estrategia
                },
                async: false,
                success: function (response) {
                    var response = JSON.parse(response);
                    $('#Total').text(response.Total)
                    $('#Buzon').text(response.Buzon)
                    $('#Equivocado').text(response.Equivocado)
                    $('#PosibleBuzon').text(response.PosibleBuzon)
                    $('#Tercero').text(response.Tercero)
                    $('#Titular').text(response.Titular)
                    EstadisticaTable = $('#bot_enviados').DataTable({
                        data: response.dataSet,
                        destroy: true,
                        columns: [
                            { data: 'Rut' },
                            { data: 'Fono' },
                            { data: 'Fecha' },
                            { data: 'Hora' },
                            { data: 'Gestion' },
                            { data: 'Respuesta' },
                            { data: 'urlGrabacion' },
                        ],
                        "columnDefs": [
                            {
                                className: "dt-center",
                                "targets": [0, 1, 2, 3, 4, 5],
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;'>" + data + "</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 6,
                                "render": function (data, type, row) {
                                    return "<div url='" + data + "' id='ListenRecord'><i style='font-size:16px;cursor:pointer' class='fa fa-play'></i></div>";
                                }
                            }
                        ]
                    });
                    $('#Cargando').modal('hide')
                }
            });
        }, 1000);
    });
    $("body").on("click", "#ListenRecord i", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var URL = ObjectDiv.attr("Url");
        var Template = $("#ListenRecordTemplate").html();
        Audio = "<audio src='" + URL + "' preload='auto' controls></audio>";
        Template = Template.replace("{RECORD_AUDIO}", Audio);
        bootbox.dialog({
            title: "GRABACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
                    className: "btn-danger",
                    callback: function () {
                    }
                }
            },
            size: 'medium'
        }).off("shown.bs.modal");
    });
    $(".filtrarEstadistica").click(function () {
        var Estrategia = $("select[name='asignacion_estadistica']").val();
        if (Estrategia) {
            var Estado = $(this).attr('id');
            if (Estado == 'TOTAL') {
                Estado = '';
            }
            EstadisticaTable
                .columns(4)
                .search(Estado)
                .draw();
        }
    });

    $("body").on("click", "#Download", function () {
        var Estrategia = $("select[name='asignacion_estadistica']").val();
        if (Estrategia != "") {
            window.open("../includes/bot/descargarEstadistica.php?Estrategia=" + Estrategia + "", "_blank");
        } else {
            bootbox.alert("Debe seleccionar una Asignación");
        }
    });

    $('.Test').on('click', function () {
        var Template = $("#TestBotTemplate").html();
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");
        bootbox.dialog({
            title: "Probar BOT",
            message: Template,
            buttons: {
                confirm: {
                    label: "Probar",
                    callback: function () {
                        var Nombre = $("#Nombre").val();
                        var Fono = $("#Fono").val();
                        if (Nombre) {
                            if (Fono) {
                                if (Fono.length == 9) {
                                    var data = new FormData();
                                    data.append("Nombre", Nombre);
                                    data.append("Fono", Fono);
                                    data.append("id", id);
                                    $.ajax({
                                        type: "POST",
                                        url: "../includes/bot/testBot.php",
                                        data: data,
                                        contentType: false,
                                        processData: false,
                                        success: function (result) {
                                            if (result) {
                                                niftySuccess("Se ha creado exitosamente el envío de BOT.");
                                            } else {
                                                niftyDanger("Error al intentar envío de BOT.");
                                            }
                                        }
                                    });
                                }else {
                                    niftyWarning("Fono no Cumple con el Formato(9 Digitos)");
                                    return false;
                                }
                            } else {
                                niftyWarning("Debe ingresar un Fono");
                                return false;
                            }
                        } else {
                            niftyWarning("Debe ingresar un Nombre");
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        setTimeout(() => {
            $('#Fono').mask("000000000");
        }, 200);
    });

    function CustomAlert(Message){
        bootbox.alert(Message,function(){
            AddClassModalOpen();
        });
    } 

    function AddClassModalOpen(){
        setTimeout(function(){
            if($("body").hasClass("modal-open")){
                $("body").removeClass("modal-open");
            }
        }, 500);
    }

    function niftyWarning(mensaje){
		$.niftyNoty({
			type: 'warning',
			icon: 'fa fa-exclamation',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
	}

	function niftyDanger(mensaje){
		$.niftyNoty({
			type: 'danger',
			icon: 'fa fa-times-circle',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
	}

	function niftySuccess(mensaje){
		$.niftyNoty({
			type: 'success',
			icon: 'fa fa-check',
			message: mensaje,
			container: 'floating',
			timer: 5000
		});
    }
});
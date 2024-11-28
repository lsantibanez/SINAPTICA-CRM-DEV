$(document).ready(function() {
    EstadisticaTable = $('#ivr_enviados').DataTable()
    var sound = document.createElement('audio');
    var duracion
    var audio
    getColas();

    $("select[name='estrategia']").change(function() {
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
            success: function(data) {
                console.log(data);
                $("select[name='asignacion']").html(data);
                $("select[name='asignacion']").selectpicker('refresh');
            }
        });
    });

    $("select[name='asignacion']").change(function() {
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        console.log(id)
    });

    $('#audio').change(function() {
        audio = $("#audio")[0].files[0]
        var objectUrl = URL.createObjectURL(audio);
        $(sound).prop("src", objectUrl);
    });

    $(sound).on("canplaythrough", function(e) {
        var seconds = e.currentTarget.duration;
        duracion = moment.duration(seconds, "seconds");
        duracion = duracion.seconds();
    });

    $('#enviar').on('click', function() {
        var Estrategia = $("#estrategia").val();
        var Queue = $('#asignacion').val();
        var canales = $('#canales').val();

        if (Estrategia != "") {
            if (Queue != "") {
                if ($('#audio').get(0).files.length > 0) {
                    if (canales > 0) {
                        var Template = $("#TipoCategoriaTemplate").html();
                        bootbox.dialog({
                            title: "Crear Campaña Predictivo",
                            message: Template,
                            buttons: {
                                confirm: {
                                    label: "Actualizar",
                                    callback: function() {
                                        var TipoTelefono = $("select[name='Categorias']").val();
                                        var TipoCategorias = $("select[name='TipoCategoria']").val();
                                        var CantTipoTelefono = 0;
                                        jQuery.each(TipoTelefono, function(i, val) {
                                            CantTipoTelefono++;
                                        });
                                        if (CantTipoTelefono > 0) {
                                            NomEstrategia = $("#estrategia").find("option:selected").text();
                                            NomAsignacion = $("#asignacion").find("option:selected").text();
                                            Nombre = NomEstrategia + ' ' + NomAsignacion;
                                            var data = new FormData();
                                            data.append("Queue", Queue);
                                            data.append("nombre", Nombre);
                                            data.append("canales", canales);
                                            data.append("audio", audio);
                                            data.append("TipoTelefono", TipoTelefono);
                                            data.append("TipoCategorias", TipoCategorias);
                                            data.append("duracion", duracion);
                                            $('#enviar').prop('disabled', true);
                                            $.ajax({
                                                type: "POST",
                                                url: "../includes/ivr/enviar.php",
                                                data: data,
                                                contentType: false,
                                                processData: false,
                                                success: function(result) {
                                                    if (result == 1) {
                                                        niftySuccess("Se ha creado exitosamente el envío de IVR.");
                                                        getColas();
                                                    } else if (result == 2) {
                                                        niftyWarning("Error, Ya existe un envío programado para la asignación: " + Queue);
                                                    } else {
                                                        niftyDanger("Error al intentar envío de IVR.");
                                                    }
                                                    $('#enviar').prop('disabled', false);
                                                }
                                            });
                                        } else {
                                            bootbox.alert("Debe seleccionar un tipo de telefono");
                                        }
                                    }
                                }
                            }
                        }).off("shown.bs.modal");
                        $(".selectpicker").selectpicker("refresh");
                    } else {
                        niftyWarning("Ingrese los canales.");
                    }
                } else {
                    niftyWarning("Error, debe seleccionar un audio.");
                }
            } else {
                niftyWarning("Error, debe seleccionar una asignacion.");
            }
        } else {
            niftyWarning("Error, debe seleccionar una estrategia.");
        }
    });
    $("body").on("change", "select[name='TipoCategoria']", function() {
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/getCategoriasFromTipoCategoria.php",
            data: {
                Tipo: Value
            },
            success: function(response) {
                $("select[name='Categorias']").html(response);
                $("select[name='Categorias']").selectpicker("refresh");
            }
        });
    });

    function getColas() {
        $.ajax({
            type: "POST",
            url: "../includes/ivr/getColas.php",
            async: false,
            success: function(response) {
                $('#Cargando').modal('hide');
                console.log(response);
                Colas = JSON.parse(response);
                IvrTable = $('#IvrTable').DataTable({
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
                    "columnDefs": [{
                            "targets": 2,
                            "data": 'Estado',
                            "render": function(data, type, row) {
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
                            "render": function(data, type, row) {
                                return "<div style='text-align: center; font-size: 15px;' id='" + data + "'><i style='cursor: pointer;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                            }
                        }
                    ]
                });
            }
        });
    }

    $("body").on("click", ".btn-repro", function() {
        var ObjectMe = $(this);
        var Estado = ObjectMe.attr("id");
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        if (Estado == 0) {
            Mensaje = "¿Desea Reiniciar la cola?"
        } else if (Estado == 1) {
            Mensaje = "¿Desea Iniciar la cola?";
        } else {
            Mensaje = "¿Desea Pausar la cola?"
        }
        bootbox.confirm({
            message: "<div style='font-size: 20px;'>" + Mensaje + "</div>",
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
            callback: function(result) {
                if (result) {
                    CambiarStatusCola(id, Estado, ObjectDiv, ObjectMe);
                }
            }
        });
    });

    function CambiarStatusCola(Cola, Estado, ObjectDiv, ObjectMe) {
        $.ajax({
            type: "POST",
            url: "../includes/ivr/CambiarStatusCola.php",
            data: {
                Cola: Cola,
                Estado: Estado
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response) {
                var response = JSON.parse(response);
                if (response.result) {
                    // niftySuccess(response.message)
                    // ObjectDiv.find(".btn-repro").removeClass("Selected");
                    // ObjectMe.addClass("Selected");
                    location.reload();
                } else {
                    niftyDanger(response.message)
                    $('#Cargando').modal('hide');
                    console.log(response);
                }
            }
        });
    }

    $("body").on("click", ".Delete", function() {
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
            callback: function(result) {
                if (result) {
                    EliminarCola(id);
                }
            }
        });
    })

    function EliminarCola(id) {
        $.ajax({
            type: "POST",
            url: "../includes/ivr/EliminarCola.php",
            data: {
                Cola: id
            },
            async: false,
            success: function(response) {
                getColas();
            }
        });
    }

    $("select[name='estrategia_estadistica']").change(function() {
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        var data = "estrategia=" + id;

        $.ajax({
            type: "POST",
            url: "../includes/email/fillQueues.php",
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                console.log(data);
                $("select[name='asignacion_estadistica']").html(data);
                $("select[name='asignacion_estadistica']").selectpicker('refresh');
            }
        });
    });
    $("select[name='asignacion_estadistica']").change(function() {
        var ObjectMe = $(this);
        var Estrategia = ObjectMe.val();
        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        })
        setTimeout(() => {
            $.ajax({
                type: "POST",
                url: "../includes/ivr/getEstadistica.php",
                data: {
                    Estrategia: Estrategia
                },
                async: false,
                success: function(response) {
                    var response = JSON.parse(response);
                    $('#Total').text(response.Total)
                    $('#Ivr').text(response.Ivr)
                    $('#Buzon').text(response.Buzon)
                    $('#No_Ivr').text(response.No_Ivr)
                    $('#Pendiente').text(response.Pendiente)
                    EstadisticaTable = $('#ivr_enviados').DataTable({
                        data: response.dataSet,
                        destroy: true,
                        columns: [
                            { data: 'Rut' },
                            { data: 'Nombre' },
                            { data: 'Fono' },
                            { data: 'Fecha' },
                            { data: 'Hora' },
                            { data: 'Duracion' },
                            { data: 'Estado' },
                        ],
                        "columnDefs": [{
                            className: "dt-center",
                            "targets": [0, 1, 2, 3, 4, 5, 6],
                            "render": function(data, type, row) {
                                return "<div style='text-align: center;'>" + data + "</div>";
                            }
                        }, ]
                    });
                    $('#Cargando').modal('hide')
                }
            });
        }, 1000);
    });
    $(".filtrarEstadistica").click(function() {
        var Estrategia = $("select[name='asignacion_estadistica']").val();
        if (Estrategia) {
            var Estado = $(this).attr('id');
            if (Estado == 'TOTAL') {
                Estado = '';
            }
            EstadisticaTable
                .columns(6)
                .search(Estado)
                .draw();
        }
    });

    $("body").on("click", "#Download", function() {
        var Estrategia = $("select[name='asignacion_estadistica']").val();
        if (Estrategia != "") {
            window.open("../includes/ivr/descargarEstadistica.php?Estrategia=" + Estrategia + "", "_blank");
        } else {
            bootbox.alert("Debe seleccionar una Asignación");
        }
    });

    function CustomAlert(Message) {
        bootbox.alert(Message, function() {
            AddClassModalOpen();
        });
    }

    function AddClassModalOpen() {
        setTimeout(function() {
            if ($("body").hasClass("modal-open")) {
                $("body").removeClass("modal-open");
            }
        }, 500);
    }

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
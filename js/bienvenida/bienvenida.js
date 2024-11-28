
$(document).ready(function() {
    return false;
    var idUsuario = $("#idUsuario").val();
    var nivel = $("#nivel").val();
    var AccesoColasTable;
    var idCalendar = 1;
    var AccesoColasTable
    var id_cola_prioridad_mayor;
    var prioridad_mayor = -1;

    // if (typeof GlobalData.nombreLogo != 'undefined' && GlobalData.nombreLogo) {
    //     var nombreLogo = GlobalData.nombreLogo;
    // }else{
        var nombreLogo = "CRM Sinaptica";
    // }

    var idUser = $("#idUser").val();
    var cedente = $("#cedente").val();
   
   /**
     * 
     * INICIO NUEVO DASH
     * 
     */
    if(cedente==7){

        getDash(idUser,cedente);

    }else{
        
        getAsignacion(idUsuario);
        getMeta(nivel,idUsuario);
        getAsignacionCobrador(idUsuario);
        getCobrador();

    }

    function getDash(idUser,cedente){
        let data = "idUser="+idUser+"&cedente="+cedente;
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/getDash.php",
            data: data,
            dataType: "html",
            success: function(response){
                $('#dash').html(response);
                $('#acceso_directo').DataTable({
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo" : false
                });
            }
        });
    }

    $(document).on('click', '.getCola', function(){
        var asig = $(this).closest("tr").attr("id");
        var cedente = $("#cedente").val();
        var cedenteN = cedente * -1;
        var post = "idcola=0&estrategia="+cedenteN+"&asignacion="+asig;

        $('#Cargando2').modal({
            backdrop: 'static',
            keyboard: false
        }); 
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/accesoDirectoColas2.php",
            data: post,
            dataType: "html",
            success: function(response){
                window.location = '../crm/index';
                $('#Cargando2').modal('hide');

            }
        });
    });

    $(document).on('click', '.getCola2', function(){
        var cedente = $("#cedente").val();
        var user = $('#cuser').val();
        var asig = "QR_"+cedente+"_0_XF_"+user+"_1_1_1_TC";
        var cedenteN = cedente * -1;
        var post = "idcola=0&estrategia="+cedenteN+"&asignacion="+asig;
        console.log("Brayan aqui "+ post);
        $('#Cargando2').modal({
            backdrop: 'static',
            keyboard: false
        }); 
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/accesoDirectoColas2.php",
            data: post,
            dataType: "html",
            success: function(response){
                window.location = '../crm/index';
                $('#Cargando2').modal('hide');

            }
        });
    });

    /**
     * 
     * FIN NUEVO DASH
     * 
     */

    

    function getAsignacionCobrador(idUsuario){
        $.ajax({
            type: "POST",
            url: "../includes/clientes/getAsignacion.php",
            data:"idUsuario="+idUsuario,
            success: function(response){
                $('#table-asignacion').html(response)
                $('#tabla_asignacion').DataTable({
                    "order": [[ 3, "desc" ]],
                    fixedHeader: {
                        header: true,
                        footer: true
                    }
                });
            } 
        });
    }
    accesoColas()
    setTimeout(() => {
        if(id_cola_prioridad_mayor){
            $("#" + id_cola_prioridad_mayor + ".toggle-switch").click()
        }else{
            VerCalendario(0);
        }
    }, 500);

    function getAsignacion(idUsuario){
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/getAsignacion.php",
            data:"idUsuario="+idUsuario,
            success: function(response){
                $('#table-asignacion').html(response)
                $('#tabla_asignacion').DataTable({});
            } 
        });
    }

    function getCobrador(){
        var cobrador = $('#cobrador').val();
        var nivel = $('#nivel').val();
        var data = "nivel="+nivel+"&cobrador="+cobrador;
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/getCobrador.php",
            data:data,
            success: function(response){
                console.log(response);
                console.log('despues de response');
                $('#table-cobrador').html(response)
                $('#tabla_cobrador').DataTable({
                    "order": [[ 2, "desc" ]],
                    "autoWidth": false,
                    "scrollX": true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    "pageLength": 10,
                    

                });
            } 
        });
    }

    $("body").on("click", ".aging ", function () {
  
        var ObjectI = $(this);
        var ObjectDIV = ObjectI.closest("th");
        var cobrador = ObjectDIV.attr("id");
        var post = "cobrador="+cobrador;
        var Template = "";

        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/getAging.php",
            data: post,
            dataType: "html",
            success: function(response){
                Template = $(".modalAging").html(response);
                $('.tabla_aging').DataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false,
                    "searching": false
                });
                console.log(response);
                
                bootbox.dialog({
                    title: "Aging cobrador : "+cobrador,
                    size: "large",
                    message: Template,
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: "Cancelar",
                            className: "btn-danger",
                            callback: function() {
                                window.location.reload();

                            }
                        }
                    }
                }).off("shown.bs.modal");
            }
        });
    });

    function getMeta(nivel,idUsuario){
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/getMeta.php",
            data:"nivel="+nivel+"&idUsuario="+idUsuario,
            success: function(response){
                $('.table-meta').html(response)
                $('#tabla_meta').DataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false,
                    "searching": false
                });
            } 
        });
    }
    function accesoColas(){
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/accesoColas.php",
            data:"idUsuario="+idUsuario,
            success: function(response){
                console.log(response);

                if(isJson(response)){
                    var dataSet = JSON.parse(response);

                    AccesoColasTable = $('#acceso_colas_usuario').DataTable({
                        order: [[0, "asc"]],
                        data: dataSet,
                        scrollX: false,
                        destroy: true,
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": false,
                        "searching": false,
                        "createdRow": function (row, data, index) {
                            $(row).attr('id',data.id)
                        },
                        columns: [
                            { data: 'prioridad' },
                            { data: 'cola' },
                            { data: 'casos' },
                            { data: 'porcentaje' },
                            { data: '' }

                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": 0,
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;'><span style='display:none'>"+data+"</span><span class='text-primary'><input type='text' class='text-transparent-min Prioridad' value='"+data+"'></div>";
                                    
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 1,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>" + data + "</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 2,
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;'>" + data + "</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 3,
                                "render": function (data, type, row) {
                                    return "<div style='text-align: center;'>" + data + "%</div>";
                                }
                            },
                            {
                                className: "dt-center",
                                "targets": 4,
                                "render": function( data, type, row ) {
                                    if(row.cola == 'TOTAL CARTERA'){
                                        return "<div style='text-align: center;'><i class='fa fa-arrow-circle-right fa-lg getCola2' style='color: green; cursor: pointer;'></i></div>";
                                    }else{
                                        return "<div style='text-align: center;' id='"+row.id+","+row.asignacion+","+row.estrategia+"'><i class='fa fa-arrow-circle-right fa-lg accedeCola' style='color: green; cursor: pointer;'></i></div>";
                                    }
                                }
                            }
                        ]
                    });
                }
            } 
        });
    }
    function VerCalendario(idCalendar){
        $('#demo-calendar').fullCalendar('destroy');
        if(idCalendar){
            $('#Cargando').modal({
                backdrop: 'static',
                keyboard: false
            })
            var idCalendar = 'id_cola='+idCalendar;
            var f = new Date();
            var Fecha = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
            var FechaSplit = Fecha.split("-");
            var Anio = FechaSplit[0];
            var Mes = FechaSplit[1];
            var Dia = FechaSplit[2];
            if(Mes<10){
                Mes = "0"+Mes;
            }
            if(Dia<10){
                Dia = "0"+Dia;
            }
            FechaFinal = Anio + "-" + Mes + "-" + Dia;
            
            $.ajax({
                type: "POST",
                data:idCalendar,
                url: "../includes/bienvenida/bienvenida.php",
                success: function(response){ 
                    Valor = JSON.parse(response); 
                    console.log(Valor);
                    $('#demo-external-events .fc-event').each(function() {
                        $(this).data('event', {
                            title: $.trim($(this).text()), // use the element's text as the event title
                            stick: true, // maintain when user navigates (see docs on the renderEvent method)
                            className : $(this).data('class')
                        });

                        // make the event draggable using jQuery UI
                        $(this).draggable({
                            zIndex: 99999,
                            revert: true,      // will cause the event to go back to its
                            revertDuration: 0  //  original position after the drag
                        });
                    });

                    // Initialize the calendar
                    // -----------------------------------------------------------------
                    $('#demo-calendar').fullCalendar({
                        
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        lang: 'es',
                        firstDay : 1,
                        editable: true,
                        droppable: true, // this allows things to be dropped onto the calendar
                        drop: function() {
                            // is the "remove after drop" checkbox checked?
                            if ($('#drop-remove').is(':checked')) {
                                // if so, remove the element from the "Draggable Events" list
                                $(this).remove();
                            }
                        },

                        defaultDate: FechaFinal,
                        eventLimit: true, // allow "more" link when too many events
                        events: Valor,
                        eventClick: function(calEvent, jsEvent, view) {

                            var Rut = calEvent.Rut;

                            $.ajax({
                                type: "POST",
                                url: "../includes/bienvenida/accesoDirectoRut.php",
                                data: {
                                    Rut: Rut
                                },
                                success: function(data){
                                window.location = '../crm/index';
                                }
                            });

                            // change the border color just for fun
                            $(this).css('border-color', 'red');

                        }
                    });  
                    $('#Cargando').modal('hide') 
                }
            });
        }else{
            // Initialize the calendar
            // -----------------------------------------------------------------
            $('#demo-calendar').fullCalendar({

                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                lang: 'es',
                firstDay: 1,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                drop: function () {
                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                eventLimit: true, // allow "more" link when too many events
            });
        }
    }
    $(document).on('click', '.accedeCola', function(){
        var ObjectI = $(this);
        var ObjectDIV = ObjectI.closest("div");
        var datosAsignacion = ObjectDIV.attr("id");

        var arreglo = datosAsignacion.split(",");
        var post = "idcola="+arreglo[0]+"&estrategia="+arreglo[2]+"&asignacion="+arreglo[1];

        $('#Cargando2').modal({
            backdrop: 'static',
            keyboard: false
        }); 
        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/accesoDirectoColas.php",
            data: post,
            dataType: "html",
            success: function(data){
               window.location = '../crm/index';
               $('#Cargando2').modal('hide');

            }
        });
    });
    $(document).on('click', '.accedeColaMeta', function(){
        var datosAsignacion = this.id;

        var arreglo = datosAsignacion.split(",");
        var post = "idcola="+arreglo[0]+"&estrategia="+arreglo[2]+"&asignacion="+arreglo[1];

        console.log(post);

        $.ajax({
            type: "POST",
            url: "../includes/bienvenida/accesoDirectoColas.php",
            data: post,
            dataType: "html",
            success: function(data){
               window.location = '../crm/index';
            }
        });
    });
    $(document).on('click', '.verCalendario', function(){
        var ObjectI = $(this);
        var ObjectDIV = ObjectI.closest("div");
        var datosAsignacion = ObjectDIV.attr("id");
        var id = $(this).closest("tr").attr("id");
        var arreglo = datosAsignacion.split(",");
        var idCalendar = arreglo[0];
        var switches = $('.verCalendario');

        if ($('.verCalendario').is(':checked')) {
            $.each(switches, function (i, array) {
                switch_id = $(array).attr('id')

                if (id == switch_id) {
                    $("#" + switch_id + ".toggle-switch").attr("disabled", false);
                } else {
                    $("#" + switch_id + ".toggle-switch").attr("disabled", true);
                }
            })
            $.niftyNoty({
                type: 'success',
                icon: 'fa fa-check',
                message: 'Calendario Seleccionado',
                container: 'floating',
                timer: 4000
            });
            $(this).closest('tr').addClass('bg-gray-dark');
            VerCalendario(idCalendar);

        }else{
            $.each(switches, function (i, array) {
                switch_id = $(array).attr('id')
                $("#" + switch_id + ".toggle-switch").attr("disabled", false);
            })
            $(this).closest('tr').removeClass('bg-gray-dark');
            VerCalendario(0);
        }
        
    });

    $(document).on('change', '.Prioridad', function () {
        var Span = $(this).closest('tr').find('.spanPrioridad')
        console.log(Span)
        var Id = $(this).closest('tr').attr('id');
        var ValorPrioridad = $(this).val();
        var data = 'Id='+Id+"&ValorPrioridad="+ValorPrioridad;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/ActualizarPrioridad.php",
            data: data,
            success: function (response) {
                accesoColas();
                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Prioridad Actualizada',
                    container: 'floating',
                    timer: 2000
                });
            }
        });
    });
});

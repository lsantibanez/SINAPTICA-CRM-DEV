$(document).ready(function(){

    NivelRapidoExistArray = [];
    showNivelRapido();
    showNivel3();
    showNivel4();

    $.ajax({
        type: "POST",
        url: "../includes/nivel/getTipoContacto.php",
        success: function (response) {
            $.each(response, function (index, array) {
                $('#storeNivel3').find('select[name="Id_TipoGestion"]').append($('<option>', {
                    value: array.Id_TipoContacto,
                    text: array.Nombre
                }))

                $('#updateNivel3').find('select[name="Id_TipoGestion"]').append($('<option>', {
                    value: array.Id_TipoContacto,
                    text: array.Nombre
                }))

                $('#storeNivel4').find('select[name="Id_TipoGestion"]').append($('<option>', {
                    value: array.Id_TipoContacto,
                    text: array.Nombre
                }))

                $('#updateNivel4').find('select[name="Id_TipoGestion"]').append($('<option>', {
                    value: array.Id_TipoContacto,
                    text: array.Nombre
                }))
            })
            $('.selectpicker').selectpicker('refresh')
        }
    });

    //NIVEL 1

    Nivel1Table = $('#Nivel1Table').DataTable({
        iDisplayLength: 100,
        pageLength: 25,
        lengthChange: false,
        processing: true,
        serverSide: false,  
        bInfo:false,
        order: [[0, 'asc']],
        language: {
            processing:     "Procesando ...",
            search:         'Buscar',
            lengthMenu:     "Mostrar _MENU_ Registros",
            info:           "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty:      "Mostrando 0 a 0 de 0 Registros",
            infoFiltered:   "(filtrada de _MAX_ registros en total)",
            infoPostFix:    "",
            loadingRecords: "...",
            zeroRecords:    "No se encontraron registros coincidentes",
            emptyTable:     "No hay datos disponibles en la tabla",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Ultimo"
            },
            aria: {
                sortAscending:  ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });

    $.ajax({
        type: "POST",
        url: "../includes/nivel/showNivel1.php",
        success: function(response){
            $.each(response.array, function( index, array ) {
                var rowNode = Nivel1Table.row.add([
                  ''+array.nombre+'',
                    '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel1" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel1" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>'+'',
                ]).draw(false).node();

                $( rowNode ).attr('id',array.id);
                $('#storeNivel2').find('select[name="nivel_1"]').append($('<option>', {
                    value: array.id,
                    text: array.nombre
                }));
                $('#updateNivel2').find('select[name="nivel_1"]').append($('<option>', {
                    value: array.id,
                    text: array.nombre
                }));
            });
            
            $('[data-toggle="popover"]').popover();
            $('.selectpicker').selectpicker('refresh')
        }
    });

    $('body').on('click', '#guardarNivel1', function () {
        $.postFormValues('../includes/nivel/storeNivel1.php', '#storeNivel1', function (response) {
            if(response.status == 1){

                $.niftyNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Registro Guardado Exitosamente',
                    container : 'floating',
                    timer : 3000
                });

                var rowNode = Nivel1Table.row.add([
                    ''+response.array.nombre+'',
                    '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil-square-o btn btn-purple btn-icon icon-lg UpdateNivel1" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel1" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>'+'',
                ]).draw(false).node();

                $( rowNode ).attr('id',response.array.id);

                $('#storeNivel2').find('select[name="nivel_1"]').append($('<option>', {
                    value: response.array.id,
                    text: response.array.nombre
                }));

                $('#updateNivel2').find('select[name="nivel_1"]').append($('<option>', {
                    value: response.array.id,
                    text: response.array.nombre
                }));

                $('[data-toggle="popover"]').popover();
                $('.selectpicker').selectpicker('refresh')

                $('#storeNivel1')[0].reset();
                $('.modal').modal('hide');

            }else if(response.status == 2){

                $.niftyNoty({
                    type: 'danger',
                    icon : 'fa fa-check',
                    message : 'Debe llenar todos los campos',
                    container : 'floating',
                    timer : 3000
                });

            }else{

                $.niftyNoty({
                    type: 'danger',
                    icon : 'fa fa-check',
                    message : 'Ocurrió un error en el Proceso',
                    container : 'floating',
                    timer : 3000
                });

            }
        });
    });

    $('body').on( 'click', '.UpdateNivel1', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        ObjectTR.addClass("Selected");
        var ObjectId = ObjectTR.attr("id");
        var ObjectName = ObjectTR.find("td").eq(0).text();
        $('#updateNivel1').find('input[name="id"]').val(ObjectId);
        $('#updateNivel1').find('input[name="nombre"]').val(ObjectName);
        $('#Nivel1FormUpdate').modal('show');  
    });

    $('body').on('click', '#actualizarNivel1', function () {
        $.postFormValues('../includes/nivel/updateNivel1.php', '#updateNivel1', function (response) {
            if(response.status == 1){
                $.niftyNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Registro Actualizado Exitosamente',
                    container : 'floating',
                    timer : 3000
                });

                ObjectTR = $("#"+response.array.id);
                ObjectTR.find("td").eq(0).html(response.array.nombre);
                $('#storeNivel2').find('#nivel_1 option[value="'+response.array.id+'"]').text(response.array.nombre);
                $('#updateNivel2').find('#nivel_1 option[value="'+response.array.id+'"]').text(response.array.nombre);
                $('.selectpicker').selectpicker('refresh')                
                $('.modal').modal('hide');
            } else if(response.status == 2) {
                $.niftyNoty({
                    type: 'danger',
                    icon : 'fa fa-check',
                    message : 'Debe llenar todos los campos',
                    container : 'floating',
                    timer : 3000
                });
            } else {
                $.niftyNoty({
                    type: 'danger',
                    icon : 'fa fa-check',
                    message : 'Ocurrió un error en el Proceso',
                    container : 'floating',
                    timer : 3000
                });
            }
        });    
    });

    $('body').on('click', '.RemoveNivel1', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectId = ObjectTR.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    $.ajax({
                        url: "../includes/nivel/deleteNivel1.php",
                        type: 'POST',
                        data:"&id="+ObjectId,
                        success:function(response){
                            setTimeout(function() {
                                if(response.status == 1){
                                    bootbox.alert("El registro ha sido eliminado!");
                                    $('#storeNivel2').find('#nivel_1 option[value="' + ObjectId + '"]').remove();
                                    $('#updateNivel2').find('#nivel_1 option[value="' + ObjectId + '"]').remove();
                                    $('.selectpicker').selectpicker('refresh')
                                    Nivel1Table.row($(ObjectTR))
                                        .remove()
                                        .draw();
                                }else if(response.status == 3){
                                    bootbox.alert("Este registro no puede ser eliminado porque posee otros registros asociados");
                                }else{
                                    bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                                }
                            }, 1000);  
                        },
                        error:function(){
                            bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                        }
                    });
                }
            }
        });
    });

    //NIVEL 2
    Nivel2Table = $('#Nivel2Table').DataTable({
        iDisplayLength: 100,
        pageLength: 25,
        lengthChange: false,
        processing: true,
        serverSide: false,
        bInfo: false,
        order: [[0, 'asc']],
        language: {
            processing: "Procesando ...",
            search: 'Buscar',
            lengthMenu: "Mostrar _MENU_ Registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty: "Mostrando 0 a 0 de 0 Registros",
            infoFiltered: "(filtrada de _MAX_ registros en total)",
            infoPostFix: "",
            loadingRecords: "...",
            zeroRecords: "No se encontraron registros coincidentes",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });

    $.ajax({
        type: "POST",
        url: "../includes/nivel/showNivel2.php",
        success: function (response) {
            console.log(response);
            $.each(response.array, function (index, array) {
                var rowNode = Nivel2Table.row.add([
                    '' + array.nivel_1 + '',
                    '' + array.nombre + '',
                    '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel2" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel2" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                ]).draw(false).node();
                $(rowNode).attr('id', array.id)
                          .data('id_nivel_1', array.id_nivel_1);

                $('#storeNivel3').find('select[name="nivel_2"]').append($('<option>', {
                    value: array.id,
                    text: array.nivel_1 + ' - ' + array.nombre
                }));

                $('#updateNivel3').find('select[name="nivel_2"]').append($('<option>', {
                    value: array.id,
                    text: array.nivel_1 + ' - ' + array.nombre
                }));
            });

            $('[data-toggle="popover"]').popover();
            $('.selectpicker').selectpicker('refresh')
        }
    });

    $('body').on('click', '#guardarNivel2', function () {
        $.postFormValues('../includes/nivel/storeNivel2.php', '#storeNivel2', function (response) {

            if (response.status == 1) {

                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Registro Guardado Exitosamente',
                    container: 'floating',
                    timer: 3000
                });

                var rowNode = Nivel2Table.row.add([
                    '' + response.array.nivel_1 + '',
                    '' + response.array.nombre + '',
                    '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil-square-o btn btn-success btn-icon icon-lg UpdateNivel2" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel2" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                ]).draw(false).node();

                $(rowNode)
                    .attr('id', response.array.id)
                    .data('id_nivel_1', response.array.id_nivel_1);

                $('#storeNivel3').find('select[name="nivel_2"]').append($('<option>', {
                    value: response.array.id,
                    text: response.array.nombre
                }));

                $('#updateNivel3').find('select[name="nivel_2"]').append($('<option>', {
                    value: response.array.id,
                    text: response.array.nombre
                }));

                $('#storeNivel2')[0].reset();
                $('[data-toggle="popover"]').popover();
                $('.selectpicker').selectpicker('refresh')
                $('.modal').modal('hide');

            } else if (response.status == 2) {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Debe llenar todos los campos',
                    container: 'floating',
                    timer: 3000
                });

            } else {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Ocurrió un error en el Proceso',
                    container: 'floating',
                    timer: 3000
                });

            }
        });
    });

    $('body').on('click', '.UpdateNivel2', function () {

        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        ObjectTR.addClass("Selected");
        var ObjectId = ObjectTR.attr("id");
        var ObjectLevel1 = ObjectTR.data("id_nivel_1");
        var ObjectName = ObjectTR.find("td").eq(1).text();
        $('#updateNivel2').find('input[name="id"]').val(ObjectId);
        $('#updateNivel2').find('select[name="nivel_1"]').val(ObjectLevel1);
        $('#updateNivel2').find('input[name="nombre"]').val(ObjectName);

        $('.selectpicker').selectpicker('refresh')
        $('#Nivel2FormUpdate').modal('show');

    });

    $('body').on('click', '#actualizarNivel2', function () {

        $.postFormValues('../includes/nivel/updateNivel2.php', '#updateNivel2', function (response) {
            if (response.status == 1) {

                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Registro Actualizado Exitosamente',
                    container: 'floating',
                    timer: 3000
                });

                ObjectTR = $("#" + response.array.id);
                ObjectTR.data('id_nivel_1',response.array.id_nivel_1);
                ObjectTR.find("td").eq(0).html(response.array.nivel_1);
                ObjectTR.find("td").eq(1).html(response.array.nombre);

                $('#storeNivel3').find('#nivel_2 option[value="' + response.array.id + '"]').text(response.array.nombre);
                $('#updateNivel3').find('#nivel_2 option[value="' + response.array.id + '"]').text(response.array.nombre);

                $('.selectpicker').selectpicker('refresh')

                $('.modal').modal('hide');


            } else if (response.status == 2) {
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Debe llenar todos los campos',
                    container: 'floating',
                    timer: 3000
                });
            } else {
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Ocurrió un error en el Proceso',
                    container: 'floating',
                    timer: 3000
                });
            }
        });
    });

    $('body').on('click', '.RemoveNivel2', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectId = ObjectTR.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    $.ajax({
                        url: "../includes/nivel/deleteNivel2.php",
                        type: 'POST',
                        data: "&id=" + ObjectId,
                        success: function (response) {
                            setTimeout(function () {
                                if (response.status == 1) {
                                    bootbox.alert("El registro ha sido eliminado!");
                                    $('#storeNivel3').find('#nivel_2 option[value="' + ObjectId + '"]').remove();
                                    $('#updateNivel3').find('#nivel_2 option[value="' + ObjectId + '"]').remove();
                                    $('.selectpicker').selectpicker('refresh')
                                    Nivel2Table.row($(ObjectTR))
                                        .remove()
                                        .draw();
                                } else if (response.status == 3) {
                                    bootbox.alert("Este registro no puede ser eliminado porque posee otros registros asociados");
                                } else {
                                    bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                                }
                            }, 1000);
                        },
                        error: function () {
                            bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                        }
                    });
                }
            }
        });
    });

    //NIVEL 3

    Nivel3Table = $('#Nivel3Table').DataTable({
        iDisplayLength: 100,
        pageLength: 25,
        lengthChange: false,
        processing: true,
        serverSide: false,
        bInfo: false,
        order: [[0, 'asc']],
        language: {
            processing: "Procesando ...",
            search: 'Buscar',
            lengthMenu: "Mostrar _MENU_ Registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty: "Mostrando 0 a 0 de 0 Registros",
            infoFiltered: "(filtrada de _MAX_ registros en total)",
            infoPostFix: "",
            loadingRecords: "...",
            zeroRecords: "No se encontraron registros coincidentes",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });

    function showNivel3()
    {
        $.ajax({
            type: "POST",
            url: "../includes/nivel/showNivel3.php",
            success: function (response) {
                $.each(response.array, function (index, array) {
                    var rowNode = Nivel3Table.row.add([
                        '' + array.nivel_1 + '',
                        '' + array.nivel_2 + '',
                        '' + array.nombre + '',
                        // '' + array.Ponderacion + '',
                        '' + array.Peso + '',
                        '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                    ]).draw(false).node();

                    $(rowNode)
                        .attr('id', array.id)
                        .data('id_nivel_2', array.id_nivel_2)
                        .data('Id_TipoGestion', array.Id_TipoGestion);

                    $('#storeNivel4').find('select[name="nivel_3"]').append($('<option>', {
                        value: array.id,
                        text: array.nivel_1 + ' - ' + array.nivel_2 + ' - ' + array.nombre
                    }))
        
                    $('#updateNivel4').find('select[name="nivel_3"]').append($('<option>', {
                        value: array.id,
                        text: array.nivel_1 + ' - ' + array.nivel_2 + ' - ' + array.nombre
                    }))

                    if ($.inArray(array.id, NivelRapidoExistArray) == -1) {
                        $('#storeNivelRapido').find('select[name="nivel_3"]').append($('<option>', {
                            value: array.id,
                            text: array.nombre
                        }));
                    }
                });

                $('[data-toggle="popover"]').popover();
                $('.selectpicker').selectpicker('refresh')
            }
        });
    }

    $('body').on('click', '#guardarNivel3', function () {
        var nivel_2 = $("#storeNivel3 select[name='nivel_2']").val();
        var nombre = $("#storeNivel3 input[name='nombre']").val();
        var Id_TipoGestion = $("#storeNivel3 select[name='Id_TipoGestion']").val();
        var Ponderacion = $("#storeNivel3 input[name='Ponderacion']").val();
        var Peso = $("#storeNivel3 input[name='Peso']").val();
        $.ajax({
            type: "POST",
            url: "../includes/nivel/storeNivel3.php",
            async: false,
            dataType: "html",
            data: {
                nivel_2: nivel_2,
                nombre: nombre,
                Id_TipoGestion: Id_TipoGestion,
                Ponderacion: Ponderacion,
                Peso: Peso,
            },
            success: function(data){
				if(isJson(data)){
                    var response = JSON.parse(data);
                    if (response.status == 1) {

                        $.niftyNoty({
                            type: 'success',
                            icon: 'fa fa-check',
                            message: 'Registro Guardado Exitosamente',
                            container: 'floating',
                            timer: 3000
                        });
        
                        var rowNode = Nivel3Table.row.add([
                            '' + response.array.nivel_1 + '',
                            '' + response.array.nivel_2 + '',
                            '' + response.array.nombre + '',
                            '' + response.array.TipoContacto + '',
                            // '' + response.array.Ponderacion + '',
                            '' + response.array.Peso + '',
                            '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                        ]).draw(false).node();
        
                        $(rowNode)
                            .attr('id', response.array.id)
                            .data('id_nivel_2', response.array.id_nivel_2)
                            .data('Id_TipoGestion', response.array.Id_TipoGestion);
        
                        $('#storeNivelRapido').find('select[name="nivel_3"]').append($('<option>', {
                            value: response.array.id,
                            text: response.array.nombre
                        }));
        
                        $('#storeNivel3')[0].reset();
                        $('.selectpicker').selectpicker('refresh')
                        $('[data-toggle="popover"]').popover();
                        $('.modal').modal('hide');
        
                    } else if (response.status == 2) {
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: 'Debe llenar todos los campos',
                            container: 'floating',
                            timer: 3000
                        });        
                    } else {        
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: 'Ocurrió un error en el Proceso',
                            container: 'floating',
                            timer: 3000
                        });
        
                    }
                }
            },
            error: function(response){
                console.log(response);
            }
        });
        /* $.postFormValues('../includes/nivel/storeNivel3.php', '#storeNivel3', function (response) {

            if (response.status == 1) {

                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Registro Guardado Exitosamente',
                    container: 'floating',
                    timer: 3000
                });

                var rowNode = Nivel3Table.row.add([
                    '' + response.array.nivel_1 + '',
                    '' + response.array.nivel_2 + '',
                    '' + response.array.nombre + '',
                    '' + response.array.TipoContacto + '',
                    // '' + response.array.Ponderacion + '',
                    '' + response.array.Peso + '',
                    '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil-square-o btn btn-success btn-icon icon-lg UpdateNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                ]).draw(false).node();

                $(rowNode)
                    .attr('id', response.array.id)
                    .data('id_nivel_2', response.array.id_nivel_2)
                    .data('Id_TipoGestion', response.array.Id_TipoGestion)
                    .addClass('text-center')

                $('#storeNivelRapido').find('select[name="nivel_3"]').append($('<option>', {
                    value: response.array.id,
                    text: response.array.nombre
                }));

                $('#storeNivel3')[0].reset();
                $('.selectpicker').selectpicker('refresh')
                $('[data-toggle="popover"]').popover();
                $('.modal').modal('hide');

            } else if (response.status == 2) {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Debe llenar todos los campos',
                    container: 'floating',
                    timer: 3000
                });

            } else {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Ocurrió un error en el Proceso',
                    container: 'floating',
                    timer: 3000
                });

            }
        }); */
    });

    $('body').on('click', '.UpdateNivel3', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr")
        ObjectTR.addClass("Selected")
        var ObjectId = ObjectTR.attr("id")
        var ObjectLevel2 = ObjectTR.data("id_nivel_2")
        var ObjectName = ObjectTR.find("td").eq(2).text()
        var ObjectGestionType = ObjectTR.data("Id_TipoGestion")
        // var ObjectPonderacion = ObjectTR.find("td").eq(3).text();
        var ObjectPeso = ObjectTR.find("td").eq(4).text()
        $('#updateNivel3').find('input[name="id"]').val(ObjectId)
        $('#updateNivel3').find('select[name="nivel_2"]').val(ObjectLevel2)
        $('#updateNivel3').find('input[name="nombre"]').val(ObjectName)
        $('#updateNivel3').find('select[name="Id_TipoGestion"]').val(ObjectGestionType)
        // $('#updateNivel3').find('input[name="Ponderacion"]').val(ObjectPonderacion);
        $('#updateNivel3').find('input[name="Peso"]').val(ObjectPeso)
        $('.selectpicker').selectpicker('refresh')
        $('#Nivel3FormUpdate').modal('show')
    });

    $('body').on('click', '#actualizarNivel3', function () {
        $.postFormValues('../includes/nivel/updateNivel3.php', '#updateNivel3', function (response) {
            if (response.status == 1) {
                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Registro Actualizado Exitosamente',
                    container: 'floating',
                    timer: 3000
                });

                $('#storeNivelRapido').find('#nivel_3 option[value="' + response.array.id + '"]').text(response.array.nombre);
                $('.selectpicker').selectpicker('refresh')

                ObjectTR = $("#" + response.array.id);
                ObjectTR.data('id_nivel_2', response.array.id_nivel_2);
                ObjectTR.data('Id_TipoGestion', response.array.Id_TipoGestion);
                ObjectTR.find("td").eq(1).html(response.array.nivel_2);
                ObjectTR.find("td").eq(2).html(response.array.nombre);
                ObjectTR.find("td").eq(3).html(response.array.TipoContacto);
                // ObjectTR.find("td").eq(3).html(response.array.Ponderacion);
                ObjectTR.find("td").eq(4).html(response.array.Peso);
                $('.modal').modal('hide');
            } else if (response.status == 2) {
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Debe llenar todos los campos',
                    container: 'floating',
                    timer: 3000
                });
            } else {
                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Ocurrió un error en el Proceso',
                    container: 'floating',
                    timer: 3000
                });
            }
        });
    });

    $('body').on('click', '.RemoveNivel3', function () {

        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectId = ObjectTR.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    $.ajax({
                        url: "../includes/nivel/deleteNivel3.php",
                        type: 'POST',
                        data: "&id=" + ObjectId,
                        success: function (response) {
                            setTimeout(function () {
                                if (response.status == 1) {
                                    bootbox.alert("El registro ha sido eliminado!");
                                    $('#storeNivelRapido').find('#nivel_3 option[value="' + ObjectId + '"]').remove();
                                    $('.selectpicker').selectpicker('refresh')
                                    Nivel3Table.row($(ObjectTR))
                                        .remove()
                                        .draw();
                                } else if (response.status == 3) {
                                    bootbox.alert("Este registro no puede ser eliminado porque posee otros registros asociados");
                                } else {
                                    bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                                }
                            }, 1000);
                        },
                        error: function () {
                            bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                        }
                    });
                }
            }
        });
    });

    //NIVEL 4

    Nivel4Table = $('#Nivel4Table').DataTable({
        iDisplayLength: 100,
        pageLength: 25,
        lengthChange: false,
        processing: true,
        serverSide: false,
        bInfo: false,
        order: [[0, 'asc']],
        language: {
            processing: "Procesando ...",
            search: 'Buscar',
            lengthMenu: "Mostrar _MENU_ Registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty: "Mostrando 0 a 0 de 0 Registros",
            infoFiltered: "(filtrada de _MAX_ registros en total)",
            infoPostFix: "",
            loadingRecords: "...",
            zeroRecords: "No se encontraron registros coincidentes",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });
    
    function showNivel4() {
        $.ajax({
            type: "POST",
            url: "../includes/nivel/showNivel4.php",
            success: function (response) {
                $.each(response.array, function (index, array) {
                    var rowNode = Nivel4Table.row.add([
                        '' + array.nivel_1 + '',
                        '' + array.nivel_2 + '',
                        '' + array.nivel_3 + '',
                        '' + array.nombre + '',
                        // '' + array.Ponderacion + '',
                        '' + array.Peso + '',
                        '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel4" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel4" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                    ]).draw(false).node()
    
                    $(rowNode).attr('id', array.id)
                              .data('id_nivel_2', array.id_nivel_2)
                              .data('id_nivel_3', array.id_nivel_3)
                              .data('Id_TipoGestion', array.Id_TipoGestion)
    
                    if ($.inArray(array.id, NivelRapidoExistArray) == -1) {
                        $('#storeNivelRapido').find('select[name="nivel_4"]').append($('<option>', {
                            value: array.id,
                            text: array.nombre
                        }))
                    }
                })    
                $('[data-toggle="popover"]').popover()
                $('.selectpicker').selectpicker('refresh')
            }
        })
    }
    
    $('body').on('click', '#guardarNivel4', function () {
            var nivel_3 = $("#storeNivel4 select[name='nivel_3']").val();
            var nombre = $("#storeNivel4 input[name='nombre']").val();
            var Id_TipoGestion = $("#storeNivel4 select[name='Id_TipoGestion']").val();
            var Ponderacion = $("#storeNivel4 input[name='Ponderacion']").val();
            var Peso = $("#storeNivel4 input[name='Peso']").val();
            $.ajax({
                type: "POST",
                url: "../includes/nivel/storeNivel4.php",
                async: false,
                dataType: "html",
                data: {
                    nivel_3: nivel_3,
                    nombre: nombre,
                    Id_TipoGestion: Id_TipoGestion,
                    Ponderacion: Ponderacion,
                    Peso: Peso,
                },
                success: function(data){
                    if(isJson(data)){
                        var response = JSON.parse(data);
                        if (response.status == 1) {    
                            $.niftyNoty({
                                type: 'success',
                                icon: 'fa fa-check',
                                message: 'Registro Guardado Exitosamente',
                                container: 'floating',
                                timer: 3000
                            });
            
                            var rowNode = Nivel3Table.row.add([
                                '' + response.array.nivel_1 + '',
                                '' + response.array.nivel_2 + '',
                                '' + response.array.nivel_3 + '',
                                '' + response.array.nombre + '',
                                '' + response.array.TipoContacto + '',
                                // '' + response.array.Ponderacion + '',
                                '' + response.array.Peso + '',
                                '' + '<i style="cursor: pointer; font-size:15px;" class="fa fa-pencil btn btn-purple btn-icon icon-lg UpdateNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Editar" title="" data-container="body"></i>' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivel3" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                            ]).draw(false).node();
            
                            $(rowNode)
                                .attr('id', response.array.id)
                                .data('id_nivel_2', response.array.id_nivel_2)
                                .data('id_nivel_3', response.array.id_nivel_3)
                                .data('Id_TipoGestion', response.array.Id_TipoGestion);
            
                            $('#storeNivelRapido').find('select[name="nivel_3"]').append($('<option>', {
                                value: response.array.id,
                                text: response.array.nombre
                            }));            
                            $('#storeNivel3')[0].reset();
                            $('.selectpicker').selectpicker('refresh')
                            $('[data-toggle="popover"]').popover();
                            $('.modal').modal('hide');            
                        } else if (response.status == 2) {            
                            $.niftyNoty({
                                type: 'danger',
                                icon: 'fa fa-check',
                                message: 'Debe llenar todos los campos',
                                container: 'floating',
                                timer: 3000
                            });            
                        } else {            
                            $.niftyNoty({
                                type: 'danger',
                                icon: 'fa fa-check',
                                message: 'Ocurrió un error en el Proceso',
                                container: 'floating',
                                timer: 3000
                            });            
                        }
                    }
                },
                error: function(response){
                    console.log(response);
                }
            });
    });
    
    $('body').on('click', '.UpdateNivel4', function () {    
        var ObjectMe = $(this)
        var ObjectTR = ObjectMe.closest("tr")
        ObjectTR.addClass("Selected")
        console.log(ObjectTR)
        var ObjectId = ObjectTR.attr("id")
        var ObjectLevel3 = ObjectTR.data("id_nivel_3")
        console.log('Level 3: ',ObjectLevel3)
        var ObjectName = ObjectTR.find("td").eq(2).text()
        var ObjectGestionType = ObjectTR.data("Id_TipoGestion")
        // var ObjectPonderacion = ObjectTR.find("td").eq(3).text();
        var ObjectPeso = ObjectTR.find("td").eq(4).text()
        $('#updateNivel4').find('input[name="id"]').val(ObjectId)
        $('#updateNivel4').find('select[name="nivel_3"]').val(ObjectLevel3)
        $('#updateNivel4').find('input[name="nombre"]').val(ObjectName)
        $('#updateNivel4').find('select[name="Id_TipoGestion"]').val(ObjectGestionType)
        // $('#updateNivel3').find('input[name="Ponderacion"]').val(ObjectPonderacion);
        $('#updateNivel4').find('input[name="Peso"]').val(ObjectPeso)
        $('.selectpicker').selectpicker('refresh')
        $('#Nivel4FormUpdate').modal('show') 
    })
    
    $('body').on('click', '#actualizarNivel4', function () {    
            $.postFormValues('../includes/nivel/updateNivel4.php', '#updateNivel4', function (response) {
                if (response.status == 1) {    
                    $.niftyNoty({
                        type: 'success',
                        icon: 'fa fa-check',
                        message: 'Registro Actualizado Exitosamente',
                        container: 'floating',
                        timer: 3000
                    });
    
                    $('#storeNivelRapido').find('#nivel_3 option[value="' + response.array.id + '"]').text(response.array.nombre)
                    $('.selectpicker').selectpicker('refresh')
    
                    ObjectTR = $("#" + response.array.id);
                    ObjectTR.data('id_nivel_3', response.array.id_nivel_3);
                    ObjectTR.data('Id_TipoGestion', response.array.Id_TipoGestion);
                    ObjectTR.find("td").eq(1).html(response.array.nivel_2);
                    ObjectTR.find("td").eq(2).html(response.array.nombre);
                    ObjectTR.find("td").eq(3).html(response.array.TipoContacto);
                    // ObjectTR.find("td").eq(3).html(response.array.Ponderacion);
                    ObjectTR.find("td").eq(4).html(response.array.Peso);    
                    $('.modal').modal('hide');    
                } else if (response.status == 2) {
                    $.niftyNoty({
                        type: 'danger',
                        icon: 'fa fa-check',
                        message: 'Debe llenar todos los campos',
                        container: 'floating',
                        timer: 3000
                    });
                } else {
                    $.niftyNoty({
                        type: 'danger',
                        icon: 'fa fa-check',
                        message: 'Ocurrió un error en el Proceso',
                        container: 'floating',
                        timer: 3000
                    });
                }
            });
    });
    
    $('body').on('click', '.RemoveNivel4', function () {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectId = ObjectTR.attr("id");
    
        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    $.ajax({
                        url: "../includes/nivel/deleteNivel4.php",
                        type: 'POST',
                        data: "&id=" + ObjectId,
                        success: function (response) {
                            setTimeout(function () {
                                if (response.status == 1) {
                                    bootbox.alert("El registro ha sido eliminado!");
                                    $('#storeNivelRapido').find('#nivel_3 option[value="' + ObjectId + '"]').remove();
                                    $('.selectpicker').selectpicker('refresh')
                                    Nivel4Table.row($(ObjectTR)).remove().draw();
                                } else if (response.status == 3) {
                                    bootbox.alert("Este registro no puede ser eliminado porque posee otros registros asociados");
                                } else {
                                    bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                                }
                            }, 1000);
                        },
                        error: function () {
                            bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                        }
                    });
                }
            }
        });
    });

    //NIVEL RAPIDO

    NivelRapidoTable = $('#NivelRapidoTable').DataTable({
        iDisplayLength: 100,
        pageLength: 25,
        lengthChange: false,
        processing: true,
        serverSide: false,
        bInfo: false,
        order: [[0, 'asc']],
        language: {
            processing: "Procesando ...",
            search: 'Buscar',
            lengthMenu: "Mostrar _MENU_ Registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty: "Mostrando 0 a 0 de 0 Registros",
            infoFiltered: "(filtrada de _MAX_ registros en total)",
            infoPostFix: "",
            loadingRecords: "...",
            zeroRecords: "No se encontraron registros coincidentes",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": habilitado para ordenar la columna en orden ascendente",
                sortDescending: ": habilitado para ordenar la columna en orden descendente"
            }
        }
    });

    function showNivelRapido(){
        $.ajax({
            type: "POST",
            url: "../includes/nivel/showNivelRapido.php",
            success: function (response) {

                $.each(response.array, function (index, array) {
                    var rowNode = NivelRapidoTable.row.add([
                        '' + array.nivel_3 + '',
                        '' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivelRapido" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                    ]).draw(false).node();

                    $(rowNode)
                        .attr('id', array.id)
                        .data('id_nivel_3', array.id_nivel_3);

                    if ($.inArray(array.id_nivel_3, NivelRapidoExistArray) == -1) {
                        NivelRapidoExistArray.push(array.id_nivel_3)
                    }
                });

                $('[data-toggle="popover"]').popover();
            }
        });
    }

    $('body').on('click', '#guardarNivelRapido', function () {
        $.postFormValues('../includes/nivel/storeNivelRapido.php', '#storeNivelRapido', function (response) {

            if (response.status == 1) {

                $.niftyNoty({
                    type: 'success',
                    icon: 'fa fa-check',
                    message: 'Registro Guardado Exitosamente',
                    container: 'floating',
                    timer: 3000
                });

                var rowNode = NivelRapidoTable.row.add([
                    '' + response.array.nivel_3 + '',
                    '' + ' <i style="cursor: pointer; font-size:15px;" class="btn fa fa-trash btn-danger btn-icon icon-lg RemoveNivelRapido" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Eliminar" title="" data-container="body"></i>' + '',
                ]).draw(false).node();

                $(rowNode)
                    .attr('id', response.array.id)
                    .data('id_nivel_3', response.array.id_nivel_3);

                $('#storeNivelRapido')[0].reset();
                $('[data-toggle="popover"]').popover();
                $('#storeNivelRapido').find('#nivel_3 option[value="' + response.array.id_nivel_3 + '"]').remove();
                $('.selectpicker').selectpicker('refresh')
                $('.modal').modal('hide');

            } else if (response.status == 2) {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Debe llenar todos los campos',
                    container: 'floating',
                    timer: 3000
                });

            } else {

                $.niftyNoty({
                    type: 'danger',
                    icon: 'fa fa-check',
                    message: 'Ocurrió un error en el Proceso',
                    container: 'floating',
                    timer: 3000
                });

            }
        });
    });

    $('body').on('click', '.RemoveNivelRapido', function () {

        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectId = ObjectTR.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar este registro?",
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
                    $.ajax({
                        url: "../includes/nivel/deleteNivelRapido.php",
                        type: 'POST',
                        data: "&id=" + ObjectId,
                        success: function (response) {
                            setTimeout(function () {
                                if (response.status == 1) {
                                    $('#storeNivelRapido').find('select[name="nivel_3"]').append($('<option>', {
                                        value: response.array.id,
                                        text: response.array.nombre
                                    }));
                                    $('.selectpicker').selectpicker('refresh')
                                    bootbox.alert("El registro ha sido eliminado!");
                                    NivelRapidoTable.row($(ObjectTR))
                                        .remove()
                                        .draw();
                                } else if (response.status == 3) {
                                    bootbox.alert("Este registro no puede ser eliminado porque posee otros registros asociados");
                                } else {
                                    bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                                }
                            }, 1000);
                        },
                        error: function () {
                            bootbox.alert("Ha ocurrido un error, intente nuevamente por favor");
                        }
                    });
                }
            }
        });
    });
});
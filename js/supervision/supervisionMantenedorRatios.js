$(document).ready(function(){

    var PenetracionTable;
    var ContactabilidadTable;
    var MuestraDataTable;

    fillTipoContactoPenetracion();
    fillTipoContactoContactabilidad();
    fillRatios();
    actualizarMuestraDataTable();

    function fillTipoContactoPenetracion(){
        var post = "ratio=Penetracion";
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillTipoContacto.php",
            data: post,
            dataType: "html",
            success: function(data){
                $("select[name='Penetracion']").html(data);
                $("select[name='Penetracion']").selectpicker('refresh');

                actualizarPenetracionDataTable();
            },
            error: function(){
            }
        });
    }

    function fillTipoContactoContactabilidad(){
        var post = "ratio=Contactabilidad";
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillTipoContacto.php",
            data: post,
            dataType: "html",
            success: function(data){
                $("select[name='Contactabilidad']").html(data);
                $("select[name='Contactabilidad']").selectpicker('refresh');

                actualizarContactabilidadDataTable();
            },
            error: function(){
            }
        });
    }

    function fillRatios(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/fillRatios.php",
            data: {},
            dataType: "html",
            success: function(data){
                $("select[name='Ratios']").html(data);
                $("select[name='Ratios']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }

    $(document).on('click', '#guardar_penetracion', function(){
        var tipoContactos = $("select[name='Penetracion']").val();
        var jsonString = JSON.stringify(tipoContactos);
        var rate = "1";

        $.ajax({
            type: "POST",
            url: "../includes/supervision/guardarRatioTipoContacto.php",
            data: { ratio : rate,
                    data : jsonString },
            dataType: "html",
            success: function(data){
                var dataSet = JSON.parse(data);
                console.log(dataSet);
                if(dataSet){
                    niftySuccess("Se agregó el tipo de contacto correctamente.");
                }else{
                    niftyDanger("Se presentó un error al tratar de guardar el tipo de contacto.");
                }
                fillTipoContactoPenetracion();
            },
            error: function(){
            }
        });
    });

    $(document).on('click', '#guardar_contactabilidad', function(){
        var tipoContactos = $("select[name='Contactabilidad']").val();
        var jsonString = JSON.stringify(tipoContactos);
        var rate = "2";

        console.log(jsonString);

        $.ajax({
            type: "POST",
            url: "../includes/supervision/guardarRatioTipoContacto.php",
            data: { ratio : rate,
                    data : jsonString },
            dataType: "html",
            success: function(data){
                var dataSet = JSON.parse(data);
                console.log(dataSet);
                if(dataSet){
                    niftySuccess("Se agregó el tipo de contacto correctamente.");
                }else{
                    niftyDanger("Se presentó un error al tratar de guardar el tipo de contacto.");
                }
                fillTipoContactoContactabilidad();
            },
            error: function(){
            }
        });
    });

    $(document).on('click', '#guardar_datos_muestra', function(){
        var time = $("select[name='tiempoAct']").val();
        var sample = $("#Muestra").val();
        var ratios = $("select[name='Ratios']").val();
        var jsonRates = JSON.stringify(ratios);

        console.log(jsonRates);

        $.ajax({
            type: "POST",
            url: "../includes/supervision/guardarMuestra.php",
            data: {tiempo : time,
                   muestra : sample,
                   ratios : jsonRates},
            dataType: "html",
            success: function(data){
                var dataSet = JSON.parse(data);
                console.log(dataSet);
                if(dataSet){
                    niftySuccess("Se guardó correctamente la nueva configuración.");
                }else{
                    niftyDanger("Se presentó un error al tratar de guardar la configuración.");
                }
                actualizarMuestraDataTable();
            },
            error: function(){
            }
        });
    });

    function actualizarPenetracionDataTable(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getRatioTipoContacto.php",
            data: {ratio : "1"},
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#PenetracionDataTable' ) ) {
                    PenetracionTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var dataSet = JSON.parse(data);
                    PenetracionTable = $('#PenetracionDataTable').DataTable({
                        data: dataSet,
                        bInfo: false,
                        columns: [
                            { data: 'Nombre', width: "30%" },
                            { data: '' }
                        ],
                        "columnDefs": [ 
                            {
                                className: "dt-center",
                                "targets": 0
                            },
                            {
                                className: "dt-center",
                                "targets": 1,
                                "render": function( data, type, row ) {
                                    return "<button class='fa fa-trash btn-danger btn btn-icon icon-lg deletePen' id='"+row.Id_TipoContacto+"'></button>";
                                }
                            }
                        ]
                    });
                }
            }
        });
    }

    function actualizarContactabilidadDataTable(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getRatioTipoContacto.php",
            data: {ratio : "2"},
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#ContactabilidadDataTable' ) ) {
                    ContactabilidadTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    ContactabilidadTable = $('#ContactabilidadDataTable').DataTable({
                            data: dataSet,
                            bInfo: false,
                            columns: [
                                { data: 'Nombre', width: "30%" },
                                { data: '' }
                            ],
                            "columnDefs": [ 
                                {
                                    className: "dt-center",
                                    "targets": 0
                                },
                                {
                                    className: "dt-center",
                                    "targets": 1,
                                    "render": function( data, type, row ) {
                                        return "<button class='fa fa-trash btn-danger btn btn-icon icon-lg deleteCon' id='"+row.Id_TipoContacto+"'></button>";
                                    }
                                }
                            ]
                        });
                }
            },
            error: function(){
            }
        });
    }

    function actualizarMuestraDataTable(){
        $.ajax({
            type: "POST",
            url: "../includes/supervision/getMuestraRatios.php",
            data: {},
            dataType: "html",
            beforeSend: function(){
                if ( $.fn.dataTable.isDataTable( '#MuestraDataTable' ) ) {
                    MuestraDataTable.destroy();
                }
            },
            success: function(data){
                console.log(data);
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var dataSet = JSON.parse(data);

                    MuestraDataTable = $('#MuestraDataTable').DataTable({
                            data: dataSet,
                            bInfo: false,
                            columns: [
                                { data: 'ratio' },
                                { data: 'muestra' },
                                { data: 'tiempo_act' }
                            ],
                            "columnDefs": [ 
                                {
                                    className: "dt-center",
                                    "targets": [0,1,2],
                                }
                            ]
                        });
                }
            },
            error: function(){
            }
        });
    }

    $("body").on("click",".deletePen",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var id = ObjectMe.attr("id");
        var tipoContacto = "tipoContacto="+id+"&ratio=Penetracion";
        bootbox.confirm({
            message: "¿Esta seguro de eliminar el registro seleccionado?",
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
                        type: "POST",
                        url: "../includes/supervision/deleteTipoContacto.php",
                        data: tipoContacto,
                        dataType: "html",
                        success: function (data) {
                            console.log(data);
                            var dataSet = JSON.parse(data);
                            console.log(dataSet);
                            if (dataSet) {
                                niftySuccess("Se eliminó con éxito el tipo de contacto.");
                            } else {
                                niftyDanger("Se presentó un error al tratar de eliminar el tipo de contacto.");
                            }
                            fillTipoContactoPenetracion();
                        },
                        error: function (data) {
                            console.log("Error: " + data);
                        }
                    });
                }
            }
        });
    });

    $("body").on("click",".deleteCon",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var id = ObjectMe.attr("id");

        var tipoContacto = "tipoContacto="+id+"&ratio=Contactabilidad";

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el registro seleccionado?",
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
                        type: "POST",
                        url: "../includes/supervision/deleteTipoContacto.php",
                        data: tipoContacto,
                        dataType: "html",
                        success: function(data){
                            var dataSet = JSON.parse(data);
                            console.log(dataSet);
                            if(dataSet){
                                niftySuccess("Se eliminó con éxito el tipo de contacto.");
                            }else{
                                niftyDanger("Se presentó un error al tratar de eliminar el tipo de contacto.");
                            }
                            fillTipoContactoContactabilidad();
                        },
                        error: function(data){
                            console.log("Error: " + data);
                        }
                    });
                }
            }
        });
    });

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
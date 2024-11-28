$(document).ready(function(){
    var PalabrasArray = [];
    var PalabrasTable;
    var SinonimosArray = [];
    var SinonimosTable;

    var idPalabraGlobal;

    getPalabrasTableList();
    updatePalabrasTable();

    $("body").on("click","#CrearPalabra",function(){
        var Template = $("#CrearPalabraTemplate").html();
        bootbox.dialog({
            title: "NUEVA PALABRA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var NombreMetrica = $("input[name='NombreMetrica']").val();
                        var Grupo = $("input[name='Grupo']").val();
                        var ValorMetrica = $("input[name='ValorMetrica']").val();
                        var PesoGrupo = $("input[name='PesoGrupo']").val();
                        var Veces = $("input[name='Veces']").val();
                        var CanAdd = false;
                        if(NombreMetrica != ""){
                            if((Grupo != "") && (Grupo > 0)){
                                if((ValorMetrica != "") && (ValorMetrica > 0)){
                                    if((PesoGrupo != "") && (PesoGrupo > 0)){
                                        if((Veces != "") && (Veces > 0)){
                                            if(CanAddPesoGrupo(Grupo,PesoGrupo)){
                                                CanAdd = true;
                                            }else{
                                                bootbox.alert("La sumatoria del peso del grupo no debe exceder 100% por Grupo");
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar la cantidad de veces que puede aparecer la palabra");
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un porcentaje de peso del grupo");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un valor de palabra");
                                }
                            }else{
                                bootbox.alert("Debe ingresar un grupo");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un nombre para la palabra");
                        }
                        if(CanAdd){
                            SavePalabra();
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
    });
    $("body").on("click",".Sinonimos",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPalabra = ObjectDiv.attr("id");
        var Template = $("#SinonimosTemplate").html();
        
        idPalabraGlobal = idPalabra

        bootbox.dialog({
            title: "SINONIMOS",
            message: Template,
            buttons: {
                close: {
                    label: "Cerrar",
                    callback: function(){
                        
                    }
                }
            }
        }).off("shown.bs.modal");
        getSinonimosTableList(idPalabra);
        updateSinonimosTable();
    });
    $("body").on("click","#CrearSinonimo",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPalabra = ObjectDiv.attr("id");
        var Template = $("#CrearSinonimoTemplate").html();
        bootbox.dialog({
            title: "NUEVO SINONIMO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var Sinonimo = $("input[name='Nombre']").val();
                        var CanAdd = false;
                        if(Sinonimo != ""){
                            CanAdd = true;
                        }else{
                            bootbox.alert("Debe ingresar un nombre para la palabra");
                        }
                        if(CanAdd){
                            SaveSinonimo();
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPalabra = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar la palabra seleccionada? al aceptar se eliminaran todos los sinonimos que estan registrados dentro de la misma.",
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
                if(result){
                    deletePalabra(idPalabra);
                }
            }
        });
    });
    $("body").on("click",".DeleteSinonimo",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idSinonimo = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar el sinonimo seleccionado?",
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
                if(result){
                    deleteSinonimo(idSinonimo);
                }
            }
        });
    });
    $("body").on("click",".Update",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPalabra = ObjectDiv.attr("id");

        var Palabra = getPalabra(idPalabra);

        var Template = $("#CrearPalabraTemplate").html();
        bootbox.dialog({
            title: "MODIFICAR PALABRA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function() {
                        var NombreMetrica = $("input[name='NombreMetrica']").val();
                        var Grupo = $("input[name='Grupo']").val();
                        var ValorMetrica = $("input[name='ValorMetrica']").val();
                        var PesoGrupo = $("input[name='PesoGrupo']").val();
                        var Veces = $("input[name='Veces']").val();
                        var CanUpdate = false;
                        if(NombreMetrica != ""){
                            if((Grupo != "") && (Grupo > 0)){
                                if((ValorMetrica != "") && (ValorMetrica > 0)){
                                    if((PesoGrupo != "") && (PesoGrupo > 0)){
                                        if((Veces != "") && (Veces > 0)){
                                            if(CanUpdatePesoGrupo(Grupo,PesoGrupo,Palabra.PesoGrupo)){
                                                CanUpdate = true;
                                            }else{
                                                bootbox.alert("La sumatoria del peso del grupo no debe exceder 100% por Grupo");
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar la cantidad de veces que puede aparecer la palabra");
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un porcentaje de peso del grupo");
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un valor de palabra");
                                }
                            }else{
                                bootbox.alert("Debe ingresar un grupo");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un nombre para la palabra");
                        }
                        if(CanUpdate){
                            UpdatePalabra(idPalabra);
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $("input[name='NombreMetrica']").val(Palabra.NombreMetrica);
        $("input[name='Grupo']").val(Palabra.Grupo);
        $("input[name='ValorMetrica']").val(Palabra.ValorMetrica);
        $("input[name='PesoGrupo']").val(Palabra.PesoGrupo);
        $("input[name='Veces']").val(Palabra.Veces);
    });
    $("body").on("click",".UpdateSinonimo",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idSinonimo = ObjectDiv.attr("id");

        var Sinonimo = getSinonimo(idSinonimo);

        var Template = $("#CrearSinonimoTemplate").html();
        bootbox.dialog({
            title: "MODIFICAR SINONIMO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Modificar",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var CanUpdate = false;
                        if(Nombre != ""){
                            CanUpdate = true;
                        }else{
                            bootbox.alert("Debe ingresar un nombre para el sinonimo");
                        }
                        if(CanUpdate){
                            UpdateSinonimo(idSinonimo);
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $("input[name='Nombre']").val(Sinonimo.Nombre);
    });

    function getPalabrasTableList(Modal = true){
        $.ajax({
            type: "POST",
            url: "../includes/speech/getPalabrasTableList.php",
            dataType: "html",
            data: {  
            },
            async: false,
            beforeSend: function(){
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                
                PalabrasArray = [];
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    PalabrasArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updatePalabrasTable(){
        PalabrasTable = $('#ListaPalabras').DataTable({
            data: PalabrasArray,
            "bDestroy": true,
            columns: [
                { data: 'NombreMetrica' },
                { data: 'Grupo' },
                { data: 'ValorMetrica' },
                { data: 'PesoGrupo' },
                { data: 'Veces' },
                { data: 'CantSinonimos' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return data+"%";
                    }
                },
                {
                    "targets": 6,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id=" + data +"><i style='cursor: pointer; margin: 0 5px;' class='fa fa-file-text-o btn btn-purple btn-icon icon-lg Sinonimos'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg Update'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg Delete'></i></div>";
                    }
                },
            ]
        });
    }
    function SavePalabra(){
        var NombreMetrica = $("input[name='NombreMetrica']").val();
        var Grupo = $("input[name='Grupo']").val();
        var ValorMetrica = $("input[name='ValorMetrica']").val();
        var PesoGrupo = $("input[name='PesoGrupo']").val();
        var Veces = $("input[name='Veces']").val();

        $.ajax({
            type: "POST",
            url: "../includes/speech/addPalabra.php",
            dataType: "html",
            data: {
                NombreMetrica: NombreMetrica,
                Grupo: Grupo,
                ValorMetrica: ValorMetrica,
                PesoGrupo: PesoGrupo,
                Veces: Veces
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getPalabrasTableList(false);
                    updatePalabrasTable();
                }
            },
            error: function(){
            }
        });
    }
    function CanAddPesoGrupo(Grupo,Peso){
        var ToReturn = false;
        var Cont = Number(Peso);
        PalabrasTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.Grupo == Grupo){
                Cont += Number(data.PesoGrupo);
            }
        });
        if(Cont <= 100){
            ToReturn = true;
        }
        return ToReturn;
    }
    function getSinonimosTableList(idPalabra){
        $.ajax({
            type: "POST",
            url: "../includes/speech/getSinonimosTableList.php",
            dataType: "html",
            data: { 
                idPalabra: idPalabra
            },
            async: false,
            beforeSend: function(){
                SinonimosArray = [];
            },
            success: function(data){
                if(isJson(data)){
                    SinonimosArray = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function updateSinonimosTable(){
        SinonimosTable = $('#ListaSinonimos').DataTable({
            data: SinonimosArray,
            "bDestroy": true,
            columns: [
                { data: 'Sinonimo' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "Accion",
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id="+data+"><i style='cursor: pointer; margin: 0 5px;' class='fa fa-times-circle icon-lg DeleteSinonimo'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil UpdateSinonimo'></i></div>";
                    }
                },
            ]
        });
    }
    function SaveSinonimo(){
        var Nombre = $("input[name='Nombre']").val();

        $.ajax({
            type: "POST",
            url: "../includes/speech/addSinonimo.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                idPalabra: idPalabraGlobal
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getSinonimosTableList(idPalabraGlobal);
                    updateSinonimosTable();
                    getPalabrasTableList(false);
                    updatePalabrasTable();
                }
            },
            error: function(){
            }
        });
    }
    function deletePalabra(idPalabra){
        $.ajax({
            type: "POST",
            url: "../includes/speech/deletePalabra.php",
            dataType: "html",
            data: {
                idPalabra: idPalabra
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getPalabrasTableList(false);
                    updatePalabrasTable();
                }
            },
            error: function(){
            }
        });
    }
    function deleteSinonimo(idSinonimo){
        $.ajax({
            type: "POST",
            url: "../includes/speech/deleteSinonimo.php",
            dataType: "html",
            data: {
                idSinonimo: idSinonimo
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getSinonimosTableList(idPalabraGlobal);
                    updateSinonimosTable();
                }
            },
            error: function(){
            }
        });
    }
    function getPalabra(idPalabra){
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/speech/getPalabra.php",
            dataType: "html",
            data: {
                idPalabra: idPalabra
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function CanUpdatePesoGrupo(Grupo,Peso,PesoActual){
        var ToReturn = false;
        var Cont = Number(Peso);
        PalabrasTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.Grupo == Grupo){
                Cont += Number(data.PesoGrupo);
            }
        });
        Cont -= Number(PesoActual);
        if(Cont <= 100){
            ToReturn = true;
        }
        return ToReturn;
    }
    function UpdatePalabra(idPalabra){
        var NombreMetrica = $("input[name='NombreMetrica']").val();
        var Grupo = $("input[name='Grupo']").val();
        var ValorMetrica = $("input[name='ValorMetrica']").val();
        var PesoGrupo = $("input[name='PesoGrupo']").val();
        var Veces = $("input[name='Veces']").val();

        $.ajax({
            type: "POST",
            url: "../includes/speech/updatePalabra.php",
            dataType: "html",
            data: {
                NombreMetrica: NombreMetrica,
                Grupo: Grupo,
                ValorMetrica: ValorMetrica,
                PesoGrupo: PesoGrupo,
                Veces: Veces,
                idPalabra: idPalabra
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getPalabrasTableList(false);
                    updatePalabrasTable();
                }
            },
            error: function(){
            }
        });
    }
    function getSinonimo(idSinonimo){
        var ToReturn;
        $.ajax({
            type: "POST",
            url: "../includes/speech/getSinonimo.php",
            dataType: "html",
            data: {
                idSinonimo: idSinonimo
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateSinonimo(idSinonimo){
        var Nombre = $("input[name='Nombre']").val();

        $.ajax({
            type: "POST",
            url: "../includes/speech/updateSinonimo.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                idSinonimo: idSinonimo
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    getSinonimosTableList(idPalabraGlobal);
                    updateSinonimosTable();
                }
            },
            error: function(){
            }
        });
    }
});
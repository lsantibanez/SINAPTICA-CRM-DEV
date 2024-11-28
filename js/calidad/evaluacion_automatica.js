$(document).ready(function(){

    //$('#tablaUsuarios').DataTable({});
    
    var ArraySelectedOptions = [];
    var ErrorCritico = 0;
    var EvaluationTable;
    var idGrabaciones = [];
    

        var numeroSemana;
        var nombreEjecutivo;
        var nombreCompletoEjecutivo;
        var fechaInicio;
        var fechaFin;
        var confTipoContacto;
        var RecordId;
        var cantEvaluaciones = 1;
        var Ejecutivos;
        var EjecutivoActual = "";


    getMesCalendarioEvaluacionesAutomaticas("","",false);
    getTiposContactos(false);

    $("body").on("click",".iniciarEvaluaciones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var Ejecutivo = ObjectDiv.data("id");
        var NombreCompletoEjecutivo = ObjectDiv.data("nombre");
        var ConfTipoContacto = $("select[name='TipoContacto']").val();
        idGrabaciones = [];
        if(Ejecutivo != ""){
            if(ConfTipoContacto != ""){
                var ObjectSemana = $(".Semanas .Enabled");
                    
                    numeroSemana = ObjectSemana.data("semana");
                    nombreEjecutivo = Ejecutivo;
                    confTipoContacto = ConfTipoContacto;
                    fechaInicio = ObjectSemana.data("fechainicio");
                    fechaFin = ObjectSemana.data("fechafin");
                    nombreCompletoEjecutivo = NombreCompletoEjecutivo;
                    cantEvaluaciones = 1;
                    EjecutivoActual = Ejecutivo;

                getRecord();
            }else{
                bootbox.alert("Debe seleccionar un Tipo de Contacto");
            }
        }else{
            bootbox.alert("Debe seleccionar un ejecutivo");
        }
    });
    $("body").on("click",".Semanas .Semana",function(){
        var ObjectMe = $(this);
        $(".Semanas .Semana").removeClass("Enabled");
        $(".Semanas .Semana").addClass("Disabled");
        ObjectMe.removeClass("Disabled");
        ObjectMe.addClass("Enabled");


        var FechaInicio = ObjectMe.data("fechainicio");
        var FechaFin = ObjectMe.data("fechafin");
        var DiasSemana = ObjectMe.data("diassemana");
        console.log(DiasSemana);

        $("select[name='DiasSemana']").html("");
        $.each(DiasSemana, function(i, Dia) {
            $("select[name='DiasSemana']").append("<option value='"+Dia+"' selected>"+Dia+"</option>");
        })
        $("select[name='DiasSemana']").selectpicker("refresh");

        getUsuariosSemana(FechaInicio,FechaFin,true);
    });
    $('body').on( 'click', '.AddAfirmaciones', function () {
        console.log(ArraySelectedOptions);
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectNameText = ObjectTR.find(".NameObject");
        var Template = $("#EvaluationFormObservation").html();

        var Row = EvaluationTable.row( ObjectTR ).data();
        var ID = Row.ID;
        var Ponderacion = Number(Row.Ponderacion);

        bootbox.dialog({
            title: "Formulario de Observación de la competencia: " + ObjectNameText.html(),
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        getEvaluationData();
                        AddClassModalOpen();
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            },
            size: "large"
        });
        var ObjectPreguntas = $(".PregunatasEvaluaciones .Preguntas");
        var ArrayDimensiones = selectAfirmaciones(ID);
        var Dimensiones = ArrayDimensiones.Dimensiones;
        var NotaMaxima = ArrayDimensiones.NotaMaxima;
        ObjectPreguntas.attr("notamaxima",NotaMaxima);
        ObjectPreguntas.attr("ponderacion",Ponderacion);
        ObjectPreguntas.attr("id",ID);
        if(ArraySelectedOptions[ID] == undefined){
            ArraySelectedOptions[ID] = [];
        }
        var Cont = 0;
        $.each(Dimensiones,function(key,Dimension){
            var Div = "<div id='D_"+Dimension.idDimension+"' class='Dimensiones' ponderacion='"+Dimension.Ponderacion+"'></div>";
            ObjectPreguntas.append(Div);
            var Preguntas = Dimension.Preguntas[0];
            $.each(Preguntas,function(key,Pregunta){
                var Color = "#FFFFFF";
                if(Cont % 2 == 0){
                    Color = "#EEEEEE";
                }
                var idAfirmacion = Pregunta.idAfirmacion;
                var Afirmacion = Pregunta.Afirmacion;
                var Ponderacion = Pregunta.Ponderacion;
                var ValueSelected = ArraySelectedOptions[ID][Cont];
                var Div = "<div class='Pregunta Afirmaciones' id='A_"+idAfirmacion+"' ponderacion='"+Ponderacion+"' style='overflow: hidden; overflow: hidden;padding: 10px 5px;background-color: "+Color+";'>"+
                                "<div class='Texto' style='width: 40%;float: left;'>"+Afirmacion+"</div>"+
                                "<div class='Opciones' style='float: left;width: 60%;display: inline-flex;'></div>"+
                            "</div>";
                ObjectPreguntas.find("#D_"+Dimension.idDimension).append(Div);
                $.each(Pregunta.Opciones,function(key,Opcion){
                    var idOpcion = Opcion.idOpcion;
                    var OpcionTxt = Opcion.Opcion;
                    var Valor = Opcion.Valor;
                    var Active = "";
                    var Checked = "";
                    if(ValueSelected != undefined){
                        var ArraySelected = ValueSelected.split("|");
                        if(ArraySelected[2] != -1){
                            if(Number(Valor) == Number(ArraySelected[2])){
                                Active = " active ";
                                Checked = " checked='' ";
                            }
                        }
                    }
                    Div = "<div class='Opcion' style='width: calc(100% / 5);float: left;'>"+
                                    "<label class='form-radio form-icon form-text "+Active+"' style='height: 100%;'><input "+Checked+" name='"+idAfirmacion+"' id='O_"+idOpcion+"' value='"+Valor+"' type='radio'> "+OpcionTxt+"</label>"+
                                "</div>";
                    ObjectPreguntas.find("#D_"+Dimension.idDimension).find("#A_"+idAfirmacion+" .Opciones").append(Div);
                });
                Cont++;
            });
        });
    });
    $("body").on("update","#Evaluations",function(){
        UpdateEvaluationSummaryFoot();
    });
    $("body").on("change","#ErrorCritico",function(){
        if($(this).is(":checked")){
           setErrorCritico(RecordId); 
           $("#EvaluationsTableContainer").hide();
           $("#ContainerObservacionErrorCritico").show();
           ErrorCritico = 1;
        }else{
            ArraySelectedOptions  = [];
            $("#EvaluationsTableContainer").show();
            $("#ContainerObservacionErrorCritico").hide();
            EvaluationTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                data.Nota = Number(0).toFixed(2);
                this.data(data);
            });
            EvaluationTable.draw();
            $("#Evaluations").trigger('update');
            ErrorCritico = 0;
        }
    });
    $("body").on("click",".Meses .MesLeft",function(){
        var ObjectMe = $(this);
        var ObjectMeses = ObjectMe.closest(".Meses");
        var Fecha = ObjectMeses.data("fecha");
        getMesCalendarioEvaluacionesAutomaticas("-1",Fecha,false);
        //getSemanasMes(Fecha);
    });
    $("body").on("click",".Meses .MesRight",function(){
        var ObjectMe = $(this);
        var ObjectMeses = ObjectMe.closest(".Meses");
        var Fecha = ObjectMeses.data("fecha");
        getMesCalendarioEvaluacionesAutomaticas("1",Fecha,false);
        //getSemanasMes(Fecha);
    });

    function getSemanasMes(Fecha){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getSemanasMesEvaluacionesAutomaticas.php",
            data: { 
                Fecha: Fecha
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $(".Semanas").html("");
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    data = JSON.parse(data);
                    var Cont = 1;
                    var FechaInicio = "";
                    var FechaFin = "";
                    var DiasSemana = "";
                    $.each(data, function(i, item) {
                        var Enabled = "";
                        switch(item.Actual){
                            case true:
                                Enabled = "Enabled";
                            break;
                            case false:
                                Enabled = "Disabled";
                            break;
                        }
                        var Template = $("#semanaTemplate").html();
                        Template = Template.replace("{NUMERO_SEMANA}",Cont);
                        Template = Template.replace("{NUMERO_SEMANA}",Cont);
                        Template = Template.replace("{NUMERO_SEMANA}",Cont);
                        Template = Template.replace("{FECHA_INICIO}",item.FechaInicio);
                        Template = Template.replace("{FECHA_FIN}",item.FechaFin);
                        Template = Template.replace("{HABILITADO}",Enabled);
                        Template = Template.replace("{DIAS_SEMANA}",item.DiasSemana);

                        $(".Semanas").append(Template);

                        if(item.Actual){
                            FechaInicio = item.FechaInicio;
                            FechaFin = item.FechaFin;
                            DiasSemana = item.DiasSemana;
                        }

                        $(".Semanas .Semana:last-child").data("semana",Cont);
                        $(".Semanas .Semana:last-child").data("fechainicio",item.FechaInicio);
                        $(".Semanas .Semana:last-child").data("fechafin",item.FechaFin);
                        $(".Semanas .Semana:last-child").data("diassemana",item.DiasSemana);

                        Cont++;
                    });

                    $("select[name='DiasSemana']").html("");
                    $.each(DiasSemana, function(i, Dia) {
                        $("select[name='DiasSemana']").append("<option value='"+Dia+"' selected>"+Dia+"</option>");
                    })
                    $("select[name='DiasSemana']").selectpicker("refresh");
                    getUsuariosSemana(FechaInicio,FechaFin,true);
                }
            },
            error: function(){
            }
        });
    }
    function getUsuariosSemana(FechaInicio,FechaFin,Modal){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getUsuariosSemanaEvaluacionesAutomaticas.php",
            data: { 
                FechaInicio: FechaInicio,
                FechaFin: FechaFin
            },
            beforeSend: function() {
                if(Modal){
                    deleteModalBackdrop();
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(data){
                $('#Cargando').modal('hide');
                deleteModalBackdrop();
                /*$("select[name='Ejecutivo']").html(data);
                $("select[name='Ejecutivo']").selectpicker("refresh");*/
                if(isJson(data)){
                    data = JSON.parse(data);
                    $('#tablaUsuarios thead tr').html("");
                    $.each(data.Header, function(i, item) {
                        console.log(item);
                        $('#tablaUsuarios thead tr').append("<th>"+item+"</th>");
                    });
                    Ejecutivos = data.Data;
                    console.log(Ejecutivos);
                    var Table = $('#tablaUsuarios').DataTable({
                        data: data.Data,
                            bDestroy: true,
                            columns: data.Columns,
                            "columnDefs": [ 
                                {
                                    "targets": (data.AccionIndex - 4),
                                    "render": function( data, type, row ) {
                                        var ToReturn = "";
                                        if(typeof data === 'undefined'){
                                            ToReturn = "0";
                                        }else{
                                            ToReturn = data;
                                        }
                                        return ToReturn;
                                    }
                                },
                                {
                                    "targets": (data.AccionIndex - 3),
                                    "render": function( data, type, row ) {
                                        var ToReturn = "";
                                        if(typeof data === 'undefined'){
                                            ToReturn = "0";
                                        }else{
                                            ToReturn = data;
                                        }
                                        return ToReturn;
                                    }
                                },
                                {
                                    "targets": data.AccionIndex,
                                    "render": function( data, type, row ) {
                                        var ToReturn = "";
                                        ToReturn = "<div style='text-align: center;' data-id='"+data+"' data-nombre='"+row.nombreEjecutivo+"'>"+
                                                        "<button class='btn btn-primary iniciarEvaluaciones'>Iniciar</button>"+
                                                    "</div>";
                                        return ToReturn;
                                    }
                                }
                            ],
                            "createdRow": function( row, cdata, dataIndex){
                                if( cdata["Total"] == 0  ){
                                    $(row).css('background-color', '#F39B9B');
                                    $(row).css('color', '#FFFFFF');
                                }
                            }
                    });
                    Table.order([data.AccionIndex - 1, 'asc']).draw();
                }
                
            },
            error: function(){
            }
        });
    }
    function getTiposContactos(Modal){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipoContactoEvaluacionesAutomaticas.php",
            data: { },
            beforeSend: function() {
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(data){
                if(Modal){
                   $('#Cargando').modal('hide'); 
                }
                if(isJson(data)){
                    data = JSON.parse(data);
                    var TipoContacto = "";
                    $.each(data, function(i, item) {
                        TipoContacto += "<option value='"+item.Accion+"'>"+item.TipoContacto+"</option>";
                    });
                    $("select[name='TipoContacto']").html(TipoContacto);
                    $("select[name='TipoContacto']").selectpicker("refresh");
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function getRecord(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getRecordEvaluacionesAutomaticas.php",
            data: { 
                NombreEjecutivo: nombreEjecutivo,
                ConfTipoContacto: confTipoContacto,
                idGrabaciones: idGrabaciones,
                Mes: $(".Meses").data("mes"),
                diasSemana: $("select[name='DiasSemana']").val()
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    data = JSON.parse(data);
                    console.log(data);
                    if(data.length > 0){
                        RecordId = data[0].id;
                        idGrabaciones.push(RecordId);
                        deleteModalBackdrop();
                        FormularioDeEvaluacion(data[0]);
                    }else{
                        bootbox.alert("No se encontraron grabaciones con los filtros aplicados.");
                    }
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function FormularioDeEvaluacion(Record){
        ArraySelectedOptions = [];
        var Template = $("#Calificacion").html();

        ErrorCritico = 0;

        var Audio = "";
        Audio = "<audio src='"+Record.Listen+"' preload='auto' controls></audio>";

        Template = Template.replace("{RECORD_AUDIO}",Audio);
        Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:block");
        
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetEvaluationTemplate.php",
            dataType: "html",
            data: {
                Ejecutivo: Record.User,
                idGrabacion: Record.id
            },
            async: false,
            beforeSend: function() {
                var Form = bootbox.dialog({
                    title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Record.nombreEjecutivo,
                    message: Template,
                    closeButton: false,
                    buttons: {
                        evaluar: {
                            label: "Evaluar",
                            className: "btn-success",
                            callback: function() {
                                var DoJob = false;
                                if(!GlobalData.isCalidadSystem){
                                    if(GlobalData.have360Evaluation){
                                        DoJob = true;
                                    }else{
                                        DoJob = false;
                                    }
                                }else{
                                    DoJob = true;
                                }
                                if(DoJob){
                                    if(HaveEvaluations()){
                                        var TableTmp = $("#Evaluations");
                                        var CantSelecteds = 0;
                                        $.each(ArraySelectedOptions,function(keyCompetencia,Competencia){
                                            if(typeof ArraySelectedOptions[keyCompetencia] != "undefined"){
                                                CantSelecteds++;
                                            }
                                        });
                                        var CantCompetencias = EvaluationTable.data().count();
                                        if(CantSelecteds == CantCompetencias){
                                            var CanSave = true;
                                            $.each(ArraySelectedOptions,function(keyCompetencia,Competencia){
                                                $.each(Competencia,function(keyOpcion,Opcion){
                                                    var ArrayOpcion = Opcion.split("|");
                                                    if(Number(ArrayOpcion[2]) == -1){
                                                        CanSave = false;
                                                    }
                                                });
                                            });
                                            if(ErrorCritico == 1){
                                                var ObservacionErrorCritivo = $("select[name='ObservacionErrorCritico']").val();
                                                if(ObservacionErrorCritivo == ""){
                                                    CanSave = false;
                                                    bootbox.alert("Debe seleccionar un error Critico");
                                                    return false;
                                                }
                                            }
                                            if(CanSave){
                                                bootbox.confirm({
                                                    message: "¿Esta seguro de guardar la evaluación?",
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
                                                            Form.modal("hide");
                                                            SaveEvaluation(TableTmp,Record.id);
                                                        }
                                                    }
                                                });
                                                return false;
                                            }else{
                                                bootbox.alert("Debe responder todas las opciones de las competencias.");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Responder todas las competencias.");
                                            return false;
                                        }
                                    }else{
                                        CustomAlert("Debe ingresar al menos una evaluación");
                                        return false;
                                    }
                                }
                            }
                        },
                        omitir: {
                            label: "Omitir",
                            className: "btn-warning",
                            callback: function() {
                                deleteModalBackdrop();
                                getRecord();
                            }
                        },
                        cerrar: {
                            label: "Cerrar",
                            className: "btn-danger",
                            callback: function() {
                                deleteModalBackdrop();
                                getCantidadEjecutivosSinEvaluaciones();
                            }
                        }
                    },
                    size: 'large'
                }).off("shown.bs.modal");
            },
            success: function(data){
                if(isJson(data)){
                    EvaluationsArray = JSON.parse(data);
                    if(EvaluationsArray.length > 0){
                        setTimeout(function(){
                            $("#NombreEjecutivoLabel").val(nombreCompletoEjecutivo);
                            $("#TipoContactoLabel").val($("select[name='TipoContacto'] option:selected").text());
                            $("#NombreGrabacionLabel").val(Record.Filename);
                            $("#NombreSupervisorLabel").val(Record.nombreSupervisor);
                            UpdateEvaluations();
                        }, 1000);
                    }else{
                        bootbox.alert("Cendente seleccionado no possee Competencias asociadas.",function(){
                            bootbox.hideAll();
                        });
                    }
                }
            },
            error: function(){
            }
        });

        //UpdateTableObjeciones(Record.id);
        GetErroresCriticos();
        $(".selectpicker").selectpicker("refresh");
    }
    function SaveEvaluation(TableTmp,RecordId){
        AddEvaluation_DB(RecordId,TableTmp);
        ArraySelectedOptions = [];
    }
    function AddEvaluation_DB(RecordId,TableTmp){
        var idErrorCritico = $("select[name='ObservacionErrorCritico']").val();
        var ObservacionEvaluacion = $("textarea[name='ObservacionEvaluacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/AddEvaluation.php",
            dataType: "html",
            async: false,
            data: {
                PersonalUsername: nombreEjecutivo,
                RecordId: RecordId,
                ErrorCritico: ErrorCritico,
                idErrorCritico: idErrorCritico,
                ObservacionEvaluacion: ObservacionEvaluacion
            },
            success: function(data){
                if(data != "0"){
                    Id_Evaluation = data;
                    //AddEvaluationDetails(Id_Evaluation,TableTmp);
                    AddEvaluationDetails(Id_Evaluation);
                }
            },
            error: function(){
            }
        });
    }
    function AddEvaluationDetails(Id_Evaluacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/AddEvaluationDetails.php",
            dataType: "html",
            async: false,
            data: {
                Id_Evaluacion: Id_Evaluacion, 
                Afirmaciones: ArraySelectedOptions
            },
            success: function(data){
                console.log(data);
                deleteModalBackdrop();
                
                cantEvaluaciones++;

                if(findRecord()){
                    getRecord();
                }else{
                    getCantidadEjecutivosSinEvaluaciones();
                }
            },
            error: function(){
            }
        });
    }
    function HaveEvaluations(){
        var ToReturn = false;
        var ContEvaluations = 0;
        $("#Evaluations tbody tr").each(function(indexTR){
            if(!$(this).find("td").hasClass("dataTables_empty")){
                ContEvaluations++;
            }
        });
        if(ContEvaluations > 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    function UpdateEvaluations(){
        CantEvaluations = 0;
        EvaluationTable = $('#Evaluations').DataTable({
            data: EvaluationsArray,
            paging: false,
            iDisplayLength: 100,
            "bDestroy" : true,
            columns: [
                { data: 'Nombre', width: "20%" },
                { data: 'Descripcion', width: "40%" },
                { data: 'Esperado', width: "10%" },
                { data: 'Nota', width: "10%" },
                { data: 'ID', width: "10%" }
            ],
            "columnDefs": [ 
                {
                    className: "NameObject",
                    "targets": 0,
                },
                {
                    className: "DescriptionObject",
                    "targets": 1,
                },
                {
                    className: "dt-center",
                    "targets": 2,
                    "data": 'Esperado',
                    "render": function( data, type, row ) {
                        return "<button class='btn btn-success showEsperadoModal' text='"+data+"'>?</button>";
                    }
                },
                {
                    className: "dt-right NoteObject",
                    "targets": 3,
                    "searchable": false
                },
                {
                    "targets": 4,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer;' class='fa fa-pencil AddAfirmaciones'></i></div>";
                    }
                }
            ]
        });
        //EvaluationTable.order([4, 'asc']).draw();
        EvaluationTable.page('last').draw(false);
        $("#Evaluations").trigger('update');
    }
    function UpdateTableObjeciones(idGrabacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetObjeciones.php",
            dataType: "html",
            data: {
                idGrabacion: idGrabacion
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                    $('#Objeciones').DataTable({
                        data: ToReturn,
                        bDestroy: true,
                        columns: [
                            { data: 'Objecion', width:"60%" },
                            { data: 'fechaObjecion', width: "20%" },
                            { data: 'nombreUsuario', width: "10%" },
                            { data: 'notaObjetada', width: "10%" }
                        ]
                    });
                }
            },
            error: function(){
            }
        });
    }
    function selectAfirmaciones(Competencia){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/selectAfirmacionesByCompetencia.php",
            dataType: "html",
            data: {
                Competencia: Competencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function GetErroresCriticos(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetErroresCriticos.php",
            data: {},
            async: false,
            success: function(data){
                $("select[name='ObservacionErrorCritico']").html(data);
            },
            error: function(){
            }
        });
    }
    function getEvaluationData(){
        var ID = $(".PregunatasEvaluaciones .Preguntas").attr("id");
        var NotaMaxima = $(".PregunatasEvaluaciones .Preguntas").attr("notamaxima");
        var PonderacionCompetencia = $(".PregunatasEvaluaciones .Preguntas").attr("ponderacion");
        var NotaCompetencia = Number(NotaMaxima * (PonderacionCompetencia / 100));
        ArraySelectedOptions[ID] = [];
        var Nota = 0;
        $(".PregunatasEvaluaciones .Preguntas .Dimensiones").each(function(){
            var ObjectDimension = $(this);
            var PonderacionDimension = ObjectDimension.attr("ponderacion");
            var NotaDimension = Number(NotaCompetencia * (PonderacionDimension / 100));
            ObjectDimension.find(".Afirmaciones").each(function(){
                var ObjectAfirmacion = $(this);
                var idAfirmacion = ObjectAfirmacion.attr("id");
                idAfirmacion = idAfirmacion.split("_");
                idAfirmacion = idAfirmacion[1];
                var PonderacionAfirmacion = ObjectAfirmacion.attr("ponderacion");
                var NotaAfirmacion = Number(NotaDimension * (PonderacionAfirmacion / 100));
                var NotaPorOpcion = Number(NotaAfirmacion / NotaMaxima);
                var Value = -1;
                ObjectAfirmacion.find(".Opciones .Opcion").each(function(){
                    var ObjectOpcion = $(this);
                    var ObjectInput = ObjectOpcion.find("input");
                    if(ObjectInput.is(':checked')){
                        Value = ObjectInput.val();
                    }
                });
                var NotaSeleccionada = Value >= 0 ? Number(Value * NotaPorOpcion) : 0;
                if(Value >= 0){
                    Nota += NotaSeleccionada;
                }
                //console.log(NotaSeleccionada);
                ArraySelectedOptions[ID].push(idAfirmacion+"|"+NotaSeleccionada+"|"+Value);
            });
        });
        EvaluationTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.ID == ID){
                data.Nota = Number(Nota).toFixed(2);
                this.data(data);
            }
        });
        EvaluationTable.draw();
        $("#Evaluations").trigger('update');
    }
    function CustomAlert(Message){
        bootbox.alert(Message,function(){
            AddClassModalOpen();
        });
    }
    function AddClassModalOpen(){
        /* setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500); */
    }
    function UpdateEvaluationSummaryFoot(){
        var ContEvaluaciones = 0;
        var SumPonderacion = 0;
        var SumNotas = 0;
        $("#Evaluations tbody tr").each(function(indexTR){
            ContEvaluaciones++;
            $(this).find("td").each(function(indexTD){
                switch(indexTD){
                    case 3:
                        SumNotas += Number($(this).text());
                    break;
                }
            });
        });
        $("#PromNota").html((SumNotas).toFixed(2));
        AddClassModalOpen();
    }
    function deleteModalBackdrop(){
        $(".modal-backdrop").remove();
    }
    function setErrorCritico(idGrabacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/setErrorCritico.php",
            data: { idGrabacion: idGrabacion },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    console.log(data);
                    ArraySelectedOptions = data.SelectedOptions;
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function getCantidadEjecutivosSinEvaluaciones(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getCantidadEjecutivosSinEvaluaciones.php",
            data: { 
                FechaInicio: fechaInicio,
                FechaFin: fechaFin
            },
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    data = JSON.parse(data);
                    console.log(data);
                    //$(".cantidadEjecutivosSinEvaluacion"+numeroSemana).html(data.result);
                    deleteModalBackdrop();
                    getUsuariosSemana(fechaInicio,fechaFin,true);
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function getMesCalendarioEvaluacionesAutomaticas(Action,Fecha,Modal){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getMesCalendarioEvaluacionesAutomaticas.php",
            data: { 
                Action: Action,
                fechaActual: Fecha
            },
            beforeSend: function() {
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    }); 
                }
                
            },
            success: function(data){
                if(Modal){
                    $('#Cargando').modal('hide');
                }
                if(isJson(data)){
                    data = JSON.parse(data);
                    console.log(data);
                    if(data.result){
                        $(".Meses").data("fecha",data.Fecha);
                        $(".Meses").data("mes",data.Mes);
                        $(".Meses .Mes").html(data.Texto);
                        console.log($(".Meses").data("mes"));
                        getSemanasMes(data.Fecha);
                    }else{
                        bootbox.alert("No puede hacer la busqueda de un mes superior al mes actual.");
                    }
                    
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });
    }
    function findRecord(){
        var ToReturn = true;
        var evaluacionesEjecutivos = $("input[name='CantEvaluaciones']").val();
        if(cantEvaluaciones > evaluacionesEjecutivos){
            var EjecutivosTmp = [];
            $.each(Ejecutivos, function(i, item) {
                if(item.idEjecutivo != EjecutivoActual){
                    EjecutivosTmp.push(item);
                }
            })
            Ejecutivos = EjecutivosTmp;
            if(Ejecutivos.length > 0){
                EjecutivoActual = Ejecutivos[0].idEjecutivo;
                nombreEjecutivo = EjecutivoActual;
                ToReturn = true;
                cantEvaluaciones = 1;
            }else{
                ToReturn = false;
            }
        }
        return ToReturn;
    }
});
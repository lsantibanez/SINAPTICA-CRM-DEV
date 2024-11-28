
audiojs.events.ready(function() {
    audiojs.createAll();
});
$(document).ready(function() {
    var RecordTable;
    var RecordId;
    var EvaluationTable;
    var Ejecutivo = [];
    var CantEvaluations = 0;
    var EvaluationsArray = [];
    var StatusObject;
    var RecordGroups = [];
    var GroupRecordsFlag = false;
    var PrintObject;
    var CarteraObject;
    var ArraySelectedOptions = [];
    var ErrorCritico = 0;
    var EvaluationData;

    FillPersonalList(GlobalData.nombre_cedente);

    $("body").on("change","select[name='Ejecutivo']",function(){
        var Val = $(this).val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        if(Val != ""){
            Ejecutivo[0] = $(this).find("option:selected").text().toUpperCase();
            Ejecutivo[1] = $(this).val();
            FillPeriodo();
            var Periodo = $("select[name='Periodos']").val();
            getTipificacion(Periodo);
            if(HizoCierre(idMandante,idCartera)){
                $("#Records .AddEvaluation").remove();
                $("#FinDeProceso").addClass("ElementInvisible");
                RecordTable.clear().draw();
                CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
            }else{
                UpdateRecords(idMandante,idCartera,Periodo);
                ShowBotonCierre();
            }
        }
    });
    function ShowBotonCierre(){
        var Periodo = $("select[name='Periodos']").val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        if(HaveEvaluatedRecords()){
            if(PuedeHacerCierreDeProceso(idMandante,idCartera)){
                $("#FinDeProceso").removeClass("ElementInvisible");
            }else{
                $("#FinDeProceso").addClass("ElementInvisible");
            }
        }
    }
    $("body").on("change","select[name='Periodos']",function(){
        var Periodo = $(this).val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        getTipificacion(Periodo);
        if(HizoCierre(idMandante,idCartera)){
            $("#Records .AddEvaluation").remove();
            $("#FinDeProceso").addClass("ElementInvisible");
            $("select[name='Tipificacion']").prop("disabled",true);
            $("select[name='Tipificacion']").selectpicker("refresh");
            RecordTable.clear().draw();
            CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
        }else{
            UpdateRecords(idMandante,idCartera,Periodo);
            ShowBotonCierre();
        }
    });
    $("body").on("change","select[name='Tipificacion']",function(){
        var Periodo = $("select[name='Periodos']").val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        UpdateRecords(idMandante,idCartera,Periodo);
    });
    $('body').on('click','.UpdateEvaluation', function(){
        RecordId = $(this).attr("id");
        RecordId = RecordId.substr(RecordId.indexOf("_") + 1, RecordId.length);

        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        
        var Id_Evaluacion = "";
        var haveEvaluation = false;
        var ActualizarEvaluacion = true;
        var CanPass = true;
        
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetEvaluation.php",
            data: { Id_Grabacion: RecordId },
            dataType: "html",
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var Evaluation = JSON.parse(data);
                    if(Evaluation.length > 0){
                        Evaluation = Evaluation[0];
                        Id_Evaluacion = Evaluation.id;
                        haveEvaluation = true;
                    }
                }
            },
            error: function(){
            }
        });
        if(!haveEvaluation){
            bootbox.alert("Esta grabación no contiene evaluaciones realizadas por usted.");
            return false;
        }
        var Template = $("#loginCredencialesTemplate").html();
        bootbox.dialog({
            title: "AUTORIZACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var userName = $("input[name='userName']").val();
                        var password = $("input[name='password']").val();
                        var Login = false;
                        $.ajax({
                            type: "POST",
                            url: "../includes/calidad/checkLoginAdmin.php",
                            async: false,
                            data: {
                                userName: userName,
                                password: password,
                                Level: '1'
                            },
                            dataType: "html",
                            success: function(data){
                                if(isJson(data)){
                                    var ToReturn = JSON.parse(data);
                                    if(ToReturn.result){
                                        Login = true;
                                    }
                                }
                            },
                            error: function(){
                            }
                        });
                        if(Login){
                            FormularioDeEvaluacion(Id_Evaluacion,ActualizarEvaluacion,ObjectTR);
                        }else{
                            bootbox.alert("Usuario o Clave invalido.");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                        CanPass = false;
                    }
                }
            },
            size: 'small'
        }).off("shown.bs.modal");
    });
    $('body').on('click','.AddEvaluation', function(){
        RecordId = $(this).attr("id");
        RecordId = RecordId.substr(RecordId.indexOf("_") + 1, RecordId.length);

        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        
        var Id_Evaluacion = "";
        var ActualizarEvaluacion = false;
        FormularioDeEvaluacion(Id_Evaluacion,ActualizarEvaluacion,ObjectTR);
    });
    function FormularioDeEvaluacion(idEvaluacion,actualizarEvaluacion,ObjectTR){
        ArraySelectedOptions = [];
        var Template = $("#Calificacion").html();

        ErrorCritico = 0;

            var Cartera = "";
            var Filename = "";
            var Audio = "";
            var Date = "";
            var Status = "";
            var NewEvaluation = false;
        var RowData = RecordTable.row(ObjectTR).data();
        Audio = "<audio src='"+RowData.Listen+"' preload='auto' controls></audio>";
        ObjectTR.find("td").each(function(index){
            switch(index){
                case 0:
                    Cartera = $(this).html();
                    CarteraObject = $(this);
                break;
                case 1:
                    Filename = $(this).html();
                break;
                case 3:
                    Date = $(this).html();
                break;
                case 4:
                    Status = $(this).html();
                    if(Status == ""){
                        NewEvaluation = true;
                    }
                    StatusObject = $(this);
                break;
                case 5:
                break;
                case 6:
                    PrintObject = $(this);
                break;
            }
        });

            Template = Template.replace("{RECORD_AUDIO}",Audio);
            if(!GlobalData.isCalidadSystem){
                if(GlobalData.have360Evaluation){
                    Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:block");
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:none");
                }else{
                    Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:none;");
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:block");
                }
            }else{
                Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:block");
                if(GlobalData.have360Evaluation){
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:none");
                }
            }

        bootbox.dialog({
            title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Ejecutivo[0],
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
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
                                        SaveEvaluation(actualizarEvaluacion, TableTmp, idEvaluacion);
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
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        if(Status != ""){
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluation.php",
                data: { Id_Grabacion: RecordId },
                dataType: "html",
                success: function(data){
                    var Evaluation = JSON.parse(data);
                    Evaluation = Evaluation[0];
                    var Id_Evaluacion = Evaluation.id;
                    if(Evaluation.errorCritico == "1"){
                        ErrorCritico = "1";
                        $("select[name='ObservacionErrorCritico']").val(Evaluation.id_errorCritico);
                        $("select[name='ObservacionErrorCritico']").selectpicker("refresh")
                        $("#ErrorCritico").prop("checked",true);
                        $("#EvaluationsTableContainer").hide();
                        $("#ContainerObservacionErrorCritico").show();
                    }
                    $("textarea[name='ObservacionEvaluacion']").val(Evaluation.observacion);
                    $.ajax({
                        type: "POST",
                        url: "../includes/calidad/GetEvaluationDetails.php",
                        data: { Id_Evaluacion: Id_Evaluacion },
                        dataType: "html",
                        success: function(data1){
                            var result = JSON.parse(data1);
                            EvaluationsArray = result.Competencias;
                            ArraySelectedOptions = result.SelectedOptions;
                            //EvaluationsArray = JSON.parse(data1);
                            UpdateEvaluations();
                        },
                        error: function(){
                        }
                    });
                },
                error: function(){
                }
            });
        }else{
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluationTemplate.php",
                dataType: "html",
                data: {
                    Ejecutivo: Ejecutivo[1],
                    idGrabacion: RecordId
                },
                success: function(data){
                    if(isJson(data)){
                        EvaluationsArray = JSON.parse(data);
                        if(EvaluationsArray.length > 0){
                            UpdateEvaluations();
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
            //UpdateEvaluations();
        }
        UpdateTableObjeciones(RecordId);
        GetErroresCriticos();
        $(".selectpicker").selectpicker("refresh");
    }
    /*$('body').on('click','.AddEvaluation', function(){
        var Template = $("#Calificacion").html();

        ErrorCritico = 0;

        RecordId = $(this).attr("id");
        RecordId = RecordId.substr(RecordId.indexOf("_") + 1, RecordId.length);
        
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
            var Cartera = "";
            var Filename = "";
            var Audio = "";
            var Date = "";
            var Status = "";
            var NewEvaluation = false;
        var RowData = RecordTable.row(ObjectTR).data();
        Audio = "<audio src='"+RowData.Listen+"' preload='auto' controls></audio>";
        ObjectTR.find("td").each(function(index){
            switch(index){
                case 0:
                    Cartera = $(this).html();
                    CarteraObject = $(this);
                break;
                case 1:
                    Filename = $(this).html();
                break;
                case 3:
                    Date = $(this).html();
                break;
                case 4:
                    Status = $(this).html();
                    if(Status == ""){
                        NewEvaluation = true;
                    }
                    StatusObject = $(this);
                break;
                case 5:
                break;
                case 6:
                    PrintObject = $(this);
                break;
            }
        });

            Template = Template.replace("{RECORD_AUDIO}",Audio);
            if(!GlobalData.isCalidadSystem){
                if(GlobalData.have360Evaluation){
                    Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:block");
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:none");
                }else{
                    Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:none;");
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:block");
                }
            }else{
                Template = Template.replace("{STYLE_DISPLAY_EVALUATIONS}","display:block");
                if(GlobalData.have360Evaluation){
                    Template = Template.replace("{STYLE_DISPLAY_OBJECIONES}","display:none");
                }
            }

        bootbox.dialog({
            title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Ejecutivo[0],
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
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
                                        SaveEvaluation(NewEvaluation, TableTmp);
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
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        if(Status != ""){
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluation.php",
                data: { Id_Grabacion: RecordId },
                dataType: "html",
                success: function(data){
                    var Evaluation = JSON.parse(data);
                    Evaluation = Evaluation[0];
                    var Id_Evaluacion = Evaluation.id;
                    if(Evaluation.errorCritico == "1"){
                        $("#ErrorCritico").prop("checked",true);
                        $("#EvaluationsTableContainer").hide();
                    }
                    $("textarea[name='ObservacionEvaluacion']").val(Evaluation.observacion);
                    $.ajax({
                        type: "POST",
                        url: "../includes/calidad/GetEvaluationDetails.php",
                        data: { Id_Evaluacion: Id_Evaluacion },
                        dataType: "html",
                        success: function(data1){
                            var result = JSON.parse(data1);
                            EvaluationsArray = result.Competencias;
                            ArraySelectedOptions = result.SelectedOptions;
                            //EvaluationsArray = JSON.parse(data1);
                            UpdateEvaluations();
                        },
                        error: function(){
                        }
                    });
                },
                error: function(){
                }
            });
        }else{
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluationTemplate.php",
                dataType: "html",
                data: {
                    Ejecutivo: Ejecutivo[1],
                    idGrabacion: RecordId
                },
                success: function(data){
                    if(isJson(data)){
                        EvaluationsArray = JSON.parse(data);
                        if(EvaluationsArray.length > 0){
                            UpdateEvaluations();
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
            //UpdateEvaluations();
        }
        UpdateTableObjeciones(RecordId);
        GetErroresCriticos();
        $(".selectpicker").selectpicker("refresh");
    });*/
    $("body").on("keypress",".justNumber",function(e){
        if(e.keyCode == 190){
            return false;
        }
    });
    $('body').on( 'click', '.AddAfirmaciones', function () {
        console.log(ArraySelectedOptions);
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectNameText = ObjectTR.find(".NameObject");
        var ObjectDescriptionText = ObjectTR.find(".DescriptionObject");
        var ObjectEsperadoText = ObjectTR.find(".showEsperadoModal");
        var ObjectObservationText = ObjectTR.find(".ObservationObject");
        var ObjectNote = ObjectTR.find(".NoteObject");
        var ObjectCalfPonderada = ObjectTR.find(".CalfPonderadaObject");
        var ObjectPonderacion = ObjectTR.find(".PonderacionObject");
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
            var NotaCompetencia = NotaMaxima * (Ponderacion / 100);
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
    $("body").on("click",".close",function(){
        AddClassModalOpen();
    });
    $("#FinDeProceso").click(function(){
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        var Template = $("#Observations").html();
        if(HaveEvaluatedRecords()){
            bootbox.dialog({
                title: "CIERRE DE PROCESO DE '" + GlobalData.nombre_mandante.toUpperCase() + "'",
                message: Template,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Observation_aspectosF = "";
                            var Observation_aspectosC = "";
                            var Observation_comprimisoE = "";
                            var TipoCierre = $("select[name='Tipo_Cierre']").val();
                            if(TipoCierre != ""){
                                CierreDeProceso(idMandante,idCartera,Observation_aspectosF,Observation_aspectosC,Observation_comprimisoE,TipoCierre);
                            }else{
                                bootbox.alert("Debe seleccionar un tipo de cierre");
                            }
                        }
                    }
                }
            }).off("shown.bs.modal");
            $("select").selectpicker("refresh");
        }else{
            bootbox.alert("No hay evaluaciones suficientes para realizar el cierre");
        }
    });
    $("body").on("click","#AddObjecion",function(){
        var Template = $("#addObjecionTemplate").html();

        if(!GlobalData.isCalidadSystem){
            Template = Template.replace("{STYLE_DISPLAY_TIPO_COMENTARIO_OBJECION}","display:none");
            Template = Template.replace("{SELECTED_OPTION_TIPO_COMENTARIO_OBJECION}","selected='selected'");
        }else{
            if(!GlobalData.have360Evaluation){
                Template = Template.replace("{STYLE_DISPLAY_TIPO_COMENTARIO_OBJECION}","display:block");
                Template = Template.replace("{SELECTED_OPTION_TIPO_COMENTARIO_OBJECION}","");
            }
        }

        bootbox.dialog({
            title: "NUEVA OBJECIÓN",
            message: Template,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Objecion = $("textarea[name='Objecion']").val();
                        var Tipo = $("select[name='Tipo_Comentario']").val();
                        var Username = Ejecutivo[1];
                        var CanAdd = false;
                        if(Objecion != ""){
                            if(Tipo != ""){
                                CanAdd = true;
                            }else{
                                bootbox.alert("Debe seleccionar un tipo de comentario");
                            }
                        }else{
                            bootbox.alert("Debe ingresar un comentario");
                        }
                        if(CanAdd){
                            saveObjecion(Username,Objecion,Tipo)
                        }else{
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");

        $("select[name='Tipo_Comentario']").selectpicker("refresh");
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
    $('body').on( 'click', '.showAfirmaciones', function () {
        console.log(ArraySelectedOptions);
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectNameText = ObjectTR.find(".NameObject");
        var ObjectDescriptionText = ObjectTR.find(".DescriptionObject");
        var ObjectEsperadoText = ObjectTR.find(".showEsperadoModal");
        var ObjectObservationText = ObjectTR.find(".ObservationObject");
        var ObjectNote = ObjectTR.find(".NoteObject");
        var ObjectCalfPonderada = ObjectTR.find(".CalfPonderadaObject");
        var ObjectPonderacion = ObjectTR.find(".PonderacionObject");
        var Template = $("#EvaluationFormObservation").html();

        var Row = EvaluationTable.row( ObjectTR ).data();
        var ID = Row.ID;
        var Ponderacion = Number(Row.Ponderacion);

        bootbox.dialog({
            title: "Formulario de Observación de la competencia: " + ObjectNameText.html(),
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Cerrar",
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
            var NotaCompetencia = NotaMaxima * (Ponderacion / 100);
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
                                    "<label class='form-radio form-icon form-text "+Active+"' style='height: 100%;'><input "+Checked+" disabled name='"+idAfirmacion+"' id='O_"+idOpcion+"' value='"+Valor+"' type='radio'> "+OpcionTxt+"</label>"+
                                "</div>";
                    ObjectPreguntas.find("#D_"+Dimension.idDimension).find("#A_"+idAfirmacion+" .Opciones").append(Div);
                });
                Cont++;
            });
        });
    });
    $("body").on("click",".showAllEvaluations",function(){
        var Template = $("#showCalificaciones").html();
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
            var Cartera = "";
            var Filename = "";
            var Audio = "";
            var Date = "";
            var Status = "";
            var NewEvaluation = false;
        EvaluationData = RecordTable.row(ObjectTR).data();
        RecordId = EvaluationData.id;

        $.ajax({
            type: "POST",
            url: "../includes/calidad/getAllEvaluations.php",
            data: { Id_Grabacion: RecordId },
            async: false,
            success: function(data){
                if(isJson(data)){
                    let Evatuations = JSON.parse(data);
                    console.log(Evatuations);
                    let evaluationsHTML = "";
                    var ContTmp = 0;
                    Evatuations.forEach(Evaluation => {
                        console.log(Evaluation);
                        var Color = "";
                        if(ContTmp % 2 == 0) {
                            Color = "efefef";
                          }
                          else {
                            Color = "cccccc";
                          }
                        evaluationsHTML += ''+
                            '<div class="contentEvaluacion" style="width: 100%;display: block;position: relative;overflow: hidden;padding: 10px 0px;height: 65px;background-color: #'+Color+';">'+
                                '<div class="Name" style="float: left;width: 70%;height: 100%;text-align: center;">'+
                                    '<div class="Text" style="line-height: 25px;font-size: 20px;">'+Evaluation.Evaluador+'</div>'+
                                    '<div class="Title" style="font-weight: bold;">Nombre Evaluador</div>'+
                                '</div>'+
                                '<div class="Note" style="float: left;width: 15%;text-align: center;height: 100%;">'+
                                    '<div class="Text" style="line-height: 25px;font-size: 20px;">'+Evaluation.Nota+'</div>'+
                                    '<div class="Title" style="font-weight: bold;">Nota</div>'+
                                '</div>'+
                                '<div class="Show" style="float: left;width: 15%;text-align: center;line-height: 45px;"><button class="btn btn-success showEvaluacionSeleccioanda" id="'+Evaluation.idUsuario+'"><i class="fa fa-arrow-right"></i></button></div>'+
                            '</div>';
                        ContTmp++;
                    });
                    Template = Template.replace("{ EVALUADORES }",evaluationsHTML);
                }else{
                    console.log(data);
                }
            },
            error: function(){
            }
        });

        bootbox.dialog({
            title: "EVALUACIONES",
            message: Template,
            closeButton: false,
            buttons: {
                cancel: {
                    label: "Salir",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'medium'
        }).off("shown.bs.modal");
    });
    $("body").on("click",".showEvaluacionSeleccioanda",function(){
        ArraySelectedOptions = [];
        var Template = $("#showCalificacion").html();
        var ObjectMe = $(this);
        var idUsuario = ObjectMe.attr("id");
        ErrorCritico = 0;

        Audio = "<audio src='"+EvaluationData.Listen+"' preload='auto' controls></audio>";

        Template = Template.replace("{RECORD_AUDIO}",Audio);

        bootbox.dialog({
            title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Ejecutivo[0].toUpperCase(),
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        UpdateTableObjeciones(EvaluationData.id);
        GetErroresCriticos();
        $(".selectpicker").selectpicker("refresh");
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetEvaluation.php",
            data: {
                Id_Grabacion: EvaluationData.id,
                idUsuario: idUsuario
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var Evaluation = JSON.parse(data);
                    Evaluation = Evaluation[0];
                    var Id_Evaluacion = Evaluation.id;
                    console.log(Evaluation);
                    if(Evaluation.errorCritico == "1"){
                        $("#isErrorCritico").prop("checked",true);
                        $("#ContainerObservacionErrorCritico").show();
                        $("select[name='ObservacionErrorCritico']").val(Evaluation.id_errorCritico);
                        $("#EvaluationsTableContainer").hide();
                        $(".selectpicker").selectpicker("refresh");
                    }
                    $("textarea[name='ObservacionEvaluacion']").val(Evaluation.observacion);
                    $.ajax({
                        type: "POST",
                        url: "../includes/calidad/GetEvaluationDetails.php",
                        data: { Id_Evaluacion: Id_Evaluacion },
                        async: false,
                        success: function(data1){
                            console.log(data1);
                            if(isJson(data1)){
                                var result = JSON.parse(data1);
                                EvaluationsArray = result.Competencias;
                                ArraySelectedOptions = result.SelectedOptions;
                                showEvaluations();
                                //UpdateEvaluations();
                            }
                        },
                        error: function(){
                        }
                    });
                }
            },
            error: function(){
            }
        });
    });
    function FillPersonalList(Cartera){
        $.ajax({
            type: "POST",
            url: "../includes/personal/fillSelectEvaluadas.php",
            dataType: "html",
            data: {
                Cartera: Cartera,
            },
            success: function(data){
                $("select[name='Ejecutivo']").html(data);
                $("select[name='Ejecutivo']").selectpicker('refresh');
                if(GlobalData.isEjecutivo){
                    $("select[name='Ejecutivo']").prop("disabled",true);
                    $("select[name='Ejecutivo']").selectpicker('refresh');
                    Ejecutivo[0] = GlobalData.personalName.toUpperCase();
                    Ejecutivo[1] = GlobalData.username;
                    FillPeriodo();
                    var Periodo = $("select[name='Periodos']").val();
                    getTipificacion(Periodo);
                    UpdateRecords(GlobalData.id_mandante,GlobalData.id_cedente,Periodo);
                    if(HaveEvaluatedRecords()){
                        var idCartera = GlobalData.id_cedente;
                        var idMandante = GlobalData.id_mandante;
                        if(HizoCierre(idMandante,idCartera)){
                            $("#Records .AddEvaluation").remove();
                            CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
                        }else{
                            $("#FinDeProceso").removeClass("ElementInvisible");
                        }
                    }
                }else{
                    PreloadRecordTable();
                }
            },
            error: function(){
            }
        });
    }
    function HaveEvaluatedRecords(){
        var ToReturn = false;
        var Cont = 0;
        RecordTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.Status != ""){
                Cont++;
            }
        });
        if(Cont > 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    function FillCarteraList(startDate, endDate){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillCartera.php",
            dataType: "html",
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function(data){
                $("select[name='Cartera']").html(data);
                $("select[name='Cartera']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function UpdateRecords(idMandante,idCartera,Periodo){
        var Tipificacion = $("select[name='Tipificacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetRecordsEvaluadas.php",
            data: { 
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo,
                Tipificacion: Tipificacion
            },
            dataType: "html",
            async: false,
            success: function(data){
                console.log(data);
                var dataSet = JSON.parse(data);
                var CantRecords = dataSet.length;
                RecordGroups = [];
                for(var i in dataSet){
                    var ID = dataSet[i].Imprimir;
                    RecordGroups[ID] = false;
                }
                UpdateRecordTable(dataSet);
            },
            error: function(){
            }
        });
    }
    function PreloadRecordTable(){
        var dataSet = [];
        RecordTable = $('#Records').DataTable({
            data: dataSet,
            bDestroy: true,
            columns: [
                { data: 'Cartera' },
                { data: 'Filename' },
                { data: 'Listen' },
                { data: 'Date' },
                { data: 'Status' }, 
                { data: 'Evaluar' },
                { data: 'Imprimir' }
            ],
            "columnDefs": [ 
                {
                    "targets": 2,
                    "data": 'Listen',
                    "render": function( data, type, row ) {
                        return "<audio src='"+data+"' preload='auto' controls></audio>";
                    }
                },
                {
                    "targets": 5,
                    "data": 'Evaluar',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-pencil AddEvaluation'></i><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-edit UpdateEvaluation'></i></div>";
                    }
                },
                {
                    "targets": 6,
                    "data": 'Imprimir',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.Status != ""){
                            ToReturn = "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                        }
                        //return "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                        return ToReturn;
                    }
                }
            ]
        });
    }
    function UpdateEvaluations(){
        CantEvaluations = 0;
        EvaluationTable = $('#Evaluations').DataTable({
            data: EvaluationsArray,
            paging: false,
            iDisplayLength: 100,
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
    function CustomAlert(Message){
        bootbox.alert(Message,function(){
            AddClassModalOpen();
        });
    }
    function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
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
    function SaveEvaluation(actualizarEvaluacion, TableTmp, idEvaluacion){
        /*var IDtmp = PrintObject.closest("tr").find(".AddEvaluation").attr("id");
        var IDArray = IDtmp.split("_");
        AddEvaluation_DB(RecordId,TableTmp);
        ArraySelectedOptions = [];
        StatusObject.html("Evaluada");
        PrintObject.html("<div style='text-align: center;'><a href='EvaluationResume.php?id="+IDArray[1]+"' target='_blank'><i style='cursor: pointer;' id='Record_"+IDArray[1]+"' class='fa fa-print Print'></i></a></div>");*/
        if(!actualizarEvaluacion){
            var IDtmp = PrintObject.closest("tr").find(".AddEvaluation").attr("id");
            var IDArray = IDtmp.split("_");
            AddEvaluation_DB(RecordId,TableTmp);
            ArraySelectedOptions = [];
            StatusObject.html("Evaluada");
            PrintObject.html("<div style='text-align: center;'><a href='EvaluationResume.php?id="+IDArray[1]+"' target='_blank'><i style='cursor: pointer;' id='Record_"+IDArray[1]+"' class='fa fa-print Print'></i></a></div>");
        }else{
            UpdateEvaluation_DB(RecordId,TableTmp);
            //AddEvaluationDetails(idEvaluacion);
        }
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
                PersonalUsername: Ejecutivo[1],
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
            },
            error: function(){
            }
        });
    }
    function UpdateEvaluation_DB(RecordId,TableTmp){
        var idErrorCritico = $("select[name='ObservacionErrorCritico']").val();
        var ObservacionEvaluacion = $("textarea[name='ObservacionEvaluacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateEvaluation.php",
            dataType: "html",
            data: {
                RecordId: RecordId,
                ErrorCritico: ErrorCritico,
                idErrorCritico: idErrorCritico,
                ObservacionEvaluacion: ObservacionEvaluacion
            },
            success: function(data){
                if(data != "0"){
                    Id_Evaluation = data;
                    AddEvaluationDetails(Id_Evaluation);
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
    
    function PuedeHacerCierreDeProceso(idMandante, idCartera){
        var ToReturn = false;
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/HaveEvaluations.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                var ObjectJson = JSON.parse(data);
                if(ObjectJson.Return){
                    ToReturn = true;
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function CierreDeProceso(idMandante,idCartera,Observation_aspectosF,Observation_aspectosC,Observation_comprimisoE,TipoCierre){
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/CierreDeProceso.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Observation_aspectosF: Observation_aspectosF,
                Observation_aspectosC: Observation_aspectosC,
                Observation_comprimisoE: Observation_comprimisoE,
                TipoCierre: TipoCierre,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                console.log(data);
                var ObjectJson = JSON.parse(data);
                if(ObjectJson.Return){
                    ToReturn = true;
                    location.reload();
                }else{
                    CustomAlert("Hubo un problema al crear el cierre");
                }
            },
            error: function(){
            }
        });
    }
    function HizoCierre(idMandante,idCartera){
        var ToReturn = false;
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/HizoCierre.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                var ObjectJson = JSON.parse(data);
                ToReturn = ObjectJson.Return;
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function FillPeriodo(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getEvaluacionesByMonthsAndYears.php",
            dataType: "html",
            data: {
                idPersonal: Ejecutivo[1]
            },
            async: false,
            success: function(data){
                $("select[name='Periodos']").html(data);
                $("select[name='Periodos']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function getTipificacion(Periodo){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipificacionGrabacionesEvaluadas.php",
            data: { 
                Ejecutivo: $("select[name='Ejecutivo']").val(),
                Periodo: Periodo
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
    function getEvaluationData(){
        var ID = $(".PregunatasEvaluaciones .Preguntas").attr("id");
        var NotaMaxima = $(".PregunatasEvaluaciones .Preguntas").attr("notamaxima");
        var PonderacionCompetencia = $(".PregunatasEvaluaciones .Preguntas").attr("ponderacion");
        var NotaCompetencia = Number(NotaMaxima * (PonderacionCompetencia / 100));
        ArraySelectedOptions[ID] = [];
        var Nota = 0;
        var Cont = 0;
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
    function saveObjecion(Username,Objecion,Tipo){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/saveObjecion.php",
            data: {
                Username: Username,
                Objecion: Objecion,
                Tipo: Tipo,
                idGrabacion: RecordId
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                    if(ToReturn.result){
                        UpdateTableObjeciones(RecordId);
                    }
                }
            },
            error: function(){
            }
        });
    }
    function UpdateRecordTable(dataSet){
        RecordTable = $('#Records').DataTable({
            data: dataSet,
            bDestroy: true,
            columns: [
                { data: 'Cartera', width:"20%" },
                { data: 'Filename', width: "40%" },
                { data: 'Tipificacion' },
                { data: 'Listen' },
                { data: 'Date' },
                { data: 'Status' }, 
                { data: 'Evaluar' },
                { data: 'Imprimir' }
            ],
            "columnDefs": [ 
                {
                    "targets": 3,
                    "data": 'Listen',
                    "visible": false,
                    "render": function( data, type, row ) {
                        //return "<audio src='"+data+"' preload='auto' controls></audio>";
                        return "<div url='"+data+"' id='ListenRecord'><i class='fa fa-play'></i></div>";
                    }
                },
                {
                    "targets": 6,
                    "data": 'Evaluar',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'><i style='cursor: pointer; margin: 0px 5px;' id='Record_"+data+"' class='fa fa-eye showAllEvaluations'></i><i style='cursor: pointer; margin: 0px 5px;' id='Record_"+data+"' class='fa fa-pencil AddEvaluation'></i><i style='cursor: pointer; margin: 0px 5px;' id='Record_"+data+"' class='fa fa-edit UpdateEvaluation'></i></div>";
                    }
                },
                {
                    "targets": 7,
                    "data": 'Imprimir',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.Status != ""){
                            ToReturn = "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                        }
                        //return "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                        return ToReturn;
                    }
                }
            ]
        });
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
    function showEvaluations(){
        CantEvaluations = 0;
        EvaluationTable = $('#Evaluations').DataTable({
            data: EvaluationsArray,
            paging: false,
            iDisplayLength: 100,
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
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer;' class='fa fa-eye showAfirmaciones'></i></div>";
                    }
                }
            ]
        });
        //EvaluationTable.order([4, 'asc']).draw();
        EvaluationTable.page('last').draw(false);
        $("#Evaluations").trigger('update');
    }
    $("body").on("click",".showEsperadoModal",function(){
        var Text = $(this).attr("text");
        CustomAlert(Text);
    });
});
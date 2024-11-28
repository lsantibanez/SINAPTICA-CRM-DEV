$(document).ready(function(){

    var TablePautas;
    var PautasDataSet;

    var TableCedentes;
    var CedentesDataSet;

    var TablePautasCedentes;
    var PautasCedentesDataSet;

    var Pauta;

    var TableCompetencias;
    var CompetenciasDataSet;

    var TableDimensiones;
    var DimensionesDataSet;

    var TableAfirmaciones;
    var AfirmacionesDataSet;

    var TableOpcionesAfirmaciones;
    var OpcionesAfirmacionesDataSet;

    var idCedenteGlobal;
    
    getPautas();
    UpdateTablePautas();

    getCedentesMandante();
    UpdateTableCedentesMandante();

    $("body").on("click","#updateNotaMaximaEvaluacion",function(){

        var Template = $("#TemplateUpdateNotaMaximaEvaluacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DE NOTA MAXIMA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var NotaMaximaEvaluacion = $("input[name='NotaMaximaEvaluacion']").val();
                        if(NotaMaximaEvaluacion != ""){
                            updateNotaMaximaEvaluacion(); 
                        }else{
                            bootbox.alert("Debe ingresar una nota maxima");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        getNotaMaximaEvaluacion();
    })
    
    $("body").on("click",".addDimensiones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        var Template = $("#TemplateDimensiones").html();
        Template = Template.replace("{COMPETENCIA}",idCompetencia);
        bootbox.dialog({
            title: "TABLA DE DIMENSIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
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
            }
        });
        getDimensiones(idCompetencia);
        UpdateTableDimensiones();
    });
    $("body").on("click",".addAfirmaciones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var IDArray = ID.split("_");
        var idCompetencia = IDArray[0];
        var idDimension = IDArray[1];
        var Template = $("#TemplateAfirmaciones").html();
        Template = Template.replace("{DIMENSION}",idDimension);
        bootbox.dialog({
            title: "TABLA DE AFIRMACIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
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
            size: "large"
        });
        getAfirmaciones(idDimension);
        UpdateTableAfirmaciones();
    });
    $("body").on("click",".addOpcion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var IDArray = ID.split("_");
        var idDimension = IDArray[0];
        var idAfirmacion = IDArray[1];
        var Template = $("#TemplateOpcionesAfirmaciones").html();
        Template = Template.replace("{AFIRMACION}",idAfirmacion);
        bootbox.dialog({
            title: "TABLA DE OPCIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
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
            size: "large"
        });
        getOpcionesAfirmaciones(idAfirmacion);
        UpdateTableOpcionesAfirmaciones();
    });
    $("body").on("click","#addCompetencia",function(){
        var CanSave = CanSavePonderacion(TableCompetencias);
        if(CanSave){
            var Template = $("#TemplateAddCompetencia").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA COMPETENCIA",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='NombreCompetencia']").val();
                            var Descripcion = $("input[name='DescripcionCompetencia']").val();
                            var Ponderacion = $("input[name='PonderacionCompetencia']").val();
                            var Tag = $("input[name='TagCompetencia']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableCompetencias,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        if(Descripcion != ""){
                                            if(Tag != ""){
                                                SaveCompetencia();
                                            }else{
                                                bootbox.alert("Debe ingresar el Tag");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar una descripcción");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });
        }else{
            bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeCompetencia",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de borrar la competencia seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteCompetencia(idCompetencia);
                }
            }
        });
    });
    $("body").on("click",".updateCompetencia",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        var Template = $("#TemplateAddCompetencia").html();
        var PonderacionAnterior = 0;
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DE COMPETENCIA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='NombreCompetencia']").val();
                        var Descripcion = $("input[name='DescripcionCompetencia']").val();
                        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
                        var Tag = $("input[name='TagCompetencia']").val();
                        if(Ponderacion != ""){
                            CanUpdate = CanUpdatePonderacion(TableCompetencias,Ponderacion,PonderacionAnterior);
                            if(CanUpdate){
                                if(Nombre != ""){
                                    if(Descripcion != ""){
                                        if(Tag != ""){
                                            UpdateCompetencia(idCompetencia);
                                        }else{
                                            bootbox.alert("Debe ingresar el Tag");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripcción");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible actualizar la competencia debido a que la sumatoria de las competencias suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Competencia = GetCompetencia(idCompetencia);
        $("input[name='NombreCompetencia']").val(Competencia.Nombre);
        $("input[name='DescripcionCompetencia']").val(Competencia.Descripcion);
        $("input[name='PonderacionCompetencia']").val(Competencia.Ponderacion);
        $("input[name='TagCompetencia']").val(Competencia.Tag);
        PonderacionAnterior = Competencia.Ponderacion;
    });


    $("body").on("click","#addDimension",function(){
        var idCompetencia = $(this).attr("competencia");
        var CanSave = CanSavePonderacion(TableDimensiones);
        if(CanSave){
            var Template = $("#TemplateAddDimension").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA DIMENSION",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='Nombre']").val();
                            var Ponderacion = $("input[name='Ponderacion']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableDimensiones,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        SaveDimension(idCompetencia);
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva dimension debido a que ya la sumatoria de las dimensiones suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });

        }else{
            bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeDimension",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idDimension = ID[1];
        var idCompetencia = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Dimension seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteDimension(idDimension,idCompetencia);
                }
            }
        });
    });
    $("body").on("click",".updateDimension",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idDimension = ID[1];
        var idCompetencia = ID[0];
        var Template = $("#TemplateAddDimension").html();
        var PonderacionAnterior = 0;
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACION DE DIMENSION",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Ponderacion = $("input[name='Ponderacion']").val();
                        if(Ponderacion != ""){
                            CanSave = CanUpdatePonderacion(TableDimensiones,Ponderacion,PonderacionAnterior);
                            if(CanSave){
                                if(Nombre != ""){
                                    //SaveDimension(idCompetencia);
                                    UpdateDimension(idDimension,idCompetencia);
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible agregar nueva dimension debido a que ya la sumatoria de las dimensiones suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Dimension = GetDimension(idDimension);
        $("input[name='Nombre']").val(Dimension.Nombre);
        $("input[name='Ponderacion']").val(Dimension.Ponderacion);
        PonderacionAnterior = Dimension.Ponderacion;
    });
    $("body").on("click","#addAfirmacion",function(){
        var idDimension = $(this).attr("dimension");
        var CanSave = CanSavePonderacion(TableAfirmaciones);
        if(CanSave){
            var Template = $("#TemplateAddAfirmacion").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA AFIRMACIÓN",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='Nombre']").val();
                            var Ponderacion = $("input[name='Ponderacion']").val();
                            var DescripcionSimple = $("input[name='DescripcionSimple']").val();
                            var Corte = $("input[name='Corte']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableAfirmaciones,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        if(DescripcionSimple != ""){
                                            if(Corte != ""){
                                                SaveAfirmacion(idDimension);
                                            }else{
                                                bootbox.alert("Debe ingresar un valor de corte");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar una descripción simple");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las afirmaciones suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });
        }else{
            bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idAfirmacion = ID[1];
        var idDimension = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Afirmacion seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteAfirmacion(idAfirmacion,idDimension);
                }
            }
        });
    });
    $("body").on("click",".updateAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idAfirmacion = ID[1];
        var idDimension = ID[0];
        var PonderacionAnterior = 0;
        var Template = $("#TemplateAddAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DE AFIRMACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Ponderacion = $("input[name='Ponderacion']").val();
                        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
                        var Corte = $("input[name='Corte']").val();
                        if(Ponderacion != ""){
                            CanUpdate = CanUpdatePonderacion(TableAfirmaciones,Ponderacion,PonderacionAnterior);
                            if(CanUpdate){
                                if(Nombre != ""){
                                    if(DescripcionSimple != ""){
                                        if(Corte != ""){
                                            UpdateAfirmacion(idAfirmacion,idDimension);
                                        }else{
                                            bootbox.alert("Debe ingresar un valor de corte");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripción simple");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las afirmaciones suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Afirmacion = GetAfirmacion(idAfirmacion);
        $("input[name='Nombre']").val(Afirmacion.Nombre);
        $("input[name='Ponderacion']").val(Afirmacion.Ponderacion);
        $("input[name='DescripcionSimple']").val(Afirmacion.DescripcionSimple);
        $("input[name='Corte']").val(Afirmacion.Corte);
        PonderacionAnterior = Afirmacion.Ponderacion;
    });
    $("body").on("click","#addOpcionAfirmacion",function(){
        var idAfirmacion = $(this).attr("afirmacion");
        var Template = $("#TemplateAddOpcionAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE NUEVA OPCIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Valor = $("input[name='Valor']").val();
                        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
                        if(Nombre != ""){
                            if((Valor != "")){
                                if(DescripcionCaracteristica != ""){
                                    SaveOpcionAfirmacion(idAfirmacion);
                                }else{
                                    bootbox.alert("Debe ingresar una descripción característica");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar un valor valido");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Nombre");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
    });
    $("body").on("click",".removeOpcionAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idOpcionAfirmacion = ID[1];
        var idAfirmacion = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Opcion seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion);
                }
            }
        });
    });
    $("body").on("click",".updateOpcionAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idOpcionAfirmacion = ID[1];
        var idAfirmacion = ID[0];
        var Template = $("#TemplateAddOpcionAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE NUEVA OPCIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Valor = $("input[name='Valor']").val();
                        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
                        if(Nombre != ""){
                            if((Valor != "")){
                                if(DescripcionCaracteristica != ""){
                                    UpdateOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion);
                                }else{
                                    bootbox.alert("Debe ingresar una descripción característica");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar un valor valido");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Nombre");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var OpcionAfirmacion = GetOpcionAfirmacion(idOpcionAfirmacion);
        $("input[name='Nombre']").val(OpcionAfirmacion.Nombre);
        $("input[name='Valor']").val(OpcionAfirmacion.Valor);
        $("input[name='DescripcionCaracteristica']").val(OpcionAfirmacion.DescripcionCaracteristica);
        console.log(OpcionAfirmacion);
    });
    $("body").on("change",".selPauta",function(){

        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        Pauta = ObjectDiv.attr("id");

        if($(this).is(":checked")){
            $(".selPauta").prop("checked",false);
            $(this).prop("checked",true);
            $("#ContenedorCompetencias").show();
        }else{
            $("#ContenedorCompetencias").hide();
        }
        

        getCompetencias();
        UpdateTableCompetencias();

    });
    $("body").on("click","#addPauta",function(){
        var Template = $("#TemplateAddPauta").html();
        bootbox.dialog({
            title: "FORMULARIO DE PAUTAS",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var nombrePauta = $("input[name='NombrePauta']").val();
                        var cedentePauta = $("select[name='TipoContacto']").val();

                        var CanSave = false;

                        if(nombrePauta != ""){
                            if(cedentePauta != ""){
                                CanSave = true;
                            }else{
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        }else{
                            bootbox.alert("Debe indicar una Descripcion para la pauta");
                        }
                        
                        if(CanSave){
                            SavePauta();
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        getTipoContactos();
    });
    $("body").on("click",".updatePauta",function(){

        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPauta = ObjectDiv.attr("id");

        var Template = $("#TemplateAddPauta").html();
        bootbox.dialog({
            title: "ACTUALIZACIÓN DE PAUTA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var nombrePauta = $("input[name='NombrePauta']").val();
                        var cedentePauta = $("select[name='TipoContacto']").val();

                        var CanUpdate = false;

                        if(nombrePauta != ""){
                            if(cedentePauta != ""){
                                CanUpdate = true;
                            }else{
                                bootbox.alert("Debe seleccionar un Cedente");
                            }
                        }else{
                            bootbox.alert("Debe indicar una Descripcion para la pauta");
                        }
                        
                        if(CanUpdate){
                            UpdatePauta(idPauta);
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        getTipoContactos();
        var Pauta = getPauta(idPauta);
        $("input[name='NombrePauta']").val(Pauta.nombrePauta);
        $("select[name='TipoContacto']").val(Pauta.tipoContacto);
        $("select[name='TipoContacto']").selectpicker('refresh');
    });
    $("body").on("click",".removePauta",function(){

        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idPauta = ObjectDiv.attr("id");

        /*bootbox.confirm("¿Desea eliminar la pauta seleccionada?", function(Result) {
            if(Result){
                DeletePauta(idPauta);
            }
        });*/
        
    });
    $("body").on("click",".asignarPautas",function(){

        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCedente = ObjectDiv.attr("id");

        var Template = $("#TemplateAsigacionPautas").html();
        bootbox.dialog({
            title: "ASIGNACION DE PAUTAS",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
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
            }
        });
        getPautasCedentes(idCedente);
        UpdateTablePautasCedentes();
        idCedenteGlobal = idCedente;
    });
    $("body").on("click","#addPautaToCedente",function(){

        var Template = $("#TemplateAddPautaToCedente").html();
        bootbox.dialog({
            title: "ASIGNACION DE PAUTAS",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Pauta = $("select[name='Pauta']").val();
                        if(Pauta != ""){
                            AsignarPauta();
                        }else{
                            bootbox.alert("Debe seleccionar una pauta.");
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        GetPautasWhereNotInCedente();
        $("select[name='Pauta']").selectpicker("refresh");
    });
    $("body").on("click",".desasignarPautas",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idContenedorCedente = ObjectDiv.attr("id");

        bootbox.confirm("¿Desea eliminar la pauta seleccionada?", function(Result) {
            if(Result){
                DeletePautaFromCedente(idContenedorCedente);
            }
        });
    });

    function updateNotaMaximaEvaluacion(){
        var NotaMaximaEvaluacion = $("input[name='NotaMaximaEvaluacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/updateNotaMaximaEvaluacion.php",
            data: {
                NotaMaximaEvaluacion: NotaMaximaEvaluacion,
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        $('.modal').modal('hide')
                        bootbox.alert("La nota maxima ha sido guardada exitosamente");
                    }
                }
            },
            error: function(){
            }
        });
    }
    
    function UpdateTableCompetencias(){
        TableCompetencias = $('#Competencias').DataTable({
            data: CompetenciasDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Nombre' },
                { data: 'Ponderacion' },
                { data: 'ID' },
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='" + data +"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg-square-o btn btn-success btn-icon icon-lg updateCompetencia'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-plus btn btn-purple btn-icon icon-lg addDimensiones'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-trash btn btn-danger btn-icon icon-lg removeCompetencia'></i></div>";
                    }
                }
            ]
        });
    }
    function getNotaMaximaEvaluacion(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getNotaMaximaEvaluacion.php",
            async:false,
            success: function(data){
                console.log(data);
                $("#NotaMaximaEvaluacion").val(data);
            },
            error: function(){
            }
        });
    }
    function getCompetencias(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCompetenciasMantenedor.php",
            data: {
                idPauta: Pauta
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    CompetenciasDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableDimensiones(){
        TableDimensiones = $('#Dimensiones').DataTable({
            data: DimensionesDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Nombre' },
                { data: 'Ponderacion' },
                { data: 'ID' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateDimension'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-plus btn btn-purple btn-icon icon-lg addAfirmaciones'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-trash btn btn-danger btn-icon icon-lg removeDimension'></i></div>";
                    }
                }
            ]
        });
    }
    function getDimensiones(idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetDimensiones.php",
            data: {
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    DimensionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableAfirmaciones(){
        TableAfirmaciones = $('#Afirmaciones').DataTable({
            data: AfirmacionesDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Nombre',width: "40%" },
                { data: 'Ponderacion',width: "10%" },
                { data: 'DescripcionSimple',width: "20%" },
                { data: 'Corte',width: "10%" },
                { data: 'ID',width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 4,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateAfirmacion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-plus btn btn-purple btn-icon icon-lg addOpcion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-trash btn btn-danger btn-icon icon-lg removeAfirmacion'></i></div>";
                    }
                }
            ]
        });
    }
    function getAfirmaciones(idDimension){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetAfirmaciones.php",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    AfirmacionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableOpcionesAfirmaciones(){
        TableOpcionesAfirmaciones = $('#Opciones').DataTable({
            data: OpcionesAfirmacionesDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Nombre', width: "40%" },
                { data: 'Valor', width: "10%" },
                { data: 'DescripcionCaracteristica', width: "40%" },
                { data: 'ID', width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updateOpcionAfirmacion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-trash btn btn-danger btn-icon icon-lg removeOpcionAfirmacion'></i></div>";
                    }
                }
            ]
        });
    }
    function getOpcionesAfirmaciones(idAfirmacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetOpcionesAfirmaciones.php",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    OpcionesAfirmacionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function CanSavePonderacion(Table,ValPonderacion){
        var ToReturn = true;
        var SumPonderacion = 0;
        Table.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            SumPonderacion += Number(data.Ponderacion);
        });
        if(typeof ValPonderacion === "undefined"){ //Si no se le pasa el parametro de valPonderacion, es decir, si solo se esta verificando si se puede o no agregar un row nuevo
            if(SumPonderacion >= 100){
                ToReturn = false;
            }
        }else{ //Si se le pasa el parametro ValPonderacion, es decir, si se esta verificando si la ponderacion nueva mas las ponderaciones actuales son mayores a 100 
            SumPonderacion += Number(ValPonderacion);
            if(SumPonderacion > 100){
                ToReturn = false;
            }
        }
        return ToReturn;
    }
    function CanUpdatePonderacion(Table,ValPonderacion,ValorAnterior){
        if(typeof ValPonderacion === "undefined"){
            ValPonderacion = 0;
        }
        var ToReturn = true;
        var SumPonderacion = 0;
        Table.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            SumPonderacion += Number(data.Ponderacion);
        });
        SumPonderacion -= Number(ValorAnterior);
        SumPonderacion += Number(ValPonderacion);
        if(SumPonderacion > 100){
            ToReturn = false;
        }
        return ToReturn;
    }
    function SaveCompetencia(){
        var Nombre = $("input[name='NombreCompetencia']").val();
        var Descripcion = $("input[name='DescripcionCompetencia']").val();
        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
        var Tag = $("input[name='TagCompetencia']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveCompetencia.php",
            data: {
                idPauta: Pauta,
                Nombre: Nombre,
                Descripcion: Descripcion,
                Ponderacion: Ponderacion,
                Tag: Tag
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteCompetencia(idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteCompetencia.php",
            data: {
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }else{
                        bootbox.alert(json.Message);
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetCompetencia(idCompetencia){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCompetencia.php",
            data: {
                idCompetencia: idCompetencia
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
    function UpdateCompetencia(idCompetencia){
        var Nombre = $("input[name='NombreCompetencia']").val();
        var Descripcion = $("input[name='DescripcionCompetencia']").val();
        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
        var Tag = $("input[name='TagCompetencia']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateCompetencia.php",
            data: {
                idCompetencia: idCompetencia,
                Nombre: Nombre,
                Descripcion: Descripcion,
                Ponderacion: Ponderacion,
                Tag: Tag
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveDimension(idCompetencia){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveDimension.php",
            data: {
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteDimension(idDimension,idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteDimension.php",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }else{
                        bootbox.alert(json.Message);
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetDimension(idDimension){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetDimension.php",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateDimension(idDimension,idCompetencia){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateDimension.php",
            data: {
                idDimension: idDimension,
                Nombre: Nombre,
                Ponderacion: Ponderacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveAfirmacion(idDimension){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
        var Corte = $("input[name='Corte']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveAfirmacion.php",
            data: {
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                DescripcionSimple: DescripcionSimple,
                Corte: Corte,
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteAfirmacion(idAfirmacion,idDimension){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteAfirmacion.php",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }else{
                        bootbox.alert(json.Message);
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetAfirmacion(idAfirmacion){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetAfirmacion.php",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateAfirmacion(idAfirmacion,idDimension){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
        var Corte = $("input[name='Corte']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateAfirmacion.php",
            data: {
                idAfirmacion: idAfirmacion,
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                DescripcionSimple: DescripcionSimple,
                Corte: Corte
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveOpcionAfirmacion(idAfirmacion){
        var Nombre = $("input[name='Nombre']").val();
        var Valor = $("input[name='Valor']").val();
        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveOpcionAfirmacion.php",
            data: {
                Nombre: Nombre,
                Valor: Valor,
                DescripcionCaracteristica: DescripcionCaracteristica,
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteOpcionAfirmacion.php",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }else{
                        bootbox.alert(json.Message);
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetOpcionAfirmacion(idOpcionAfirmacion){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetOpcionAfirmacion.php",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion){
        var Nombre = $("input[name='Nombre']").val();
        var Valor = $("input[name='Valor']").val();
        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateOpcionAfirmacion.php",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion,
                Nombre: Nombre,
                Valor: Valor,
                DescripcionCaracteristica: DescripcionCaracteristica
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function getPautas(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetPautasMantenedor.php",
            data: {},
            async: false,
            success: function(data){
                if(isJson(data)){
                    PautasDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTablePautas(){
        TablePautas = $('#Pautas').DataTable({
            data: PautasDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Nombre' },
                { data: 'TipoContacto' },
                { data: 'seleccion' },
                { data: 'ID' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><input class='toggle-switch selPauta' type='checkbox'><label class='toggle-switch-label'></label></div>";
                    }
                },
                {
                    "targets": 3,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg updatePauta'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-trash btn btn-danger btn-icon icon-lg removePauta'></i></div>";
                    }
                }
            ]
        });
    }
    function getTipoContactos(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipoContactos.php",
            data: {},
            async: false,
            success: function(data){
                $("select[name='TipoContacto']").html(data);
                $("select[name='TipoContacto']").selectpicker('refresh');
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    function SavePauta(){
        var nombrePauta = $("input[name='NombrePauta']").val();
        var tipoPauta = $("select[name='TipoContacto']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SavePauta.php",
            data: {
                tipoPauta: tipoPauta,
                nombrePauta: nombrePauta
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getPautas();
                        UpdateTablePautas();
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    function getPauta(idPauta){
        var ToReturn = [];
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetPauta.php",
            data: {
                idPauta: idPauta
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(err){
                console.log(err);
            }
        });
        return ToReturn;
    }
    function UpdatePauta(idPauta){
        var nombrePauta = $("input[name='NombrePauta']").val();
        var idContacto = $("select[name='TipoContacto']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdatePauta.php",
            data: {
                idPauta: idPauta,
                idContacto: idContacto,
                nombrePauta: nombrePauta
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getPautas();
                        UpdateTablePautas();
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    function UpdatePauta(idPauta){
        var nombrePauta = $("input[name='NombrePauta']").val();
        var idContacto = $("select[name='TipoContacto']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdatePauta.php",
            data: {
                idPauta: idPauta,
                idContacto: idContacto,
                nombrePauta: nombrePauta
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    data = JSON.parse(data);
                    if(data.result){
                        getPautas();
                        UpdateTablePautas();
                    }
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
    function getCedentesMandante(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCedentesMandante.php",
            data: {},
            async: false,
            success: function(data){
                if(isJson(data)){
                    CedentesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableCedentesMandante(){
        TableCedentes = $('#Cedentes').DataTable({
            data: CedentesDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Cedente' },
                { data: 'Pautas' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg asignarPautas'></i></div>";
                    }
                }
            ]
        });
    }
    function getPautasCedentes(idCedente){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetPautasCedentes.php",
            data: {
                idCedente: idCedente
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    PautasCedentesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTablePautasCedentes(){
        TablePautasCedentes = $('#asignacionPautas').DataTable({
            data: PautasCedentesDataSet,
            "bDestroy": true,
            columns: [
                { data: 'Pauta' },
                { data: 'Accion' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa fa-trash btn btn-danger btn-icon icon-lg desasignarPautas'></i></div>";
                    }
                }
            ]
        });
    }
    function GetPautasWhereNotInCedente(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetPautasWhereNotInCedente.php",
            data: {
                idCedente: idCedenteGlobal
            },
            async: false,
            success: function(data){
                $("select[name='Pauta']").html(data);
            },
            error: function(){
            }
        });
    }
    function AsignarPauta(){
        var Pauta = $("select[name='Pauta']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/asignarPautaToCedente.php",
            data: {
                idCedente: idCedenteGlobal,
                idPauta: Pauta
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    json = JSON.parse(data);
                    if(json.result){
                        getPautasCedentes(idCedenteGlobal);
                        UpdateTablePautasCedentes();
                        getCedentesMandante();
                        UpdateTableCedentesMandante();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeletePautaFromCedente(idContenedorCedente){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/desasignarPautaFromCedente.php",
            data: {
                idContenedorCedente: idContenedorCedente
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    json = JSON.parse(data);
                    if(json.result){
                        getPautasCedentes(idCedenteGlobal);
                        UpdateTablePautasCedentes();
                        getCedentesMandante();
                        UpdateTableCedentesMandante();
                    }
                }
            },
            error: function(){
            }
        });
    }
});
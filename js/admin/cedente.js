$(document).ready(function() {
    var tablaCedente;
    var tablaMandante;
    //listarCedentes();
    listarMandantes();
    var id_mandante;

    function listarCedentes(ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetListarCedentesMandantes.php",
            data: {idMandante: ID},
            //dataType: "json",
            success: function(data){
                TablaCedente = $('#listaCedentes').DataTable({                    
                    data: JSON.parse(data),
                    //data: data, // este es mi json
                    paging: true,
                    columns: [
                        { data : 'NombreCedente',"width": "80%", }, // campos que trae el json
                        { data: 'idCedente',"width": "20%", }
                    ],
                     "columnDefs": [
                        {
                            "targets": 0,
                            "data": 'NombreCedente',
                        },
                        {
                            "targets": 1,
                            "data": 'idCedente',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='" + data +"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg modificaCedente'></i><i style='cursor: pointer; margin: 0 10px;' class='btn eliminarCedente fa fa-trash btn-danger btn-icon icon-lg'></i></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(){
                alert('erroryujuuu2');
            }
        });
    }

    function listarMandantes(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetListar_mandantes.php",
            //data: data,
            //dataType: "json",
            success: function(data){
                // console.log(data);
                TablaMandante = $('#listaMandantes').DataTable({                   
                    data: JSON.parse(data), // este es mi json
                    paging: false,
                    //"scrollX": false,
                    columns: [
                        { data : 'nombre' }, // campos que trae el json
                        { data: 'id' }                        
                    ],
                     "columnDefs": [                      
                        {
                            "targets": 1,
                            "data": 'Actions', //<i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificar'>
                            "render": function( data, type, row ) {
                                // return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-gear btn btn-primary btn-icon icon-lg listarCedentes' title='Proyectos'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg modificaMandante'></i><i style='cursor: pointer; margin: 0 10px;' class='btn eliminarMandante fa fa-trash btn-danger btn-icon icon-lg'></i></div>";
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-gear btn btn-primary btn-icon icon-lg listarCedentes' title='Proyectos'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pen btn btn-success btn-icon icon-lg modificaMandante'></i></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(response){
                alert(response);
                console.log(response);
            }
        });
    }    

    $('body').on( 'click', '#AddCedente', function () {
       bootbox.dialog({
            title: "Crear proyecto",
            message: $("#RegistrarCedente").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombreCedente = $('#nombreCedente').val();
                        var fechaIngreso = $('#fechaIngreso').val();
                        var plan = $('#PlanDiscado').val();
                        var discado = $('#TipoOperacion').val();
                        var ipdiscador = plan == "0" ? $("#IPDiscador").val() : ""; //si - no
                        var dialplan = plan == "0" ? $("#DialPlan").val() : ""; //si - no
                        var datos = { 'nombreCedente':nombreCedente, 'fechaIngreso':fechaIngreso, 'idMandante':id_mandante, 'plan':plan, 'discado':discado, 'ipdiscador':ipdiscador, 'dialplan':dialplan };

                        if ((nombreCedente == 0) || (nombreCedente == ""))
                        {
                          CustomAlert("Debe ingresar el nombre del proyecto");
                          return false;
                        }
                        if ((fechaIngreso == 0) || (fechaIngreso == "") || (fechaIngreso == null))
                        {
                          CustomAlert("Debe seleccionar la fecha de ingreso del proyecto");
                          return false;
                        }
                        if ((discado == ""))
                        {
                            //CustomAlert("Debe ingresar un Tipo de Operación");
                            //return false;
                            discado = '1';
                        }
                        if ((plan == ""))
                        {
                            //CustomAlert("Debe ingresar un plan de plan");
                            //return false;
                            plan = 1;

                        }
                        if ((plan != ""))
                        {
                            ipdiscador = '';
                            dialplan = '';
                            /*
                            if ((ipdiscador == "") && (plan == "0"))
                            {
                                CustomAlert("Debe ingresar ip del servidor de discado");
                                return false;
                            }
                            if ((dialplan == "") && (plan == "0"))
                            {
                                CustomAlert("Debe ingresar ip del servidor de discado");
                                return false;
                            }
                            */
                        }
                        addCedente(datos);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
       if(GlobalData.focoConfig.tipoMenu.indexOf("foco") >= 0){
            $("#focoOptions").show();
        }
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen(); 
       $('.selectpicker').selectpicker("refresh");
       $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
    }); 


    $('body').on( 'click', '.modificaMandante', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr"); 
        bootbox.dialog({
            title: "Modificar empresa",
            message: $("#modificarMandante").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreMandante').val();
                        var evaluar = $('#evaluar').val();
                        if ((nombre == 0) || (nombre == ""))
                        {
                            CustomAlert("Debe ingresar el nombre de la empresa");
                            return false;
                        }
                        var datos = {'nombre':nombre, 'evaluar':evaluar, 'id':ID};           
                        modificarMandante(datos);
                        
                    }
                }                
            }
        }).off("shown.bs.modal");
        if(GlobalData.focoConfig.tipoMenu.indexOf("cal") >= 0){
            $("#EmpiezaEvaluacion").show();
        }
        $(".selectpicker").selectpicker("refresh");
        getDatosMandante(ID);
        //FiltrarTablas(GlobalData.id_cedente);
        //resetearCombo();
        //AddClassModalOpen();
    }); 

    $('body').on( 'click', '.modificaCedente', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr"); 
        bootbox.dialog({
            title: "Modificar proyecto",
            message: $("#modificaCedente").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreCedente').val();
                        var fechaIngreso = $('#fechaIngreso').val();
                        var plan = $('#PlanDiscado').val();
                        var discado = $('#TipoOperacion').val();
                        var ipdiscador = plan == "0" ? $("#IPDiscador").val() : ""; //si - no
                        var dialplan = plan == "0" ? $("#DialPlan").val() : ""; //si - no
                        var tipo_refresco = $('#tipo_refresco').val();

                        if ((fechaIngreso == 0) || (nombre == ""))
                        {
                            CustomAlert("Debe ingresar el nombre del proyecto");
                            return false;
                        }
                        if ((fechaIngreso == 0) || (fechaIngreso == "") || (fechaIngreso == null))
                        {
                            CustomAlert("Debe seleccionar la fecha de ingreso del proyecto");
                            return false;
                        }/*
                        if ((discado == ""))
                        {
                            CustomAlert("Debe ingresar un Tipo de Operación");
                            return false;
                        }
                        if ((plan == ""))
                        {
                            CustomAlert("Debe ingresar un plan de plan");
                            return false;
                        }
                        if ((plan != ""))
                        {
                            if ((ipdiscador == "") && (plan == "0"))
                            {
                                CustomAlert("Debe ingresar ip del servidor de discado");
                                return false;
                            }
                            if ((dialplan == "") && (plan == "0"))
                            {
                                CustomAlert("Debe ingresar ip del servidor de discado");
                                return false;
                            }
                        }
                        */
                        var datos = $('#form_cedente').serialize()+"&id="+ID
                        modificarCedente(datos,ObjectTR);
                        
                    }
                }                
            }
        }).off("shown.bs.modal");
        if(GlobalData.focoConfig.tipoMenu.indexOf("foco") >= 0){
            $("#focoOptions").show();
        }
        $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
        $(".selectpicker").selectpicker("refresh");
        getDatosCedente(ID);
        //FiltrarTablas(GlobalData.id_cedente);
        //resetearCombo();
        //AddClassModalOpen();
    });

    $("body").on("change","#PlanDiscado",function(){
        if ($(this).is(":checked")){
            $("#IPDiscadorContainer").hide();
        }else{
            $("#IPDiscadorContainer").show();
        }
    });
    $("body").on("change", "#agendamiento", function () {
        if ($(this).is(":checked")) {
            $("#AgendamientoContainer").show();
        } else {
            $("#AgendamientoContainer").hide();
        }
    });
    function modificarCedente(datos,TableRow){  
        $.ajax({
            type: "POST",
            url: "../includes/admin/modificar_cedente.php",
            data: datos,
            async: false,
            dataType: "json",
            success: function(data){
                TableRow.find("td:eq(0)").text(data.nombreCedente)
                CustomAlert("¡Proyecto modificado con éxito!");
            },
            error: function(){
                alert('error');
            }
        });
    }

    function getDatosMandante(idMandante)   
    {
    $.ajax({
        type:"POST",
        data: {idMandante: idMandante},
        //dataType: "json",
        url:"../includes/admin/GetMostrarMandante.php",
        success:function(data){  
        data = JSON.parse(data);
         console.log(data);

            $('#nombreMandante').val(data[0].nombre);
            $('#evaluar').val(data[0].Empieza); 
            $('#evaluar').selectpicker("refresh");          
            
          },
          error: function(){             
            alert('errorrrrrrDatostrabajador');
          }          
    });
  }

   function getDatosCedente(idCedente)   
    {
    $.ajax({
        type:"POST",
        data: {idCedente: idCedente},
        //dataType: "json",
        url:"../includes/admin/GetMostrarCedente.php",
        async: false,
        success:function(data){  
            data = JSON.parse(data);
            console.log(data);

            $('#nombreCedente').val(data[0].Nombre_Cedente);
            $('#fechaIngreso').val(data[0].Fecha_Ingreso);
            $('#TipoOperacion').val(data[0].tipo);
            $('#tipo_refresco').val(data[0].tipo_refresco); 
            $('#IPDiscador').val(data[0].IPDiscador);
            $('#DialPlan').val(data[0].DialPlan);
            if(data[0].planDiscado == "0"){
                $('#IPDiscadorContainer').show();
            }else{
                $('#PlanDiscado').prop('checked', true);
            }
            if (data[0].posee_speech == 1){
                $('#posee_speech').prop('checked',true);
            }
            if (data[0].omnicanalidad == 1) {
                $('#omnicanalidad').prop('checked', true);
            }
            if (data[0].compromiso == 1) {
                $('#compromiso').prop('checked', true);
            }
            if (data[0].agendamiento == 1) {
                $('#AgendamientoContainer').show();
                $('#agendamiento').prop('checked', true);
            }
            if (data[0].facturas == 1) {
                $('#facturas').prop('checked', true);
            }
            if (data[0].posee_scoring == 1) {
                $('#posee_scoring').prop('checked', true);
            }
            if (data[0].carga_personalizada == 1) {
                $('#carga_personalizada').prop('checked', true);
            }
            if (data[0].agendamiento_obligatorio == 1) {
                $('#agendamiento_obligatorio').prop('checked', true);
            }
            $('#algoritmo_discado').val(data[0].algoritmo_discado);
            $('.selectpicker').selectpicker("refresh");
        },
        error: function(){             
            alert('error');
        }          
    });
  }


    $('body').on( 'click', '#AddMandante', function () {
       bootbox.dialog({
            title: "Crear empresa",
            message: $("#RegistrarMandante").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreMandante').val();
                        var evaluar = $('#evaluar').val();                         
                        if ((nombreMandante == 0) || (nombreMandante == ""))
                        {
                          CustomAlert("Debe ingresar el nombre de la empresa");
                          return false;
                        } 
                        var datos = {'nombre':nombre, 'evaluar':evaluar};                
                        addMandante(datos);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
       if(GlobalData.focoConfig.tipoMenu.indexOf("cal") >= 0){
           $("#EmpiezaEvaluacion").show();
       }
       $(".selectpicker").selectpicker("refresh");
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen();
    }); 


    function addCedente(datos){    
        $.ajax({
            type: "POST",
            url: "../includes/admin/crear_cedente.php",
            dataType: "html",
            data: datos,
            success: function(data){        
                CustomAlert("¡Proyecto creado con éxito!");
                //location.reload();
                    TablaCedente.row.add(
                        {
                            "NombreCedente": datos['nombreCedente'],
                            "idCedente": data // OJOOOOOOOOOOO
                        }
                   ).draw(false);         
            },
            error: function(){
                alert('error');
            }
        });
    }

     function modificarMandante(datos){    
        $.ajax({
            type: "POST",
            url: "../includes/admin/modificar_mandante.php",
            dataType: "html",
            data: datos,
            success: function(data){       
                CustomAlert("¡Empresa actualizada con éxito!");
                location.reload();              
            },
            error: function(){
                alert('error');
            }
        });
    }

    



    function addMandante(datos){      
        $.ajax({
            type: "POST",
            url: "../includes/admin/crear_mandante.php",
            dataType: "html",
            data: datos,
            success: function(data){
                CustomAlert("¡Empresa creada con éxito!");
                location.reload();
                    /*TablaCedente.row.add(
                        {
                            "fechaTermino": nombre,
                            "Actions": idCedente // OJOOOOOOOOOOO
                        }
                   ).draw(false);    */            
            },
            error: function(){
                alert('error');
            }
        });
    }

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

    $("body").on("click",".eliminarCedente", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar el proyecto?", function(result) {
            if (result) {                
                eliminarCedente(ObjectTR, ID);                
            }
            //AddClassModalOpen();
        });
    }); 


     $("body").on("click",".eliminarMandante", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar la empresa?.. También se eliminaran sus proyectos automaticamente", function(result) {
            if (result) {
                eliminaMandante(ObjectTR, ID);                
            }
            //AddClassModalOpen();
        });
    }); 


    $("body").on("click",".listarCedentes", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        id_mandante = ID;
        bootbox.dialog({
            title: "Lista de proyectos",
            message: $("#listaCedente").html(),
            size: 'large'
       }).off("shown.bs.modal");
       listarCedentes(ID);        
        
    }); 

    function eliminarCedente(TableRow, ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/eliminar_cedente.php",
            dataType: "html",
            data: {
                idCedente: ID
            },
            success: function(data){
                CustomAlert("El proyecto ha sido eliminado");
                TablaCedente.row(TableRow).remove().draw();
                $("#listaCedente").trigger('update');                
            },
            error: function(){

            }
        });
    } 


    function eliminaMandante(TableRow, ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/eliminar_mandante.php",
            dataType: "html",
            data: {
                idMandante: ID
            },
            success: function(data){
                CustomAlert("La empresa ha sido eliminada con éxito");
                location.reload();
            },
            error: function(){

            }
        });
    }   

});    
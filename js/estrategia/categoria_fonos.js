$(document).ready(function(){

    var CategoriaArray = [];
    var CategoriaTable;

    getCategoriaTableList();
    updateCategoriaTableList();

    function getCategoriaTableList(Modal = true) {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/getCategoriaTableList.php",
            dataType: "html",
            async: false,
            beforeSend: function () {
                if (Modal) {
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                CategoriaArray = [];
            },
            success: function (data) {
                console.log(data);
                $('#Cargando').modal('hide');
                if (isJson(data)) {
                    CategoriaArray = JSON.parse(data);
                }
            }
        });
    }
    function updateCategoriaTableList() {
        CategoriaTable = $('#CategoriaTable').DataTable({
            data: CategoriaArray,
            "bDestroy": true,
            "order": [[2, 'asc']],
            columns: [
                { data: 'color_nombre' },
                { data: 'color_hex' },
                { data: 'prioridad' },
                { data: 'tipo_var' },
                { data: 'dias' },
                { data: 'cond1' },
                { data: 'cant1' },
                { data: 'logica' },
                { data: 'cond2' },
                { data: 'cant2' },
                { data: 'id' }
            ],
            "columnDefs": [
                {
                    "targets": 1,
                    "searchable": false,
                    "data": "color",
                    "render": function (data, type, row) {
                        return "<center><input type='text' readonly='readonly' style='background:"+data+"; width: 30px;' /></center>";
                    }
                },
                {
                    "targets": 10,
                    "searchable": false,
                    "data": "id",
                    "render": function (data, type, row) {
                        if (row.color != 0) {
                            return "<div style='text-align: center;' id=" + data + "><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-success btn-icon icon-lg Update'></i><i style='cursor: pointer; margin: 0 10px;' class='btn fa fa-trash btn-danger btn-icon icon-lg Delete'></i></div>";
                        } else {
                            return "";
                        }
                    }
                },
            ]
        });
    }

    $('body').on('click', '#CrearCategoria', function () {
        bootbox.dialog({
            title: "Creación Categoria Fono",
            message: $("#CategoriaTemplate").html(),
            buttons: {
                success: {
                    label: "Agregar",
                    className: "btn-primary",
                    callback: function () {

                        var tipoContacto = $('#tipo_contacto').val();
                        var color = $('#color').val();
                        var condicion1 = $('#cond1').val();
                        var condicion2 = $('#cond2').val();
                        var logica = $('#logica').val();
                        var cantidad1 = $('#cant1').val();
                        var cantidad2 = $('#cant2').val();
                        var dias = $('#dias').val();
                        var prioridad = $('#prioridad').val();
                        var nombreContacto = $("#tipo_contacto option:selected").html();

                        if ((tipoContacto == 0) || (color == 0) || (dias == "") || (cantidad1 == "") || (prioridad == "")) {
                            CustomAlert("Debe ingresar todos los datos");
                            return false;
                        }
                        if (logica == 1) {
                            cantidad2 = "";
                            condicion2 = "";
                        } else {
                            if ((cantidad2 == "") || (condicion2 == 0)) {
                                CustomAlert("Debe ingresar todos los datos");
                                return false;
                            }
                        }
                        var datos = { 'tipoContacto': tipoContacto, 'color': color, 'condicion1': condicion1, 'condicion2': condicion2, 'logica': logica, 'cantidad1': cantidad1, 'cantidad2': cantidad2, 'dias': dias, 'nombreContacto': nombreContacto, 'prioridad': prioridad };
                        CrearCategoria(datos);
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        mostrarCategoriaFonos();
        mostrarTipoContacto();
        logica();
    });

    function CrearCategoria(datos) {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/crearCategoria.php",
            dataType: "html",
            data: datos,
            success: function (data) {
                getCategoriaTableList(false);
                updateCategoriaTableList();
            }
        });
    }

    $('body').on( 'click', '.Update', function () {   
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCategoria = ObjectDiv.attr("id");   
        bootbox.dialog({
            title: "Modificar Categoria Fono",
            message: $("#CategoriaTemplate").html(),
            buttons: {
                success: {
                    label: "Modificar",
                    className: "btn-primary",
                    callback: function() {

                        var tipoContacto = $('#tipo_contacto').val();  
                        var color = $('#color').val();  
                        var condicion1 = $('#cond1').val();  
                        var condicion2 = $('#cond2').val();        
                        var logica = $('#logica').val(); 
                        var cantidad1 = $('#cant1').val();
                        var cantidad2 = $('#cant2').val();
                        var dias = $('#dias').val();
                        var prioridad = $('#prioridad').val();
                        var nombreContacto = $("#tipo_contacto option:selected").html(); 

                        if ((tipoContacto == 0) || (color == 0) || (dias == "") || (cantidad1 == "") || (prioridad == "")){
                            CustomAlert("Debe ingresar todos los datos");
                            return false;
                        }  
                        if (logica == 1){
                            cantidad2 = "";
                            condicion2 = "";
                        }else{
                            if ((cantidad2 == "") || (condicion2 == 0)){
                                CustomAlert("Debe ingresar todos los datos");
                                return false;
                            } 
                        }

                        var datos = {'tipoContacto':tipoContacto, 'color':color, 'condicion1':condicion1, 'condicion2':condicion2, 'logica':logica, 'cantidad1':cantidad1, 'cantidad2':cantidad2, 'dias':dias, 'idCategoria':idCategoria, 'nombreContacto':nombreContacto, 'prioridad':prioridad};
                        UpdateCategoria(datos);    
                    }
                }                
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        mostrarCategoriaFonos();
        mostrarTipoContacto();
        getDatosCategoria(idCategoria);
        logica();      
    });

    function logica(){
        var logica = $('#logica').val();
        if (logica != 1){     
            $('#cant2').removeAttr("disabled");
        }else{
            $('#cant2').val(0);
            $('#cant2').attr('disabled', 'disabled');        
            $('#cond2').val(0); 
        }
    }

    $(document).on('change', '#logica', function(){
        logica();    
    });
	
    function UpdateCategoria(datos){      
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/updateCategoria.php",
            dataType: "html",
            data: datos,
            success: function(data){                
                getCategoriaTableList(false);
                updateCategoriaTableList();          
            }
        });
    }

    function mostrarCategoriaFonos(){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/GetListarCategoria.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='color']").html(data);
                $("select[name='color']").selectpicker('refresh');
            }
        });
    }

    function mostrarTipoContacto(){
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/GetListarTipoContacto.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='tipo_contacto']").html(data);
                $("select[name='tipo_contacto']").selectpicker('refresh');
            }
        });
    }

    function getDatosCategoria(idCategoria){
        $.ajax({
            type:"POST",
            data: {idCategoria: idCategoria},
            async: false,
            url:"../includes/estrategia/categoria_fonos/GetListarDatosCategoria.php",
            success:function(data){  
                data = JSON.parse(data);
                console.log(data);
                data = data[0];  
                $('#tipo_contacto').val(data.idTipoContacto);  
                $('#color').val(data.idColor);  
                $('#cond1').val(data.condicion1);  
                $('#cond2').val(data.condicion2);        
                $('#logica').val(data.logica); 
                if (data.logica == 1){
                    $('#cond2').val(0); 
                }
                $('#cant1').val(data.cantidad1);
                $('#cant2').val(data.cantidad2);
                $('#dias').val(data.dias);
                $('#prioridad').val(data.prioridad);
                $(".selectpicker").selectpicker("refresh");
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
    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var id = ObjectDiv.attr("id");

        bootbox.confirm({
            message: "¿Esta seguro de eliminar la categoria seleccionada?",
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
                    deleteCategoria(id);
                }
            }
        });
    });
    function deleteCategoria(id) {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/categoria_fonos/deleteCategoria.php",
            dataType: "html",
            data: {
                id: id
            },
            async: false,
            beforeSend: function () {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function (data) {
                getCategoriaTableList(false);
                updateCategoriaTableList();
            }
        });
    }
});
$(document).ready(function() {
    $('body').on('focus', ".dateEstrategia", function () {
        $('.dateEstrategia').datepicker({
            format: "dd-mm-yyyy",
            weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es',
            defaultDate: new Date()
        });
    });

    $('.InputPorcentaje').mask('000');
    var idCola;

    function ReadOnly() {
        //$('#SeleccioneTipoEstrategia').prop("disabled", true);
        //$('#SeleccioneTipoEstrategia').val('').selectpicker('refresh');
        $('#SeleccioneTabla').prop( "disabled", true );
        $('#SeleccioneTabla').val('').selectpicker('refresh');
        $('#SeleccioneColumna').prop( "disabled", true );
        $('#SeleccioneColumna').val('').selectpicker('refresh');
        $('#SeleccioneLogica').prop( "disabled", true );
        $('#SeleccioneLogica').val('').selectpicker('refresh');
        $('#SeleccioneValor').prop( "disabled", true );
        $('#SeleccioneValor').val('');
        $('#SeleccioneValor').val('').selectpicker('refresh');
        $('.IntegerValidar').prop( "disabled", true );
        $('.IntegerValidar').val('');
        $('.IntegerValidar2').prop( "disabled", true );
        $('.IntegerValidar2').val('');
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');       
    }

    function ReadOnlyTablas() {
        $('#SeleccioneTabla').prop( "disabled", true );
        $('#SeleccioneTabla').val('').selectpicker('refresh');
        $('#SeleccioneColumna').prop( "disabled", true );
        $('#SeleccioneColumna').val('').selectpicker('refresh');
        $('#SeleccioneLogica').prop( "disabled", true );
        $('#SeleccioneLogica').val('').selectpicker('refresh');
        $('#SeleccioneValor').prop( "disabled", true );
        $('#SeleccioneValor').val('');
        $('#SeleccioneValor2').prop( "disabled", true );
        $('#SeleccioneValor2').val('');
        $('.IntegerValidar').prop( "disabled", true );
        $('.IntegerValidar').val('');
        $('.IntegerValidar2').prop( "disabled", true );
        $('.IntegerValidar2').val('');
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
    }

    function ReadOnlyColumnas() {
        $('#SeleccioneColumna').prop( "disabled", true );
        $('#SeleccioneColumna').val('').selectpicker('refresh');
        $('#SeleccioneLogica').prop( "disabled", true );
        $('#SeleccioneLogica').val('').selectpicker('refresh');
        $('#SeleccioneValor').prop( "disabled", true );
        $('#SeleccioneValor').val('');
        $('#SeleccioneValor2').prop( "disabled", true );
        $('#SeleccioneValor2').val('');
        $('.IntegerValidar').prop( "disabled", true );
        $('.IntegerValidar').val('');
        $('.IntegerValidar2').prop( "disabled", true );
        $('.IntegerValidar2').val('');
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
        //New
        $('div#inputColumna').hide();
        $('div#inputColumnaContent').html('&nbsp;');
    }

    function ReadOnlyLogica() {
        $('#SeleccioneLogica').prop( "disabled", true );
        $('#SeleccioneLogica').val('').selectpicker('refresh');
        $('#SeleccioneValor').prop( "disabled", true );
        $('#SeleccioneValor').val('');
        $('#SeleccioneValor2').prop( "disabled", true );
        $('#SeleccioneValor2').val('');
        $('.IntegerValidar').prop( "disabled", true );
        $('.IntegerValidar').val('');
        $('.IntegerValidar2').prop( "disabled", true );
        $('.IntegerValidar2').val('');
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
        //New
        $('div#inputLogica').hide();
        $('div#inputLogicaContent').html('&nbsp;');
    }

    function ReadOnlyValor() {
        console.info('Limpiar Valor');
        var htmlCampo = `<div class="input-group">`;
        htmlCampo += `<span class="input-group-addon" id="sizing-addon1">...</span>`;
        htmlCampo += `<input type="text" class="form-control sin-bordes-izquierdos" id="SeleccioneValor" value="" disabled>`;
        htmlCampo += `</div>`;
        $('div#DivValor').html(htmlCampo);
        $('div#DivValor2').html(htmlCampo);
        /*
        $('#SeleccioneValor').prop( "disabled", true );
        $('#SeleccioneValor').val('');
        $('#SeleccioneValor2').prop( "disabled", true );
        $('#SeleccioneValor2').val('');
        */
        $('.IntegerValidar').prop( "disabled", true );
        $('.IntegerValidar').val('');
        $('.IntegerValidar2').prop( "disabled", true );
        $('.IntegerValidar2').val('');
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
    }

    function ReadOnlyCola() {
        $('#NombreCola').prop( "disabled", true );
        $('#NombreCola').val('');
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
    }

    function ReadOnlyBoton() {
        $('#CrearEstrategia').prop( "disabled", true );
        $('#CrearEstrategia').val('');
    }

    function ReadOnlyTipo() {
        $('#SeleccioneTabla').prop( "disabled", true );
        $('#SeleccioneTipoEstrategia').selectpicker('refresh'); 
        $('#SeleccioneColumna').prop( "disabled", true );
        $('#SeleccioneTipoEstrategia').selectpicker('refresh');  
        $('#SeleccioneLogica').prop( "disabled", true );
        $('#SeleccioneTipoEstrategia').selectpicker('refresh');  
    }

    function NoReadOnly() {
        $('#SeleccioneTipoEstrategia').prop("disabled", false);
        $('#SeleccioneTipoEstrategia').selectpicker('refresh');  
    }

    var IdEstrategia = $('#IdEstrategia').val();
    var DataIdEstrategia = "IdEstrategia="+IdEstrategia;
    if($("#container").hasClass("isVerEstrategia")) MostrarEstrategias(DataIdEstrategia);    
    var IdCedente = $('#IdCedente').val();
    var DataTotal = "IdCedente="+IdCedente;
    
    function Registros(DataTotal) {
        $('div#msgRegistros').hide();
        $.ajax(
        {
            type: "POST",
            url: "../includes/estrategia/Total.php",
            data: DataTotal,
            success: function(response)
            {
                $('div#msgRegistros').hide();
                console.log('Respuesta: ', response);
                $("#DivRegistros").html(response);
                if (response.toString().trim() !== '') {
                    console.log('Mostrar');
                    $('div#msgRegistros').show();
                }
            }
        });   
    }

    if($("#container").hasClass("isVerEstrategia")) Registros(DataTotal);    
    //$(document).on('change', '#SeleccioneTipoEstrategia', function() {
    function startTypeEstrategies() {
        var IdTipoEstrategia = '0'; //$('#SeleccioneTipoEstrategia').val();
        $('#Between').hide();
        $('div#divFormularioSegmentacion').show();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        /*
        if (IdTipoEstrategia=='-1') {
            ReadOnlyTablas();
            $('#Between').hide();            
        } else {
            */
            var IdCedente = $('#IdCedente').val();
            var IdEstrategia = $('#IdEstrategia').val();
            var DataIdCedente = "IdCedente="+IdCedente+"&IdTipoEstrategia="+IdTipoEstrategia+"&IdEstrategia="+IdEstrategia;
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/MostrarTabla.php",
                data:DataIdCedente,
                success: function(response)
                {
                    if(response == 0) {
                        $.niftyNoty(
                        {
                            type: 'danger',
                            icon : 'fa fa-close',
                            message : 'Debe crear una estrategia Estatica!' ,
                            container : 'floating',
                            timer : 2000
                        });
                        ReadOnlyTablas(); 
                    } else {
                        $('#selTable').html(response);
                        $('#SeleccioneTabla').selectpicker('refresh');
                    }                    
                }
            }); 
        //}        
    };

    function MostrarEstrategias(DataIdEstrategia) {
        const divContenido = $('#DivMostrarEstrategias');
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/segmentacion.php",
            data: DataIdEstrategia,
            beforeSend: function (){
                divContenido.html('<h2>Bucando información, por favor espere...</h2>');
            },
            success: function(response) {
                console.log('Respuesta: ', response);
                if(response == 0) {                    
                    let html = '<icon class="fa fa-warning"></icon>&nbsp;&nbsp;No hay datos';
                    divContenido.html(html);
                    $('#Deshacer').prop( "disabled", true );
                    NoReadOnly();
                    startTypeEstrategies();                   
                } else {
                    ReadOnly();
                    divContenido.html(response);
                }                
            }
        });   
    }

    function cancelarFormulario() {
        $('div#msgRegistros').hide();
        $('div#msgRegistros').html('&nbsp;');
        $('div#divFormularioSegmentacion').hide();
        ReadOnlyColumnas();
        ReadOnlyLogica();
        ReadOnlyValor();
        $('select#selTable').val('');
    }

    $(document).on('click', 'button#resetForm', function () {
        console.info('Reiniciar formulario');
        ReadOnlyColumnas();
        ReadOnlyLogica();
        ReadOnlyValor();
        $('select#selTable').val('');
    });

    $(document).on('click','button#cancelForm', function () {
        cancelarFormulario();
    });

    //$(document).on('change', '#SeleccioneTabla', function() {
    $(document).on('change', 'select#selTable', function () {
        var IdTabla = this.value;
        const divColumnas = $('div#inputColumna');
        ReadOnlyColumnas();
        ReadOnlyLogica();
        $('#Between').hide();
        if (IdTabla == '') {
            divColumnas.hide();
            return;
        }
        /*
        if(IdTabla == '-1') {
           ReadOnlyColumnas();
           $('#Between').hide();
        } else {
            */
            var DataIdTabla = "IdTabla="+IdTabla;
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/MostrarColumna.php",
                data: DataIdTabla,
                success: function(response) {
                    console.log(response);
                    $('select#selColumn').html(response).val('');
                    divColumnas.show();
                },
                error: function () {
                    divColumnas.hide();
                }
            });  
        //}             
    });   

    //$(document).on('change', '#SeleccioneColumna', function() {
    $(document).on('change', 'select#selColumn', function () {
        var IdColumna = this.value; 
        console.log('Id columna: ', IdColumna);
        const divLogica = $('div#inputLogica');
        ReadOnlyLogica();
        ReadOnlyValor();
        if (IdColumna == '') {
            divLogica.hide();
            return;
        }
        /*
        if(IdColumna == '-1') {
           ReadOnlyLogica();
           $('#Between').hide();           
        } else {
            */
            var DataIdColumna = "IdColumna="+IdColumna;
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/MostrarLogica.php",
                data:DataIdColumna,
                success: function(response) {
                    $('select#selLogic').html(response);
                    divLogica.show();
                },
                error: function () {
                    divLogica.hide();
                }
            }); 
        //}              
    }); 

    //$(document).on('change', '#SeleccioneLogica', function() {
    $(document).on('change', 'select#selLogic', function () {
        var IdLogica = this.value;
        if (IdLogica == '') {
            ReadOnlyValor();
            return;
        }        
        function mostrarValor()
        {
            var Id = $('Select#selColumn').val();
            var DataIdLogica = "IdLogica="+IdLogica+"&Id="+Id;
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/MostrarValor.php",
                data:DataIdLogica,
                success: function(response) {
                    response = response.trim();
                    var htmlCampo = `<div class="input-group">`;
                    htmlCampo += `<span class="input-group-addon" id="sizing-addon1">[ICON]</span>`;
                    htmlCampo += `<input type="text" class="form-control sin-bordes-izquierdos" id="SeleccioneValor" readonly>`;
                    htmlCampo += `</div>`;
                    if(response=="1") {
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-calendar"></i>');
                        $('#DivValor').html(htmlCampo);                        
                        $('#SeleccioneValor').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
                    } else if(response=="2") {
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-calendar"></i>');
                        $('#DivValor').html(htmlCampo);                        
                        $('#SeleccioneValor').datetimepicker({
                            format: 'YYYY-MM-DD HH:mm:ss',
                            locale: 'es'
                        }).on('dp.change', function (e) {
                            datetimepickerChange();
                        });
                        $('#SeleccioneValor').val();
                        //Fecha
                    } else if(response=="10") {
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-calendar"></i>');
                        $('#DivValor').html(htmlCampo);                        
                        $('#Between').show();
                        var htmlCampo2 = htmlCampo.replace('id="SeleccioneValor"','id="SeleccioneValor2"');
                        $('#DivValor2').html(htmlCampo2);                        
                        $('#SeleccioneValor').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
                        $('#SeleccioneValor2').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
                        //Fecha rango    
                    } else if(response=="12"){
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-calendar"></i>');
                        $('#DivValor').html(htmlCampo);                        
                        $('#Between').show();
                        var htmlCampo2 = htmlCampo.replace('id="SeleccioneValor"','id="SeleccioneValor2"');
                        $('#DivValor2').html(htmlCampo2);
                        $('#SeleccioneValor').datetimepicker({
                            format: 'YYYY-MM-DD HH:mm:ss',
                            locale: 'es'
                        }).on('dp.change', function (e) {
                            datetimepickerChange();
                        });
                        $('#SeleccioneValor2').datetimepicker({
                            format: 'YYYY-MM-DD HH:mm:ss',
                            locale: 'es'
                        })
                    } else if(response=="11") {
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-sack-dollar"></i>');
                        htmlCampo = htmlCampo.replace('class="form-control ','class="form-control IntegerValidar ');
                        $('#DivValor').html(htmlCampo); 
                        var htmlCampo2 = htmlCampo.replace('id="SeleccioneValor"','id="SeleccioneValor2"');
                        htmlCampo2 = htmlCampo2.replace('IntegerValidar ','IntegerValidar2 ');
                        $('#DivValor2').html(htmlCampo2);
                        //$('#DivValor').html('<input type="text" class="form-control IntegerValidar" ><br>');
                        $('#Between').show();
                        //$('#DivValor2').html('<input type="text" class="form-control IntegerValidar2" ><br>');
                    } else if(response=="0") {
                        htmlCampo = htmlCampo.replace('[ICON]','<i class="fa fa-sack-dollar"></i>');
                        htmlCampo = htmlCampo.replace('readonly','');
                        htmlCampo = htmlCampo.replace('class="form-control ','class="form-control IntegerValidar ');
                        $('#DivValor').html(htmlCampo); 
                        // $('#DivValor').html('<input type="text" class="form-control IntegerValidar" ><br>');
                    } else {
                        $('#DivValor').html(response);
                        $('#SeleccioneValor').selectpicker('refresh');
                    }
                }
            });    
        }

        if(IdLogica == '-1') {
            ReadOnlyValor();
            $('#Between').hide(); 
        } else if(IdLogica != '-1' && IdLogica != '-2') {
            $('#Between').hide(); 
            mostrarValor();
        } else {
            mostrarValor();
        }           
    }); 

    function datetimepickerChange() {
        var IdValor = $('#SeleccioneValor').val();
        if(IdValor=='-1' || IdValor=='') {
            ReadOnlyCola();
        } else {
            $('#DivCola').html('<input type="text" class="form-control" id="NombreCola">');
        }
    }

    $(document).on('change', '#SeleccioneValor', function() {
        var IdValor = $('#SeleccioneValor').val();
        if(IdValor=='-1' || IdValor=='') {
            ReadOnlyCola();
        } else {
            $('#DivCola').html('<input type="text" class="form-control" id="NombreCola">');
        }        
    });

    $(document).on('keyup', '#NombreCola', function() {
        var IdCola = $('#NombreCola').val();
        if(IdCola=='') {
            ReadOnlyBoton();
        } else {
            $('#CrearEstrategia').prop( "disabled", false );            
        }
    });

     /*$(document).on('change', '#SeleccioneColor', function()
    {
        var IdTabla = $('#SeleccioneColor').val();
        if(IdTabla=='-1')
        {
           ReadOnlyColumnas();
        }
        else
        {

        }
             
    });   */

     $(document).on('click', '#CrearEstrategia', function() {
        var Valor = '';
        var Valor2 = '';
        var NombreCola = $('#NombreCola').val();
        var Logica = $('select#selLogic').val(); //$('#SeleccioneLogica').val();
        var IdColumna = $('select#selColumn').val(); //$('#SeleccioneColumna').val();
        var IdCedente = $('#IdCedente').val();
        var IdEstrategia = $('#IdEstrategia').val();
        var IdSubQuery = $('#IdSubQuery').val();
        var IdTabla = $('select#selTable').val();//$('#SeleccioneTabla').val();
        var Color = ''; //$('#SeleccioneColor').val();
        
        if($('.IntegerValidar').length > 0 ){
            var IntValor = $('.IntegerValidar').val(); 
            Valor = IntValor.replace(/\./g,'');  
        } else {
            Valor = $('#SeleccioneValor').val();
            Valor2 = $('#SeleccioneValor2').val();
        }
        if($('.IntegerValidar2').length > 0 ){
            var IntValor = $('.IntegerValidar2').val(); 
            Valor2 = IntValor.replace(/\./g,'');  
        }
        if(Valor2 == undefined) {
            Valor2 = '';
        }
        //console.log(Valor);
        //console.log(Valor2);

        var DataQuery = "Valor="+Valor+"&Logica="+Logica+"&NombreCola="+NombreCola+"&IdColumna="+IdColumna+"&IdCedente="+IdCedente+"&IdEstrategia="+IdEstrategia+"&IdSubQuery="+IdSubQuery+"&IdTabla="+IdTabla+"&Color="+Color+"&Valor2="+Valor2;
        if(Logica == -2) {
            if(Valor > Valor2){
                $.niftyNoty( {
                    type: 'danger',
                    icon : 'fa fa-close',
                    message : 'Valor  Inicial no Puede ser Mayor que Valor de Termino' ,
                    container : 'floating',
                    timer : 4000
                });    
            } else {
                // $('body').addClass("loading");        
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });    
                $.ajax({
                    type: "POST",
                    url: "../includes/estrategia/CrearQuery.php",
                    data:DataQuery,
                    success: function(response) {
                        if(response == '0') {
                            // $('body').removeClass("loading");
                            $('#Cargando').modal('hide');
                            $.niftyNoty({
                                type: 'danger',
                                icon : 'fa fa-close',
                                message : 'Otro usuario esta creando Estrategias , acción bloqueada temporalmente' ,
                                container : 'floating',
                                timer : 4000
                            });
                        } else {
                            $.niftyNoty({
                                type: 'success',
                                icon : 'fa fa-close',
                                message : 'Segmento creado con éxito' ,
                                container : 'floating',
                                timer : 4000
                            }); 
                            $('#DivMostrarEstrategias').html(response);
                            $('#Cargando').modal('hide');
                            ReadOnly();
                            cancelarFormulario();
                            $('#Between').hide();
                            $('#Deshacer').prop( "disabled", false);
                        }                          
                    }, 
                    error() {
                        console.error('Error al intentar crear segmento');
                    }
                });    
            }
        } else {
            //$('body').addClass("loading");            
            $('#Cargando').modal({
                backdrop: 'static',
                keyboard: false
            });  
            $.ajax({                
                type: "POST",
                url: "../includes/estrategia/CrearQuery.php",
                data:DataQuery,
                success: function(response){
                    if(response == 0){
                        $('#Cargando').modal('hide');
                        $.niftyNoty({
                            type: 'danger',
                            icon : 'fa fa-close',
                            message : 'Otro usuario esta creando Estrategias , acción bloqueada temporalmente' ,
                            container : 'floating',
                            timer : 4000
                        });    
                    } else {
                        $.niftyNoty({
                            type: 'success',
                            icon : 'fa fa-close',
                            message : 'Segmento creado con éxito' ,
                            container : 'floating',
                            timer : 4000
                        }); 
                        $('#DivMostrarEstrategias').html(response);
                        $('#Cargando').modal('hide');
                        ReadOnly();
                        $('#Between').hide();
                        $('#Deshacer').prop( "disabled", false );
                        cancelarFormulario();                        
                        // console.log(response);
                    }    
                }
            });    
        }
    });

    $(document).on('click', '.SubEstrategia', function() {
        startTypeEstrategies();
        var IdSubQuery = this.id; //$(this).closest('tr').attr('id');
        console.log('ID: ', IdSubQuery);
        $('#IdSubQuery').val(IdSubQuery);
        var NewIdSubQuery = $('#IdSubQuery').val();
        var DataMover = "IdSubQuery=" + NewIdSubQuery;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/MoverGrupo.php",
            data: DataMover,
            success: function(response) {
                $.niftyNoty({
                    type: 'primary',
                    icon: 'fa fa-check',
                    message: response ,
                    container: 'floating',                    
                    timer: 3000
                });
                $('div#msgRegistros').show();
                $("#DivRegistros").html(response);
                NoReadOnly();
            }
        });
    });

    $(document).on('change', '.Prioridad', function() {
        var Prioridad = $(this).closest('tr').attr('id');
        var ValorPrioridad = $('#P'+Prioridad).val();
        var DataPrioridad = 'Id='+Prioridad+"&ValorPrioridad="+ValorPrioridad;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/ActualizarPrioridad.php",
            data:DataPrioridad,
            success: function(response)
            {
                $.niftyNoty(
                {
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Prioridad Actualizada' ,
                    container : 'floating',
                    timer : 2000
                });
            }
        });  
    });

    $(document).on('change', '.Comentario', function() {
        var Comentario = $(this).closest('tr').attr('id');
        var ValorComentario = $('#C'+Comentario).val();
        var DataComentario = 'Id='+Comentario+"&ValorComentario="+ValorComentario;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/ActualizarComentario.php",
            data:DataComentario,
            success: function() {
                $.niftyNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Comentario Actualizado' ,
                    container : 'floating',
                    timer : 2000
                });
            }
        });  
    });

    $(document).on('change', '.Cola', function() {
        var Cola = $(this).closest('tr').attr('id');
        var ValorCola= $('#K'+Cola).val();
        var DataCola = 'Id='+Cola+"&ValorCola="+ValorCola;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/ActualizarCola.php",
            data:DataCola,
            success: function() {
                $.niftyNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Nombre Grupo Actualizado.' ,
                    container : 'floating',
                    timer : 2000
                });
            }
        });  
    });

    $(document).on('keyup', '.IntegerValidar', function() {        
        this.value = (this.value + '').replace(/[^0-9]/g, '').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        var IntValidar = this.value;        
        if(IntValidar=='') {
            $('#NombreCola').prop( "disabled", true);
            $('#NombreCola').val('');
        } else {            
            $('#DivCola').html('<input type="text" class="form-control" id="NombreCola">');
        }        
    });

    $(document).on('keyup', '.IntegerValidar2', function() {        
        this.value = (this.value + '').replace(/[^0-9]/g, '').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        var IntValidar = this.value;        
        if(IntValidar=='') {
            $('#NombreCola').prop( "disabled", true);
            $('#NombreCola').val('');
        } else {            
            $('#DivCola').html('<input type="text" class="form-control" id="NombreCola">');
        }        
    });

    $(document).on('click', '.Terminal', function() {   
        var Row = $(this).closest('tr')
        var Cola = $(Row).find("td:first input").val();
    	var IdTerminal = $(Row).attr('id');
        var Terminal = '#T'+IdTerminal;
        var Ver = Row.find('td').find('.Ver')
        var Asignar = Row.find('td').find('.Asignar')

		if ($(Terminal).is(':checked')) { 
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/VerFonos.php",
                data:"",
                success: function(response) {
                    resp = response;
                    // console.log(resp);
                    var data_modal = resp;
                    bootbox.dialog({
                        title: "Configuración Cola Terminal: " + Cola,
                        message:data_modal,
                        buttons: {
                            success: {
                                label: "Guardar",
                                className: "btn-primary",
                                callback: function() {
                                    var CategoriasArray = $('#Categorias').val();
                                    if (CategoriasArray == null) {
                                        $.niftyNoty({
                                            type: 'danger',
                                            icon : 'fa fa-close',
                                            message : "Debe Seleccionar a lo menos una Categoría" ,
                                            container : 'floating',
                                            timer : 4000
                                        });
                                        $(".Terminal").prop('checked', false);
                                    } else {
                                        var DataTerminal = "TipoCategoria=" + $("select[name='TipoCategoria']").val() + "&Categorias=" + $("select[name='Categorias']").val() + "&prioridad=" + $("input[name='prioridad']").val() + "&ver_agenda=" + $("input[name='ver_agenda']").val() + "&idUserCautiva=" + $("select[name='idUserCautiva']").val() + "&comentario=" + $("textarea[name='comentario']").val() + "&IdTerminal=" + IdTerminal +"&Check=1";
                                        //var DataTerminal = $('#Form_Terminal').serialize()+"&IdTerminal="+IdTerminal+"&Check=1";
                                        $.ajax({
                                            type: "POST",
                                            url: "../includes/estrategia/Terminal.php",
                                            data:DataTerminal,
                                            success: function(response) {
                                                if(response){
                                                    $.niftyNoty({
                                                        type: 'success',
                                                        icon: 'fa fa-check',
                                                        message: "Cola Terminal Activada",
                                                        container: 'floating',
                                                        timer: 2000
                                                    });
                                                    $(Asignar).removeClass('disabled')
                                                    $(Ver).removeClass('disabled')
                                                } else {
                                                    $.niftyNoty({
                                                        type: 'success',
                                                        icon: 'fa fa-close',
                                                        message: "Error en Cola Terminal",
                                                        container: 'floating',
                                                        timer: 2000
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        },
                        onEscape: function () {
                            $(Terminal).prop('checked',false)
                        }
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
            });  
        } else {
            var DataTerminal = "IdTerminal="+IdTerminal+"&Check=0";
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/Terminal.php",
                data:DataTerminal,
                success: function() {                    
                    $.niftyNoty({
                        type: 'warning',
                        icon : 'fa fa-check',
                        message : "Cola Terminal Desactivada!" ,
                        container : 'floating',
                        timer : 2000
                    });
                    $(Asignar).addClass('disabled')
                    $(Ver).addClass('disabled')
                }
            });  
        }    
    });

    $('#Deshacer').on('click', function() {
		var IdEstrategia = $('#IdEstrategia').val();
        var DataDeshacer = 'IdEstrategia='+IdEstrategia;
        bootbox.confirm("Esta Seguro de Deshacer la Última Segmentación?", function(result) {
			if (result) {				
                $.ajax({
                    type: "POST",
                    url: "../includes/estrategia/Deshacer.php",
                    data:DataDeshacer,
                    success: function() {
                        $.niftyNoty({
                            type: 'warning',
                            icon : 'fa fa-check',
                            message : 'Última División Eliminada' ,
                            container : 'floating',
                            timer : 2000
                        });
                        MostrarEstrategias(DataDeshacer);
                        $('#IdSubQuery').val(0);
                        Registros(DataTotal);
                    }
                });  
			}
		});
	});

    $('#crear_estrategia').submit(function(e) {        
        e.preventDefault();
        if($("#nombre_estrategia").val().length < 5) {
	        $.niftyNoty({
                type: 'danger',
                icon : 'fa fa-check',
                message : 'El nombre debe tener como mínimo 5 caracteres' ,
                container : 'floating',
                timer : 2000
            });
            return;
        } else {
		    $('body').addClass("loading");
            var separar = 1;
            var nombre_estrategia = $('#nombre_estrategia').val();
		    var tipo_estrategia = $('#tipo_estrategia').val();
            var comentario_estrategia = $('#comentario_estrategia').val();
            if($('#separar').is(':checked')) separar = 0;
		    var usuario = $('#usuario').val();
		    var cedente = $('#cedente').val();
            var idUsuario = $('#idUsuario').val();         
            var datos_estrategia = 'nombre_estrategia='+nombre_estrategia+'&comentario_estrategia='+comentario_estrategia+'&tipo_estrategia='+tipo_estrategia+'&cedente='+cedente+'&usuario='+usuario+'&idUsuario='+idUsuario+"&separar="+separar;
            $.ajax({
                type: "POST",
                url: "../includes/estrategia/crear_estrategia.php",
                data:datos_estrategia,
                success: function(response) {
                    if(response == 0){
                        $('body').removeClass("loading");
                        $.niftyNoty({
                            type: 'danger',
                            icon : 'fa fa-check',
                            message : 'Otro usuario esta creando Estrategias , acción bloqueada temporalmente' ,
                            container : 'floating',
                            timer : 4000
                        });
                    } else {
                        $('body').removeClass("loading");
                        window.location.replace("segmentacion.php");
                    }                    
                }
            });
   		}
    });

    $(document).on('click','#Actualizar', function() {
        var idEst = $(this).closest('td').attr('id');        
        $('body').addClass("loading");
        var IdCedente = $('#IdCedente').val();
        var data = "IdCedente="+IdCedente+"&IdEstrategia="+idEst;
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/recalculaQueryCedente.php",
            data:data,
            success: function() {
                    $.niftyNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : 'Cola Actualizada' ,
                    container : 'floating',
                    timer : 2000
                });
                // console.log(response);
                $('body').removeClass("loading");
                window.location.replace("segmentacion.php");
            }
		});
    });

    $("body").on("change","select[name='TipoCategoria']",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/getCategoriasFromTipoCategoria.php",
            data: {
                Tipo: Value
            },
            success: function(response) {
               // console.log(response);
                $("#Categorias").html(response);
                $("#Categorias").selectpicker("refresh");
            }
        });
    });

    // $(document).on('click', '.Ver', function () {
    //     var Template = $("#Template").html();
    //     var Row = $(this).closest('tr')
    //     var Cola = $(Row).find("td:first input").val();
    //     var IdCola = $(Row).attr('id');
    //     bootbox.dialog({
    //         title: "Ver Resumen: " + Cola,
    //         message: Template,
    //         buttons: {
    //             success: {
    //                 label: "Descargar",
    //                 className: "btn-primary",
    //                 callback: function () {
    //                     var Porcentaje = $('#Porcentaje').val();
    //                     if (Porcentaje != '') {
    //                         url = "../includes/estrategia/DownloadResumenAsignacion.php?IdCola="+IdCola+"&Porcentaje="+Porcentaje;
    //                         window.open(url, '_blank');
    //                     } else {
    //                         $.niftyNoty({
    //                             type: 'danger',
    //                             icon: 'fa fa-close',
    //                             message: "Debe Seleccionar un porcentaje",
    //                             container: 'floating',
    //                             timer: 4000
    //                         });
    //                         return false;
    //                     }
    //                 }
    //             }
    //         }
    //     }).off("shown.bs.modal");
    //     setTimeout(() => {
    //         $('#Porcentaje').selectpicker('refresh'); 
    //         $("#IdCola").val(IdCola);
    //     }, 200);
    // });
    $(document).on('click', '.Ver', function () {
        if(!$(this).hasClass('disabled')){ 
            const btnId = this.id.split('-')[1];
            console.log('ID: ', btnId);
            /*  
            var Row = $(this).closest('tr')
            var IdCola = $(Row).attr('id');
            */
            url = "../includes/estrategia/DownloadDetalleEstrategia.php?IdCola="+btnId;
            window.open(url, '_blank');
        }
    });

    $("body").on("click", ".reconstruirCola", function() {
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var idCola = ObjectTR.attr("id");
        bootbox.confirm("¿Esta seguro que desea reconstruir la cola y las asignaciones correspondientes? Recuerde que los ejecutivos deben estar fuera de la asignación.", function(Result) {
            if(Result) {
                $('body').addClass("loading");
                $.ajax({
                    type: "POST",
                    url: "../includes/tareas/reconstruirColasAsignaciones_estrategia.php",
                    data:{
                        idCola: idCola
                    },
                    success: function(Data) {
                        $('body').removeClass("loading");
                        if(isJson(Data)){
                            Data = JSON.parse(Data);
                            if(Data.result){
                                bootbox.alert("Cola reconstruida satisfactoriamente.");
                            }else{
                                bootbox.alert(Data.message);
                            }
                        }
                    }
                });
            }
        });
    });
    var IDCola;
    var TablaDeAsignados;
    var ArrayEE = [];
    var ArrayPersonal = [];
    var ArrayGrupo = [];
    var serviceSelected = '';
    $("body").on("click", ".Asignar", function () {
        var IDCola = this.id.split('-')[1]; //$(this).closest('tr');
        //IDCola = $(Row).attr('id');

        $('div#modalDivVicidial').hide();
        $('form#frmAsignarServicio')[0].reset();
        $('input#idFlow').val(IDCola);
        $('#modalAsignar').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#selServicio").on("change", function (e) {
        const valor = this.value;
        console.log('Valor: ', valor);
        const modalDiv = $('div#modalDivDiscador');
        modalDiv.hide();
        if (valor !== '') {
            modalDiv.show();
            if (valor === 'discador') {
                const fID = $('input#idFlow').val();
                const nombre = $('input#K'+fID).val();
                $('input#nombre_campana').val('Campaña ' + fID.padStart(4,'0') + ' - ' + nombre);
                $('input#descripcion_campana').val('Campaña para discador ' + fID.padStart(4,'0') + ' - ' + nombre);
            } else {
                $('input#nombre_campana').val('');
                $('input#descripcion_campana').val('');
            }
        } else {
            modalDiv.hide();
        }
        e.preventDefault();
    });

    $("body").on("click", "#Asignacion_Servicio_btn", function () {
        $(this).attr('disabled',true);
        var frmAsigaciones = $('form#frmAsignarServicio');
        var listaDatos = frmAsigaciones.serializeArray();
        var listaDatosJson = {};
        $.map(listaDatos, function(n, i){
            listaDatosJson[n['name']] = n['value'];
        });
        var fId = $('input#idFlow').val();
        const nId = fId;
        Object.assign(listaDatosJson,{ id: nId });
        Object.assign(listaDatosJson,{ auto_list: true });
        Object.assign(listaDatosJson,{ rand: generateRandomId(8) })
        Object.assign(listaDatosJson,{ action: 'create' });
        const apiUrl = './asignacion/discador.php'; 
    
        $.ajax({
            url: apiUrl,
            type: "POST",
            dataType: "json",
            data: listaDatosJson,
            success: function (response) {
                if (response.success === true) {
                    upploadDataDiscador();
                } else if (response.data.exist === true) {
                    console.info('Campaña creada previamente');
                    upploadDataDiscador();
                } else {
                    bootbox.alert('<h3 style="color: #f00;">¡La campaña no pudo ser asignada!</h3>');
                }                
            },
            error: function (xhr, status, error) {
                alert("Error al crear la campaña");
                closeAsignacionModal();
                console.error(error);
            }
        });        
    });

    function upploadDataDiscador() {
        console.log('Subir archivo CSV.');
        var apiUrl = "./asignacion_discador.php";
        var fId = $('input#idFlow').val();
        console.log('Id Cola: ', fId);
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: {
                id_grupo: fId,
                servicio: 'discador',
            },
            success: function (response) {
                if (response.success === true) {
                    closeAsignacionModal();
                    bootbox.alert('<h2 style="color: green; margin: 10px auto; display: block; padding: 15px 5px; text-align: center;">¡Asignación procesada con éxito!</h2><br/>' + response.message);
                } else {
                    closeAsignacionModal();
                    bootbox.alert(response.message);
                }  
            },
            error: function (xhr, status, error) {
               closeAsignacionModal();
               bootbox.alert('<h3 style="color: #f00;">¡Error al intentar procesar los datos!</h3>');
               console.error(error);
            }
        });   
    }

    function closeAsignacionModal() {
        console.info('Reset Form');
        $('form#frmAsignarServicio')[0].reset();
        $('button#Asignacion_Servicio_btn').removeAttr('disabled');
        console.info('Close Modal.');
        $('#modalAsignar').modal('hide');
    }

    function generateRandomId(length) {
      const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      let id = "";
      for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        id += charset[randomIndex];
      }
      return id;
    }

    $("body").on("change", "select[name='Entidad']", function () {
        var Entidad = $(this).val();
        var CantEntidades = 0;
        jQuery.each(Entidad, function (i, val) {
            CantEntidades++;
        });
        if (CantEntidades > 1) {
            $("#NombreGrupo").show();
        } else {
            $("#NombreGrupo").hide();
        }
    })

    $("body").on("click", "#AddEntidad", function () {
        var Template = $("#TemplateAddEntidad").html();
        bootbox.dialog({
            title: "Agregue Nueva Entidad",
            message: Template,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var Entidad = $("select[name='Entidad']").val();
                        var NombreEntidad = $("select[name='Entidad'] option:selected").text();
                        var TipoEntidad = "";
                        var idEntidad = "";
                        var CantEntidades = 0;
                        var CanSave = true;
                        jQuery.each(Entidad, function (i, val) {
                            CantEntidades++;
                        });
                        if (CantEntidades <= 1) {
                            if (CantEntidades > 0) {
                                Entidad = Entidad[0];
                                var ArrayEntidad = Entidad.split("_");
                                TipoEntidad = ArrayEntidad[0];
                                idEntidad = ArrayEntidad[1];
                            }
                        }
                        if (CantEntidades > 1) {
                            var NombreGrupo = $("#NombreGrupo input[name='nombreGrupo']").val();
                            if (NombreGrupo == "") {
                                CanSave = false;
                            }
                            if (CanSave) {
                                NombreEntidad = NombreGrupo;
                                var Personas = [];
                                var Empresas = [];
                                jQuery.each(Entidad, function (i, val) {
                                    var ArrayPersona = val.split("_");
                                    var Persona = ArrayPersona[1];
                                    switch (ArrayPersona[0]) {
                                        case 'S':
                                        case 'E':
                                            Personas.push(Persona);
                                            break;
                                        case 'EE':
                                            Empresas.push(Persona);
                                            break;
                                    }
                                });
                                TipoEntidad = "G";
                                console.log(Empresas);
                                idEntidad = createGroup(NombreGrupo, Personas, Empresas);
                                Entidad = "G_" + idEntidad;
                                Entidad = Entidad.replace("/(?:\r\n|\r|\n)/g", "");
                            }
                        }
                        switch (TipoEntidad) {
                            case 'S':
                                TipoEntidad = "Personal";
                                ArrayPersonal.push(idEntidad);
                                break;
                            case 'E':
                                TipoEntidad = "Personal";
                                ArrayPersonal.push(idEntidad);
                                break;
                            case 'EE':
                                TipoEntidad = "Empresa Externa";
                                ArrayEE.push(idEntidad);
                                break;
                            case 'G':
                                TipoEntidad = "Grupo";
                                ArrayGrupo.push(idEntidad);
                                break;
                        }
                        if (CanSave) {
                            if (CantEntidades > 0) {
                                TablaDeAsignados.row.add({
                                    Nombre: NombreEntidad,
                                    Tipo: TipoEntidad,
                                    Porcentaje: "0",
                                    id: Entidad,
                                    Actions: Entidad
                                }).draw();
                            } else {
                                CustomAlert("Debe llenar todos los datos.");
                                return;
                            }
                        } else {
                            CustomAlert("Debe llenar todos los datos.");
                            return;
                        }
						/*if(CantEntidades > 0){
							TablaDeAsignados.row.add({
								Nombre: NombreEntidad,
								Tipo: TipoEntidad,
								Porcentaje: "0",
								id: Entidad,
								Foco: "0",
								Actions: Entidad
							}).draw();
						}else{
							CustomAlert("Debe llenar todos los datos.");
						}*/
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });

    $("body").on("change", "select[name='TipoEntidad']", function () {
        var tipoEntidad = $(this).val();
        $("select[name='Entidad']").html();
        $("select[name='Entidad']").prop("disabled", false);
        $("select[name='Entidad']").selectpicker("refresh");
        var ArrayTmp = [];
        switch (tipoEntidad) {
            case '1':
                ArrayTmp = ArrayEE;
                break;
            case '2':
                ArrayTmp = ArrayPersonal;
                break;
            case '3':
                ArrayTmp = ArrayGrupo;
                break;
        }
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getEntidades.php",
            data: {
                tipoEntidad: tipoEntidad, ArrayIds: ArrayTmp
            },
            beforeSend: function () {
                $('body').addClass("loading");
            },
            success: function (response) {
                $('body').removeClass("loading");
                $("select[name='Entidad']").html(response);
                $("select[name='Entidad']").selectpicker("refresh");
            }
        });
    });

    $("body").on("click", ".Delete", function () {
        var ObjectMe = $(this);
        var ID = ObjectMe.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar esta entidad?", function (result) {
            if (result) {
                DeleteEntidad(ObjectTR, ID);
            }
        });
    });

    $("body").on("click", "#Seleccionar_Modo_Asignacion", function () {
        var Porcentaje = $("#SumPorcentaje").html();
        Porcentaje = Porcentaje.replace("%", "");
        Porcentaje = Number(Porcentaje);
        if (Porcentaje == 100) {
            var Template = $("#TemplateSeleccionModoAsignacion").html();
            bootbox.dialog({
                title: "SELECCIONE ALGORITMO DE ASIGNACIÓN",
                message: Template,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function () {
                            var MetodoAsignacion = $("select[name='MetodoAsignacion']").val();
                            var Rows = [];
                            TablaDeAsignados.rows().eq(0).each(function (index) {
                                var row = TablaDeAsignados.row(index);
                                var data = row.data();
                                var ArrayTmp = [];
                                $.each(data, function (indexCol, value) {
                                    switch (indexCol) {
                                        case 'Nombre':
                                            ArrayTmp.push(value);
                                            break;
                                        case 'Porcentaje':
                                            ArrayTmp.push(value);
                                            break;
                                        case 'id':
                                            ArrayTmp.push(value);
                                            break;
                                    }
                                });
                                Rows.push(ArrayTmp);
                            });

                            switch (MetodoAsignacion) {
                                case '1':
                                    //Ruts
                                    $.ajax({
                                        type: "POST",
                                        url: "../includes/tareas/SeparateByRuts.php",
                                        data: {
                                            idCola: IDCola, Rows: Rows
                                        },
                                        beforeSend: function () {
                                            $('body').addClass("loading");
                                        },
                                        success: function (response) {
                                            $('body').removeClass("loading");
                                            var json = JSON.parse(response);
                                            console.log(json);
                                            $.niftyNoty({
                                                type: 'success',
                                                icon: 'fa fa-check',
                                                message: "Asignación creada Exitosamente",
                                                container: 'floating',
                                                timer: 2000
                                            });
                                            $('#AsignadorDeCasos').modal('hide')
                                        }
                                    });
                                    break;
                                case '2':
                                    //Deuda
                                    $.ajax({
                                        type: "POST",
                                        url: "../includes/tareas/SeparateByDeuda.php",
                                        data: {
                                            idCola: IDCola, Rows: Rows
                                        },
                                        beforeSend: function () {
                                            $('body').addClass("loading");
                                        },
                                        success: function (response) {
                                            $('body').removeClass("loading");
                                            var json = JSON.parse(response);
                                            $.niftyNoty({
                                                type: 'success',
                                                icon: 'fa fa-check',
                                                message: "Asignación creada Exitosamente",
                                                container: 'floating',
                                                timer: 2000
                                            });
                                            $('#AsignadorDeCasos').modal('hide')
                                        }
                                    });
                                    break;
                            }
                        }
                    }
                }
            }).off("shown.bs.modal");
            $(".selectpicker").selectpicker("refresh");
        } else {
            CustomAlert("El porcentaje total debe ser de 100%");
        }
    });

    $("body").on("click", ".DownloadAsignacionTipo2", function () {
        var ObjectMe = $(this);
        var Tabla = ObjectMe.attr("table");
        window.location = "../includes/tareas/crearArchivosDeAsignacion.php?idCola=" + idCola + "&Tabla=" + Tabla + "&Tipo=2";
    });

    $("body").on("change", "input.InputPorcentaje", function () {
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        var Row = ObjectMe.attr("row");
        var ObjectTD = ObjectMe.closest("td");
        var cell = TablaDeAsignados.cell(ObjectTD);
        cell.data(Value).draw();
        $("#TablaDeAsignados").trigger('update');
        Porcentaje = $('#SumPorcentaje').text()
        PorcentajeSplit = Porcentaje.split('.');
        SumPorcentaje = parseInt(PorcentajeSplit[0]);
        if (SumPorcentaje > 100) {
            CustomAlert("El porcentaje total debe ser de 100%");
            cell.data(0).draw();
            $("#TablaDeAsignados").trigger('update');
        }
    });

    $("body").on("click", "input.Checkbox", function () {
        var ObjectMe = $(this);
        var Value = "0";
        if (ObjectMe.is(':checked')) {
            Value = "1";
        }
        var Row = ObjectMe.attr("row");
        var ObjectTD = ObjectMe.closest("td");
        var cell = TablaDeAsignados.cell(ObjectTD);
        cell.data(Value).draw();
        $("#TablaDeAsignados").trigger('update');
    });

    $("body").on("update", "#TablaDeAsignados", function () {
        UpdateEntidadSummaryFoot();
    });

    $("body").on("click", ".Cautiva", function () {
        var ObjectMe = $(this);
        //var ObjectTR = ObjectMe.closest("tr");
        var ID = ObjectMe.attr("id");
        showModalCautiva(ID);
    });

    function DeleteEntidad(TableRow, ID) {
        $("#Downloads").find("#Tipo2").empty()
        TablaDeAsignados.row(TableRow).remove().draw();
        var ArrayEntidad = ID.split("-");
        var TipoEntidad = ArrayEntidad[0];
        var idEntidad = ArrayEntidad[1];
        switch (TipoEntidad) {
            case 'S':
                removeItem(ArrayPersonal, idEntidad);
                break;
            case 'E':
                removeItem(ArrayPersonal, idEntidad);
                break;
            case 'EE':
                removeItem(ArrayEE, idEntidad);
                break;
        }
        $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check',
            message: "Entidad Eliminada Exitosamente",
            container: 'floating',
            timer: 2000
        });
        $("#TablaDeAsignados").trigger('update');
    }

    function removeItem(array, item) {
        for (var i in array) {
            if (array[i] == item) {
                array.splice(i, 1);
                break;
            }
        }
        console.log(array);
    }

    function UpdateEntidadSummaryFoot() {
        var SumPorcentaje = 0;
        TablaDeAsignados.rows().eq(0).each(function (index) {
            var row = TablaDeAsignados.row(index);
            var data = row.data();
            $.each(data, function (indexCol, value) {
                switch (indexCol) {
                    case 'Porcentaje':
                        SumPorcentaje += Number(value);
                        break;
                }
            });
        });
        $("#SumPorcentaje").html(SumPorcentaje.toFixed(2) + "%");
    }

    function createGroup(Nombre, Personas, Empresas) {
        ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../grupos/ajax/insertGrupo.php",
            data: {
                nombre: Nombre,
                personas: Personas,
                empresas: Empresas,
                cola: IDCola
            },
            async: false,
            beforeSend: function () {
                $('body').addClass("loading");
            },
            success: function (response) {
                var json = JSON.parse(response);
                $('body').removeClass("loading");
                ToReturn = json.idGrupo;
            }
        });
        return ToReturn;
    }

    function showModalCautiva(idCola) {
        var Template = $("#TemplateCautivo").html();
        bootbox.dialog({
            title: "SELECCIÓN DE EJECUTIVO ",
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function () {
                        var ObjectStatus = $("input[name='inputCautiva']");
                        var Status = ObjectStatus.is(":checked") ? "1" : "0";
                        var Usuario = $("select[name='EjecutivoColaCautiva']").val();
                        if (Usuario != "") {
                            updateColaCautiva(idCola);
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function () {
                    }
                }
            }
        }).off("shown.bs.modal");
        var Usuarios = getEjecutivosActivos();
        $("select[name='EjecutivoColaCautiva']").html(Usuarios);
        $(".selectpicker").selectpicker("refersh");
        getCola(idCola);
    }

    function getCola(idCola) {
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getCola.php",
            data: {
                cola: idCola
            },
            async: false,
            beforeSend: function () {
                $('body').addClass("loading");
            },
            success: function (response) {
                $('body').removeClass("loading");
                if (isJson(response)) {
                    var json = JSON.parse(response);
                    if (json.Cautiva == "1") {
                        $("input[name='inputCautiva']").prop("checked", true);
                        $("input[name='inputCautiva']").closest("label").addClass("active");
                        $("select[name='EjecutivoColaCautiva']").val(json.idUserCautiva).change();
                        $(".selectpicker").selectpicker("refersh");
                    }
                }
            }
        });
    }

    function getEjecutivosActivos() {
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getEjecutivosActivos.php",
            data: {},
            async: false,
            beforeSend: function () {
                $('body').addClass("loading");
            },
            success: function (response) {
                $('body').removeClass("loading");
                ToReturn = response;
            }
        });
        return ToReturn;
    }

    function updateColaCautiva(idCola) {
        var Ejecutivo = $("select[name='EjecutivoColaCautiva']").val();
        var ObjectStatus = $("input[name='inputCautiva']");
        var Status = ObjectStatus.is(":checked")? "1" : "0";
        $.ajax({
            type: "POST",
            url: "../includes/tareas/updateColaCautiva.php",
            data: {
                cola: idCola,
                Ejecutivo: Ejecutivo,
                Cautiva: Status
            },
            async: false,
            beforeSend: function () {
                $('body').addClass("loading");
            },
            success: function (response) {
                $('body').removeClass("loading");
            }
        });
    }

    function CustomAlert(Message) {
        bootbox.alert(Message)
    }
});
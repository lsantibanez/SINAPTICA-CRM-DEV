
audiojs.events.ready(function () {
    audiojs.createAll();
});
var paleta = [];
var nivel2 = [];

function llenarNivel1 () {
    nivel2 = [] //reset level 2
    let elemento = $('select#nivel_1');
    let opciones = '<option value="">-- Todas --</option>';
    if (paleta.length) {
        paleta.forEach((item) => {
            opciones += `<option value="${item.id}">${item.respuesta}</option>`;
        })
    }

    console.log('Llenar n1')
    elemento.html(opciones)
}

function getLevel2(idLevel){
    nivel2 = []
    let elemento = $('select#nivel_2');
    $('select#nivel_3').html('<option value="">-- Todas --</option>')
    let opciones = '<option value="">-- Todas --</option>';
    if (idLevel !== '' && parseInt(idLevel) > 0) {
        const level1 = paleta.find(n => n.id === parseInt(idLevel))        
        if (level1 !== undefined && level1['Nivel 2'] !== undefined) {
            nivel2 = level1['Nivel 2']
            if (nivel2.length) {
                nivel2.forEach((item) => {
                    opciones += `<option value="${item.id}">${item.respuesta}</option>`;
                })
            }
        }        
    }

    console.log('Llenar n2')
    elemento.html(opciones)
}

function getLevel3(idLevel){
    let elemento = $('select#nivel_3');
    let opciones = '<option value="">-- Todas --</option>';
    if (idLevel !== '' && parseInt(idLevel) > 0) {
        const level2 = nivel2.find(n => n.id === parseInt(idLevel))   
        if (level2 !== undefined && level2['Nivel 3'] !== undefined) {
            const nivel3 = level2['Nivel 3']
            if (nivel3.length) {
                nivel3.forEach((item) => {
                    opciones += `<option value="${item.id}">${item.respuesta}</option>`;
                })
            }
        }        
    }

    console.log('Llenar n3')
    elemento.html(opciones)
}

$(document).ready(function () {
    var dataSet = [];    
    UpdateRecords();
    getPaletaGestiones();

    $("body").on("click", "#ListenRecord i", function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var URL = ObjectDiv.attr("Url");
        $.get(URL, function(data) {
            console.log('Data: ', data);
            var Template = $("#ListenRecordTemplate").html();
            Audio = "<audio src='" + data + "' preload='auto' controls></audio>";
            Template = Template.replace("{RECORD_AUDIO}", Audio);
            Template = Template.replace("{RECORD_URL}", data);
            bootbox.dialog({
                title: "GRABACIÓN DE LLAMADA",
                message: Template,
                closeButton: false,
                buttons: {
                    cancel: {
                        label: "Cerrar",
                        className: "btn-danger"
                    }
                },
                size: 'medium'
            }).off("shown.bs.modal");
        }).fail(function() {
          console.info('Error al intentar obtener audio');
        })
    });

    const botonContactabilidadAgente = $('button#btnReporteContacAgente');
    botonContactabilidadAgente.attr('disabled', true);

    $('select#agente').change(function () {
        botonContactabilidadAgente.attr('disabled', true);
        console.log('Agente: ', this.value);
        if (this.value !== '') {
            botonContactabilidadAgente.removeAttr('disabled');
        }
    });

    function getFormData($form) {
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};            
        $.map(unindexed_array, function(n, i) {
            indexed_array[n['name']] = n['value'];
        });
        return indexed_array;
    }

    $("#BuscarGestiones").click(function (e) {
        var formulario = $('form#busquedaGestiones')
        var checkBoxValues = [];
        var valores = getFormData(formulario)
        $.each($("input[name='proyectos']:checked"), function(){
            checkBoxValues.push($(this).val());
        });
        valores.proyectos = checkBoxValues
        BuscarGrabaciones(valores)
    });

    function BuscarGrabaciones(datos = null) {
        let dataRequest = {
            cedente: $("input#cedente").val(),
            rut: $("input#rut").val(),
            telefono:  $("input#telefono").val(),
            n1:  $("select#nivel_1").val(),
            n2:  $("select#nivel_2").val(),
            n3:  $("select#nivel_3").val(),
            fecha_gestion: $('#fechaGestion').val(),
        }
        if (datos !== null) dataRequest = datos
        const boton = $('button#BuscarGestiones')
        $.ajax({
            type: "POST",
            url: "../includes/reporteria/GetGestiones.php",
            data: {
                datos: dataRequest
            },
            dataType: "json",
            beforeSend: function () {
                boton.attr('disabled', true)
                dataSet = [];
            },
            success: function (data) {
                dataSet = data;
                UpdateRecords();
                $('#btnDescarga').hide();
                if(data.length > 0){
                    $.niftyNoty({
                        type: 'success',
                        icon: 'fa fa-check',
                        message: 'Se han encontrado **'+data.length+'** registro(s).',
                        container: 'floating',
                        timer: 3000
                    });
                    $('#btnDescarga').show();
                } else {
                    $.niftyNoty({
                        type: 'info',
                        icon: 'fa fa-check',
                        message: 'La búsqueda no entontró resultados',
                        container: 'floating',
                        timer: 4000
                    });
                }
                boton.removeAttr('disabled')
            },
            error: function() {
                boton.removeAttr('disabled')
            }
        });
    }

    $("button#Download").click(function (e) {
        /*
        let dataRequest = {
            cedente: $("input#cedente").val(),
            rut: $("input#rut").val(),
            telefono:  $("input#telefono").val(),
            n1:  $("select#nivel_1").val(),
            n2:  $("select#nivel_2").val(),
            n3:  $("select#nivel_3").val(),
            file_name: 'Reporte_Gestiones'
        }
        */
        var formulario2 = $('form#busquedaGestiones')
        var checkBoxValues = [];
        var dataRequest = getFormData(formulario2)
        $.each($("input[name='proyectos']:checked"), function(){
            checkBoxValues.push($(this).val());
        });
        dataRequest.proyectos = checkBoxValues
        Object.assign(dataRequest, { file_name: 'Reporte_Gestiones' })
        const boton = $('button#Download')

        $.ajax({
            type: "POST",
            url: "../includes/reporteria/DownloadGestiones.php",
            data: {
                datos: dataRequest
            },
            beforeSend: function () {
                boton.attr('disabled', true)
            },
            success: function(data) {
                /*
                 * Make CSV downloadable
                 */
                var downloadLink = document.createElement("a");
                var fileData = ['\ufeff'+data];  
                var blobObject = new Blob(fileData,{
                   type: "application/vnd.ms-excel"
                });  
                var url = URL.createObjectURL(blobObject);
                downloadLink.href = url;
                downloadLink.download = "Reporte_Gestiones.csv";  
                /*
                 * Actually download CSV
                 */
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                boton.removeAttr('disabled')
            },
            error: function () {
                boton.removeAttr('disabled')
            }
        });
        //url = "../includes/reporteria/DownloadGestiones.php?Telefono=" + Telefono + "&Rut=" + Rut;
        //window.open(url, '_blank');
    });

    function UpdateRecords() {
        GestionesTable = $('#GestionesTable').DataTable({
            data: dataSet,
            pageLength: 25,
            destroy: true,
            columns: [
                { data: 'fecha_gestion' },
                { data: 'cartera' },
                { data: 'rut' },
                { data: 'nombre_ejecutivo' },
                { data: 'fono_discado' },
                { data: 'n1' },
                { data: 'n2' },
                { data: 'n3' },
                { data: 'fec_compromiso' },
                { data: 'monto_comp' },
                { data: 'observacion' },
                { data: 'Listen' }
            ],
            "columnDefs": [
                {
                    "targets": 11,
                    "render": function (data, type, row) {
                        if (data == '' || data == null) return '<span>&nbsp;</span>';
                        return "<div url='" + data + "' id='ListenRecord'><i class='fa fa-play'></i></div>";
                    }
                }
            ],
            language: {
                zeroRecords:    "Ningún elemento a mostrar",
                emptyTable:     "No hay datos disponibles en la tabla",
                paginate: {
                    first:      "Primer",
                    previous:   "Previos",
                    next:       "Siguientes",
                    last:       "Último"
                },
            }
        });
    }

    $(document).on('click', '#btnReporteGeneral', function () {
        let dataRequest = {
            cedente: $("input#cedente").val(),
            file_name: 'Reporte_General'
        }
        $.ajax({
            type: "POST",
            url: "../includes/reporteria/General.php",
            data: {
                datos: dataRequest
            },
            success: function(data) {
                var fileData = ['\ufeff'+data];
                const url = window.URL.createObjectURL(new Blob(fileData));
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "Reporte_General.xlsx");
                document.body.appendChild(link);
                link.click();
                /*
                 * Make CSV downloadable
                 */
                /*
                var downloadLink = document.createElement("a");
                var fileData = ['\ufeff'+data];  
                var blobObject = new Blob(fileData);  
                var url = URL.createObjectURL(blobObject);
                downloadLink.href = url;
                downloadLink.download = "Reporte_General.xlsx";  
                /*
                 * Actually download CSV
                 *//*
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                */
          }
        });
        //url = "../includes/reporteria/DownloadGestiones.php?Telefono=" + Telefono + "&Rut=" + Rut;
        //window.open(url, '_blank');
    });

    function getPaletaGestiones() {
        var idCedente = $("input#cedente").val()
        $.ajax({
            type: "GET",
            url: "../endpoint/script.php?Id_Cedente=" + idCedente,
            dataType: "json",
            success: function (data) {
                if (data['Nivel 1'] !== undefined && data['Nivel 1'].length) {
                    paleta = data['Nivel 1']
                }
                llenarNivel1()
            }
        });
    }
});
$(document).ready(function(){

    getPersonas();

    CreditoTable = $('#CreditoTable').DataTable({
        paging: false,
        iDisplayLength: 100,
        processing: true,
        serverSide: false,  
        scrollY:false,
        scrollX:false,
        bInfo:false,
        order: [[5, 'desc']],
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

    ReavenimientoTable = $('#ReavenimientoTable').DataTable({
        paging: false,
        iDisplayLength: 100,
        processing: true,
        serverSide: false,  
        scrollY:false,
        scrollX:false,
        bInfo:false,
        order: [[5, 'desc']],
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

    //CONFIGURACION DEL SELECTPICKER, DATETIMEPICKER Y DATA-MASK

    $('.selectpicker').selectpicker();
    $('.date').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        language: 'es'
    });

    $(".number").mask("0000000000");
    $(".percent").mask("000");

    //CLIENTES

    function getPersonas(){

        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            type: "POST",
            url: "../includes/judicial/getPersonas.php",
            success: function(response){
                $("#personaempresa_id").html(response);
                $("#personaempresa_id_liquidacion").html(response);
                $("#personaempresa_id_reavenimiento").html(response);
                $('.selectpicker').selectpicker('refresh');
                $('#Cargando').modal('hide');
            },
            error: function(response){
                $('#Cargando').modal('hide');
            }
        });
    }
    
    $('#personaempresa_id').on('change', function () {
        Rut = $(this).val()
        $.ajax({
            type: "POST",
            url: "../includes/judicial/getPersona.php",
            data: "&Rut="+Rut,
            success: function(response){
                response = JSON.parse(response)
                $('#rut').val(response.Rut)
                $('#domicilio_particular').val(response.Direccion)
                $('#telefono_particular').val(response.Telefono)
                $('#saldo_capital').val(response.SaldoCapital)
                $('#interes_vencido').val(response.InteresVencido)
                $('#interes_suspendido').val(response.InteresSuspendido)
                $('#interes_penal').val(response.InteresPenal)
                $('#gastos_cobranza').val(response.GastosCobranza)

                $('#monto_saldo_capital').val(response.SaldoCapital)
                $('#monto_interes_vencido').val(response.InteresVencido)
                $('#monto_interes_suspendido').val(response.InteresSuspendido)
                $('#monto_interes_penal').val(response.InteresPenal)
                $('#monto_gastos_cobranza').val(response.GastosCobranza)

                calcular_avenimiento()

                $("#numero_operaciones").html(response.Deudas);
                $('#numero_operaciones').selectpicker('refresh');
                $("#numero_operaciones").val(response.DeudaId);
                $('#numero_operaciones').selectpicker('refresh');
            
            }
        });
    });

    $('#numero_operaciones').on('change', function () {
        Id = $(this).val()
        $.ajax({
            type: "POST",
            url: "../includes/judicial/getDeuda.php",
            data: "&Id="+Id,
            success: function(response){
                response = JSON.parse(response)
                $('#saldo_capital').val(response.SaldoCapital)
                $('#interes_vencido').val(response.InteresVencido)
                $('#interes_suspendido').val(response.InteresSuspendido)
                $('#interes_penal').val(response.InteresPenal)
                $('#gastos_cobranza').val(response.GastosCobranza)

                $('#monto_saldo_capital').val(response.SaldoCapital)
                $('#monto_interes_vencido').val(response.InteresVencido)
                $('#monto_interes_suspendido').val(response.InteresSuspendido)
                $('#monto_interes_penal').val(response.InteresPenal)
                $('#monto_gastos_cobranza').val(response.GastosCobranza)

                calcular_avenimiento()
            }
        });
    });

    $('#personaempresa_id_liquidacion').on('change', function () {
        Rut = $(this).val()
        $.ajax({
            type: "POST",
            url: "../includes/judicial/getPersona.php",
            data: "&Rut="+Rut,
            success: function(response){
                response = JSON.parse(response)
                $('#rut_liquidacion').val(response.Rut)
                $('#domicilio_particular_liquidacion').val(response.Direccion)
                $('#telefono_particular_liquidacion').val(response.Telefono)
                $('#saldo_capital_liquidacion').val(response.SaldoCapital)
                $('#interes_vencido_liquidacion').val(response.InteresVencido)
                $('#interes_suspendido_liquidacion').val(response.InteresSuspendido)
                $('#interes_penal_liquidacion').val(response.InteresPenal)
                $('#gastos_cobranza_liquidacion').val(response.GastosCobranza)

                $('#monto_saldo_capital_liquidacion').val(response.SaldoCapital)
                $('#monto_interes_vencido_liquidacion').val(response.InteresVencido)
                $('#monto_interes_suspendido_liquidacion').val(response.InteresSuspendido)
                $('#monto_interes_penal_liquidacion').val(response.InteresPenal)
                $('#monto_gastos_cobranza_liquidacion').val(response.GastosCobranza)

                calcular_liquidacion()

                $("#numero_operaciones_liquidacion").html(response.Deudas);
                $('#numero_operaciones_liquidacion').selectpicker('refresh');
                $("#numero_operaciones_liquidacion").val(response.DeudaId);
                $('#numero_operaciones_liquidacion').selectpicker('refresh');
            
            }
        });
    });

    $('#numero_operaciones_liquidacion').on('change', function () {
        Id = $(this).val()
        $.ajax({
            type: "POST",
            url: "../includes/judicial/getDeuda.php",
            data: "&Id="+Id,
            success: function(response){
                response = JSON.parse(response)
                $('#saldo_capital_liquidacion').val(response.SaldoCapital)
                $('#interes_vencido_liquidacion').val(response.InteresVencido)
                $('#interes_suspendido_liquidacion').val(response.InteresSuspendido)
                $('#interes_penal_liquidacion').val(response.InteresPenal)
                $('#gastos_cobranza_liquidacion').val(response.GastosCobranza)

                $('#monto_saldo_capital_liquidacion').val(response.SaldoCapital)
                $('#monto_interes_vencido_liquidacion').val(response.InteresVencido)
                $('#monto_interes_suspendido_liquidacion').val(response.InteresSuspendido)
                $('#monto_interes_penal_liquidacion').val(response.InteresPenal)
                $('#monto_gastos_cobranza_liquidacion').val(response.GastosCobranza)
                
                calcular_liquidacion()
            }
        });
    });

    $('#personaempresa_id_reavenimiento').on('change', function () {
        Rut = $(this).val()
        $.ajax({
            type: "POST",
            url: "../includes/judicial/getPersona.php",
            data: "&Rut="+Rut,
            success: function(response){
                response = JSON.parse(response)
                $('#rut_reavenimiento').val(response.Rut)
                $('#domicilio_particular_reavenimiento').val(response.Direccion)
                $('#telefono_particular_reavenimiento').val(response.Telefono)
                $("#numero_operaciones_reavenimiento").html(response.Deudas);
                $('#numero_operaciones_reavenimiento').selectpicker('refresh');
                $("#numero_operaciones_reavenimiento").val(response.DeudaId);
                $('#numero_operaciones_reavenimiento').selectpicker('refresh');
            
            }
        });
    });

    //AVENIMIENTO

    $('.avenimiento').blur(function () {

        //MONTOS

        name = $(this).attr('id');
        value = $(this).val()
        if(!value){
            value = 0
        }
        percent = $('#porcentaje_'+name).val();
        if(percent){
            percent = percent / 100
        }else{
            percent = 0
        }

        value_percent = value * percent
        value = value - value_percent

        $('#monto_'+name).val(value)
        calcular_avenimiento();
    });

    $('.avenimiento_porcentaje').blur(function () {

        //PORCENTAJES

        if($(this).val() < 0 || $(this).val() > 100){
            bootbox.alert('<h3>Descuento debe ser entre 0% y 100%</<h3>')
            $(this).val(0)
        }

        name = $(this).data('input');
        value = $('#'+name).val()
        if(!value){
            value = 0
        }
        percent = $(this).val();
        if(percent){
            percent = percent / 100
        }else{
            percent = 0
        }
        
        value_percent = value * percent
        value = value - value_percent

        $('#monto_'+name).val(value)
        calcular_avenimiento();
    });

    $('#porcentaje_honorario_abogado').blur(function () {

        //PORCENTAJE HONORARIO ABOGADO

        if($(this).val() < 0 || $(this).val() > 100){
            bootbox.alert('<h3>Descuento debe ser entre 0% y 100%</<h3>')
            $(this).val(0)
        }
        
        calcular_avenimiento();
    });

    $('#porcentaje_deuda_avenida').blur(function () {

        //PORCENTAJE ABONO INICIAL

        if($(this).val() < 3 || $(this).val() > 5){
            bootbox.alert('<h3>Honorario debe fluctuar entre 3% y 5% según estado de la causa</<h3>')
            $(this).val(3)
        }
        
        calcular_avenimiento();
    });

    $('#abono_inicial').blur(function () {

        //ABONO INICIAL 

        monto_honorario_abogado = $(this).val() * 0.10
        $('#monto_honorario_abogado').val(monto_honorario_abogado)

        calcular_avenimiento();
    });    

    $('#numero_cuotas').blur(function () {

        //NUMERO DE CUOTAS

        if($(this).val() < 1 || $(this).val() > 60){
            bootbox.alert('<h3>El numero de cuotas debe ser entre 1 y 60</<h3>')
            $(this).val(60)
        }
        
        calcular_avenimiento();
    });

    $('#primer_vencimiento').blur(function () {
        
        calcular_avenimiento();
    });

    function calcular_avenimiento(){

        montos = $('.desglose_disabled')
        total = 0;

        //MONTOS DE CADA INPUT CON SUS PORCENTAJES

        $.each(montos,function(index,array){
            if($(array).val()){
                monto = $(array).val()
            }else{
                monto = 0;
            }
            total += parseFloat(monto)
        })

        //ABONO INICIAL MINIMO
        
        abono_inicial_minimo = total - $('#monto_honorario_abogado').val()
        abono_inicial_minimo = abono_inicial_minimo * 0.15
        abono_inicial_minimo = Math.round(abono_inicial_minimo)
        $('#abono_inicial_minimo').text(abono_inicial_minimo)

        //ABONO INICIAL

        abono_inicial = $('#abono_inicial').val();
        if(abono_inicial > 0){
            porcentaje_abono_inicial = (total / abono_inicial) / 100
        }else{
            porcentaje_abono_inicial = 0
        }

        //PORCENTAJE HONORARIO ABOGADO POR DEUDA AVENIDA

        porcentaje_deuda_avenida = $('#porcentaje_deuda_avenida').val()
        porcentaje_deuda_avenida = porcentaje_deuda_avenida / 100

        monto_deuda_avenida = total * porcentaje_deuda_avenida;
        deuda_avenida = monto_deuda_avenida * porcentaje_deuda_avenida
        monto_deuda_avenida = monto_deuda_avenida - deuda_avenida
        monto_deuda_avenida = Math.round(monto_deuda_avenida)
        $('#monto_deuda_avenida').val(monto_deuda_avenida)

        //TOTAL DEUDA Y TOTAL AVENIR

        total = total + monto_deuda_avenida
        $('#total_deuda').val(total)
        total = total - abono_inicial
        $('#total_avenir').val(total)

        //NUMERO DE CUOTAS

        numero_cuotas = $('#numero_cuotas').val()

        //VALOR POR CUOTA

        valor_cuota = total / numero_cuotas
        valor_cuota = Math.round(valor_cuota);
        $('#valor_cuota').val(valor_cuota)

        primer_vencimiento = $('#primer_vencimiento').val()

        if(primer_vencimiento){
            fecha = moment(primer_vencimiento, 'DD-MM-YYYY')
        }else{
            fecha = ''
        }

        CreditoTable.clear().draw();

        if(total && fecha){

            var rowNode = CreditoTable.row.add([
                ''+"Abono Inicial"+'',
                ''+0+'',
                ''+abono_inicial+'',
                ''+"0"+'',
                ''+"0"+'',
                ''+total+'',
            ]).draw(false).node();

            cuota = 1;

            while(total >= 0){

                total -= valor_cuota

                fecha.add('1','M')

                var rowNode = CreditoTable.row.add([
                    ''+fecha.format('DD-MM-YYYY')+'',
                    ''+cuota+'',
                    ''+valor_cuota+'',
                    ''+"0"+'',
                    ''+valor_cuota+'',
                    ''+total+'',
                ]).draw(false).node();

                cuota++;
            }
        }
    }

    //LIQUIDACION

    $('.liquidacion').blur(function () {

        //MONTOS

        name = $(this).attr('id');
        value = $(this).val()
        if(!value){
            value = 0
        }
        percent = $('#porcentaje_'+name).val();
        if(percent){
            percent = percent / 100
        }else{
            percent = 0
        }

        value_percent = value * percent
        value = value - value_percent

        $('#monto_'+name).val(value)
        calcular_liquidacion();
    });

    $('.liquidacion_porcentaje').blur(function () {

        //PORCENTAJES

        if($(this).val() < 0 || $(this).val() > 100){
            bootbox.alert('<h3>Descuento debe ser entre 0% y 100%</<h3>')
            $(this).val(0)
        }

        name = $(this).data('input');
        value = $('#'+name).val()
        if(!value){
            value = 0
        }
        percent = $(this).val();
        if(percent){
            percent = percent / 100
        }else{
            percent = 0
        }
        
        value_percent = value * percent
        value = value - value_percent

        $('#monto_'+name).val(value)
        calcular_liquidacion();
    });

    function calcular_liquidacion(){

        //SUBTOTAL

        montos = $('.liquidacion')
        subtotal = 0;

        //MONTOS DE CADA INPUT CON SUS PORCENTAJES

        $.each(montos,function(index,array){
            if($(array).val()){
                monto = $(array).val()
            }else{
                monto = 0;
            }
            subtotal += parseFloat(monto)
        })

        $('#subtotal_deuda_liquidacion').val(subtotal)

        honorario = subtotal * 0.10

        $('#honorario_abogado_liquidacion').val(honorario)

        total = subtotal + honorario

        $('#total_deuda_liquidacion').val(total)

        //TOTAL

        montos = $('.desglose_disabled_liquidacion')
        subtotal_descuento = 0;

        //MONTOS DE CADA INPUT CON SUS PORCENTAJES

        $.each(montos,function(index,array){
            if($(array).val()){
                monto = $(array).val()
            }else{
                monto = 0;
            }
            subtotal_descuento += parseFloat(monto)
        })

        $('#subtotal_deuda_descuento_liquidacion').val(subtotal_descuento)

        porcentaje = $('#porcentaje_honorario_abogado_liquidacion').val()
        descuento = honorario * (porcentaje / 100)
        honorario_descuento = (subtotal * 0.10) - descuento
        $('#monto_honorario_abogado_liquidacion').val(honorario_descuento)

        total = subtotal_descuento + honorario

        $('#total_deuda_descuento_liquidacion').val(total)
    }

    //REAVENIMIENTO

    $('.reavenimiento').change(function(){
        calcular_reavenimiento()
    })

    $('#porcentaje_honorario_abogado_reavenimiento').blur(function () {

        //PORCENTAJE HONORARIO

        if($(this).val() < 3 || $(this).val() > 5){
            bootbox.alert('<h3>Honorario debe fluctuar entre 3% y 5% según estado de la causa</<h3>')
            $(this).val(3)
        }
        
        calcular_reavenimiento();
    });

    $('#abono_inicial_reavenimiento').change(function(){
        abono_inicial = $(this).val()

        // if(abono_inicial >= 5000000){
        //     console.log('a1')
        //     honorario = abono_inicial * 0.10
        // }else if(abono_inicial >= 3000000){
        //     console.log('a2')
        //     honorario = abono_inicial * 0.12
        // }else if(abono_inicial >= 1000000){
        //     console.log('a3')
        //     honorario = abono_inicial * 0.15
        // }else if(abono_inicial >= 400000){
        //     console.log('a4')
        //     honorario = abono_inicial * 0.18
        // }else if(abono_inicial >= 200000){ 
        //     console.log('a5')
        //     honorario = abono_inicial * 0.20
        // }else{
        //     console.log('a6')
        //     honorario = abono_inicial * 0.08
        // }
        honorario = abono_inicial * 0.10
        $('#honorario_abogado_reavenimiento').val(honorario)

        calcular_reavenimiento()
    })

    $('#numero_cuotas_reavenimiento').blur(function () {

        //NUMERO DE CUOTAS

        if($(this).val() < 1 || $(this).val() > 60){
            bootbox.alert('<h3>El numero de cuotas debe ser entre 1 y 60</<h3>')
            $(this).val(60)
        }
        
        calcular_reavenimiento();
    });

    $('#primer_vencimiento_reavenimiento').blur(function () {
        
        calcular_reavenimiento();
    });

    function calcular_reavenimiento(){

        i = 1
        total = 0;

        valor_cuota = parseFloat($('#valor_cuota_reavenimiento').val())
        cuotas_totales = parseFloat($('#numero_cuotas_totales_reavenimiento').val())
        cuotas_pagadas = parseFloat($('#numero_cuotas_pagadas_reavenimiento').val())
        cuotas_morosas = parseFloat($('#numero_cuotas_morosas_reavenimiento').val())
        cuoton = parseFloat($('#cuoton_reavenimiento').val())

        costos_judiciales = parseFloat($('#costos_judiciales_reavenimiento').val());
        honorario = parseFloat($('#honorario_abogado_reavenimiento').val())
        abono_inicial = parseFloat($('#abono_inicial_reavenimiento').val())
        porcentaje_honorario = $('#porcentaje_honorario_abogado_reavenimiento').val()
        porcentaje_honorario = porcentaje_honorario / 100

        if (typeof valor_cuota == 'undefined' || !valor_cuota) {
            valor_cuota = 0
        }

        if (typeof cuotas_totales == 'undefined' || !cuotas_totales) {
            cuotas_totales = 0
        }

        if (typeof cuotas_pagadas == 'undefined' || !cuotas_pagadas) {
            cuotas_pagadas = 0
        }

        if (typeof cuotas_morosas == 'undefined' || !cuotas_morosas) {
            cuotas_morosas = 0
        }

        if (typeof cuoton == 'undefined' || !cuoton) {
            cuoton = 0
        }

        // -----

        if (typeof costos_judiciales == 'undefined' || !costos_judiciales) {
            costos_judiciales = 0
        }

        if (typeof honorario == 'undefined' || !honorario) {
            honorario = 0
        }

        if (typeof abono_inicial == 'undefined' || !abono_inicial) {
            abono_inicial = 0
        }

        // ------


        //CUOTAS ( F23-(F24+25) )
        cuotas = cuotas_totales - (cuotas_pagadas + cuotas_morosas);

        //FORMULA VA ( VA(E27;F23-(F24+25);-F22) )

        // pago = valor_cuota
        // 1+i = 1.0019
        // Math.pow = potencia

        while(i <= cuotas){
            subtotal = valor_cuota / Math.pow(1.0019, i)
            total += subtotal
            i++;
        }

        //CONTINUACION FORMULA EXCEL
        // +F22*F25+F26
        
        total += valor_cuota * cuotas_morosas + cuoton

        if (typeof total == 'undefined' || !total) {
            total = 0
        }

        $('#deuda_actual_reavenimiento').val(total.toFixed(2))

        total_honorario = total + costos_judiciales + honorario - abono_inicial

        if (typeof total_honorario == 'undefined' || !total_honorario) {
            total_honorario = 0
        }

        // if(total_honorario >= 5000000){
        //     porcentaje_honorario = total_honorario * 0.06
        //     console.log(5)
        // }else if(total_honorario >= 3000000){
        //     porcentaje_honorario = total_honorario * 0.08
        //     console.log(4)
        // }else if(total_honorario >= 2000000){
        //     porcentaje_honorario = total_honorario * 0.10
        //     console.log(3)
        // }else if(total_honorario >= 500000){
        //     porcentaje_honorario = total_honorario * 0.15
        //     console.log(2)
        // }else if(total_honorario >= 200000){ 
        //     porcentaje_honorario = total_honorario * 0.20
        //     console.log(1)
        // }else{
        //     porcentaje_honorario = total_honorario * 0.04
        //     console.log(6)
        // }

        monto_honorario = total_honorario * porcentaje_honorario

        $('#monto_honorario_abogado_reavenimiento').val(monto_honorario.toFixed(2))

        total_deuda = total_honorario + monto_honorario + abono_inicial

        if (typeof total == 'undefined' || !total_deuda) {
            total_deuda = 0
        }

        $('#total_deuda_reavenimiento').val(total_deuda.toFixed(2))

        total = total_deuda - abono_inicial

        $('#total_avenir_reavenimiento').val(total)

        //NUMERO DE CUOTAS

        numero_cuotas = $('#numero_cuotas_reavenimiento').val()

        //VALOR POR CUOTA

        valor_cuota = total / numero_cuotas
        valor_cuota = Math.round(valor_cuota);
        $('#valor_cuota_condiciones').val(valor_cuota)

        primer_vencimiento = $('#primer_vencimiento_reavenimiento').val()

        if(primer_vencimiento){
            fecha = moment(primer_vencimiento, 'DD-MM-YYYY')
        }else{
            fecha = ''
        }

        ReavenimientoTable.clear().draw();

        if(total && fecha){

            var rowNode = ReavenimientoTable.row.add([
                ''+"Abono Inicial"+'',
                ''+0+'',
                ''+abono_inicial+'',
                ''+"0"+'',
                ''+"0"+'',
                ''+total+'',
            ]).draw(false).node();

            cuota = 1;

            while(total >= 0){

                total -= valor_cuota

                fecha.add('1','M')

                var rowNode = ReavenimientoTable.row.add([
                    ''+fecha.format('DD-MM-YYYY')+'',
                    ''+cuota+'',
                    ''+valor_cuota+'',
                    ''+"0"+'',
                    ''+valor_cuota+'',
                    ''+total+'',
                ]).draw(false).node();

                cuota++;
            }
        }
    }

    $('#tab_avenimiento').on('click', function (e) {
        $('#form_calculo_avenimiento input').val('');
        $('#form_calculo_avenimiento select').val('');
        $('.selectpicker').selectpicker('refresh')
        CreditoTable.clear().draw();
    });

    $('#tab_liquidacion').on('click', function (e) {
        $('#form_calculo_liquidacion_judicial input').val('');
        $('#form_calculo_liquidacion_judicial select').val('');
        $('.selectpicker').selectpicker('refresh')
    });

    $('#tab_reavenimiento').on('click', function (e) {
        $('#form_calculo_reavenimiento input').val('');
        $('#form_calculo_reavenimiento select').val('');
        $('.selectpicker').selectpicker('refresh')
        ReavenimientoTable.clear().draw();
    });
});
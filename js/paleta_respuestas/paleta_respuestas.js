$(document).ready(function () {
    //solo numeros
    $("#ponderacion").keydown(function (event) {
        if (event.shiftKey) {
            event.preventDefault();
        }

        if (event.keyCode == 46 || event.keyCode == 8) {} else {
            if (event.keyCode < 95) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            } else {
                if (event.keyCode < 96 || event.keyCode > 105) {
                    event.preventDefault();
                }
            }
        }
    });
    //solo numeros
    $("#peso").keydown(function (event) {
        if (event.shiftKey) {
            event.preventDefault();
        }

        if (event.keyCode == 46 || event.keyCode == 8) {} else {
            if (event.keyCode < 95) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            } else {
                if (event.keyCode < 96 || event.keyCode > 105) {
                    event.preventDefault();
                }
            }
        }
    });

    $('#continuar').click(function (e) {
        e.preventDefault();
        if ($('#mandante').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Seleccione Mandante</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#cedente').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Seleccione Cedente</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        } else {

            $("#oculto").css("display", "block", "animation", "fadeIn 1s");
            $("#oculto").css("animation", "fadeIn 1s");

        }
    });

    //modal nivel 1 inicio 
    $('#btnregistrar1').click(function (e) {
        e.preventDefault();
        idcedente = $('#cedente').val();
        if ($('#nombreRespuesta').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese un nombre</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        } else {

            var datos = $('#formnivel1').serialize();
            cedentenombre = $('#cedente option:selected').text();
            cedente_clear = cedentenombre.replace(/\d/g, ""); //eliminar numeros
            $.ajax({
                type: "POST",
                url: "../includes/paleta_respuestas/insertNivel1.php",
                data: datos + '&cedentenombre=' + cedente_clear + '&idcedente=' + idcedente,
                success: function (data) {
                    if (data) {
                        $('#formnivel1')[0].reset();
                        $('.selectpicker').selectpicker('deselectAll');
                        $.niftyNoty({
                            type: 'success',
                            icon: 'fa fa-check',
                            message: '<h4>Configuración Nivel 1 Creada</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        $("#oculto").fadeOut("slow");
                    } else {
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: '<h4>Error al crear la Configuración Nivel 1</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        return false;
                    }
                }

            });
        }
    });
    //modal nivel 1 fin

    //modal nivel 2 inicio
    $('#btnregistrar2').click(function (e) {
        e.preventDefault();
        idnivel1 = $('#nombrenivel1_2').val();
        if ($('#nombrenivel1_2').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Seleccione un Nivel</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#nombreRespuesta2').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese un nombre</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        } else {
            var datos = $('#formnivel2').serialize();
            cedentenombre = $('#cedente option:selected').text();
            cedente_clear = cedentenombre.replace(/\d/g, ""); //eliminar numeros
            $.ajax({
                type: "POST",
                url: "../includes/paleta_respuestas/insertNivel2.php",
                data: datos + '&cedentenombre=' + cedente_clear + '&idnivel1=' + idnivel1,
                success: function (data) {
                    if (data) {
                        $('#formnivel2')[0].reset();
                        $('.selectpicker').selectpicker('deselectAll');
                        $.niftyNoty({
                            type: 'success',
                            icon: 'fa fa-check',
                            message: '<h4>Configuración Nivel 2 Creada</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        $("#oculto").fadeOut("slow");
                    } else {
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: '<h4>Error al crear la Configuración Nivel 2</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        return false;
                    }
                }
            });
        }
    });
    //modal nivel 2 fin

    //modal nivel 3 inicio 
    $('#btnregistrar3').click(function (e) {
        e.preventDefault();
        idnivel2 = $('#nombrenivel2_1').val();

        if ($('#nombrenivel2_1').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Seleccione un nivel</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#gestion').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese Tipo de Gestion</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#ponderacion').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese una Ponderación</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#peso').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese un Peso</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        }
        if ($('#nombreRespuesta3').val() == '') {
            $.niftyNoty({
                type: 'danger',
                icon: 'fa fa-check',
                message: '<h4>Ingrese una Respuesta</h4>',
                container: 'floating',
                timer: 4000
            });
            return false;
        } else {
            var datos = $('#formnivel3').serialize();
            cedentenombre = $('#cedente option:selected').text();
            cedente_clear = cedentenombre.replace(/\d/g, ""); //eliminar numeros
            $.ajax({
                type: "POST",
                url: "../includes/paleta_respuestas/insertNivel3.php",
                data: datos + '&cedentenombre=' + cedente_clear + '&idnivel2=' + idnivel2,
                success: function (data) {
                    if (data) {
                        $('#formnivel3')[0].reset();
                        $('.selectpicker').selectpicker('deselectAll');
                        $.niftyNoty({
                            type: 'success',
                            icon: 'fa fa-check',
                            message: '<h4>Configuración Nivel 3 Creada</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        $("#oculto").fadeOut("slow");
                    } else {
                        $.niftyNoty({
                            type: 'danger',
                            icon: 'fa fa-check',
                            message: '<h4>Error al crear la Configuración Nivel 3</h4>',
                            container: 'floating',
                            timer: 4000
                        });
                        return false;
                    }
                }

            });
        }
    });
    //modal nivel 3 fin

    //funcion para ver mandante
    function getMandante() {
        $.ajax({
            url: "../includes/paleta_respuestas/getMandante.php",
            method: "POST",
            success: function (data) {
                $('#mandante').html(data);
                $('#mandante').selectpicker('refresh');
            }
        });
    }
    getMandante();


    //seleccionar los cedentes coincidentes con los mandantes
    function getCedentes() {
        $('#mandante').on('change', function () {
            idmandante = this.value;
            $.ajax({
                type: "POST",
                url: "../includes/paleta_respuestas/getCedentes.php",
                data: "&idmandante=" + idmandante, // envio al post de php
                success: function (data) {
                    $('#cedente').html(data);
                    $('#cedente').selectpicker('refresh');
                }
            })
        })
    }
    getCedentes();



    $('#cedente').on('change', function () {
        idcedente = this.value;
        //funcion para ver Id y nombre nivel 1
        function getNombreNivel1() {
            $.ajax({
                url: "../includes/paleta_respuestas/getNombreNivel1.php",
                data: "&idcedente=" + idcedente,
                method: "POST",
                success: function (data) {
                    $('#nombrenivel1').html(data);
                    $('#nombrenivel1').selectpicker('refresh');
                    $('#nombrenivel1_2').html(data);
                    $('#nombrenivel1_2').selectpicker('refresh');
                }
            });
        }
        getNombreNivel1();
    });

    $('#nombrenivel1_2').on('change', function () {
        idnivel1 = this.value;
        //funcion para ver Id y nombre nivel 2
        function getNombreNivel2() {
            $.ajax({
                url: "../includes/paleta_respuestas/getNombreNivel2.php",
                data: "&idnivel1=" + idnivel1,
                method: "POST",
                success: function (data) {
                    $('#nombrenivel2').html(data);
                    $('#nombrenivel2').selectpicker('refresh');
                }
            });
        }
        getNombreNivel2();
    });

    //funcion para ver Id tipo gestion nivel 2 en nivel 3
    function Nivel3getNombreNivel2() {
        $.ajax({
            url: "../includes/paleta_respuestas/Nivel3getNombreNivel2.php",
            method: "POST",
            success: function (data) {
                $('#nombrenivel2_1').html(data);
                $('#nombrenivel2_1').selectpicker('refresh');
            }
        });
    }
    Nivel3getNombreNivel2();

    //relacion entre nivel 2 y 3
    $('#nombrenivel2_1').on('change', function () {
        idnivel2 = this.value;
        //funcion para ver Id y nombre nivel 3
        function getNombreNivel3() {
            $.ajax({
                url: "../includes/paleta_respuestas/getNombreNivel3.php",
                data: "&idnivel2=" + idnivel2,
                method: "POST",
                success: function (data) {
                    $('#nombrenivel3').html(data);
                    $('#nombrenivel3').selectpicker('refresh');
                }
            });
        }
        getNombreNivel3();
    });



});
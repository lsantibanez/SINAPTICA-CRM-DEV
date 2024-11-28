$(document).ready(function(){

    $('#Cargando').modal({
        backdrop: 'static',
        keyboard: false
    });

    $.ajax({
        type: "POST",
        url: "../includes/admin/GetDatatableMenu.php",
        dataType: 'html',
        success: function(data){

            data = JSON.parse(data)

            $.each(data.headers, function( index, array ) {
                $('#TableMenu_thead').append('<th class="min-desktop"><center>'+array+'</center></th>')
            });

            $.each(data.menus, function( index, menus ) {
                $('#TableMenu_tbody').append('<tr id='+index+'>')
                $.each(menus, function( i, array ) {
                    if(i > 1){
                        $('#'+index).append('<td><center>'+array+'</center></td>')
                    }else{
                        $('#'+index).append('<td>'+array+'</td>')
                    }
                });
                
                $('#TableMenu_tbody').append('</tr>')
            });

            TableMenu = $('#TableMenu').DataTable({
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false
            });

            $('#Cargando').modal('hide')

        },
        error: function(response){
            console.log(response);
        }
    });
});

$(document).on('change', 'input[type=checkbox]', function() {

    $('#Cargando').modal({
        backdrop: 'static',
        keyboard: false
    });

    if($(this).is(":checked")) {
        $.ajax({
            type: "POST",
            url: "../includes/admin/crear_privilegio.php",
            data:"&id="+$(this).attr('id'),
            success: function(data){
                console.log('success privilegio');
            },
            error: function(response){
                console.log('error privilegio');
            }
        });

        message = "Privilegio creado exitosamente";

    }else{
        $.ajax({
            type: "POST",
            url: "../includes/admin/eliminar_privilegio.php",
            data:"&id="+$(this).attr('id'),
            success: function(data){
                console.log('success privilegio');
            },
            error: function(response){
                console.log('error privilegio');
            }
        });

        message = "Privilegio eliminado exitosamente";
    }

    setTimeout(function(){

        $.niftyNoty({
            type: 'success',
            icon : 'fa fa-check',
            message : message,
            container : 'floating',
            timer : 2000
        });

        $('#Cargando').modal('hide')

    },1000)
});
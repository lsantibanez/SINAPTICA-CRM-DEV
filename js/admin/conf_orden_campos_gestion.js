$(document).ready(function(){

    var CanUpdateOrder = false;
    getCedentes();

    $("#OrdenContainer").sortable({
        revert: true,
        update: function (event, ui) {
            jQuery.event.trigger('htmlchanged');
        }
    });
    $("select[name='Cedente']").change(function () {
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        CanUpdateOrder = false;
        getCamposSinOrden(Value);
        getCamposConOrden(Value);
    });
    $(document).bind('htmlchanged', function(){
        if (CanUpdateOrder) {
            var ArrayCampos = [];
            $(".FieldOrden").each(function(index){
                var ObjectMe = $(this);
                console.log(ObjectMe)
                var idCampo = ObjectMe.attr("id");
                var Anchura = ObjectMe.attr("anchura");
                ArrayCampos.push(
                    {
                        Campo: idCampo,
                        Anchura: Anchura
                    }
                );
            });
            console.log(ArrayCampos);
            actualizarOrdenCampos(ArrayCampos);
        } else {
            CanUpdateOrder = true;
        }
    });
    $("body").on("click",".deleteOrdenCampo",function(){
        var ObjectMe = $(this);
        var ObjectFieldOrden = ObjectMe.closest(".FieldOrden");
        ObjectFieldOrden.remove();
        jQuery.event.trigger('htmlchanged');
        getCamposSinOrden($("select[name='Cedente']").val());
    });
    $("body").on("click",".Field",function(){
        var ObjectMe = $(this);
        var idCampo = ObjectMe.attr("id");
        var Codigo = ObjectMe.attr("codigo");
        var Tipo = ObjectMe.attr("Tipo");
        var Template = $("#AgregarOrdenCampoTemplate").html()
        bootbox.dialog({
            title: "SELECCIONE LA ANCHURA DEL CAMPO SELECCIONADO",
            message: Template,
            buttons: {
                confirm: {
                    label: "Agregar",
                    callback: function() {
                        var Anchura = $("select[name='Anchura']").val();
                        if(Anchura != ""){
                            var Campo = "<div id='"+idCampo+"' anchura='"+Anchura+"' class='FieldOrden form-group col-md-"+Anchura+"'><div style='height: 80px; padding: 5px 10px; background-color: #eeeeee; border: 2px dashed #333333; cursor: pointer; position: relative;'><div class='deleteOrdenCampo fa fa-times' style='position: absolute; right: 15px; top: 15px;'></div><div class='CodigoCampo'>"+Codigo+"</div><div class='TipoCampo'>"+Tipo+"</div></div></div>";
                            $("#OrdenContainer").append(Campo);
                            jQuery.event.trigger('htmlchanged');
                            getCamposSinOrden($("select[name='Cedente']").val());
                        }else{
                            bootbox.alert("Debe seleccionar una Anchura para el Campo seleccionado.");
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });

    function getCamposSinOrden(Cedente){
		$.ajax({
			type: "POST",
            url: "../includes/admin/conf_campos_gestion/getCamposSinOrden.php",
            async: false,
            data: {
                Cedente: Cedente
            },
			success: function(data){
                $("#FieldList").html(data);
				console.log(data);
			},
			error: function(){
			}
		});
    }
    function getCamposConOrden(Cedente){
		$.ajax({
			type: "POST",
			url: "../includes/admin/conf_campos_gestion/getCamposConOrden.php",
            async: false,
            data: {
                Cedente: Cedente
            },
			success: function(data){
                $("#OrdenContainer").html(data);
                jQuery.event.trigger('htmlchanged');
                console.log(data);
			},
			error: function(){
			}
		});
    }
    function actualizarOrdenCampos(ArrayCampos){
        $.ajax({
            type: "POST",
            url: "../includes/admin/conf_campos_gestion/actualizarOrdenCampos.php",
            data: {
                Cedente: $("select[name='Cedente']").val(),
                ArrayCampos: ArrayCampos
            },
            async: false,
            success: function(data){
                console.log(data);
            },
            error: function(){
            }
        });
    }
    function getCedentes() {
        $.ajax({
            type: "POST",
            url: "../includes/estrategia/fillSelectCedentes.php",
            dataType: "html",
            data: {
            },
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                $("select[name='Cedente']").html(data);
                $("select[name='Cedente']").selectpicker('refresh')
            },
            error: function () {
            }
        });
    }
});
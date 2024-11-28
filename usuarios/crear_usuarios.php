<?php

require_once('../class/db/DB.php');
require_once('../class/session/session.php');
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
  
$objetoSession = new Session($Permisos, false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,crea_usua"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); 
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$usuario = $_SESSION['MM_Username'];
$idMandante = '';
$strSql = "SELECT id, nombre FROM mandante ORDER BY nombre ASC";

if (isset($_SESSION['empresas']) && is_array($_SESSION['empresas'])) {
  $idMandante = implode(',',$_SESSION['empresas']);
  $strSql = "SELECT id, nombre FROM mandante WHERE id IN (".$idMandante.") ORDER BY nombre ASC";
} 

$id_usuario = (isset($_GET['id_usuario']) && !empty($_GET['id_usuario']))? (int) $_GET['id_usuario']:'';
include("../includes/usuarios/datos_usuario.php");
$db = new DB();
$roles = $db->select("SELECT id, nombre, nivel FROM Roles ORDER BY nivel ASC");

$empresas = $db->select($strSql);
if ($empresas) {
  foreach((array) $empresas as $key => $empresa) {
    $empresas[$key]['proyectos'] = [];
    $proyectos = $objetoCedente->getCedentesMandante((int) $empresa['id']);
    if ($proyectos) {
      $proyectos = array_map(function ($a) {
        return [
          'id' => $a['idCedente'],
          'nombre' => $a['NombreCedente']
        ];
      }, (array) $proyectos);
      $empresas[$key]['proyectos'] = $proyectos;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia | Gestión de usuarios | <?php echo $tituloVentana; ?></title>
    <!--STYLESHEET-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/global/global.css">
    <style>
      .panel {
        border: 1px solid #ccc;
        box-shadow: none !important;
      }
      .panel-header {
        padding: 5px 6px;
        font-weight: 600;
        border-bottom: 1px solid #ccc;
        background-color: #efefef;
      }
      .panel-header > .checkbox {
        padding-top: 4px !important;
        padding-left: 6px !important;
      }
      .panel-header > .checkbox > label {
        font-weight: 600;
      }
      .panel-body {
        padding: 10px;
      }
    </style>
</head>
<body>
<!-- <input type="hidden" id="cedente" value="<?php //echo $cedente; ?>">-->
  <div id="container" class="effect mainnav-lg">
    <!--NAVBAR-->
    <!--===================================================-->
    <?php
    include("../layout/header.php");
    ?>
    <!--===================================================-->
    <!--END NAVBAR-->
    <div class="boxed">
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
      <div id="content-container">
          <!--Page Title-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title"></div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
          <li><a href="#">Configuración</a></li>
          <li><a href="#">Sistema</a></li>
          <li class="active">Gestionar usuarios</li>
        </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
        <div id="page-content">
          <div class="row">
            <div class="col-md-12">
              <div class="panel">
                <div class="panel-heading">
                  <h3 class="panel-title">Gestión de usuarios - <?php echo $tituloVentana; ?></h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-8">
                      <form action="" method="post" onsubmit="return validaFormulario(this)" class="form-horizontal" autocomplete="off">
                        <div class="form-group mb-4">
                          <label for="usuario" class="col-sm-4 control-label">Nombre y apellido:</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" placeholder="Nombre y apellido" required>
                          </div>
                        </div><!-- nombre -->
                        <div class="form-group">
                          <label for="usuario" class="col-sm-4 control-label">Usuario:</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario; ?>" placeholder="Usuario" <?php if ($modificar) echo 'readonly'; ?> required>
                          </div>
                        </div><!-- usuario -->
                        <?php if (!empty($extension) && !is_null($extension) && $modificar) { ?>
                        <div class="form-group">
                          <label for="usuario" class="col-sm-4 control-label">Extensión:</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="extension" name="extension" value="<?php echo $extension; ?>" readonly>
                          </div>
                        </div><!-- usuario -->
                        <?php } ?>
                        <div class="form-group">
                          <label for="password" class="col-sm-4 control-label">Contraseña:</label>
                          <div class="col-sm-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" <?php if (!$modificar) echo 'required'; ?>>
                            <?php if ($modificar) { ?>
                            <small style="margin-top: 5px; display:block;">Para cambiar las contraseña, llene este campo.</small>
                            <?php } ?>
                          </div>
                        </div><!-- password -->                        
                        <?php if((int) $_SESSION['id_usuario'] === (int) $id_usuario) { 
                          echo '<input type="hidden" name="rol" value="'.$rolUsuario.'">'.PHP_EOL;
                        } else { ?>
                        <div class="form-group">
                          <label for="rol" class="col-sm-4 control-label">Rol:</label>
                          <div class="col-sm-4">
                            <select name="rol" id="rol" size="1" class="form-control" onchange="validaRol(this.value)" <?php if((int) $_SESSION['id_usuario'] === (int) $id_usuario) echo 'readonly'; ?>>
                              <option value="">-- Seleccione --</option>
                              <?php if ($roles) { 
                                foreach((array) $roles as $rol) {
                                  $activo = '';
                                  if ($rol['id'] == $rolUsuario) $activo = 'selected';
                                  echo '<option value="'.$rol['id'].'" '.$activo.'>'.$rol['nombre'].'</option>'.PHP_EOL;
                                }
                              } ?>
                            </select>
                          </div>
                        </div><?php } ?><!-- rol -->
                        <div class="form-group">
                          <label for="empresa" class="col-md-4 control-label">Empresa(s):</label>
                          <div class="col-md-8">
                            <div class="row" style="margin-top: 8px;" id="listEmpresas2">No hay</div>
                          </div>
                        </div><!-- empresa -->                       
                        <div class="form-group" style="margin-top: 30px;">
                          <div class="col-sm-offset-4 col-sm-4">
                            <button type="submit" id="guardar" class="btn btn-block btn-success">
                              Guardar
                            </button>
                          </div>
                          <div class="col-sm-2">
                            <button type="button" id="guardar" class="btn btn-block btn-info" onclick="regresar()">
                              Regresar
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div><!-- panel body -->
              </div><!-- panel -->
            </div>
          </div><!-- row -->
        </div>
      </div>
    </div>
        <!--MAIN NAVIGATION-->
        <!--===================================================-->
        <?php include("../layout/main-menu.php"); ?>
        <!--===================================================-->
        <!-- FOOTER -->
        <?php include("../layout/footer.php"); ?>
        <!--===================================================-->
        <!-- SCROLL TOP BUTTON -->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <div class="modal fade" tabindex="-1" role="dialog" id="Cargando">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="spinner loading"></div>
                        <h4 class="text-center">Procesando por favor espere...</h4>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	  <script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../js/global.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <?php /* <script src="../js/usuarios/usuarios.js"></script> */ ?>
    <script>
      const empresas = <?php echo json_encode($empresas); ?>;
      const idEmpresa = <?php echo (int) $idEmpresa; ?>;
      const idProyecto = <?php echo (int) $idProyecto; ?>;
      const isEdicion = <?php if((bool) $modificar) echo 1; else echo 0; ?>;
      const uNivel = <?php if((int) $rolUsuario > 0) echo (int) $rolUsuario; else echo 0; ?>;
      const proyectosUsuario = <?php echo json_encode($userProyectos); ?>;
      const listEmpresasActivas = [];

      $(document).ready(function() {
        llenarEmpresas();
      });
      /*
      function validaRol(valor) {
        const elEmpresas = $('select#empresa');
        const elProyectos = $('select#proyecto');
        if(parseInt(valor) === 2) {
          elEmpresas.attr('disabled', true).removeAttr('required').val('');
          elProyectos.attr('disabled', true).removeAttr('required').val('');
        } else {
          elEmpresas.removeAttr('disabled').attr('required', true).val('');
          elProyectos.removeAttr('disabled').attr('required', true).val('');
          if (idEmpresa > 0 && idProyecto > 0 && isEdicion === 1) { 
            elEmpresas.val(idEmpresa);
            llenarProyectos(idEmpresa);
          }
        }
      }
      */
      function llenarEmpresas() {
        const elEmpresas = $('div#listEmpresas2');
        let html = `No hay empresas`;
        if (empresas.length) {
          html = '';
          empresas.forEach((item) => {
            html += '<div class="col-md-6"><div class="panel"><div class="panel-header">';
            var activo = '';
            if (parseInt(item.id) === idEmpresa) { 
              activo = 'checked';
              listEmpresasActivas.push(item);
            }
            html += `<div class="checkbox"><label><input type="checkbox" class="itemEmpresa" id="empresa_${item.id}" value="${item.id}" ${activo}>${item.nombre}</label></div>`;
            html += `</div><div class="panel-body" id="empresa_proyectos_${item.id}">No seleccionado</div>`
            html += '</div></div>';
          });
        }
        elEmpresas.html(html);
        capturarEvento();
        llenarProyectos();
      }

      function llenarProyectos() {
        const elProyectos = $('div#listProyectos');
        //let html = `<div class="col-md-6">Seleccione empresa</div>`;

        if (listEmpresasActivas.length) {
          // html = `<div class="col-md-6">Sin proyectos</div>`;
          listEmpresasActivas.forEach((empresa) => {
            if (empresa['proyectos'].length) {
              /*
              html = `<div class="col-md-6">\n`;
              html += `<div class="panel panel-default">\n`;
              html += `<div class="panel-heading" style="padding: 5px 10px; height: 25px;">${empresa.nombre}</div>\n`;
              html += `<div class="panel-body" style="padding: 5px 10px;">\n`;
              */
              let divProyectos = $(`div#empresa_proyectos_${empresa.id}`)
              let htmlProyecto = '';
              if (!empresa['proyectos'].length) htmlProyecto = 'No hay proyectos'
              empresa['proyectos'].forEach((item) => {
                let activo = '';
                if (proyectosUsuario.includes(parseInt(item.id))) activo = 'checked';
                //html += `<div class="checkbox"><label><input type="checkbox" class="itemProyecto" id="proyecto_${item.id}" value="${item.id}" ${activo}>${item.nombre}</label></div>`;
                htmlProyecto += `<div class="checkbox"><label><input type="checkbox" class="itemProyecto" id="proyecto_${item.id}" value="${item.id}" ${activo}>${item.nombre}</label></div>`;
              })
              //html += `</div></div></div>\n`;
              htmlProyecto += `</div>\n`;
              divProyectos.html(htmlProyecto)
            }
          });
        }
        //elProyectos.html(html);
        capturarEventoProyecto();
      }

      function validaFormulario(form) {
        if (parseInt(uNivel) !== 2) {
          if (!listEmpresasActivas.length) {
            $.niftyNoty({
              type: 'warning',
              icon : 'fa fa-close',
              message : 'Debe seleccionar al menos 1 empresa',
              container : 'floating',
              timer : 5000
            });
            return false;
          }

          if (listEmpresasActivas.length &&!proyectosUsuario.length) {
            $.niftyNoty({
              type: 'warning',
              icon : 'fa fa-close',
              message : 'Debe seleccionar al menos 1 proyecto',
              container : 'floating',
              timer : 5000
            });
            return false;
          }
        }

        const data = $(form).serializeArray();
        console.log(data);
        let fields = {};
        data.forEach((item) => {
          fields[item.name] = item.value;
        });
        
        fields['empresas'] = listEmpresasActivas.map(e => parseInt(e.id));
        fields['proyectos'] = proyectosUsuario;
        let request = {
          accion: (isEdicion === 1)? 'update':'create',
          id_usuario: <?php echo (int) $id_usuario; ?>,
          campos: fields,
        }

        var randNumber = Math.floor(Math.random() * 9999);
        $.ajax({
            type: 'POST',
            url: '../includes/usuarios/guardar_usuario.php?.rand=' + randNumber,
            dataType: 'json',
            data: request,
            beforeSend: function () {
              $('button#guardar').attr('disabled', true);
            },
            success: function(data) {
              console.log('Respuesta: ',data);
              $('button#guardar').removeAttr('disabled');
              if (data.success) {
                $('button#guardar').hide();
                $.niftyNoty({
                  type: 'success',
                  icon : 'fa fa-close',
                  message : data.message,
                  container : 'floating',
                  timer : 4000
                });
                /*
                $.niftyNoty({
                  type: 'info',
                  icon : 'fa fa-close',
                  message : 'Espere unos segundos para finalizar la operación.',
                  container : 'floating',
                  timer : 4000
                });
                */
                
                setTimeout(() => {
                  window.location.href = 'usuarios';
                }, 1000);
              } else {
                $.niftyNoty({
                  type: 'warning',
                  icon : 'fa fa-close',
                  message : data.message,
                  container : 'floating',
                  timer : 5000
                });
              }
            },
            error: function() {
              $('button#guardar').removeAttr('disabled');
              //alert('error listaEmpresas');
              $.niftyNoty({
                type: 'danger',
                icon : 'fa fa-close',
                message : "Error al intentar ejecutar la solicitud" ,
                container : 'floating',
                timer : 5000
              });
            }
        });
        return false;
      }

      function capturarEvento() {
        $('.itemEmpresa').on('change', (e) =>{
          const valor = e.target.value;
          if (e.target.checked) {
            console.log('Agregar empresa');
            const empresa = empresas.find(e => parseInt(e.id) === parseInt(valor));
            const existe = listEmpresasActivas.find(e => parseInt(e.id) === parseInt(valor));
            if (empresa !== undefined && existe === undefined) {
              listEmpresasActivas.push(empresa);
            }
          } else {
            console.log('Quitar empresa');
            const i = listEmpresasActivas.findIndex(e => parseInt(e.id) === parseInt(valor));
            if (i !== -1) listEmpresasActivas.splice(i,1);
          }
          llenarProyectos();
        });
      }

      function capturarEventoProyecto() {
        $('.itemProyecto').on('change', (e) => {
          const valor = e.target.value;
          if (e.target.checked) {
            console.log('Agregar proyecto');
            const existe = proyectosUsuario.find(e => parseInt(e) === parseInt(valor));
            if (existe === undefined) {
              proyectosUsuario.push(parseInt(valor));
            }
          } else {
            console.log('Quitar proyecto');
            const i = proyectosUsuario.findIndex(e => parseInt(e) === parseInt(valor));
            if (i !== -1) proyectosUsuario.splice(i,1);
          }
        });
      }

      function regresar() {
        window.location.href = 'usuarios';
      }
    </script>
</body>
</html>
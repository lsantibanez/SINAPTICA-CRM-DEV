<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
include("../class/global/global.php");
include("../class/global/cedente.php");

$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,gestion,agregar_campos"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  //o fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM | Software de Estrategia</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/nifty.min.css" rel="stylesheet">
  <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
  <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
  <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
  <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/global/global.css" rel="stylesheet">
</head>
<body>
  <div id="container" class="effect mainnav-lg">
    <?php include("../layout/header.php"); ?>
    <div class="boxed">
      <div id="content-container">
        <div id="page-title">
          <h1 class="page-header text-overflow">Gestión de tablas</h1>
        </div><!--Searchbox-->
        <ol class="breadcrumb">
          <li><a href="#">Configuración</a></li>
          <li class="active">Gestión de tablas</li>
        </ol><!--Breadcrumb-->
        <div id="page-content">
          <div id="app">
            <div class="row">
              <div class="col-md-12">
                <div class="panel">
                  <div class="panel-body">
                    <div class="row" style="padding: 12px;" v-if="!showForm">
                      <div class="col-md-10 col-md-offset-1">
                        <h5>Formulario</h5>
                        <form class="form-horizontal" onsubmit="return false;">
                          <div class="form-group">
                            <label for="nombre" class="col-sm-2 control-label" style="text-align:right;">Nombre:</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="nombre" placeholder="Nombre de tabla" v-model="elemento.title">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="campos" class="col-sm-2 control-label" style="text-align:right;">Campo(s):</label>
                            <div class="col-sm-10">
                              <table class="table table-sm">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th style="width: 20%; text-align: center;">Tipo</th>
                                    <th style="width: 10%; text-align: center;">Tamaño</th>
                                    <th style="width: 5%; text-align: center;">Requerido</th>
                                    <th style="width: 5%; text-align: center;">Indice</th>
                                    <th style="width: 5%; text-align: center;">Estrategia</th>
                                    <th style="width: 5%; text-align: center;">&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="(campo, index) in elemento.fields" :key="index">
                                    <td style="vertical-align: middle;">
                                      <input type="text" name="" id="" class="form-control text-center" v-model="campo.name">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <select name="" id="" class="form-control" v-model="campo.type">
                                        <option value="">-- Seleccione --</option>
                                      </select>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <input type="text" name="" id="" class="form-control text-center" v-model="campo.length">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <input type="checkbox" name="" id="" class="form-control">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <input type="checkbox" name="" id="" class="form-control">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <input type="checkbox" name="" id="" class="form-control">
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                      <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                  </tr>
                                  <tr v-if="!elemento.fields.length">
                                    <td colspan="7">No hay campos disponibles</td>
                                  </tr>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td colspan="3">
                                      <button class="btn btn-success" type="button" @click.prevent="addField">
                                        <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar campo</button>
                                    </td>
                                    <td colspan="4">&nbsp;</td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div v-if="!showForm">
                      <div class="row" style="padding: 12px;">
                        <div class="col-md-12">
                          <button class="btn btn-success" @click.prevent="crearNueva"><i class="fa fa-plus"></i>&nbsp;&nbsp;Crear nueva tabla</button>
                        </div>
                      </div>                    
                      <div class="row" style="padding: 12px;">
                        <div class="col-md-12">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Nombre</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(tabla, index) in lista" :key="index">
                                <td>{{ tabla.nombre }}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- page content -->
      </div><!-- content-container -->
      <?php include("../layout/main-menu.php"); ?>
    </div><!-- boxed -->
  </div><!-- container -->
  <script src="../js/extra/vue.min.js"></script>
  <script src="../js/extra/axios.min.js"></script>
  <script>
    var app = new Vue({
      el: '#app',
      data:function () {
        return {
          message: 'Hola Vue!',
          lista: [],
          showForm: false,
          elemento: {
            name: '',
            title: '',
            fields: []
          },
          field: {
            name: '',
            type: '',
            length: '',
            required: false,
            index: false,
            nullable: true,
          }
        }
      },
      created: function () {
        this.getTables();
      },
      methods: {
        getTables: function() {
          axios.get('../includes/admin/get_tablas.php').then(response => {
            console.log(response)
            this.lista = response.data;
          }).catch(error => {
            console.error(error);
          })
        },
        crearNueva: function () {
          this.showForm = true;
          setTimeout(() => {
            this.showForm = false;
          }, 3000);
          alert('Crear nueva tabla');
        },
        addField: function () {
          this.elemento.fields.push(JSON.parse(JSON.stringify(this.field)));
        }
      },
    });
  </script>
</body>
</html>
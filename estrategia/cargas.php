<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession(["idMenu" => "est,loadestra"]);
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = '';
$mandante = '';
if (isset($_SESSION['mandante']) && !empty($_SESSION['mandante'])) $mandante = (int) $_SESSION['mandante'];
if (isset($_SESSION['cedente']) && !empty($_SESSION['cedente']))  $cedente  = (int) $_SESSION['cedente'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM Sinaptica | Software de Estrategia</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/nifty.min.css" rel="stylesheet">
  <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
  <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
  <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
  <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
  <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
  <link href="../plugins/pace/pace.min.css" rel="stylesheet">
  <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
  <script src="../plugins/bootbox/bootbox.min.js"></script>
  <link rel="stylesheet" href="/css/global/global.css">
</head>
<body>
  <div id="container" class="effect mainnav-lg">
    <?php include("../layout/header.php"); ?>
    <div class="boxed">
      <div id="content-container">
        <div id="page-title">
          <h1 class="page-header text-overflow">Segmentador</h1>
        </div><!--Searchbox-->
        <ol class="breadcrumb">
          <li><a href="#">Segmentador</a></li>
          <li class="active">Gestionar cargas</li>
        </ol><!--Breadcrumb-->
        <div id="page-content">
          <div id="appCargas">
            <div id="cuerpo" style="display: none">
              <div class="row" v-if="showAsociation">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="row" style="padding: 12px;">
                      <div class="col-md-10">
                        <h3>Asociación de columnas para cargar archivo</h3>
                        <div class="form-horizontal" style="margin-top: 15px;">
                          <div class="form-group" style="margin-bottom: 0;">
                            <label for="inputEmail3" class="col-sm-2 control-label">Archivo:</label>
                            <div class="col-sm-10">
                              <p class="form-control-static" style="font-weight: 600;">{{ itemAsociation.archivo }}</p>
                            </div>
                          </div><!-- row 0 -->
                          <div class="form-group" style="margin-bottom: 0;">
                            <label for="inputEmail3" class="col-sm-2 control-label">Configuración:</label>
                            <div class="col-sm-10">
                              <p class="form-control-static" style="font-weight: 600;">{{ itemAsociation.nombre }}</p>
                            </div>
                          </div><!-- row 1 -->
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Asociación:</label>
                            <div class="col-sm-10">
                              <div class="row" style="padding: 10px;" v-if="messageColumns.text !== ''">
                                <div class="col-md-12">
                                  <div class="alert" :class="'alert-' + messageColumns.type" role="alert" style="border-radius: 5px;" v-html="messageColumns.text"></div>
                                </div>
                              </div>
                              <table class="table table-sm table-striped">
                                <thead>
                                  <tr>
                                    <th style="width: 48%;">Requerido en el sistema</th>
                                    <th>&nbsp;</th>
                                    <th style="width: 48%;">Columna en el archivo</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="(columna, index) in itemAsociation.columnas" :key="index">
                                    <td style="vertical-align: middle;">
                                      {{ columna.name }}</td>
                                    <td style="text-align: center; vertical-align: middle; font-size: 14px;">                                    
                                      <i class="fa fa-circle" v-if="columna.file === '' || columna.file === null" style="color: red;"></i>
                                      <i class="fa fa-circle-check" v-if="columna.file !== '' && columna.file !== null" style="color: green;"></i>
                                    </td>
                                    <td>
                                      <select class="form-control" size="1" v-model="columna.file" :id="'columna-' + index" @change="onFilterField" :required="columna.required">
                                        <option value="">-- Seleccione --</option>
                                        <option v-for="(fColumna, indexCol) in fileColumns" :key="indexCol" :value="fColumna.value" :disabled="fColumna.selected">{{ fColumna.value }}</option>
                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div><!-- row 2 -->
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                              <div class="row">
                                <div class="col-md-4" v-if="canSave">
                                  <button type="button" class="btn btn-lg btn-block btn-success" @click.prevent="saveAssociation" style="padding-top: 10px !important;">
                                    <i class="fa fa-hard-drive"></i>&nbsp;&nbsp;Guardar asociación
                                  </button>
                                </div>
                                <div class="col-md-4">
                                  <button type="button" class="btn btn-lg btn-block btn-danger" @click.prevent="cancelAssociation" style="padding-top: 10px !important;">
                                    <i class="fa fa-close"></i>&nbsp;&nbsp;Cancelar
                                  </button>
                                </div>
                              </div>                            
                            </div>
                          </div><!-- row 3 -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" v-if="!showAsociation">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                      <div class="row" style="padding: 12px;" v-if="message.text !== ''">
                        <div class="col-md-12">
                          <div class="alert" :class="'alert-' + message.type" role="alert" style="border-radius: 5px;">
                            {{ message.text }}
                          </div>
                        </div>
                      </div>
                      <div class="row" style="padding: 12px;" v-if="isAsignacion">
                        <div class="col-md-12">
                          <h3>Asignar carga</h3>
                        </div>
                      </div>
                      <div class="row" style="padding: 12px;" v-else>
                        <div class="col-md-12">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th style="width: 20%; text-align: center;">Acciones</th>
                                <th style="text-align: left;">Asociación de columnas</th>
                                <th style="width: 15%;">Archivo</th>
                                <th style="width: 10%; text-align: center;">Configuración</th>
                                <th style="width: 10%; text-align: center;">Estado</th>
                                <th style="width: 15%; text-align: center;">Registrado</th>                            
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(carga, index) in lista" :key="index">
                                <td style="text-align: center;">
                                  <div v-if="carga.con_asociacion !== '0'">
                                    <button type="button" class="btn btn-info" @click.prevent="setCargar(carga.load_id, carga.archivo, $event)" v-if="carga.procesado === '0'">
                                      <i class="fa fa-file-arrow-up"></i>&nbsp;&nbsp;Cargar
                                    </button>
                                    <button type="button" class="btn btn-primary" @click.prevent="editConfiguracion(carga, $event)" v-if="carga.procesado === '0'">
                                      <i class="fa fa-pen"></i>&nbsp;&nbsp;Editar asociación
                                    </button>
                                    <button type="button" class="btn btn-success" @click.prevent="goSegmentador" v-if="carga.procesado === '1'">
                                      <i class="fa fa-arrows-split-up-and-left"></i>&nbsp;&nbsp;Crear segmentación
                                    </button>
                                  </div>
                                  <div v-else>
                                    <button type="button" class="btn btn-primary" @click.prevent="getConfiguracion(carga, $event)">
                                    <i class="fa fa-diagram-predecessor"></i>&nbsp;&nbsp;Crear asociación
                                    </button>
                                  </div>
                                  <br/>
                                  <button type="button" class="btn btn-danger" style="margin-top: 10px;" @click.prevent="releaseFile(carga, $event)" v-if="carga.procesado === '0'">
                                    <i class="fa fa-trash"></i>&nbsp;&nbsp;Descartar archivo
                                  </button>
                                </td>
                                <td style="text-align: left; vertical-align:middle;">
                                  <span v-if="carga.con_asociacion === '0'">No posee asociación</span>
                                  <div v-else>
                                    <ul style="padding-left: 10px;">
                                      <li v-for="(relacion, rIndex) in carga.relacion" :key="rIndex">
                                        {{ relacion.name }}&nbsp;&nbsp;<strong>[{{ relacion.file }}]</strong>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                                <td>{{ carga.archivo }}</td>
                                <td style="text-align: center;">{{ carga.configuracion }}</td>
                                <td style="text-align: center;">
                                  <div v-if="carga.procesado === '0'">
                                    <span v-if="carga.con_asociacion === '0'">Sin asociación</span>
                                    <span v-else>No cargado</span>
                                  </div>
                                  <div v-else>
                                    <span>Lista para segmentar</span>
                                  </div>
                                </td>
                                <td style="text-align: center; font-size: 11px;">{{ carga.creado_el }}</td>                            
                              </tr>
                              <tr v-if="!lista.length">
                                <td colspan="6" style="text-align: center;">No hay registros</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>                  
                    </div>
                  </div>              
                </div>
              </div><!-- row 0 -->
            </div>
            <div id="cargando">
              <div class="row" v-if="showAsociation">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                      <h4>Cargando módulo, por favor espere...</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- app -->
        </div><!-- page-content -->
      </div><!-- content-container -->
      <?php include("../layout/main-menu.php"); ?>
    </div><!-- boxed -->
    <?php include("../layout/footer.php"); ?>
    <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
  </div><!-- container -->
  <script src="../js/jquery-2.2.1.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/nifty.min.js"></script>
  <script src="../plugins/fast-click/fastclick.min.js"></script>
  <script src="../plugins/bootbox/bootbox.min.js"></script>
  <script src="../js/demo/ui-alerts.js"></script>
  <script src="../js/global/funciones-global.js"></script>
  <script src="../js/extra/vue.min.js"></script>
  <script src="../js/extra/axios.min.js"></script>
  <script>
    var appCargas = new Vue({
      el: '#appCargas',
      data: () => {
        return {
          isAsignacion: false,
          showAsociation: false,
          canSave: false,
          itemAsociation: {
            id: '',
            uuid: '',
            nombre: '',
            archivo: '',
            columnas: []
          },
          fileColumns: [],
          lista: [],
          message: {
            type: 'info',
            text: ''
          },
          messageColumns: {
            type: 'info',
            text: ''
          }
        }
      },
      mounted: function() {
        //setTimeout(() => {
          document.getElementById('cuerpo').style.display = 'block';
          document.getElementById('cargando').style.display = 'none';
          console.info('Módulo cargado');
        //}, 2000);
      },
      created: function () {
        // `this` hace referencia a la instancia vm
        console.log('Creada');
        this.getCargas();
      },
      methods: {
        getCargas() {
          axios.get('../includes/cargas/lista.php').then(response => {
            if (response.data.length)  this.lista = response.data;
          }).catch(error => {
            console.error(error);
          });
        },
        setCargar(id, archivo, e) {
          const boton = e.target;
          if (!confirm('¿Confirma procesar el archivo *'+ archivo +'*?')) return;
          boton.disabled = true;
          this.message.text = 'Realizando operación, por favor espere...';
          axios.post('../includes/cargas/load.php', { id }).then(response => {
            const datos = response.data;            
            this.getCargas();
            boton.disabled = false;
            this.message.type = (response.data.success)? 'success':'danger';
            this.message.text = response.data.message;
            setTimeout(() => {
              this.message.text = '';
              this.message.type = 'info';
            }, 5000);            
          }).catch(error => {
            console.error(error);
            boton.disabled = false;
            this.message.type = 'danger';
            this.message.text = 'Se ha presentado un erro, no se pudo completar la operación.';
            setTimeout(() => {
              this.message.text = '';
              this.message.type = 'info';
            }, 5000);
          });          
        },
        releaseFile(item, e) {
          const id = item.load_id;
          const boton = e.target;
          if (!confirm('¿Confirma descartar el archivo *'+ item.archivo +'*?')) return;
          boton.disabled = true;
          this.message.text = 'Realizando operación, por favor espere...';
          axios.post('../includes/cargas/release.php', { id }).then(response => {
            const datos = response.data;
            boton.disabled = false;
            this.message.type = (response.data.success)? 'success':'danger';
            this.message.text = response.data.message;
            this.getCargas();
            setTimeout(() => {
              this.message.text = '';
              this.message.type = 'info';
            }, 5000);            
          }).catch(error => {
            console.error(error);
            boton.disabled = false;
            this.message.type = 'danger';
            this.message.text = 'Se ha presentado un erro, no se pudo completar la operación.';
            setTimeout(() => {
              this.message.text = '';
              this.message.type = 'info';
            }, 5000);
          });          
        },
        getConfiguracion(item, e) {
          this.fileColumns = item.columnas.map(c => { return ({ value: c, selected: false }) });
          axios.post('../includes/cargas/configuracion_carga.php', { id: item.configuracion_id }).then(response => {
            const datos = response.data;
            this.itemAsociation = datos;
            this.itemAsociation.archivo = item.archivo;
            this.itemAsociation.uuid = item.load_id;
            this.showAsociation = true;
          }).catch(error => {
            console.error(error);
          });
        },
        editConfiguracion(item, e) {
          this.fileColumns = item.columnas.map(c => { return ({ value: c, selected: false }) });
          axios.post('../includes/cargas/configuracion_carga.php', { id: item.configuracion_id }).then(response => {
            const datos = response.data;
            this.itemAsociation = datos;
            this.itemAsociation.columnas = item.relacion;
            this.itemAsociation.archivo = item.archivo;
            this.itemAsociation.uuid = item.load_id;
            this.showAsociation = true;
            this.validateForm();
          }).catch(error => {
            console.error(error);
          });
        },
        onFilterField() {
          let campos = this.itemAsociation.columnas.filter(c => c.file !== null && c.file !== '');
          this.fileColumns.forEach( campo => {
            if(campos.find( h => h.file == campo.value) !== undefined) {
              campo.selected = true;
            } else {
              campo.selected = false;
            }
          });
          this.validateForm();
        },
        validateForm() {
          const faltantes = this.itemAsociation.columnas.filter(c => ((c.file == null || c.file === '') && c.required === true));
          const requeridas = this.itemAsociation.columnas.filter(c => c.required === true).length;
          if (faltantes.length) {
            this.messageColumns.text = `Faltan por asociar <strong>${faltantes.length}</strong> de <strong>${requeridas}</strong> columnas requeridas`;
            this.messageColumns.type = 'info';
            this.canSave = false;
            return false;
          } else {
            this.messageColumns.text = `¡Ya se han asociado las <strong>${requeridas}</strong> columnas requeridas!`;
            this.messageColumns.type = 'success';
            this.canSave = true;
          }
          return true;
        },
        saveAssociation(e) {
          const boton = e.target;        

          if (!confirm('¿Seguro que desea guardar la asociación establecida?')) {
            return false;
          }

          boton.disabled = true;
          if (this.validateForm()) {
            axios.post('../includes/cargas/guardar_asociacion.php', this.itemAsociation).then(response => {
              const datos = response.data;
              if (datos.success) {
                this.cancelAssociation(e);
                this.message.type = 'success';
                this.message.text = datos.message;
              } else {
                this.message.type = 'warning';
                this.message.text = datos.message;
                boton.disabled = true;
              }   
              this.getCargas();           
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
                boton.disabled = false;
              }, 5000);
            }).catch(error => {
              console.error(error);
              boton.disabled = true;
            });
          } else {
            boton.disabled = false;
          }
        },
        cancelAssociation(e) {
          e.target.disabled = true;
          this.showAsociation = false;
          this.fileColumns = [];
          this.messageColumns.text = '';
          this.itemAsociation = null;
          setTimeout(() => {
            e.target.disabled = false;
          }, 2000)
        },
        goSegmentador(e) {
          e.target.disabled = true;
          window.location.href= '/estrategia/crear';
        }
      }, 
    });
  </script>
</body>
</html>
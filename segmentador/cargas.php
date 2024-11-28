<?PHP
include("../class/db/DB.php");
require_once('../class/session/session.php');
include("../class/global/global.php");
include("../class/estrategia/estrategia.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "est,loadestra"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
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
    <link href="../plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../css/global/global.css" rel="stylesheet">
  </head>
  <body>
    <div id="container" class="effect mainnav-lg">
      <?php include("../layout/header.php");  ?>
      <div class="boxed">
        <div id="content-container">
          <div id="page-title">
            <h1 class="page-header text-overflow">Segmentador</h1>
          </div><!-- page title -->
          <ol class="breadcrumb">
            <li><a href="#">Segmentador</a></li>
            <li class="active">Cargas Castigadas</li>
          </ol><!-- breadcrumb -->
          <div id="page-content">
            <div class="row" style="padding: 12px; margin-bottom: 5px;" v-if="message.text !== ''">
              <div class="col-md-12">
                <div class="alert" :class="'alert-' + message.type" role="alert" style="border-radius: 5px;">
                  {{ message.text }}
                </div>
              </div>
            </div>
            <div class="row" v-if="showNewLoad">
              <div class="eq-height">
                <div class="col-sm-12 eq-box-sm">
                  <div class="panel">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-2">&nbsp;</div>
                        <div class="col-md-6">
                          <h2>Nueva carga</h2>
                          <form class="form-horizontal" onsubmit="return false;">
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">Configuración:</label>
                              <div class="col-sm-10">
                                <select class="form-control" v-model="newConfigId" @change="setConfig($event)" name="configId" id="configId">
                                  <option value="">-- Seleccione --</option>
                                  <option v-for="iConfig in configList" :value="iConfig.id">{{ iConfig.nombre }}</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-2 control-label">Archivo:</label>
                              <div class="col-sm-10">
                                <input class="form-control" type="file" :id="files_config.name" :name="files_config.name" @change="handleFileUpload($event)" :multiple="files_config.multiple" :accept="files_config.accept" />
                                <small>Max. archivos: <strong>{{ files_config.cant }}</strong></small>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-10">
                                <button type="button" class="btn btn-primary" @click.prevent="submitFile" :disabled="!(newConfigId !== '' && file !=='')">Enviar</button>
                              </div>
                            </div>
                          </form>
                          <div class="progress" v-if="uploadPercentage > 0">
                            <div class="progress-bar" role="progressbar" :aria-valuenow="uploadPercentage" aria-valuemin="0" aria-valuemax="100" :style="{'width': uploadPercentage + '%'}">
                            {{ uploadPercentage }} %
                            </div>
                          </div>                        
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" v-if="!showNewLoad">
              <div class="eq-height">
                <div class="col-sm-12 eq-box-sm">
                  <div class="panel">
                    <div class="panel-body">
                      <div class="row" style="padding: 10px 15px; margin-bottom: 25px;">
                        <div class="col-md-12">
                          <button type="button" class="btn btn-success" @click.prevent="newLoad">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva carga 
                          </button>
                        </div>
                      </div>
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th style="text-align: center; width: 10%;">&nbsp;</th>
                            <th style="text-align: left; width: 20%;">Archivo</th>
                            <th style="text-align: left; width: 15%;">Configuración</th>
                            <!-- <th>Relación</th> -->
                            <th style="text-align: center; width: 15%;">Fecha</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(carga, cIndex) in loadList" :key="cIndex">
                            <td style="text-align: center;">
                              <button type="button" class="btn btn-block btn-primary" @click.prevent="setCargar(carga.load_id, carga.archivo, $event)" v-if="carga.procesado === '0'">
                                <i class="fa fa-file-arrow-up"></i>&nbsp;&nbsp;Procesar carga
                              </button>
                              <button type="button" class="btn btn-block btn-danger" @click.prevent="releaseFile(carga, $event)" v-if="carga.procesado === '0'">
                                <i class="fa fa-trash"></i>&nbsp;&nbsp;Eliminar
                              </button>
                              <div v-if="carga.procesado === '1'">
                                <span class="label label-info" style="padding: 5px 10px; margin-top: 10px;" title="Archivo cargado">Cargado</span>
                                <br/><br/>
                                <ul v-if="carga.resultados !== null" style="text-align: left;">
                                  <li v-for="(resultado, rIndex) in carga.resultados" :key="rIndex"><span style="text-transform: capitalize;">{{ rIndex }}</span>:&nbsp;&nbsp;{{ resultado }}</li>
                                </ul>
                              </div>
                            </td>
                            <td>
                              {{ carga.archivo }}
                              <div v-if="carga.procesado === '1' && carga.asignado === '1'">
                                <table class="table table-sm">
                                  <thead>
                                    <tr>
                                      <th colspan="2">Segmentación de cartera</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td style="width: 70%;font-weight: 600;">Cartera</td>
                                      <td style="text-align: right;font-weight: 600;">Registros</td>
                                    </tr>
                                    <tr v-for="(cartera, cIndex) in carga.segmentacion_inicial.segmentacion" :key="cIndex">
                                      <td>{{ cartera.cartera }}</td>
                                      <td style="text-align: right;">{{ cartera.cant }}</td>
                                    </tr>
                                    <tr v-if="carga.segmentacion_inicial.en_baja > 0">
                                      <td style="font-weight: 600;">Retirados o BAJA:</td>
                                      <td style="text-align: right;">{{ carga.segmentacion_inicial.en_baja }}</td>
                                    </tr>
                                    <tr style="font-weight: 600;">
                                      <td>Total:</td>
                                      <td style="text-align: right;font-weight: 600;">{{ carga.segmentacion_inicial.total }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                            <td>{{ carga.configuracion }}</td>
                            <td style="text-align: center;">{{ carga.creado_el }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- page content -->
        </div><!-- content container -->
        <?php include("../layout/main-menu.php"); ?>
      </div>
    </div>
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/funciones.js"></script>
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
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../js/global.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/demo/tables-datatables.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/extra/vue.min.js"></script>
    <script src="../js/extra/axios.min.js"></script>
    <script>
      var app = new Vue({
        el: '#page-content',
        data: {
          showNewLoad: false,
          loadList: [],
          configList: [],
          archivo: null,
          newConfigId: '',
          message: {
            type: 'info',
            text: ''
          },
          uploadPercentage: 0,
          mandante: <?php echo (int) $_SESSION['mandante']; ?>,
          showSegmentButton: false,
          files_config: {
            name: 'file',
            cant: 1,
            type: 'xlsx',
            accept: '.xlsx',
            multiple: false,
            route: '',
          }
        },
        created: function () {
          console.log('¡Creado!')
          this.getConfigs()
          this.getLoads()
          if (this.mandante !== 2) this.showSegmentButton = false
        },
        methods: {
          getConfigs: function() {
            axios.get('/includes/segmentador/loads/configs').then(response => {
              const datos = response.data
              this.loadList = []
              if (datos.success) {
                this.configList = datos.items
              }
            }).catch(error => {
              console.error(error)
            })
          },
          getLoads: function () {
            axios.get('/includes/segmentador/loads/list').then(response => {
              const datos = response.data
              this.loadList = []
              if (datos.success) this.loadList = datos.items
            }).catch(error => {
              console.error(error)
            })
          },
          newLoad: function(e) {
            const boton = e.target;
            boton.disabled = true;
            this.showNewLoad = true;
          },
          handleFileUpload: function(event) {
            if (this.files_config.cant > 1) {
              if (event.target.files.length > this.files_config.cant) {
                alert('Solo se permiten ' + this.files_config.cant + ' archivo(s).');
                return;
              }
              this.archivo = event.target.files;
            } else { 
              this.archivo[0] = event.target.files[0];  
            }
          },
          setConfig: function (e) {
            const valor = parseInt(e.target.value);
            const itemConfig = this.configList.find(c => c.id === valor)?.files_config;
            if (itemConfig !== undefined) {
              console.log('Encontrado: ', itemConfig)
              this.files_config.cant = parseInt(itemConfig.cant_files);
              this.files_config.type = itemConfig.type_files;
              this.files_config.accept = `.${itemConfig.type_files}`;
              this.files_config.route = `${itemConfig.route}`;
              if (this.files_config.cant > 1){
                this.files_config.name = 'files';
                this.files_config.multiple = true;
              }
              console.log(itemConfig, this.files_config);
            }
          },
          submitFile: function (e) {
            const boton = e.target;
            if (!confirm('¿Confirma subir el archivo?')) return;
            boton.disabled = true;

            let formData = new FormData()
            formData.append('configId', this.newConfigId)
            if (this.archivo.length > 1) {
              console.log('Tiene mas de 1');
              for (let i = 0; i < this.archivo.length; i++) {
                console.log(`file[${i}]`, this.archivo[i]);
                formData.append(`files[${i}]`, this.archivo[i]);
              }
            } else {
              formData.append('file', this.archivo[0])
            }

            formData.append('route', this.files_config.route);

            const axiosOptions = {
                headers: {
                  'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: ( progressEvent ) => {
                  progress = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));
                  this.uploadPercentage = progress;
                }
            };

            this.message.text = 'Procesando...';
            this.message.type = 'info';

            axios.post('/includes/segmentador/loads/upload', formData, axiosOptions)
            .then(response => {
              const datos = response.data;
              this.message.type = (datos.success)? 'success':'danger';
              this.message.text = datos.message;
              if (datos.success) this.showNewLoad = false;
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              this.uploadPercentage = 0;
              this.getLoads();
              boton.disabled = false;
              console.log('SUCCESS!!');
            }).catch(error => {
              console.log('FAILURE!!');
              this.message.type = 'danger';
              this.message.text = 'Se ha presentado un error, no se pudo completar la operación.';
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              boton.disabled = false;
            });
          },
          setCargar(id, archivo, e) {
            const boton = e.target;
            if (!confirm('¿Confirma procesar el archivo *'+ archivo +'*?')) return;
            this.showSegmentButton = false
            boton.disabled = true;
            this.message.text = 'Realizando operación, por favor espere...';
            axios.post('/includes/segmentador/loads/proccess', { id }).then(response => {
              const datos = response.data;
              boton.disabled = false;
              this.message.type = (response.data.success)? 'success':'danger';
              this.message.text = response.data.message;
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              this.getLoads();
              if (this.mandante !== 3) this.showSegmentButton = true
            }).catch(error => {
              console.error(error);
              boton.disabled = false;
              this.message.type = 'danger';
              this.message.text = 'Se ha presentado un error, no se pudo completar la operación.';
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
            })
          },
          releaseFile(item, e) {
            const id = item.load_id;
            const boton = e.target;
            if (!confirm('¿Confirma descartar el archivo *'+ item.archivo +'*?')) return;
            boton.disabled = true;
            this.message.text = 'Realizando operación, por favor espere...';
            axios.post('/includes/segmentador/loads/release', { id }).then(response => {
              const datos = response.data;
              boton.disabled = false;
              this.message.type = (response.data.success)? 'success':'danger';
              this.message.text = response.data.message;
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              this.getLoads();           
            }).catch(error => {
              console.error(error);
              boton.disabled = false;
              this.message.type = 'danger';
              this.message.text = 'Se ha presentado un error, no se pudo completar la operación.';
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              this.getLoads();
            });          
          },
        },
      });
    </script>
  </body>
</html>
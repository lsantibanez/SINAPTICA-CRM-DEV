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
            <li class="active">Cargas Vigentes</li>
          </ol><!-- breadcrumb -->
          <div id="page-content">
            <div class="row" style="padding: 12px; margin-bottom: 5px;" v-if="message.text !== ''">
              <div class="col-md-12">
                <div class="alert" :class="'alert-' + message.type" role="alert" style="border-radius: 5px;">
                  {{ message.text }}
                </div>
              </div>
            </div>
            <div class="row" style="padding: 20px;">
              <div class="panel">
                <div class="panel-body">
                  <div class="row" style="padding: 20px;">
                    <div class="col-lg-12">
                      <h4 style="margin-bottom: 25px;">Nueva carga de archivos de deudas vigentes</h4>
                      <form class="form-horizontal" onsubmit="return false;">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Archivos (max 7):</label>
                          <div class="col-sm-8">
                            <input class="form-control" type="file" multiple id="archivos" name="archivos" @change="handleFileUpload($event)" />
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-primary" @click.prevent="submitFiles" :disabled="!files.length">Enviar</button>
                          </div>
                        </div>
                      </form>
                      <div class="progress" v-if="uploadPercentage > 0">
                        <div class="progress-bar" role="progressbar" :aria-valuenow="uploadPercentage" aria-valuemin="0" aria-valuemax="100" :style="{'width': uploadPercentage + '%'}">
                          {{ uploadPercentage }} %
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="padding: 15px;" v-if="procesados.length">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                      <h3 style="margin: 0;">Resultados</h3>
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>Archivo</th>
                            <th style="width: 15%; text-align: center;">Filas</th>
                            <th style="width: 15%; text-align: center;">Procesadas</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(archivo,aIndex) in procesados" :key="aIndex">
                            <td>{{ archivo.name }}</td>
                            <td style="text-align: center;">{{ archivo.rows }}</td>
                            <td style="text-align: center;">{{ archivo.processed }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-lg-3"></div>
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
          archivo: null,
          files: [],
          uploadPercentage: 0,
          message: {
            type: 'info',
            text: ''
          },
          procesados: [],
        },
        created: function () {
          console.log('¡Creado!')
        },
        methods: {
          handleFileUpload: function(event) {
            const archivos = event.target.files;
            for( var i = 0; i < archivos.length; i++ ){
              this.files.push(archivos[i]);
            }
            this.procesados = []
          },
          submitFiles: function (e) {
            const boton = e.target;
            if (!confirm(`¿Confirma subir *${this.files.length}* archivos seleccionados?`)) return;
            boton.disabled = true;

            let formData = new FormData();
            formData.append('configId', 11);
            //formData.append('file', this.archivo);

            for(i = 0; i < this.files.length; i++) {
              let file = this.files[i];
              formData.append(`archivos[${i}]`, file);
            }

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

            axios.post('/includes/segmentador/loads/upload_vigente', formData, axiosOptions)
            .then(response => {
              const datos = response.data;
              console.log(datos)
              this.message.type = (datos.success)? 'success':'danger';
              this.message.text = datos.message;
              if (datos.success) {
                if (datos.items.length) this.procesados = datos.items
                this.files = [];
              }
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              this.uploadPercentage = 0;
              boton.disabled = false;
              console.log('SUCCESS!!');
            }).catch(error => {
              console.log('FAILURE!!', error);
              this.message.type = 'danger';
              this.message.text = 'Se ha presentado un error, no se pudo completar la operación.';
              setTimeout(() => {
                this.message.text = '';
                this.message.type = 'info';
              }, 5000);
              boton.disabled = false;
            });
          }
        },
      });
    </script>
  </body>
</html>
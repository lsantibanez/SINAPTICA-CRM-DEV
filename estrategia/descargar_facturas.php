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
          <li class="active">Descargo facturas</li>
        </ol><!--Breadcrumb-->
        <div id="page-content">
          <div id="appFacturas">
            <div class="row" id="cuerpo" style="display: none">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <div class="row" style="padding: 0 12px;">
                      <div class="col-lg-6" v-if="archivo.nombre === ''">
                        <h1>UNO</h1>
                        {{ message }}<br/>{{ file }}<br/>                
                        <br/>
                        <input class="input-control" type="file" id="file" ref="file" />
                        <br/>
                        <button type="button" class="btn btn-success" @click="subirArchivo($event)" :enabled="file !== ''">Upload file</button>
                      </div>
                      <div class="col-lg-6" v-if="archivo.nombre !== ''">
                        Archivo: {{ archivo.nombre }} <br/>
                        <button type="button" class="btn btn-success" @click="procesarArchivo($event)" >Procesar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="cargando">
              <div class="col-lg-12">
                Cargando...
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
    var app2 = new Vue({
      el: '#appFacturas',
      data: () => {
        return {
          file: '',
          message: 'Usted cargó esta página el ' + new Date().toLocaleString(),
          archivo: {
            nombre: '',
            ruta: ''
          }
        }
      },
      mounted: function() {
        document.getElementById('cuerpo').style.display = 'block';
        document.getElementById('cargando').style.display = 'none';
        console.info('Módulo cargado');
      },
      methods: {
        subirArchivo() {
          this.file = this.$refs.file.files[0];
          let formData = new FormData();
          formData.append("file", this.file);

          const onUploadProgress = (event) => {
            const percentage = Math.round((100 * event.loaded) / event.total);
            console.log(percentage);
          };
          
          axios.post('/includes/estrategia/facturas/upload_file.php', formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress,
          }).then(response => {
            const datos = response.data;
            console.log(datos);
            if (datos.success) {
              this.message = 'Archivo cargado'
              this.archivo.nombre = datos.info.archivo
              this.archivo.ruta = datos.info.cargado.split('/').pop()
            }
          });
        },
        procesarArchivo(e) {
          const boton = e.target
          boton.disabled = true
          let formData = new FormData();
          formData.append('archivo', this.archivo.ruta);

          axios.post('/includes/estrategia/facturas/proccess_file.php', formData)
          .then(response => {
            console.log(response)
          })
          .catch(error => console.error(error))
        }
      },
    })
  </script>
</body>
</html>
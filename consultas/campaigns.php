<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false);
$objetoSession->crearVariableSession($array = array("idMenu" => "constas,constasrut"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = (int) $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$idUsuarioLogin = $_SESSION['id_usuario'];
$nombreProyecto = $_SESSION['nombreCedente'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM Sinaptica | Software de Estrategia | Consultas</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/nifty.min.css" rel="stylesheet">
  <link href="/premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
  <link href="/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
  <link href="/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
  <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="/plugins/animate-css/animate.min.css" rel="stylesheet">
  <link href="/plugins/switchery/switchery.min.css" rel="stylesheet">
  <link href="/plugins/morris-js/morris.min.css" rel="stylesheet">
  <link href="/css/demo/nifty-demo.min.css" rel="stylesheet">
  <link href="/plugins/pace/pace.min.css" rel="stylesheet">
  <link href="/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
  <link href="/plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
  <link href="/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
  <link href="/plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
  <link href="/css/global/global.css" rel="stylesheet">
</head>
<body>
  <div id="container" class="effect mainnav-lg">
    <?php include '../layout/header.php';  ?>
    <div class="boxed">
      <div id="content-container">
        <div id="page-title">
          <h1 class="page-header text-overflow">Consultas</h1>
        </div><!-- page title -->
        <ol class="breadcrumb">
          <li><a href="#">Consultas</a></li>
          <li class="active">Campañas</li>
        </ol><!-- breadcrumb -->
        <div id="page-content">
          <div id="appConsultaCampaigns">
            <div class="row mb-4">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <h4>Campañas</h4>
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th style="width: 15%; text-align: center;">Servicio</th>
                          <th style="width: 10%; text-align: center;">Registros</th>
                          <th style="width: 25%; text-align: center;">Información</th>
                          <th style="width: 15%; text-align: center;">Creada</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="!campaigns.length">
                          <td colspan="5">No hay datos.</td>
                        </tr>
                        <tr v-for="campaign in campaigns" :key="campaign.id">
                          <td>
                            {{ campaign.nombre }}<br/><br/>
                            <span class="label label-success p-2" v-if="campaign.activa === '1'">Activa</span>
                            <span class="label label-danger p-2" v-else>Inactiva</span>
                          </td>
                          <td style="text-align: center;">{{ campaign.tipo.toUpperCase() }}</td>
                          <td style="text-align: center;">{{ campaign.registros }}</td>
                          <td>
                            <ul>
                              <li><strong>Tipo discado:</strong>&nbsp;&nbsp;{{ campaign.parametros.dialer_type }}</li>
                              <li v-if="campaign.parametros.dialer_type !== 'Asistido'"><strong>Intensidad:</strong>&nbsp;&nbsp;{{ campaign.parametros.intensity }}</li>
                              <li><strong>Asignación:</strong>&nbsp;&nbsp;{{ campaign.parametros.assing_to }}</li>
                              <li v-if="campaign.parametros.agents.length">
                                <strong>Agentes:</strong>
                                <ul>
                                  <li v-for="(agent, aIndex) in campaign.parametros.agents" :key="aIndex">{{  agent }}</li>
                                </ul>
                              </li>
                            </ul>
                          </td>
                          <td style="text-align: center;">{{ campaign.creada_el }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- page content --->
      </div><!-- page container -->
      <?php include '../layout/main-menu.php'; ?>
    </div><!-- boxed -->
    <footer id="footer">
      <div class="show-fixed pull-right">
        <ul class="footer-list list-inline">
          </li>
        </ul>
      </div>
    </footer>
    <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
  </div>
  <script src="/js/jquery-2.2.1.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
  <script src="/js/nifty.min.js"></script>
  <script src="/js/demo/nifty-demo.min.js"></script>
  <script src="/plugins/pace/pace.min.js"></script>
  <script src="/plugins/bootbox/bootbox.min.js"></script>
  <script src="/js/global/funciones-global.js"></script>
  <script src="/js/extra/vue.min.js"></script>
  <script src="/js/extra/axios.min.js"></script>
  <script>
    var app = new Vue({
      el: '#appConsultaCampaigns',
      data: {
        campaigns: [],
      },
      created: function () {
        console.log('¡¡Cargado!!')
        this.getCampaigns()
      },
      methods: {
        getCampaigns: function() {
          this.portfolios = []
          axios.get('/includes/consultas/campaigns').then(response => {
            const datos = response.data
            if (datos.success) this.campaigns = datos.items
          }).catch(error => {
            console.error(error)
          })
        },
      },
    });
  </script>
</body>
</html>
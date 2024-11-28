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
  <style>
    .tbodyDiv {
      max-height: 320px;
      overflow-y: auto;
      min-height: 300px;
    }
    .tbodyDiv > .table {
      text-align: left;
      position: relative;
      border-collapse: collapse; 
    }
    .tbodyDiv > .table th, .tablePhones td {
      padding: 0.25rem;
    }
    .tbodyDiv > .table th {
      background: white;
      position: sticky;
      top: 0; /* Don't forget this, required for the stickiness */
      box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }
  </style>
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
          <li class="active">RUT</li>
        </ol><!-- breadcrumb -->
        <div id="page-content">
          <div id="appConsultaRut">
            <div class="row mb-4">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <div class="form-inline">
                      <div class="form-group">
                        <label for="findRut">Rut:&nbsp;&nbsp;</label>
                        <input type="text" id="findRut" class="form-control" v-model="finderData.rut">
                      </div>
                      <div class="form-group" style="margin-left: 10px; margin-right: 20px">
                        <label for="portFolio">Cartera:&nbsp;&nbsp;</label>
                        <select id="portFolio" class="form-control" size="1" style="min-width: 200px;" v-model="finderData.portfolio">
                          <option value="">-- Todas --</option>
                          <option v-for="portfolio in portfolios" :key="portfolio.id" :value="portfolio.id">
                            {{ portfolio.nombre }}
                          </option>
                        </select>
                      </div>
                      <button type="button" class="btn btn-primary" @click.prevent="findRutAction($event)">Buscar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- row 0 -->
            <div class="row" v-if="showBuscando">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <h2 style="margin: 0;">Buscando...</h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" v-if="showResult">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <span v-if="!customersData.length" style="margin-bottom: 25px; display: block; width: 100%;">
                      <i class="fa-solid fa-circle-exclamation"></i>&nbsp;&nbsp;No se encontró ningún registro con la información suministrada.
                    </span>
                    <template v-for="(customer, cuIndex) in customersData" :key="cuIndex">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th style="width: 20%; text-align:center;">Cartera</th>
                            <th style="width: 15%; text-align:center;">Rut</th>
                            <th>Nombre</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="text-align: center;">{{ customer.proyecto }}</td>
                            <td style="text-align: center;">{{ customer.rut }}-{{ customer.digito }}</td>
                            <td>{{ customer.nombre }}</td>
                          </tr>
                        </tbody>
                      </table>
                      <table class="table table-sm">
                        <tbody>
                          <tr>
                            <td style="width: 50%;">
                              <div class="tbodyDiv">
                                <table class="table table-sm">
                                  <thead class="sticky-top bg-white">
                                    <tr>
                                      <th>Número teléfono</th>
                                      <th style="width: 25%; text-align: center;">Tipo</th>
                                      <th style="width: 25%;">Marca</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(telefono, tIndex) in customer.telefonos" :key="tIndex">
                                      <td>{{ telefono.phone }}</td>
                                      <td style="text-align: center;">{{ telefono.type }}</td>
                                      <td>
                                        <span v-if="telefono.marca !== null">{{ telefono.mark }}</span>
                                        <span v-else>...</span>
                                      </td>
                                    </tr>
                                    <tr v-if="!customer.telefonos.length">
                                      <td>No hay datos</td>
                                    </tr>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td colspan="3">
                                       
                                      </td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div><!-- div -->
                              <div class="row">
                                <div class="col-md-4">
                                  <h5>Nuevo teléfono</h5>
                                  <input type="text" class="form-control" v-model="newPhone.phone" maxlength="9">
                                </div>
                                <div class="col-md-4">
                                  <h5>Marca</h5>
                                  <select class="form-control col-3" v-model="newPhone.mark" size="1">
                                    <option value="">-- Marca --</option>
                                    <option value="Particular">Particular</option>
                                    <option value="Otro">Otro</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <h5>&nbsp;</h5>
                                  <button class="btn  btn-block btn-success" type="button" :disabled="newPhone.phone.length < 9" @click="guardaTelefono($event)">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar
                                  </button>
                                </div>
                              </div><!-- row -->
                            </td>
                            <td>
                              <div class="tbodyDiv">
                                <table class="table table-sm">
                                  <thead class="sticky-top bg-white">
                                    <tr>
                                      <th>Correo</th>
                                      <th style="width: 30%; text-align: center;">Marca</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(email, mIndex) in customer.emails" :key="mIndex">
                                      <td>{{ email.email }}</td>
                                      <td style="text-align: center;">{{ email.mark }}</td>
                                    </tr>
                                    <tr v-if="!customer.emails.length">
                                      <td colspan="2">No hay datos.</td>
                                    </tr>
                                  </tbody>
                                  <tfoot>
                                  <tr>
                                    <td colspan="3">
                                      <h4>Nuevo email</h4>
                                    </td>
                                  </tr>
                                </tfoot>
                                </table>
                              </div><!-- div -->
                              <div class="row">
                                <div class="col-md-5">
                                  <h5>Nuevo correo electrónico</h5>
                                  <input type="email" class="form-control" v-model="newEmail.email">
                                </div>
                                <div class="col-md-3">
                                  <h5>Marca</h5>
                                  <select class="form-control col-3" v-model="newEmail.mark" size="1">
                                    <option value="">-- Marca --</option>
                                    <option value="Particular">Particular</option>
                                    <option value="Otro">Otro</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <h5>&nbsp;</h5>
                                  <button class="btn btn-block btn-success" type="button" :disabled="newEmail.email.length < 5" @click="guardaEmail($event)">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar
                                  </button>
                                </div>
                              </div><!-- row -->
                            </td>
                            <tr v-if="customer.deudas.rows.length">
                              <td colspan="2" style="padding-bottom: 0;">
                                <h5><i class="fa fa-bills"></i>&nbsp;&nbsp;Deudas Castigadas</h5>
                                <table class="table table-sm" style="margin-bottom: 0;">
                                  <thead>
                                    <tr>
                                      <th v-for="(cabecera, caIndex) in customer.deudas.headers" :key="caIndex" :style="'text-align: '+cabecera.tAling+';'">
                                        {{ cabecera.title }}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(fila, roIndex) in customer.deudas.rows" :key="roIndex">
                                      <td v-for="(campo, caIndex) in fila" :key="caIndex" :style="'text-align: '+campo.tAling+';'">
                                        {{  campo.value }}
                                      </td>
                                    </tr>
                                    <tr v-if="!customer.deudas.rows.length">
                                      <td :colspan="customer.deudas.headers.length">No hay datos</td>
                                    </tr>
                                  </tbody>
                                  <tfoot v-if="customer.deudas.footer !== undefined && customer.deudas.footer.length">
                                    <tr>
                                      <td v-for="(footer, foIndex) in customer.deudas.footer" :key="foIndex" :style="'text-align: '+footer.tAling+';font-weight: 600;'">
                                        {{ footer.value }}
                                      </td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </td>
                            </tr>
                            <tr v-if="customer.deudas_vigentes.rows.length">
                              <td colspan="2" style="padding-bottom: 0;">
                                <h5><i class="fa fa-bills"></i>&nbsp;&nbsp;Deudas Vigentes</h5>
                                <table class="table table-sm" style="margin-bottom: 0;">
                                  <thead>
                                    <tr>
                                      <th v-for="(cabecera, caIndex) in customer.deudas_vigentes.headers" :key="caIndex" :style="'text-align: '+cabecera.tAling+';'">
                                        {{ cabecera.title }}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(fila, roIndex) in customer.deudas_vigentes.rows" :key="roIndex">
                                      <td v-for="(campo, caIndex) in fila" :key="caIndex" :style="'text-align: '+campo.tAling+';'">
                                        {{  campo.value }}
                                      </td>
                                    </tr>
                                    <tr v-if="!customer.deudas_vigentes.rows.length">
                                      <td :colspan="customer.deudas_vigentes.headers.length">No hay datos</td>
                                    </tr>
                                  </tbody>
                                  <tfoot v-if="customer.deudas_vigentes.footer !== undefined && customer.deudas_vigentes.footer.length">
                                    <tr>
                                      <td v-for="(footer, foIndex) in customer.deudas_vigentes.footer" :key="foIndex" :style="'text-align: '+footer.tAling+';font-weight: 600;'">
                                        {{ footer.value }}
                                      </td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                <h5><i class="fa fa-list"></i>&nbsp;&nbsp;Gestiones</h5>
                                <div class="tbodyDiv">
                                  <table class="table table-sm">
                                    <thead class="sticky-top bg-white">
                                      <tr>
                                        <th style="width: 15%; text-align: center;">Fecha/Hora</th>
                                        <th style="width: 15%; text-align: center;">Contactado por</th>
                                        <th style="width: 20%; text-align: center;">Agente</th>
                                        <th>Tipificación</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <template v-if="customer.gestiones.length">
                                        <template v-for="(gestion, gIndex) in customer.gestiones" :key="gIndex">
                                          <tr>
                                            <td style="text-align: center; background-color: #eeeeee;">{{ gestion.fecha }}</td>
                                            <td style="text-align: center; background-color: #eeeeee;">
                                              {{ gestion.telefono }}
                                            </td>
                                            <td style="text-align: center; background-color: #eeeeee;">
                                              {{ gestion.agente }}
                                            </td>
                                            <td style="background-color: #eeeeee;">
                                              {{ gestion.n1 }}
                                              <span v-if="gestion.n2 !== null && gestion.n2 !== ''">&nbsp;/&nbsp;{{ gestion.n2 }}</span>
                                              <span v-if="gestion.n3 !== null && gestion.n3 !== ''">&nbsp;/&nbsp;{{ gestion.n3 }}</span>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="text-align: right; font-weight: 600;">Observaciones:</td>
                                            <td colspan="4">{{ gestion.observacion }}</td>
                                          </tr>
                                        </template>
                                      </template>
                                      <tr v-else>
                                        <td colspan="4">
                                          No hay gestiones.
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tr>
                        </tbody>
                      </table>
                    </template>
                    <br />
                    <button type="button" class="btn btn-sm btn-danger" @click="clearData"><i class="fa fa-trash"></i>&nbsp;&nbsp;Limpiar</button>
                  </div>
                </div>
              </div>
            </div><!-- row 1 -->
          </div><!-- appconsultaRut -->
        </div><!-- page-content -->
      </div><!-- content-container -->
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
  </div><!-- container -->
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
  <script src="/js/jquery.mask.min.js"></script>
  <script>
    $(document).ready(function(){
      console.log('Jquery Ready')
      $('#findRut').mask('00000000-Z', {
        reverse: true,
        translation: {
          'Z': { pattern: /[0-9kK]/ }
        },
      });
    });
  </script>
  <script>
    var app = new Vue({
      el: '#appConsultaRut',
      data: {
        customersData: [],
        portfolios: [],
        finderData: {
          rut: '',
          portfolio: '',
        },
        showBuscando: false,
        showResult: false,
        newPhone: {
          phone: '',
          mark: ''
        },
        newEmail: {
          email: '',
          mark: ''
        },
      },
      created: function () {
        console.log('¡¡Cargado!!')
        this.getPortfolios()
      },
      methods: {
        getPortfolios: function() {
          this.portfolios = []
          axios.get('/includes/consultas/portfolios').then(response => {
            const datos = response.data
            if (datos.success) this.portfolios = datos.items
          }).catch(error => {
            console.error(error)
          })
        },
        findRutAction: function(e) {
          const boton = e.target
          boton.disabled = true
          this.showResult = false
          this.customersData = []
          this.showBuscando = true
          axios.post('/includes/consultas/find_rut', this.finderData).then(response => {
            const datos = response.data
            console.log(datos)
            if (datos.success) this.customersData = datos.customers
            boton.disabled = false
            this.showBuscando = false
            this.showResult = true
          }).catch(error => {
            boton.disabled = false
            this.showBuscando = false
            this.showResult = false
            console.error(error)
          })
        },
        guardaTelefono: function(e) {
          const boton = e.target
          if (!confirm('¿Confirma agregar el télefono ingresaso?')) return
          const rutPhone = this.finderData.rut.split('-')[0]
          Object.assign(this.newPhone, { rut: rutPhone })
          axios.post('/includes/consultas/add_phone', this.newPhone).then(response => {
            const datos = response.data
            //console.log(datos)
            if (datos.success) {
              const customerItem = this.customersData.find(c => c.rut === rutPhone)
              if (customerItem !== undefined)  customerItem.telefonos.push(datos.item)
              this.newPhone = {
                phone: '',
                mark: ''
              }
              alert('¡Teléfono registrado con éxito!')
            } else {
              alert('No se pudo procesar la solicitud')
              boton.disabled = false
            }
          }).catch(error => {
            boton.disabled = false
            console.error(error)
          })
        },
        guardaEmail: function(e) {
          const boton = e.target
          if (!confirm('¿Confirma agregar el correo electrónico ingresaso?')) return
          const rutEmail = this.finderData.rut.split('-')[0]
          Object.assign(this.newEmail, { rut: rutEmail })
          axios.post('/includes/consultas/add_email', this.newEmail).then(response => {
            const datos = response.data
            //console.log(datos)
            if (datos.success) {
              const customerItem = this.customersData.find(c => c.rut === rutEmail)
              if (customerItem !== undefined)  customerItem.emails.push(datos.item)
              this.rutEmail = {
                email: '',
                mark: ''
              }
              alert('¡Teléfono registrado con éxito!')
            } else {
              alert(datos.message)
              boton.disabled = false
            }
          }).catch(error => {
            boton.disabled = false
            console.error(error)
          })
        },
        clearData: function() {
          this.customersData = []
          this.showResult = false
        }
      },
    });
  </script>
</body>
</html>
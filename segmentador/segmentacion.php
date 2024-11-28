<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = (int) $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = $_SESSION['IdEstrategia'];
$idUsuarioLogin = $_SESSION['id_usuario'];
$nombreProyecto = $_SESSION['nombreCedente'];
$idUsuarioEstrategia = 0;
$sql = $db->select("SELECT nombre, id_usuario FROM SIS_Estrategias WHERE id = '".$id_estrategia."' AND Id_Cedente = '".$cedente."'");
foreach((array) $sql as $row){
    $nombre_estrategia = $row["nombre"];
    $idUsuarioEstrategia = $row["id_usuario"];
}
if(empty($id_estrategia) || $idUsuarioEstrategia == 0) {
  header('Location: segmentaciones.php'); 
  exit;
} else{
  $id_estrategia = $id_estrategia;
}

/**
  * Verifico si el usuario conectado es el mismo que creo la estrategia
  * para asi dejarlo crear y deshacer, de lo contrario deshabilitar los botones
*/
if ($idUsuarioEstrategia == $idUsuarioLogin) {
  $habilitado = "";
} else {
  $habilitado = "disabled='disabled'";
}

$NombreEstrategia = '';
$QueryNombreEstrategia = $db->select("SELECT Nombre FROM SIS_Estrategias WHERE id = $id_estrategia LIMIT 1");
if ($QueryNombreEstrategia) {
  $NombreEstrategia = $QueryNombreEstrategia[0]["Nombre"];
}
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
    <style>
      input[type="date"].form-control {
        line-height: 16px !important;
      }
    </style>
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
            <li class="active">Segmentación</li>
          </ol><!-- breadcrumb -->
          <div id="page-content">
            <div id="appSegmentador">
              <div class="row mb-4">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                      <h2 style="margin: 0;"><?php echo $NombreEstrategia ?></h2>
                      <h4><?php echo $nombreProyecto; ?></h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3" v-if="showTool">
                <div class="eq-height">
                  <div class="col-sm-12 eq-box-sm">
                    <div class="panel">
                      <div class="panel-body">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th style="width: 5%;">&nbsp;</th>
                              <th style="width: 10%;">Acción</th>
                              <th style="width: 20%;">Origen</th>
                              <th style="width: 20%;">Columna</th>
                              <th style="width: 15%;">Lógica</th>
                              <th style="width: 15%;">Valor</th>
                              <th style="width: 15%;">&nbsp;</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(campo, index) in subQueries.rows" :key="index">
                              <td style="text-align: center;">                  
                                <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeRow(index)" v-if="index > 0">
                                  <i class="fa fa-trash"></i>
                                </button>
                              </td>
                              <td style="text-align: center;">
                                <select class="form-control" name="action[]" :id="'action_'+index" v-model="campo.action" v-if="index > 0">
                                  <option v-for="(actionItem, aIndex) in campo.actionList" :key="aIndex" :value="actionItem.value">{{ actionItem.label }}</option>
                                </select>
                                <span v-else>&nbsp;</span>
                              </td>
                              <td style="text-align: left;">
                                <select class="form-control" name="table[]" :id="'table_'+index" v-model="campo.table" @change="filterDataTo(index)">
                                  <option value=""> -- Seleccione -- </option>
                                  <option v-for="table in tables" :value="table.name">{{ table.title }}</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-control" name="colum[]" :id="'column_'+index" v-model="campo.column" :disabled="columns[index] === undefined || !columns[index].length" @change="filterDataTo(index, 'logics')">
                                  <option value=""> -- Seleccione -- </option>
                                  <option v-for="column in columns[index]" :value="column.name" v-if="columns[index] !== undefined">{{ column.title }}</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-control" name="logic[]" @click="verDatos" :id="'logic_'+index" v-model="campo.logic" :disabled="campo.logicList === undefined || !campo.logicList.length">
                                  <option value=""> -- Seleccione -- </option>
                                  <option v-for="logica in campo.logicList" :value="logica.value">{{ logica.title }}</option>
                                </select>
                              </td>
                              <td>
                                <input type="date" name="value" id="value" class="form-control form-control-sm col-6" v-model="campo.value" :disabled="campo.logic === ''" v-if="campo.type === 'date'">
                                <select name="value" id="value" class="form-control" v-model="campo.value" :disabled="campo.logic === ''" v-else-if="campo.type === 'list'" :multiple="campo.multi">
                                  <option value="" v-if="!campo.multi"> -- Seleccione -- </option>
                                  <option  v-for="(item,index) in campo.itemsList" :key="index" :value="item.id" v-if="campo.itemsList.length">{{ item.name }}</option>
                                </select>
                                <input type="text" name="value" id="value" class="form-control form-control-sm col-6" v-model="campo.value" :disabled="campo.logic === ''" v-else>
                              </td>
                              <td>
                                <div v-if="['btw','nbtw'].includes(campo.logic.split('$')[0])">
                                  <input type="date" name="value2" id="value2" class="form-control form-control-sm col-6" v-model="campo.value2" :disabled="campo.logic === ''" v-if="campo.type === 'date'">
                                  <select name="value2" id="value2" class="form-control" v-model="campo.value2" :disabled="campo.logic === ''" v-else-if="campo.type === 'list'" :multiple="campo.multi">
                                    <option value="" v-if="!campo.multi"> -- Seleccione -- </option>
                                    <option  v-for="(item,index) in campo.itemsList" :key="index" :value="item.id" v-if="campo.itemsList.length">{{ item.name }}</option>
                                  </select>
                                  <input type="text" name="value2" id="value2" class="form-control form-control-sm col-6" v-model="campo.value2" :disabled="campo.logic === ''" v-else>
                                </div>                  
                              </td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-success" title="Agregar condición" @click.prevent="addRow" v-if="subQueries.rows.length <= 3">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </td>               
                              <td colspan="2" style="text-align: right; vertical-align: middle;">Nombre de segmento:</td>
                              <td colspan="2">
                                <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" :length="37 - nombreProyecto.length" :maxlength="37 - nombreProyecto.length" v-model="subQueries.name">
                              </td>
                              <td>
                                <button type="button" class="btn btn-sm btn-primary" @click.prevent="creaResultado" :disabled="subQueries.name === ''">Crear segmento</button>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                          </tfoot>
                        </table>
  
                        <div v-if="querySql !== ''">
                          <h3>Información preliminar</h3>
                          <table class="table table-sm">
                            <thead>
                              <tr>
                                <th>Segmento</th>
                                <th style="width: 10%; text-align:center;">RUTS</th>
                                <th style="width: 10%; text-align:center;">Doc./Oper.</th>
                                <th style="width: 15%; text-align:right">Saldos ($)</th>
                                <th style="width: 10%;">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(segmento,sIndex) in resultados" :key="sIndex">
                                <td>{{ segmento.segment }}</td>
                                <td style="text-align:center;">{{ segmento.bills.ruts }}</td>
                                <td style="text-align:center;">{{ segmento.bills.documentos }}</td>
                                <td style="text-align: right;">{{ segmento.bills.saldos }}</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr v-if="!resultados.length">
                                <td colspan="4">No hay datos</td>
                              </tr>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="5">
                                  <button type="button" class="btn btn-success" @click.prevent="saveResultados">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar segmento
                                  </button>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3" v-if="showAsignacion">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel">
                        <div class="panel-body">
                          <h3 style="margin: 0;">Acción</h3>
                          <div class="form-horizontal" v-if="showFormAssignation">
                            <div class="form-group">
                              <label for="inputPassword3" class="col-sm-2 control-label">Segmento:</label>
                              <div class="col-sm-10">
                                <p class="form-control-static">{{ actionData.segment }}</p>
                              </div>
                            </div><!-- item -->                            
                            <div class="form-group">
                              <label for="inputPassword3" class="col-sm-2 control-label">Servicio:</label>
                              <div class="col-sm-5">
                                <select v-model="actionData.service" class="form-control" @change="setSeviceActive($event)">
                                  <option value="">-- Seleccione --</option>
                                  <option v-for="servicio in serviceList" :value="servicio.id" :key="servicio.id">{{ servicio.name }}</option>
                                </select>
                              </div>
                            </div><!-- item -->
                            <div id="dialerService" v-if="actionData.service === 'discador'">
                              <h4>Asignar al servicio discador</h4>
                              <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Nombre de carga/campaña:</label>
                                <div class="col-sm-5">
                                  <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><?php echo $nombreProyecto ?> - </span>
                                    <input type="text" class="form-control" v-model="actionData.params.name" :length="37 - nombreProyecto.length" :maxlength="37 - nombreProyecto.length">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Tipo de discado:</label>
                                <div class="col-sm-4">
                                  <select name="" id="" v-model="actionData.params.dealer_type" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    <option v-for="dialerType in serviceActive.params.dealer_type" :value="dialerType.id" :key="dialerType.id">{{ dialerType.name }}</option>
                                  </select>
                                </div>
                              </div>                              
                              <template v-if="hasTypeDealerParams().length">
                                <div class="form-group" v-for="typeDealer in hasTypeDealerParams()" :key="typeDealer.id">
                                  <label for="inputPassword3" class="col-sm-2 control-label">{{ typeDealer.label }}:</label>
                                  <div class="col-sm-4">
                                    <select v-model="actionData.params.intensity" class="form-control">
                                      <option v-for="dialerTypeParam in typeDealer.items" :value="dialerTypeParam.id" :key="dialerTypeParam.id">{{ dialerTypeParam.name }}</option>
                                    </select>
                                  </div>
                                </div>
                              </template>
                              <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Asignar a:</label>
                                <div class="col-sm-4">
                                  <select v-model="actionData.params.assign_to" class="form-control">
                                    <option value="ALL">Todos los agentes</option>
                                    <option value="AGENTS">El/los agente(s)</option>                                   
                                  </select>
                                </div>
                              </div>
                              <div class="form-group" v-if="actionData.params.assign_to === 'AGENTS'">
                                <label for="inputPassword3" class="col-sm-2 control-label">Agente(s):</label>
                                <div class="col-sm-4">
                                  <div class="checkbox" v-for="(agent,aIndex) in serviceActive.params.agents" :key="agent.id">
                                    <label>
                                      <input type="checkbox" :value="agent.usuario" v-model="actionData.params.agents">
                                      {{ agent.nombre }}
                                    </label>
                                  </div>
                                </div>
                              </div>
                               
                            </div>
                            <template v-if="assosiationData.service !== null && assosiationData.service.params.length">
                            <div class="form-group" v-for="(parametro, pIndex) in assosiationData.service.params" :key="pIndex">
                              <label for="inputPassword3" class="col-sm-2 control-label">{{ parametro.label }}:</label>
                              <div class="col-sm-4">                                
                                <select name="" id="" v-model="parametro.value" class="form-control">
                                  <option value="">-- Seleccione --</option>
                                  <option v-for="itemParametro in parametro.items" :value="itemParametro.id" :key="itemParametro.id">{{ itemParametro.name }}</option>
                                </select>
                              </div>
                            </div>
                            </template>
                            <div class="form-group" v-if="actionData.service !== ''">
                              <label for="inputPassword3" class="col-sm-2 control-label">&nbsp;</label>
                              <div class="col-sm-4">
                                <button type="button" class="btn btn-success" @click.prevent="sendAssignation($event)">Enviar</button>
                              </div>
                            </div>
                          </div><!-- form -->
                          <div class="col-md-12" v-if="!showFormAssignation">
                            <h4>Resultados</h4>
                            <p v-html="resultAssign"></p>
                          </div>

                          <div class="col-md-12" v-if="messageAssign.text !==''">
                            <div class="alert" :class="'alert-' + messageAssign.type" role="alert" style="border-radius: 5px;">
                              {{ messageAssign.text }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3" v-if="segmentList.length && !showAsignacion">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>Segmento</th>
                            <th style="width: 10%; text-align:center;">RUTS</th>
                            <th style="width: 10%; text-align:center;">Doc./Oper.</th>
                            <th style="width: 15%; text-align:right;">Saldos ($)</th>
                            <th style="width: 20%; text-align:center;">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(segmento, sIndex) in segmentList" :key="sIndex">
                            <td>{{ segmento.nombre }}</td>
                            <td style="text-align: center;">
                              {{ segmento.documentos }}
                            </td>
                            <td style="text-align: center;">
                              {{ segmento.deudas }}
                            </td>
                            <td style="text-align:right;">
                              {{ segmento.saldos }}
                            </td>
                            <td style="text-align:right;">
                              <button type="button" class="btn btn-success" v-if="parseInt(segmento.deudas) > 0" @click.prevent="assignation(segmento, $event)">
                                <i class="fa fa-send"></i>&nbsp;Acción
                              </button>
                            </td>
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
        <?php include("../layout/main-menu.php"); ?>
      </div>

      <footer id="footer">
        <!-- Visible when footer positions are fixed -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="show-fixed pull-right">
          <ul class="footer-list list-inline">
            </li>
          </ul>
        </div>
      </footer>
      <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
      <div class="modal"><!-- Place at bottom of page --></div>
    </div>

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
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>
    <script src="../plugins/bootstrap-datetimepicker/moment.js"></script>   
    <script src="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script> 
    <script src="../js/estrategia/Estrategias.js"></script>
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet" media="screen">
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/extra/vue.min.js"></script>
    <script src="../js/extra/axios.min.js"></script>
    <script>
      var app = new Vue({
        el: '#appSegmentador',
        data: {
          showAsignacion: false,
          showFormAssignation: true,
          resultAssign: '',
          message: 'Segmentador',
          resultados: [],
          catResult: 0,
          listParams: [],
          tables: [],
          columns: [],
          logics: [],
          listItems: [],
          queryResult: [],
          querySql: '',
          querySqlNegative: '',
          showTool: false,
          segmentList: [],
          nombreProyecto: '<?php echo $nombreProyecto; ?>',
          actionList: [
            { value: 'AND$AND', label: '- Y -', eq: true },
            { value: 'OR$OR', label: '- Ó -', eq: true },
            { value: 'IN$NOT IN', label: 'Incluye', eq: false },
            { value: 'NOT IN$IN', label: 'Excluye', eq: false },
          ],
          messageAssign: {
            type: 'info',
            text: ''
          },
          subQueries: {
            name: '',
            rows: [
              {
                field: '',
                pivot: '',
                action: 'AND$AND',
                table:'',
                column:'',
                logic: '',
                type: 'text',
                multi: false,
                value: '',
                value2: '',
                actionList: [],
                logicList: [],
                itemsList: [],
              }
            ]
          },
          serviceList: [],
          assosiationData: {
            id: '',
            name: '',
            segment: '',
            service: null
          },
          actionData: {
            id: '',
            project: '',
            segment: '',
            service: '',
            params: null,
          },
          dealerParams: {
            name: '',
            campaign: '',
            dialerType: '',
            intensity: 1,
            assigTo: 'ALL'
          },
          serviceActive: null,
        },
        created: function () {
          this.getSegementos()
          this.getParams()
        },
        methods: {
          getParams: function() {
            axios.get('/includes/segmentador/params').then(response => {
              const datos = response.data
              this.listParams = datos.items
              this.tables = [...datos.items.map(t => ({ name: t.name, title: t.title, field: t.field, pivot: t.pivot }))]
              // console.log('Tables: ', this.tables)
            }).catch(error => {
              console.error(error)
            })
          },
          getSegementos: function() {
            const postRequest = {
              id: <?php echo $id_estrategia ?>
            }
            axios.post('/includes/segmentador/list', postRequest).then(response => {
              const datos = response.data
              console.log(datos)
              this.segmentList = datos.items
              if (!this.segmentList.length) this.showTool = true
            }).catch(error => {
              console.error(error)
            })
          },
          getServices: function () {
            this.serviceList = []
            axios.get('/includes/segmentador/services').then(response => {
              const datos = response.data
              this.serviceList = datos.items
            }).catch(error => {
              console.error(error)
            })
          },
          setSeviceActive: function (e) {
            const value = event.target.value
            console.log('Valor: ', value)
            if (this.serviceList.length && value !== '') {
              const encontrado = this.serviceList.find(s => s.id === value)
              console.log('Encontrado: ', encontrado)
              if (encontrado !== undefined) this.serviceActive = encontrado
              console.log(encontrado.params)
              this.actionData.params = encontrado.params.options
              this.actionData.params.name = this.actionData.segment
            } else {
              this.serviceActive = null
            }
          },
          hasTypeDealerParams: function() {
            const activoType = this.serviceActive.params.dealer_type.find(d => d.id === this.actionData.params.dealer_type)
            if (activoType !== undefined) return activoType.params
            return []
          },
          filterDataTo: function (index, section = 'columns') {
            if (this.subQueries.rows.length > 1) {
              if (this.subQueries.rows[index].table !== this.subQueries.rows[index-1].table) {
                this.subQueries.rows[index].actionList = JSON.parse(JSON.stringify(this.actionList)).filter(r => r.eq === false)
                this.subQueries.rows[index].action = 'IN$NOT IN'
              } else {
                this.subQueries.rows[index].actionList = JSON.parse(JSON.stringify(this.actionList))
                this.subQueries.rows[index].action = 'AND$AND'
              }
            }
            if (section === 'columns') {
              this.subQueries.rows[index].logic = ''
              this.subQueries.rows[index].column = ''
              this.subQueries.rows[index].type = 'text'
              this.subQueries.rows[index].value = ''
              this.subQueries.rows[index].value2 = ''
              const valores = this.listParams.find(t => t.name === this.subQueries.rows[index].table)
              if (valores !== undefined) {
                this.subQueries.rows[index].field = valores.field
                this.subQueries.rows[index].pivot = valores.pivot
                this.columns[index] = valores.columns?.map(c => ({ name: c.name, title: c.title, type: c.type, items: c.items, multi: c.multi, logics: c.logics }))
              }
            }
            if (section === 'logics') {
              this.subQueries.rows[index].type = 'text'
              this.subQueries.rows[index].value = ''
              this.subQueries.rows[index].value2 = ''
              this.subQueries.rows[index].logic = ''
              // this.subQueries.rows[index].logics = []
              const valores = this.columns[index].find(t => t.name === this.subQueries.rows[index].column)
              if (valores !== undefined) {            
                this.subQueries.rows[index].type = valores.type
                this.subQueries.rows[index].multi = valores.multi
                this.subQueries.rows[index].itemsList = valores.items
                const logicsList = valores.logics?.map(c => ({ value: c.value, title: c.title }))
                this.subQueries.rows[index].logicList = logicsList
              }
            }
          },
          getItemValues: function(index) {
            let item = this.listItems.find(t => t.row === index)
            if (item !== undefined){
              item = JSON.parse(JSON.stringify(item))
              console.log('Valores encontrados: ', item.values)
              return item.values
            } 
            return []
          },
          addRow: function() {
            this.subQueries.rows.push({
              field: '',
              pivot: '',
              action:'AND$AND',
              table:'',
              column:'',
              logic: '',
              multi: false,
              type: 'text',
              value: '',
              actionList: JSON.parse(JSON.stringify(this.actionList))
            })
            this.tables.push([...this.tables])
          },
          removeRow: function(index) {
            if (index > 0){
              this.subQueries.rows.splice(index, 1)
              this.tables.splice(index, 1)
              this.columns.splice(index, 1)
              this.logics.splice(index, 1)
            }  
          },
          createQuery: function(rows) {
              let strSQL = 'SELECT ' + rows[0].field + ' FROM ';
              let strSQLNegative = 'SELECT ' + rows[0].field + ' FROM ';
              let wheres = [];
              const actions = [];
              const enFuncion = []; 
              const pivotValue = <?php echo $cedente; ?>;
              let tableMain = ''
              let fieldMain = ''
              let pivotMain = ''
              const filas = rows.length - 1;
              for(let i = 0; i <= filas; i++) {
                const row = rows[i]
                if (i === 0) {
                  fieldMain = row.field
                  pivotMain = row.pivot
                  tableMain = row.table
                }

                const positiveAction = row.action.split('$')[0]
                const negativeAction = row.action.split('$')[1]
                const positiveLogic = row.logic.split('$')[0]
                const negativeLogic = row.logic.split('$')[1]

                const logica = positiveLogic.split('|')[0]
                const funcion = positiveLogic.split('|')[1]
                const logicaNegativa = negativeLogic.split('|')[0]
                const funcionNegativa = negativeLogic.split('|')[1]
                let columna = `${row.column}`

                const fieldValue = this.setFieldValue({
                  type: row.type,
                  logic: row.logic,
                  value: row.value,
                  value2: row.value2
                });

                if (['IN','NOT IN'].includes(positiveAction)) {
                  let enLista = actions.find(a => a.table === row.table);
                  if (enLista === undefined) {
                    enLista = {
                      action: positiveAction,
                      table: row.table,
                      field: row.field,
                      pivot: row.pivot,
                      pivotValue: pivotValue,
                      noAction: negativeAction,
                      wheres: []
                    }
                    actions.push(enLista)
                  }

                  enLista.wheres.push({
                    action: ' AND',
                    field: columna,
                    value: fieldValue[0],
                    logic: logica,
                    funcion,
                    noAction: ' AND',
                    noLogic: logicaNegativa,
                    noFunction: funcionNegativa
                  });
                  //` ${columna} ${logica} `
                } else {
                    if (wheres.find(w => w.field === row.pivot) === undefined) wheres.push({
                      field: row.pivot,
                      value: pivotValue,
                      logic: '=',
                      action: '',
                      noLogic: '=',
                      noAction: ''
                    });
                  
                    // wheres += ` ${positiveAction} ${columna} ${logica} `
                    wheres.push({
                      action: ` ${positiveAction}`,
                      field: columna,
                      value: fieldValue[0],
                      logic: logica,
                      funcion,
                      noAction: negativeAction,
                      noLogic: logicaNegativa,
                      noFunction: funcionNegativa
                    })
                }
              }

              let campoFuncion = ''
              let campoFuncionNegative = ''
              strSQL += `${tableMain} WHERE `
              strSQLNegative += `${tableMain} WHERE `
              wheres.forEach((item) => {
                if (item.funcion !== undefined && item.funcion !== null) {
                  campoFuncion = ` HAVING ${item.funcion}(${item.field}) ${item.logic} ${item.value}`;
                  campoFuncionNegative =  ` HAVING ${item.noFunction}(${item.field}) ${item.noLogic} ${item.value}`;
                } else {
                  if (item.logic === 'btw') {
                    strSQL += ` ${item.action} (${item.field} BETWEEN ${item.value}`;
                    strSQLNegative += ` ${item.noAction} (${item.field} BETWEEN ${item.value}`;
                  } else {
                    strSQL += `${item.action} ${item.field} ${item.logic} ${item.value}`;
                    strSQLNegative += ` ${item.noAction} ${item.field} ${item.noLogic} ${item.value}`;
                  }
                }    
              });

              //console.log(actions)
              if (actions.length) {
                //console.log('Tiene inclusiones o exclusiones')
                actions.forEach(item => {
                  let funcion = '';
                  let funcionNegative = '';
                  //console.log('Actions: ', item)
                  strSQL += ` AND ${fieldMain} ${item.action} (SELECT ${item.field} FROM ${item.table} WHERE ${item.pivot} = ${item.pivotValue}`;
                  strSQLNegative += ` AND ${fieldMain} ${item.noAction} (SELECT ${item.field} FROM ${item.table} WHERE ${item.pivot} = ${item.pivotValue}`
                  if (item.wheres.length) {
                    item.wheres.forEach(w => {
                      if (w.funcion !== undefined && w.funcion !== null) {
                        funcion = ` HAVING ${w.funcion}(${w.field}) ${w.logic} ${w.value}`
                        funcionNegative = ` HAVING ${w.funcion}(${w.field}) ${w.noLogic} ${w.value}`
                      } else {
                        if (item.logic === 'btw') {
                          strSQL += ` ${w.action} (${w.field} BETWEEN ${w.value}`
                          strSQLNegative += ` ${w.noAction} (${w.field} BETWEEN ${w.value}`
                        } else {
                          strSQL += ` ${w.action} ${w.field} ${w.logic} ${w.value}`
                          strSQLNegative += ` ${w.noAction} ${w.field} ${w.noLogic} ${w.value}`
                        }
                      } 
                    }); 
                  }
                  
                  strSQL += ` GROUP BY ${item.field}`;
                  strSQLNegative += ` GROUP BY ${item.field}`;
                  if (funcion !== '') strSQL += funcion;
                  if (funcionNegative !== '') strSQLNegative += funcionNegative;
                  strSQLNegative += `)`;
                  strSQL += `)`;
                });
              }

              strSQLNegative += ` GROUP BY ${fieldMain}`;
              strSQL += ` GROUP BY ${fieldMain}`;
              if (campoFuncion !== '') strSQL += campoFuncion;
              if (campoFuncionNegative !== '') strSQLNegative += campoFuncionNegative;
              this.querySql = strSQL.replace(/  /i, ' ');
              this.querySqlNegative = strSQLNegative.replace(/\s+\s+/i, ' ');
          },
          setFieldValue: function(info) {
            const respuesta = [];
            if (info.type === 'money') {
              respuesta[0] = ` ${info.value}`
              if (info.logic === 'btw$nbtw' ) {
                respuesta[0] += ` AND ${info.value2})`
              }
            } else {
              respuesta[0] = ` '${info.value}'` 
              if (info.logic === 'btw$nbtw') {
                respuesta[0] += ` AND '${info.value2}')`
              }
            }
            return respuesta
          },
          creaResultado: function() {
            this.resultado = this.subQueries
            this.createQuery(this.subQueries.rows)
            //console.log('SQL: ',this.querySql)
            const requestData = {
              id: <?php echo $id_estrategia ?>,
              name: this.subQueries.name,
              token: btoa(this.querySql),
              data: this.subQueries.rows.map(r => ({
                action: r.action.split('$')[0],
                column: r.column,
                field: r.field,
                table: r.table,
                logic: r.logic.split('$')[0],
                pivot: r.pivot,
                type: r.type,
                value: r.value,
                value2: r.value2,
                multi: r.multi
              })),
            }

            axios.post('/includes/segmentador/create', requestData).then(response => {
              const datos = response.data
              //console.log(datos)
              this.resultados = datos.result
            }).catch(error => {
              console.error(error)
            })
          },
          saveResultados: function () {
            const requestData = {
              id: <?php echo $id_estrategia ?>,
              name: this.subQueries.name,
              token: btoa(this.querySql),
              data: this.subQueries.rows.map(r => ({
                action: r.action.split('$')[0],
                column: r.column,
                field: r.field,
                table: r.table,
                logic: r.logic.split('$')[0],
                pivot: r.pivot,
                type: r.type,
                value: r.value,
                value2: r.value2,
                multi: r.multi
              })),
            }

            axios.post('/includes/segmentador/save', requestData).then(response => {
              const datos = response.data
              if (datos.success) {
                this.querySql = ''
                this.querySqlNegative = ''
                this.getSegementos()
              }
              //console.log(datos)
              // this.resultados = datos.result
            }).catch(error => {
              console.error(error)
            })
          },
          assignation: function (segmento, e) {
            const max = (37 - this.nombreProyecto.length)
            const boton = e.target
            this.actionData.service = ''
            this.getServices()
            this.actionData.segment = segmento.nombre.toString().trim().substr(0, max)
            this.actionData.id = segmento.id
            this.actionData.project = segmento.id
            this.showAsignacion = true
          },
          sendAssignation: function (e) {
            const boton = e.target
            if (!confirm('¿Confirma procesar la acción configurada?')) return;
            const campos = this.actionData.params
            let params = [];

            for(let i=0; i<Object.keys(campos).length; i++) {
              let name = Object.keys(campos)[i];
              let value = campos[name];
              let newObj = {
                name: name,
                value: value
              }
              params.push(newObj);
            }

            const requestParams = {
              id: this.actionData.id,
              id_grupo: this.actionData.id,
              params: this.actionData.params,
              auto_list: true,
              rand: this.generateRandomId(8),
              action: 'create'
            }
            
            const formData = new FormData()
            formData.append('id', this.actionData.id)
            formData.append('id_grupo', this.actionData.id)
            formData.append('params', params)
            formData.append('auto_list',true)
            formData.append('rand', this.generateRandomId(8))
            formData.append('action','create')
            boton.disabled = true
            this.messageAssign.text = 'Procesando la información al discador, por favor espere...'
            axios.post('/includes/segmentador/assignation/create', requestParams, {
              headers: {
                'Content-Type': 'application/json'
              }
            }).then(response => {
              const datos = response.data
              // console.log(datos)
              this.messageAssign.type = (datos.success)? 'success':'danger'
              this.messageAssign.text = datos.message

              if (datos.success) {                
                this.messageAssign.text = 'Enviado los datos de la carga, por favor espere...'
                // console.log(this.actionData.params.dealer_type)
                const formData2 = new FormData()
                formData2.append('id_campaign', datos.data.id_campaign)
                formData2.append('id_grupo', this.actionData.id)
                formData2.append('servicio', 'discador')
                formData2.append('type', this.actionData.params.dealer_type)
                axios.post('/includes/segmentador/assignation/load', formData2).then(response => {
                  const datos = response.data
                  this.messageAssign.type = (datos.success)? 'success':'danger'
                  this.messageAssign.text = datos.message
                  this.showFormAssignation = false
                  boton.disabled = false
                  setTimeout(() => {
                    this.messageAssign.text = ''
                    this.messageAssign.type = 'info'
                    this.showFormAssignation = true
                    this.showAsignacion = false                    
                  }, 8000)
                }).catch(error => {
                  console.error(error)
                  this.messageAssign.type = 'danger'
                  this.messageAssign.text = 'Se ha presentado un error al intentar procesar la solicitud.'
                  setTimeout(() => {
                    this.messageAssign.text = ''
                    this.messageAssign.type = 'info'
                  }, 5000)
                  boton.disabled = false
                })
              } else {
                boton.disabled = false
              }
              setTimeout(() => {
                this.messageAssign.text = ''
                this.messageAssign.type = 'info'
              }, 6000)
              //this.serviceList = datos.items
              // boton.disabled = false
            }).catch(error => {
              console.error(error)
              this.messageAssign.type = 'danger'
              this.messageAssign.text = 'Se ha presentado un error al intentar procesar la solicitud.'
              setTimeout(() => {
                this.messageAssign.text = ''
                this.messageAssign.type = 'info'
              }, 5000)
              boton.disabled = false
            })
          },
          verDatos: function() {
            //console.log('Sub Queries: ', this.subQueries.name)
            //console.log('Sub Queries: ', this.subQueries.rows)
            //console.log(this.listItems)
            return;
          },
          generateRandomId: function (length) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
            let id = "";
            for (let i = 0; i < length; i++) {
              const randomIndex = Math.floor(Math.random() * charset.length)
              id += charset[randomIndex]
            }
            return id;
          }
        }
      })
    </script>
  </body>
</html>
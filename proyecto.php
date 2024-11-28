<?PHP
require_once('class/db/DB.php');
require_once('class/session/session.php');
$objetoSession = new Session('1,2,3,4,5,6',false);
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("index.php");
}
$objetoSession->creaMM_restrictGoTo();
if ($_SESSION['MM_UserGroup'] == 6){ 
  $tipoSis = $_SESSION['MM_UserGroup'];
}else{
  $tipoSis = 10; 
}
$db = new DB();
$sqlWhere = '';
$sqlWhereProyectos = '';
if (isset($_SESSION['empresas']) && !empty($_SESSION['empresas']) && is_array($_SESSION['empresas']) && count((array) $_SESSION['empresas']) > 0) {
  $sqlWhere = 'AND id IN('.implode(',',$_SESSION['empresas']).')';
}

if (isset($_SESSION['proyectos']) && !empty($_SESSION['proyectos']) && is_array($_SESSION['proyectos']) && count((array) $_SESSION['proyectos']) > 0) {
  $sqlWhereProyectos = 'AND c.Id_Cedente IN('.implode(',',$_SESSION['proyectos']).')';
}

$rsMandantes = $db->select("SELECT id, nombre FROM mandante WHERE estatus = '1' AND nombre !='' {$sqlWhere} ORDER BY nombre ASC;");
if ($rsMandantes) {
  foreach ((array) $rsMandantes as $key => $iMandante) {
    $rsMandantes[$key]['cedentes'] = [];
    $sql = "SELECT DISTINCT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c
        INNER JOIN mandante_cedente AS mc ON (mc.Id_Cedente = c.Id_Cedente) 
        INNER JOIN mandante AS m ON (m.id = mc.Id_Mandante) 
        WHERE m.id = '" . $iMandante['id'] . "' AND c.Id_Cedente != 100 {$sqlWhereProyectos} ORDER BY c.Id_Cedente ASC";
    $rsCedentes = $db->select($sql);
    if ($rsCedentes) {
      $rsMandantes[$key]['cedentes'] = (array) $rsCedentes;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM - Sinaptica</title>
  <meta name="theme-color" content="#ffffff">
  <!-- Vendors styles-->
  <link href="v2/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="bg-light min-vh-100 d-flex flex-row align-items-center dark:bg-transparent">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card-group d-block d-md-flex row">
            <div class="card col-md-5 text-white bg-primary py-5">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <img src="v2/img/logo.png" alt="CRM Logo" style="width: 100%; height: auto; margin-top: 4%;" />
                  </div>
                </div>
              </div>
            </div><!-- Card 1 -->
            <div class="card col-md-7 p-4 mb-0">
              <div class="card-body">
                <h4>Seleccione</h4>
                <form action="./estrategia/sesion_cedente.php" method="POST">
                  <div class="row">
                    <div class="col-12">
                      <label for="mandante" class="form-label">Empresa</label>
                      <div class="input-group mb-3">
                        <span class="input-group-text">
                          <svg class="icon">
                            <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-blur"></use>
                          </svg>
                        </span>
                        <select class="form-select" id="mandante" name="mandante" onchange="llenarCedentes(this.value)" required>
                          <option value="">-- Seleccione --</option>                     
                        </select>
                      </div><!-- input 1 -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <label for="cedente" class="form-label">Proyecto</label>
                      <div class="input-group mb-3">
                        <span class="input-group-text">
                          <svg class="icon">
                            <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-blur-linear"></use>
                          </svg>
                        </span>
                        <select class="form-select" id="cedente" name="cedente" onchange="validaDatos()" required>
                          <option value="">-- Seleccione --</option>                   
                        </select>
                      </div><!-- input 1 -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-8 pt-3">
                      <div class="d-grid gap-2">
                        <button class="btn btn-block btn-primary px-4" id="continuar" type="submit" title="Continuar" disabled>
                          <svg class="icon">
                            <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-paper-plane"></use>
                          </svg>
                          &nbsp;&nbsp;Continuar
                        </button>
                      </div>
                    </div>
                    <div class="col-4 pt-3">
                      <div class="d-grid gap-2">
                        <button class="btn btn-info text-white" type="button" onclick="regresar()">Regresar</button>
                      </div>
                    </div>
                  </div><!-- row button -->
                </form>                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-2.2.1.min.js"></script>
  <script>
    const lstMandandantes = <?php  echo ($rsMandantes)? json_encode($rsMandantes): '[]'; ?>;
    
    $(document).ready(function() {
      llenarMandantes();
      validaDatos();
    });
    function regresar(){
      window.location.href = '/';
    }

    function llenarMandantes() {
      const selMandante = $('select#mandante');
      var html = `<option value="">-- Seleccione --</option>\n`;
      if (lstMandandantes.length) {
        lstMandandantes.forEach((mandante) => {
          html += `<option value="${mandante.id}">${mandante.nombre}</option>\n`;
        });        
      }
      selMandante.html(html);
      validaDatos();
    }

    function llenarCedentes(value) {
      const selCedente = $('select#cedente');
      var html = '<option value="">-- Seleccione --</option>';
      const iCedente = lstMandandantes.find(m => parseInt(m.id) === parseInt(value));
      if (iCedente !== undefined && iCedente['cedentes'].length) {
        iCedente['cedentes'].forEach((cedente) => {
          html += `<option value="${cedente.id}">${cedente.nombre}</option>\n`;
        })
      }
      selCedente.html(html);
    }

    function validaDatos() {
      const boton = $('button#continuar');
      const selMandante = $('select#mandante');
      const selCedente = $('select#cedente');
      if ((selMandante.val() !== '') && selCedente.val() !== '') {
        boton.removeAttr('disabled');
        return;
      }
      boton.attr('disabled');
    }
  </script>
</body>
</html>
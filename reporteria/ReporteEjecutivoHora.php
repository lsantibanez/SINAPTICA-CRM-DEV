<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "inicio,bien"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
if (isset($_SESSION['cedente'])){
    $cedente = $_SESSION['cedente'];
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
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
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


    <style type="text/css">

    .modalreporte{
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 )
        url('../img/gears.gif')
        50% 50%
        no-repeat;
    }
    body.loading{
        overflow: hidden;
    }
    body.loading .modal{
        display: block;
    }
    #VerReporteOculto{
        display : none;
    }

    </style>
</head>
<body>
    <div id="container" class="effect mainnav-lg">
    <!--NAVBAR-->
    <!--===================================================-->
    <?php
        include("../layout/header.php");
    ?>
    <!--===================================================-->
    <!--END NAVBAR-->
        <div class="boxed">
            <div id="content-container">
                <div id="page-title">
                </div>
                <br>
                <ol class="breadcrumb">
                    <li><a href="#">Reporteria</a></li>
                    <li class="active">Reporte Ejecutivos Horas</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-primary">Filtro</h2>
                                </div>
                                <div class="panel-body">

                                    <div class="col-sm-3"  id="date-range">
                                        <div class="form-group" >
                                            <div class="input-daterange input-group " id="datepicker">
                                              <label class="control-label">Seleccione Fecha</label>
                                                <input type="text" class="form-control" id="fechaReporteHora" name="fechaReporteHora">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div id="Div3">
                                                <label for="sel1" >Seleccione Grupo</label>
                                                <select name="mostrargrupos" id="mostrargrupos" class="selectpicker mostrargrupos form-control"  data-live-search="true" data-width="100%" title="Seleccione un Grupo">       
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;&nbsp;</label>
                                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalcreagrupo" id="CrearGrupo"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;&nbsp;</label>
                                            <button class="btn btn-success btn-block" id="verReporte">Ver Reporte</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



  <!-- Inicio Modal registrar actualizar grupo-->
  <div class="row">
    <div class="modal fade" id="modalcreagrupo" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Creación de Grupos</h4>
          </div>
        <div class="modal-body">

          <div class="row">
              <div class="col-sm-3 col-sm-offset-2">
                  <div class="form-group">
                    <label class="control-label">Grupo</label>
                  </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <select name="grupotipo" id="grupotipo" class="selectpicker form-control" data-live-sarch="true" data-width="100%" title="Cree o Seleccione un grupo">
                    <option id="gruponuevo" value="gruponuevo">Nuevo</option>
                    <option id="grupoexistente" value="grupoexistente">Existente</option>
                 </select>
                </div>
              </div>
          </div>
          <hr>

          <!--inicio content hide grupo nuevo -->
          <div id="creagruponuevo" style="display:none;">
                <div class="row">
                <form id="forminsertGrupo"  role="form" name="insertGrupo">
                  <div class="col-sm-3 col-sm-offset-2">
                      <div class="form-group">
                        <label class="control-label">Nombre de grupo</label>
                      </div>
                  </div>
              <div class="col-sm-4">
                    <div class="form-group">
                      <input type="text" name="crearnombregrupo" id="crearnombregrupo" class="form-control">
                    </div>
              </div>
              <div class="col-sm-3 col-sm-offset-2">
                    <div class="form-group">
                      <label class="control-label">Seleccione Ejecutivos</label>
                    </div>
              </div>
              <div class="col-sm-4">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="newEjecutivos[]" id="newEjecutivos" data-live-search="true" multiple data-width="100%" title="Seleccione uno o más">
                      
                      </select>
                    </div>
              </div>
              <div class="col-sm-4 col-sm-offset-5">
                    <div class="form-group">
                      <button type="submit"  id="btnregistrar" class="btn btn-primary btn-block" data-dismiss="modal">Crear Grupo</button>
                    </div>
              </div>
              </form>
              </div>
          </div>
            <!--fin hide content grupo nuevo-->

            <!--inicio content hide grupo existe -->
            <div id="vergrupoexistente" style="display:none;">
              <div class="row">
                <form id="formEditGrupo" role="form">
                  <div class="col-sm-3 col-sm-offset-2">
                    <div class="form-group">
                      <label class="control-label">Seleccione Grupo</label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="editGrupo" id="editGrupo" class="selectpicker form-control" data-live-search="true" title="Actualice Grupo">
                            
                      </select>
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-2">
                  <div class="form-group">
                    <label class="control-label">Seleccione Ejectivos</label>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <select name="editEjecutivos[]" id="editEjecutivos" class="selectpicker form-control" data-live-search="true" multiple title="Actualice Ejecutivos">
                        
                    </select>
                  </div>
                </div>
                  <div class="col-sm-5 col-sm-offset-5">
                    <div class="form-group">
                      <button type="submit" name="btneditar" id="btneditar" class="btn btn-primary btn-block" data-dismiss="modal">Actualizar Grupo</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!--fin hide content existe -->
        </div>

      </div>

    </div>
  </div>
</div>

<!--fin modal registrar actualizar grupos-->



                    <div id="VerReporteOculto">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-primary">Reporte Ejecutivo</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-sm-12">
                                            <div id="Mostrar">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
                <?php include("../layout/main-menu.php"); ?>
            </div>
            <?php include("../layout/footer.php"); ?>

            <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
            <div class="modalreporte">
            </div>
        </div>
    </div>

    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/reporteria/reporteEjecutivoHora.js"></script>

</body>
</html>
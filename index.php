<?php
require_once('class/db/DB.php');
require_once('class/session/session.php');
include("class/usuarios/hash.php");

$objetoSession = new Session('1',false); 

if (isset($_GET['accesscheck'])) {
  $objetoSession->crearVariableSession($array = array("PrevUrl" => $_GET['accesscheck']));
}

$acceso = "";
if (isset($_POST['usuario'])) {
  $acceso = $objetoSession->login($_POST['usuario'],$_POST['password']);
} else {
  $objetoSession->borrarVariablesSession();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM - Sinaptica</title>
  <meta name="theme-color" content="#ffffff">
  <link href="v2/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="bg-light min-vh-100 d-flex flex-row align-items-center dark:bg-transparent">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card-group d-block d-md-flex row">
            <div class="card col-md-7 p-4 mb-0">
              <div class="card-body">
                <h2>CRM CLOUD</h2>
                <p class="text-medium-emphasis">Ingrese sus credenciales</p>
                <form action="./" method="POST">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg>
                    </span>
                    <input class="form-control" type="text" placeholder="Usuario" name="usuario" title="Usuario" maxlength="20" required>
                  </div><!-- input 1 -->
                  <div class="input-group mb-4">
                    <span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                      </svg>
                    </span>
                    <input class="form-control" type="password" placeholder="Contraseña" name="password" maxlength="25" title="Contraseña" required>
                  </div><!-- input 2  -->
                  <?php if((int) $acceso == 1) { ?>
                  <div class="row">
                    <div class="col-12 pb-3">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>¡Acceso Denegado!</strong>
                    </div>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="row">
                    <div class="col-12">
                      <div class="d-grid gap-2">
                        <button class="btn btn-block btn-primary px-4" type="submit" title="Ingresar">
                          <svg class="icon">
                            <use xlink:href="v2/vendors/@coreui/icons/svg/free.svg#cil-paper-plane"></use>
                          </svg>
                          &nbsp;&nbsp;Ingresar
                        </button>
                      </div>
                    </div>
                  </div><!-- row button -->
                </form>
              </div>
            </div><!-- card 1 -->
            <div class="card col-md-5 text-white bg-primary py-5">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <img src="v2/img/logo.png" alt="CRM Logo" style="width: 100%; height: auto; margin-top: 4%;" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
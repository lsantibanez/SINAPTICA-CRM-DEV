<?php

class Menu
{
  public $tipoSistema;
  public $FocoConfig;

  private $idMenu;
  private $nivelUsuario;

  public function __construct($idMenu, $nivelUsuario)
  {
    $this->idMenu = $idMenu;
    $this->nivelUsuario = $nivelUsuario;
    $this->tipoSistema = isset($_SESSION['tipoSistema']) ? $_SESSION['tipoSistema'] : 0;
    $this->FocoConfig = $this->getFocoConfig();
  }

  // mostrare solo los sub menu que el usuario en cuestion tenga permiso de ver
  // los nodos padres se mostraran solo si al menos uno de los hijos es visible
  public function crearMenu()
  {
    echo "<script src='../plugins/push.js/push.min.js'></script>
          <script src='../js/socket.io.js'></script>
    <!--MAIN NAVIGATION-->
    <!--===================================================-->
    <nav id='mainnav-container'>
      <div id='mainnav'>
        <!--Shortcut buttons-->
        <!--================================-->
        <div id='mainnav-shortcut'>
          <ul class='list-unstyled'>
            <li class='col-xs-4' data-content='Page Alerts'></li>
          </ul>
        </div>
        <!--================================-->
        <!--End shortcut buttons-->
        <!--Menu-->
        <!--================================-->
        <div id='mainnav-menu-wrap'>
          <div class='nano'>
            <div class='nano-content'>
              <ul id='mainnav-menu' class='list-group'>
                <!--Category name-->";
                $resultadoNivel1 = $this->consultaMenu(0); // 0 porque aqui busco al nivel 1 del menu
                //while ($resultNivel1 = mysql_fetch_array($resultadoNivel1))
                if($resultadoNivel1){
                  foreach((array) $resultadoNivel1 as $resultNivel1)
                  {
                    //$classArrow = "";
                    //if ($resultNivel1["id_menu"] != 1) // le enviamos la clase solo si menu es diferente a INICIO
                    $classArrow = "arrow"; // clase para la flecha
                    $classActiveSub = ""; //class='active-sub' sombreado en azul (pagina donde estoy parada)
                    $classCollapseIn = "collapse";
                    // Verifico la pagina donde estoy parada para darle las clases correspondientes al menu
                    if ($resultNivel1["nombre"] == $this->idMenu[0])
                    {
                      $classActiveSub = "active-sub";
                      $classCollapseIn = "collapse in";
                    }
                    // <!--Menu list item-->
                    echo "<li class='".$classActiveSub."'>";
                      $this->menuTitulo($resultNivel1["enlace"], $resultNivel1["icono"], $resultNivel1["descripcion"], $classArrow);
                      // <!--Submenu -->
                      echo "<ul class='".$classCollapseIn."'>";
                        // Busco los subMenu del nivel 1 en cuestion
                        $resultadoNivel2 = $this->consultaMenu($resultNivel1["id_menu"]);
                        foreach((array) $resultadoNivel2 as $resultNivel2)
                        {
                          // Verifico si el usuario tiene permiso para ver este Nivel del menu (Nivel 1)
                          if ($this->BlockMenu_ByName($resultNivel2["nombre"]))
                          {
                            $classActiveLink = "";
                            $classActiveSub = "";
                            $classCollapseIn = "collapse";
                            if ($resultNivel2["nombre"] == $this->idMenu[1]) {
                              $classActiveLink = "active-link";
                              $classActiveSub = "active-sub";
                              $classCollapseIn = "collapse in";
                            }
                            // inicio nivel 3
                            // Busco los subMenu del nivel 2 si es que tiene
                            $resultadoNivel3 = $this->consultaMenu($resultNivel2["id_menu"]);
                            $row = count((array) $resultadoNivel3);
                            // fin nivel 3
                            if ($row == 0) $this->subMenuTitulo($classActiveLink, $resultNivel2["descripcion"], $resultNivel2["enlace"]);
                            // INICIO NIVEL 3 --------------
                            //if ($resultNivel2["descripcion"] == "Sistema"){
                            if ($row > 0){
                            echo "<li class='".$classActiveSub."'>";
                            //echo "<a href='#'>Third Level<i class='arrow'></i></a>";
                            $this->subMenuTituloNivel3($resultNivel2["descripcion"], $resultNivel2["enlace"]);
                            echo "<!--SubmenuNivel 3-->
                                <ul class='".$classCollapseIn."' aria-expanded='false'>";
                                foreach((array) $resultadoNivel3 as $resultNivel3)
                                {  // subMenuTituloNivel3($tituloSubMenu, $linkSubMenu)
                                  $classActiveLink = "";
                                  if (count($this->idMenu) == 3) {
                                    if ($resultNivel3["nombre"] == $this->idMenu[2]) $classActiveLink = "active-link";
                                  }
                                  $this->subMenuTitulo($classActiveLink, $resultNivel3["descripcion"], $resultNivel3["enlace"]);
                                }
                            echo "</ul>
                            </li>";
                            }
                            // FIN NIVEL 3 -----------------
                          }
                        }
                      echo "</ul>";
                      // <!--End SubMenu-->
                    echo "</li>";
                    // <!-- End Menu list item-->
                  }
                }
        echo "</ul> <!-- Fin <ul id='mainnav-menu' class='list-group'>  -->
            </div>
          </div>
        </div>
        <!--================================-->
        <!--End menu-->
      </div>
    </nav>
    <!--===================================================-->
    <!--END MAIN NAVIGATION-->";
  }

  // este va dentro de un <li active-sub> Ejemplo INICIO ESTRATEGIA <!-- End Menu list item-->
  public function menuTitulo($linkMenu, $classIcono, $tituloMenu, $classArrow)
  {
    echo "<a href='".$linkMenu."'>
      <i class='".$classIcono."'></i>
      <span class='menu-title'>
       <strong>".$tituloMenu."</strong>
      </span>
      <i class=".$classArrow."></i>
    </a>";
  }

  // esto va dentro de  <ul class="collapse in">   <!--End Submenu-->
  public function subMenuTitulo($classActiveLink, $tituloSubMenu, $linkSubMenu)
  {
    // <!-- class='active-link' esto coloca en negrita el link donde estoy parada dependiendp de la pagina -->
    echo "<li class='".$classActiveLink."'>
           <a href='".$linkSubMenu."'>".$tituloSubMenu."</a>
          </li>";
  }

  public function subMenuTituloNivel3($tituloSubMenu, $linkSubMenu)
  {
    echo "<a href='".$linkSubMenu."'>".$tituloSubMenu."<i class='arrow'></i></a>";
  }

  public function consultaMenu($idPadre)
  {
    $Where = "INNER JOIN menu_roles ON menu_roles.id_menu = menu.id_menu WHERE menu_roles.id_rol = '".$this->nivelUsuario."'";
    $WhereTipoMenu = $idPadre == "0" ? " AND FIND_IN_SET(tipoMenu,'".$this->FocoConfig["tipoMenu"]."')  " : "";
    $db = new DB();
    $consultaMenu = "SELECT DISTINCT menu.* FROM menu ".$Where."  AND padre = '".$idPadre."' ".$WhereTipoMenu." AND activo = 'Si' ORDER BY prioridad";
    $resultado = $db->select($consultaMenu);
    return $resultado;
  }

  public function isAuthorized($strPermisos)
  {
    $isValid = False;
    $arrPermisos = Explode(",", $strPermisos); // Niveles del item menu
    // busco si el nivel del usuario se encuentra en los niveles del menu
    if (in_array($this->nivelUsuario, $arrPermisos))
      {
        $isValid = true;
      }
    return $isValid;
  }

  public function adminCedente()
  {
    //echo "hola";
  }

  function BlockMenu_ByName($nombreMenu){
    $ToReturn = true;
    $db = new DB();
    if(!isset($_SESSION)){
      session_start();
    }
    switch($nombreMenu){
      case 'cal_eva':
        switch($this->nivelUsuario){
          case '6':
            $consultaMenu = "SELECT * FROM Usuarios where id = '".$_SESSION["id_usuario"]."'";
            $resultado = $db->select($consultaMenu);
            foreach((array) $resultado as $row1)
            {
              $consultaMenu = "SELECT empieza FROM mandante where id = '".$_SESSION["mandante"]."'";
              $resultado = $db->select($consultaMenu);
              foreach((array) $resultado as $row2)
              {
                if(($row2["empieza"] == "0") && ($row1["mandante"] != "")){
                  $ToReturn = false;
                }
                if(($row2["empieza"] == "1") && ($row1["mandante"] == "")){
                  $ToReturn = false;
                }
              }
            }
          break;
        }
      break;
    }
    return $ToReturn;
  }

  function getFocoConfig() {
    $db = new DB();
    $SqlFocoConfig = "SELECT * FROM fireConfig";
    $FocoConfig = $db->select($SqlFocoConfig);
    return $FocoConfig[0];
  }
}
?>
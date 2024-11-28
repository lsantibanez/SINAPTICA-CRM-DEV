<?php 
    include_once("../../includes/functions/Functions.php");
    /**
    * Clase para configuracion de los privilegios del menu
    */
    class confMenu{

        public function GetDatatableMenu(){

            $db = new Db();
            $array = array();
            
            $headers = array();
            $headers[] = "Menu";
            $headers[] = "Submenu";

            $roles = $db->select("SELECT * FROM Roles");
            foreach($roles as $rol){
                $headers[] = utf8_encode($rol['nombre']);
            }

            $array['headers'] = $headers;
            
            $menus = array();
            $FocoConfig = getFocoConfig();
            $tipoMenu = $FocoConfig['tipoMenu'];
            $consulta_menu = $db->select("SELECT * FROM menu WHERE FIND_IN_SET(tipoMenu,'".$tipoMenu."') AND padre = '0'");

            foreach($consulta_menu as $menu){
                $i = 0;
                $id = $menu['id_menu'];
                $result = '';
                $menus[$id][$i] = utf8_encode($menu['descripcion']);
                $i++;
                $result =  $result . utf8_encode($menu['descripcion']) . '<div class="clearfix" style="margin-bottom:10px !important;"></div>';
                $submenus = $db->select("SELECT * FROM menu WHERE padre = ".$menu['id_menu']);
                foreach($submenus as $submenu){
                    $result =  $result . '--' . utf8_encode($submenu['descripcion']) . '<div class="clearfix" style="margin-bottom:10px !important;"></div>';
                    $last_menus = $db->select("SELECT * FROM menu WHERE padre = ".$submenu['id_menu']);
                    foreach($last_menus as $last){
                        $result = $result . '----' . utf8_encode($last['descripcion']) . '<div class="clearfix" style="margin-bottom:10px !important;"></div>';
                    }
                }

                $menus[$id][$i] = $result;
                $i++;
                foreach($roles as $rol){
                    $result = '';

                    $privilegio = $db->select("SELECT * FROM menu_roles where id_menu = ".$menu['id_menu']." AND id_rol = ".$rol['id']);
                    if(count($privilegio) == 0){
                        $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$menu['id_menu'].'"><br style="margin-bottom:1.5%">';
                    }else{
                        $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$menu['id_menu'].'" checked><br style="margin-bottom:1.5%">';
                    }
                    
                    foreach($submenus as $submenu){

                        $privilegio = $db->select("SELECT * FROM menu_roles where id_menu = ".$submenu['id_menu']." AND id_rol = ".$rol['id']);
                        if(count($privilegio) == 0){
                            $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$submenu['id_menu'].'"><br style="margin-bottom:1.5%">';
                        }else{
                            $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$submenu['id_menu'].'" checked><br style="margin-bottom:1.5%">';
                        }

                        $last_menus = $db->select("SELECT * FROM menu WHERE padre = ".$submenu['id_menu']);
                        foreach($last_menus as $last){
                            $privilegio = $db->select("SELECT * FROM menu_roles where id_menu = ".$submenu['id_menu']." AND id_rol = ".$rol['id']);
                            if(count($privilegio) == 0){
                                $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$last['id_menu'].'"><br style="margin-bottom:1.5%">';
                            }else{
                                $result = $result . '<input type="checkbox" style="margin-bottom: 10px !important;" id = "'.$rol['id'].'-'.$last['id_menu'].'" checked><br style="margin-bottom:1.5%">';
                            }
                        }
                    }

                    $menus[$id][$i] = $result;
                    $i++;
                }
            } 
            
            $array['menus'] = $menus;

            echo json_encode($array);
            
        }

        function crearPrivilegio($ID){
            $db = new Db();

            $Explode = explode('-',$ID);
            $id_rol = $Explode[0];
            $id_menu = $Explode[1];
            $query = $db->query("DELETE FROM menu_roles WHERE id_menu = ".$id_menu." AND id_rol = ".$id_rol);
            $query = $db->query("INSERT INTO menu_roles (id_menu,id_rol) VALUES ('".$id_menu."','".$id_rol."')");
        }

        function eliminarPrivilegio($ID){
            $db = new Db();

            $Explode = explode('-',$ID);
            $id_rol = $Explode[0];
            $id_menu = $Explode[1];
            $query = $db->query("DELETE FROM menu_roles WHERE id_menu = ".$id_menu." AND id_rol = ".$id_rol);
        }
    }
 ?>
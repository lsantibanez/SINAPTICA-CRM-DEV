<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("grupos");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Colas = $tareas->getColas();
    $ToReturn = "";
    if($Colas){
        foreach($Colas as $Cola){
            $Tabla = $Cola["tabla"];
            $ArrayAsignacion = explode("_",$Tabla);
            $idCola = $ArrayAsignacion[2];
            $TipoEntidad = $ArrayAsignacion[3];
            $idEntidad = $ArrayAsignacion[4];
            $Foco = $ArrayAsignacion[7];
            $Nombre = "";
            switch($TipoEntidad){
                case 'E':
                case 'S':
                break;
                case 'EE':
                break;
                case 'G':
                    $GrupoClass = new Grupos();
                    $Grupo = $GrupoClass->getGroup($idEntidad);
                    if($Grupo){
                        $ColaTarea = $tareas->getCola($idCola);
                        if($ColaTarea){
                            $Estrategia = $tareas->getEstrategia($ColaTarea['id_estrategia']);
                            if($Estrategia){
                                $Nombre = $Estrategia['nombre'] . ' - ' . $ColaTarea['cola'] . ' - ' . $Grupo["Nombre"];
                            }
                            else{
                                $Nombre = $ColaTarea['cola'] . ' - ' . $Grupo["Nombre"];
                            }
                        }
                        else{
                            $Nombre = $Grupo["Nombre"];
                        }
                    }
                    $Nombre = utf8_encode($Nombre);
                break;
            }
            if($Nombre != ""){
                $ToReturn .= "<option value='".$Tabla."'>".$Nombre."</option>";
            }
        }   
    }
    echo $ToReturn;
?>
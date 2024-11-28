<?php
    //include("../../class/global/global.php");
    include("../../class/db/DB.php");

    $db = new DB();
    /* $query = "SELECT DISTINCT
                    O.*,
                    P.Nombre,
                    (SELECT id from evaluaciones where Id_Grabacion = E.Id_Grabacion AND lastEvaluation = '1' AND Id_Usuario = E.Id_Usuario) as idEvaluacion
                FROM
                    objeciones_calidad O
                        inner join Personal P on P.id_usuario = O.id_usuario
                        inner join evaluaciones E on E.Id_Grabacion = O.id_grabacion
                WHERE
                    (E.Id_Usuario = '".$_SESSION["id_usuario"]."' OR O.id_usuario = '".$_SESSION["id_usuario"]."') AND
                    O.id_mandante = '".$_SESSION["mandante"]."' AND
                    O.notificacionVisible = '1'
                ORDER BY
                    O.fechaObjecion DESC"; */
    $query = "SELECT DISTINCT
                    O.*,
                    P.Nombre,
                    (SELECT id from evaluaciones where Id_Grabacion = E.Id_Grabacion AND lastEvaluation = '1' AND Id_Usuario = E.Id_Usuario) as idEvaluacion
                FROM
                    objeciones_calidad O
                            inner join Personal P on P.id_usuario = O.id_usuario
                            inner join evaluaciones E on E.Id_Grabacion = O.id_grabacion
                WHERE
                    O.id_mandante = '".$_SESSION["mandante"]."' AND
                    O.notificacionVisible = '1' AND
                    (O.id_grabacion in (select id_grabacion from objeciones_calidad where id_usuario='".$_SESSION["id_usuario"]."' and id_mandante='".$_SESSION["mandante"]."') OR E.Id_Usuario='".$_SESSION["id_usuario"]."')
                ORDER BY
                    O.fechaObjecion DESC";
    $Notificaciones = $db->select($query);
    $ToReturn = array();
    foreach($Notificaciones as $Notificacion){
        $isVisible = "1";
        $SqlVisible = "select * from objeciones_calidad_usuarios where id_usuario='".$_SESSION["id_usuario"]."' and id_objecion='".$Notificacion["id"]."' and tipo='1'";
        $Visible = $db->select($SqlVisible);
        if(count($Visible) > 0){
            $isVisible = "0";
        }
        $isVisto = "0";
        $SqlVisto = "select * from objeciones_calidad_usuarios where id_usuario='".$_SESSION["id_usuario"]."' and id_objecion='".$Notificacion["id"]."' and tipo='2'";
        $Visto = $db->select($SqlVisto);
        if(count($Visto) > 0){
            $isVisto = "1";
        }
        if($isVisible == "1"){
            $ArrayTmp = array();
            $ArrayTmp["Usuario"] = $Notificacion["Nombre"];
            //$ArrayTmp["Tipo"] = ($Notificacion["tipo_comentario"] == "0") ? "Objecion" : "Coaching";
            $ArrayTmp["idEvaluacion"] = $Notificacion["idEvaluacion"];
            $ArrayTmp["visto"] = $isVisto;//$Notificacion["visto"];
            $ArrayTmp["idObjecion"] = $Notificacion["id"];
            switch($Notificacion["tipo_comentario"]){
                case "0":
                    $ArrayTmp["Tipo"] = "Apelacion";
                break;
                case "1":
                    $ArrayTmp["Tipo"] = "Retroalimentacion Calidad";
                break;
                case "2":
                    $ArrayTmp["Tipo"] = "Evaluadora Respuesta Apelacion";
                break;
            }
            array_push($ToReturn,$ArrayTmp);
        }
    }
    echo json_encode($ToReturn);
?>
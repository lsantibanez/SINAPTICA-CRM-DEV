<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

    $var_id = $_POST["id"];

    if(isset($_POST["tipoEnvio"]) && ($_POST["tipoEnvio"] == "sms")){
        $tablaQuery = "VariablesSMS";
    }else{
        $tablaQuery = "Variables";
    }

    $query_select = "SELECT id, variable, tabla, campo, operacion FROM " . $tablaQuery . " WHERE id = ".$var_id;

    $row_select = $db->select($query_select);

    if(count($row_select) > 0){

        $row = $row_select[0];

        $is_tabla = strpos($row['campo'], ',');
        $preview = "";
        if($row['operacion'] !== ''){
            $tipo = 'operacion';
            $preview = '';
        } else if($is_tabla !== false){
            $tipo = 'tabla';
            $CamposTmp = explode("$&$",$row['campo']);
            $CamposTmp = $CamposTmp[0];
            $CamposTmpArray = explode(",",$CamposTmp);
            $CamposArray = array();

            foreach($CamposTmpArray as $Campo){
                $CampoTmpArray = explode("|",$Campo);
                array_push($CamposArray,$CampoTmpArray[0]);
                if(isset($CampoTmpArray[1])){
                    $preview .= '<th><span><strong class="field">'.$CampoTmpArray[0].'</strong><br>'.$CampoTmpArray[1].'</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th>';
                }
                else{
                    $preview .= '<th><span><strong class="field">'.$CampoTmpArray[0].'</strong></span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th>';
                }
            }
            $Campos = implode(",",$CamposArray);
            //$preview = str_replace(',', '</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th><th><span>', $Campos);
            //$preview = '<th><span>'.$preview.'</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th>';
        } else {
            $tipo = 'valor';
            $preview = '';
        }

        $temp = array($row['id'],$row['variable'],$tipo,$row['tabla'],$row['campo'],$row['operacion'],$preview);

    } else {
        $temp = array('','','','','');
    }

    echo json_encode($temp);

?>
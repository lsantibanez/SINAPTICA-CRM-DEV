<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/estrategia/config_campos.php");
QueryPHP_IncludeClasses("db");
$ConfigCampos = new ConfigCampos(); 
if(isset($_POST['camposArray'])){
	$camposArray = $_POST['camposArray'];
}else{
	$camposArray = array();
}
$campos = $ConfigCampos->getListar_camposNoConfig($_POST['nombreTabla'], $camposArray);

$ToReturn = "<option value='0'>Seleccione</option>";
foreach($campos as $campo){
    if($campo != ""){
        $ToReturn .= "<option value='".$campo."'>".$campo."</option>";
    }
}
echo $ToReturn; 
?>
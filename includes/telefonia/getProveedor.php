<?php 
	include_once("../functions/Functions.php");
	include_once("../../class/telefonia/telefonia.php");
    QueryPHP_IncludeClasses("db");

	 $telefonia = new Telefonia();
	 $proveedores = $telefonia->getProveedor();
	 $ToReturn = "";

    foreach($proveedores as $proveedor){
        $ToReturn .= "<option value='".$proveedor['Codigo']."'>".$proveedor['Codigo']."</option>";
    }
    echo $ToReturn;
?>
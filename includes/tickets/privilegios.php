<?php
	session_start();
	if(isset($_SESSION['MM_UserGroup'])){
		switch ($_SESSION['MM_UserGroup']) {
		    case 1:
		        echo "administrador";
		        break;
		    case 2:
		        echo "soporte";
		        break;
		    case 3:
		        echo "terreno";
		        break;
		    default:
		    	echo "administrador";
		}
	}else{
		echo "administrador";
	}
?>
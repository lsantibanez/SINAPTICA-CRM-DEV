<?php

require __DIR__.'/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

if (!isset($_SESSION)) session_start();

function include_all_php($folder){
    foreach ((array)glob("{$folder}/*.php") as $filename)
    {
        if($filename != "") {
			if (!in_array($filename, get_included_files())) {
				include_once $filename;  
			}                 
        }
    }
}

function Main_IncludeClasses($folder){
    include_all_php("../class/".$folder);
}

function QueryPHP_IncludeClasses($folder){
    include_all_php("../../class/".$folder);
}

function Prints_IncludeClasses($folder){
    include_all_php("../../../class/".$folder);
}

function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    function unlinkRecursive($dir, $deleteRootToo){
        if(!$dh = @opendir($dir)){
            return;
        }
        while (false !== ($obj = readdir($dh))){
            if($obj == '.' || $obj == '..'){
                continue;
            }
            if (!@unlink($dir . '/' . $obj)){
                unlinkRecursive($dir.'/'.$obj, true);
            }
        }
        closedir($dh);
        if ($deleteRootToo){
            @rmdir($dir);
        }
        return;
    }
    function Depurador($Codigo,$Fono,$Rut,$ImportFonos = true){
		$db = new DB();
		$ContarCodigo = strlen($Codigo);
		$Fono = ereg_replace("[^0-9]", "", $Fono);
		$Largo = strlen($Fono);
		$Fail = 0;
		$array = array();

		switch ($Largo) {
			case 11:
				$Fono = substr($Fono,2,strlen($Fono));
				$Fail = 1;
			break;
			case 9:
				$Fail = 1;
			break;
			case 8:
				$Consulta = substr($Fono,0,2);
				$CodigoArea = 0;
				$ConsultaCodigoArea = $db->select("SELECT Codigo FROM Codigo_Area WHERE Codigo=$Consulta LIMIT 1");
				foreach($ConsultaCodigoArea as $row){
					$CodigoArea = $row["Codigo"];
				}
				if($Consulta==$CodigoArea && $Codigo==''){
					if($CodigoArea == $Codigo){
						$part2 = substr($Fono, 2, 10);
						$Fono = $Consulta."2".$part2;
						$Fail = 1;
					}else{
						$part2 = substr($Fono, 2, 10);
						$Fono1  = $Consulta."2".$part2;
						$Fono2  = "9".$Fono;
						$FonoArray = array();
						array_push($FonoArray,$Fono1);
						array_push($FonoArray,$Fono2);
						$Fail = 1;
						$contar = count($FonoArray);
						$i = 0;
						while($i<$contar){
							$Fono = $FonoArray[$i];
							$i++;
						}
					}
				} else{
					$PrimerDigito = substr($Fono, 0, 1);
					if($PrimerDigito>=4){
						$Fono = "9".$Fono;
						$Fail = 1;
					}
				else{
						$Fono = "2".$Fono;
						$Fail = 1;
					}  
				}
			break;
			case 7:
				if($Codigo){
					$Fono = $Codigo.$Fono;
					$Fail = 1;
				}
			break;
			case 6:
				if($Codigo){
					if($ContarCodigo==2){
						$Fono  = $Codigo."2".$Fono;
						$Fail = 1;
					}elseif($ContarCodigo==1){
						$Fono = "222".$Fono;
						$Fail = 1;
					}else{
						$Fail = 0;
					}
				}
				else
				{
					$Fail = 0;
				}
			break;
			default:
				$Fail = 0;
			break;
		}
		$ToReturn = $Fail;
		if($ImportFonos){
			if($Fail==0){
				if(is_array($Fono)){
					$array[0] ="";
					for ($i=0; $i < count($Fono); $i++) {
						$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}
				}else{
					$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
				}
			}else{
				if(is_array($Fono)){
					for ($i=0; $i < count($Fono); $i++) {
						$array[0] ="";
						if (stripos($Fono[$i], 'NULL') === 0) {
							$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
						}else{
							$array[1] = "('".$Rut."','".$Fono[$i].",'".date("Y-m-d")."','".date("Y-m-d")."'','".date("Y-m-d")."','".date("Y-m-d")."'),";
						}
					}
				}else{
					if (stripos($Fono, 'NULL') === 0) {
						$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}else{
						$array[1] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}
				}
			}
			$ToReturn = $array;
		}
		return $ToReturn;
	}

	function utf8_ArrayConverter($array) {
		array_walk_recursive($array, function(&$item, $key){
			if(!mb_detect_encoding($item, 'utf-8', true)){
				$item = utf8_encode($item);
			}
		});
		return $array;
	}

	function getFocoConfig() {
		$db = new DB();
		$SqlFocoConfig = "SELECT * FROM fireConfig WHERE id = 1 LIMIT 1";
		$FocoConfig = $db->select($SqlFocoConfig);
		return $FocoConfig[0];
	}

	function getSemanasMes($Year,$Month) {
		$DateStrToTime = strtotime($Year.$Month."01");
		$Month = date('m',$DateStrToTime);
		$CantidadDias = date('t',$DateStrToTime);
		$Semanas = array();
		$Semana = "";
		$ContSemanas = 1;
		for($i=1; $i<=$CantidadDias; $i++){
			$Day = $i > 9 ? $i : "0".$i;
			//$DateStrToTime = strtotime($Year.$Month.$Day);
			$Week = date("W",mktime(0,0,0,$Month,$Day,$Year));
			if($Semana != $Week){
				$ArrayTmp = array();
				$ArrayTmp["WeekTxt"] = "Semana ".$ContSemanas;
				$ArrayTmp["Week"] = $Week;
				$Semana = $Week;
				$ContSemanas++;
				$Semanas[$Semana] = array();
				$Semanas[$Semana] = $ArrayTmp;
				//array_push($Semanas,$ArrayTmp);
			}
		}
		/*echo "<pre>";
		print_r($Semanas);
		echo "</pre>";*/
		return $Semanas;
	}
	function limpiarString($string){
        $string = preg_replace("/[áàâãª]/","a",$string);
        $string = preg_replace("/[ÁÀÂÃ]/","A",$string);
        $string = preg_replace("/[éèê]/","e",$string);
        $string = preg_replace("/[ÉÈÊ]/","E",$string);
        $string = preg_replace("/[íìî]/","i",$string);
        $string = preg_replace("/[ÍÌÎ]/","I",$string);
        $string = preg_replace("/[óòôõº]/","o",$string);
        $string = preg_replace("/[ÓÒÔÕ]/","O",$string);
        $string = preg_replace("/[úùû]/","u",$string);
        $string = preg_replace("/[ÚÙÛ]/","U",$string);
        $string = str_replace("%","",$string);
        $string = str_replace("ñ","n",$string);
		$string = str_replace("Ñ","N",$string);
		$string = str_replace("a±","n",$string);

        return $string;
    }
?>
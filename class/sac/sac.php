<?php
 
class sac{
	function __construct(){
		if(!isset($_SESSION)){
			session_start();
		}
	}
	public function seleccioneTipo($tipo){
        $mysqli = new DB();
        if($tipo == 1){
            $query = $mysqli->select("SELECT nombre,rut FROM foco.clientes");
            echo "<select class='selectpicker' id='subTipo' name='cargo' data-live-search='true' data-width='100%'>";
            echo "<option value='0'>Seleccione</option>";
            foreach($query as $row){
                $nombre = $row['nombre'];
                echo "<option value='".$row["rut"]."'>".$nombre."</option>";
   
            }
            echo "</select>";

        }elseif($tipo == 2){
            $query = $mysqli->select("SELECT rut FROM foco.clientes");
            echo "<select class='selectpicker' id='subTipo' name='cargo' data-live-search='true' data-width='100%'>";
            echo "<option value='0'>Seleccione</option>";
            foreach($query as $row){
                $rut = $row['rut'];
                echo "<option value='".$row["rut"]."'>".$rut."</option>";
   
            }
            echo "</select>";

        }elseif($tipo == 3){
            $query = $mysqli->select("SELECT contrato,rut FROM foco.contratos group by contrato");
            echo "<select class='selectpicker' id='subTipo' name='cargo' data-live-search='true' data-width='100%'>";
            echo "<option value='0'>Seleccione</option>";
            foreach($query as $row){
                $rut = $row['rut'];
                $contrato = $row['contrato'];
                $dato = $rut."|".$contrato;
                echo "<option value='".$dato."'>".$contrato."</option>";
   
            }
            echo "</select>";
        }elseif($tipo == 4){
            $query = $mysqli->select("SELECT nombre,contrato,rut FROM foco.contratos group by nombre");
            echo "<select class='selectpicker' id='subTipo' name='cargo' data-live-search='true' data-width='100%'>";
            echo "<option value='0'>Seleccione</option>";
            foreach($query as $row){
                $rut = $row['rut'];
                $contrato = $row['contrato'];
                $nombre = $row['nombre'];
                $dato = $rut."|".$contrato;
                echo "<option value='".$dato."'>".$nombre."</option>";
   
            }
            echo "</select>";
        }
        
    }

    public function subTipo($tipo,$dato){
        $mysqli = new DB();
        if($tipo == 1 || $tipo == 2 || $tipo ==  3 || $tipo == 4){
            $query = "";
            if($tipo == 3 || $tipo == 4){
                $ex = explode("|",$dato);
                $dato = $ex[0];
                $contrato = $ex[1];
                $query = $mysqli->select("SELECT CT.rut as rut,CT.contrato as contrato , CL.nombre as cliente FROM foco.contratos  CT JOIN operaciones.clientes  CL  ON CT.rut = CL.rut WHERE CT.rut = '$dato' and CT.contrato = $contrato group by CT.contrato");
                echo "<select class='selectpicker' id='dato' data-live-search='true' data-width='100%'>";
                foreach($query as $row){
                    $contrato = $row['contrato'];
                    $cliente = $row['cliente'];
                    echo "<option value='".$row["contrato"]."'>".$contrato." - ".$cliente."</option>";
                }
                echo "</select>";
            }else{
                $query = $mysqli->select("SELECT rut,contrato FROM foco.contratos WHERE rut = '$dato' group by contrato");
                echo "<select class='selectpicker' id='dato' data-live-search='true' data-width='100%'>";
                echo "<option value='0'>Seleccione Contrato</option>";
                foreach($query as $row){
                    $contrato = $row['contrato'];
                    echo "<option value='".$row["contrato"]."'>".$contrato."</option>";
                }
                echo "</select>";
            }
        } 
    }

    public function buscar($tipo,$subtipo,$dato){
        $mysqli = new DB();
        if($tipo == 1 || $tipo == 2 || $tipo == 3 || $tipo == 4){
            $query = "";
            if($tipo == 3 || $tipo == 4){
                $ex = explode("|",$subtipo);
                $subtipo = $ex[0];
                $dato = $ex[1];
                $query = $mysqli->select("SELECT fono,nombre as contacto FROM foco.contratos WHERE rut = $subtipo AND contrato = $dato GROUP BY fono");
            }else{
                $query = $mysqli->select("SELECT fono,nombre as contacto FROM foco.contratos WHERE rut = $subtipo AND contrato = $dato GROUP BY fono");
            }
            $i = 1;
            $uno="<table id='llamadaSac' class='display' style='width:100%'>";
            $uno.= "<thead>";
            $uno.="<tr>";
            $uno.="<th>Contacto</th>";
            $uno.="<th>Telefono</th>";
            $uno.="<th>Llamar</th>";
            $uno.="</tr>";
            $uno.="</thead>";
            $uno.="<tbody>";
            foreach($query as $row){
                $fono = $row['fono'];
                $contacto = $row['contacto'];
                $uno.="<tr id='$i'>";
                $uno.="<td class='text-sm'>".$contacto."</td>";
                $uno.="<td class='text-sm'><input type='hidden' id='telefono$i' value'$fono' name='telefono$i'><input type='text' class='telefono_cambiar text6 telefono SoloNumeros' value='$fono'></td>";
                $uno.="<td class='text-sm'><button id='fono$i' class='btn btn-success btn-icon icon-lg fa fa-phone Llamar'  value='Llamar'> </button> </td>";
                $uno.="</tr>";
                $i++;
            }
            $uno.="</tbody>";
            $uno.="</table>";
            echo $uno;

        }else{
            echo "otro tipo";
        }
        
    }

    public function buscarDatos($tipo,$subtipo,$dato){
        $mysqli = new DB();
        if($tipo == 1 || $tipo == 2 || $tipo == 3 || $tipo == 4){
            $queryCliente = "";
            if($tipo == 3 || $tipo == 4){
                $ex = explode("|",$subtipo);
                $subtipo = $ex[0];
                $dato = $ex[1];
                $queryCliente = $mysqli->select("SELECT CL.nombre as nombreCliente,CO.contrato as contrato,CO.nombre as contacto,
                CO.fono as fono,CO.sucursal as sucursal , CO.fecha as fecha FROM foco.`contratos` CO JOIN foco.clientes CL ON CO.rut = CL.rut WHERE CO.contrato = $dato group by CO.contrato"); 
                $query = $mysqli->select("SELECT fono,nombre as contacto FROM foco.contratos WHERE rut = $subtipo AND contrato = $dato GROUP BY fono");
            }else{
                $queryCliente = $mysqli->select("SELECT CL.nombre as nombreCliente,CO.contrato as contrato,CO.nombre as contacto,
                CO.fono as fono,CO.sucursal as sucursal , CO.fecha as fecha FROM foco.`contratos` CO JOIN foco.clientes CL ON CO.rut = CL.rut WHERE CO.contrato = $dato group by CO.contrato");
            }
            $nombreCliente = "";
            $contrato = "";
            $contacto = "";
            $fono = "";
            $sucursal = "";
            $fecha = "";
            foreach($queryCliente as $row){
                $nombreCliente = $row['nombreCliente'];
                $contrato = $row['contrato'];
                $contacto = $row['contacto'];
                $fono = $row['fono'];
                $sucursal = $row['sucursal']; 
                $fecha = $row['fecha']; 
            }
            $dos = "<p><h5>Nombre : <b>".$nombreCliente."</b></h5></p>";
            $dos .= "<p>N° Contrato :<b> ".$contrato."</b></p>";
            $dos .= "<p>Nombre Contacto : ".$contacto."</p>";
            $dos .= "<p>Teléfono Contacto : ".$fono."</p>";
            $dos .= "<p>Sucursal : ".$sucursal."</p>";
            $dos .= "<p>Fecha Ingreso : ".$fecha."</p>";

            echo $dos;

        }else{
            echo "otro tipo";
        }
        
    }

    function getGestion($tipo,$subTipo,$dato){
        $mysqli = new DB();
        $query = "";
        if($tipo == 3 || $tipo == 4){
            $ex = explode("|",$subtipo);
            $subtipo = $ex[0];
            $dato = $ex[1];
            $query = $mysqli->select("SELECT * FROM foco.gestion_ult_trimestre where rut_cliente = $subTipo");
        }else{
            $query = $mysqli->select("SELECT * FROM foco.gestion_ult_trimestre where rut_cliente = $subTipo");
        }    
        $gestion ="<table id='gestiones' class='display' style='width:100%'>";
        $gestion.= "<thead>";
        $gestion.="<tr>";
        $gestion.="<th>Area</th>";
        $gestion.="<th>Rut</th>";
        $gestion.="<th>Telefono</th>";
        $gestion.="<th>Fecha</th>";
        $gestion.="<th>Hora</th>";
        $gestion.="<th>Respuesta</th>";
        $gestion.="<th>Sub respuesta</th>";
        $gestion.="<th>Sub respuesta</th>";
        $gestion.="</tr>";
        $gestion.="</thead>";
        $gestion.="<tbody>";
        foreach($query as $row){
            $area = $row['area'];
            $rut = $row['rut_cliente'];
            $fono = $row['fono_discado'];
            $fecha = $row['fecha_gestion'];
            $hora =  $row['hora_gestion'];
            $n1 = $row['n1'];
            $n2 = $row['n2'];
            $n3 =$row['n3'];

            $gestion.="<tr>";
            $gestion.="<td class='text-sm'>".$area."</td>";
            $gestion.="<td class='text-sm'>".$rut."</td>";
            $gestion.="<td class='text-sm'>".$fono."</td>";
            $gestion.="<td class='text-sm'>".$fecha."</td>";
            $gestion.="<td class='text-sm'>".$hora."</td>";
            $gestion.="<td class='text-sm'>".$n1."</td>";
            $gestion.="<td class='text-sm'>".$n2."</td>";
            $gestion.="<td class='text-sm'>".$n3."</td>";

            $gestion.="</tr>";
        }
        $gestion.="</tbody>";
        $gestion.="</table>";
        echo $gestion;
        echo "";
        
	}

    function insertGestion($tipo,$subTipo,$dato,$tipificacion,$observacion,$fono){
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		$usuario = "Demo01";
		$db = new DB();
		$sqlCreaRegistro = "INSERT INTO operaciones.`gestion`(`usuario`, `nombre`,`origen`, `cliente`, `tipificacion`, 
		`observacion`, `fecha`, `hora`,`destino`,`fono`) VALUES ('$usuario',
        '$usuario','SAC','RUT','$tipificacion','$observacion','$fecha','$hora','RUT','$fono')";
        $db->insert($sqlCreaRegistro);
        //asd
    }
    
    

}
?>

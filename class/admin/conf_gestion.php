<?php
/**
* Clase para configuracion de la pantalla de gestion por Cedente
*/
class ConfGestion //extends Conexion
{
    private $nomTabla;

    function ConfGestion()
    {
        //parent::__construct();
    }
	
	public function configGuardadas()
	{
        $db = new DB();
        //$cedente = $this->cedente=$cedente;
        $sql_num = $db->select("SELECT * FROM Conf_Pantalla_Cedente ");
        if(count($sql_num)>0)
        {
    		echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
    		echo '<thead>';
    		echo '<tr>';
            echo '<th>Nombre Config</th>';
            echo '<th class="min-desktop"><center>Cedente</center></th>';
            echo '<th class="min-desktop"><center>Tabla</center></th>';
            echo '<th class="min-desktop"><center>Campos</center></th>';
            echo '<th class="min-desktop"><center>Columnas</center></th>';
    		echo '</tr>';
    		echo '</thead>';
            echo '<tbody>';
            
            foreach($sql_num as $row4)
            {                  
                echo "<td>".$row4['Nombre_Conf']."</td>";                
                echo '<td><center>';                                                           
                $idCedente = $row4['Id_Cedente'];
                $sql_ced = $db->select("SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '$idCedente'");
                foreach($sql_ced as $row) {
                    echo $row['Nombre_Cedente'];
                }
                echo '</center></td>';
                echo "<td><center>".$row4["Nombre_Tabla"]."</center></td>";
				echo "<td><center>".$row4["Nombre_Campos"]."</center></td>";
				echo "<td><center>".$row4["Nombre_Columnas"]."</center></td>";
                echo "<td><center><button type='button' class='btn ' data-toggle='modal' data-target='#dataDelete' data-id='$row4[0]'><i class='fa fa-trash'></i> </button></center></td>";
                echo '</tr>';
            } 
            echo '</tbody>';
    		echo '</table>';   
    	}
        else 
        {
            echo "No hay Configuraciones creadas en la BD ";
        }    
	}

	public function listarCamposTablaold($tabla)
	{
        $db = new DB();
        $this->nomTabla = $tabla;
        $sql_num = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '" . $this->nomTabla ."'");
        echo "<select class='selectpicker' id='campo'  name='campo'>";
        foreach((array) $sql_num as $row)
        {
        	echo "<option value='".$row['Field']."'>" .$row['Field']."</option>";
        }
        echo "</select>";
	}

    public function listarCamposTabla($tabla)
    {
        $db = new DB();
        $this->nomTabla = $tabla;
        $sql_num = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '" . $this->nomTabla ."'");
        echo "<select class='selectpicker' id='campo'  name='campo'>";
        foreach((array) $sql_num as $row)
        {
            echo "<option value='".$row['Field']."'>" .$row['Field']."</option>";
        }
        echo "</select>";
    }

    public function guardarConfigGestion($campos1)
    {
        $db = new DB();
        $campos = json_decode($campos1);
        //$campos2 = json_decode($_POST['data']);
        $total = count($campos);
        $esID = 0;
        $resp = 0;
        $sw1 = 0;
        $Nombre_Conf = "";
        $Id_Cedente = "";
        $Nombre_Tabla = "";
        $Descripcion_Consulta= "";
        $Nombre_Campos = "";
        $Nombre_Columnas = "";

        foreach($campos as $obj){
            //Para campos fijos 
            if($sw1 == 0 ) {
                $Nombre_Conf = $obj->NomConfig;
                $Id_Cedente = $obj->idCedente;;
                $Nombre_Tabla = $obj->nomTabla;                
                $sw1 = 1;
            }
            $Nombre_Campos .= $obj->campo . ",";
            $Nombre_Columnas .= $obj->nombreE . ",";
        }
        //Armar consulta
        $Nombre_Campos = trim($Nombre_Campos, ',');
        $Nombre_Columnas = trim($Nombre_Columnas, ',');
        $Descripcion_Consulta = "SELECT ". $Nombre_Campos . " FROM ". $Nombre_Tabla; 

        $strSql = "INSERT INTO Conf_Pantalla_Cedente (Nombre_Conf,Id_Cedente,Nombre_Tabla,Descripcion_Consulta,Nombre_Campos,Nombre_Columnas) values ('$Nombre_Conf','$Id_Cedente','$Nombre_Tabla','$Descripcion_Consulta','$Nombre_Campos','$Nombre_Columnas' )";
        //$strSql = addslashes($strSql);
        
        try {    
            if ($db->query($strSql) === TRUE) {
                $resp = 0;
            } else {
                echo "Error creating table ";
                $resp = 1;
            }
            $resp = 0;
        } catch (Exception $e) {
            $mensj = "Caught exception: ".  $e->getMessage() ;
            $resp = 1;
        }        
        return $resp;
    }

    public function eliminarConfig($id)
    {
        $db = new DB();
        $resultado = $db->query("DELETE FROM Conf_Pantalla_Cedente WHERE Id_Conf = '$id' ");
        return 0;
    }
}
 ?>
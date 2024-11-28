
<?php

require_once __DIR__.'/../db/DB.php';
require_once __DIR__.'/../logs.php';

class Estrategia
{
	private $db;
	private $logs;
	private $id;
	private $nombre;
	private $idc;
	private $idv;
	private $tablas;
	private	$id_estrategia;
	private $columnas;
	private $logica;
	private $valor;
	private $siguiente_nivel;
	private $nombre_nivel;
	private $id_clases;
	private $cedente;
	private $estrategia;
	private $color;
	private $tipo_contacto;
	private $dias;
	private $cant1;
	private $cond1;
	private $cant2;
	private $cond2;
	private $w;
	private $mundo;
	private $nombre_estrategia;
	private $tipo_estrategia;
	private $comentario;
	private $fecha;
	private $hora;
	private $usuario;
	private $idUsuario;
	private $id_prioridad;
	private $valor_prioridad;
	private $id_com;
	private $valor_com;
	private $id_cola;
	private $valor_cola;
	private $lista;
	private $periodo;

	function __construct()
	{
		$this->db = new Db();
		$this->logs = new Logs();
		if(!isset($_SESSION))	session_start();
	}

	public function asignarColumnas($id)
	{
		$this->id = (int) $id;
	}

	public function mostrarColumnas()
	{
		$db = new DB();
		$query = $db->select("SELECT id, columna, alias FROM SIS_Columnas WHERE id_tabla = {$this->id} AND view = 1");
		echo "<select name='columnas' id='columnas' class='select1' >";
		echo "<option value='0'>Seleccione Columna</option>";
		foreach((array) $query as $row) {
			$id1 = (int) $row["id"];
			$columna = $row["columna"];
			if(!is_null($row["alias"]) && !empty($row["alias"])) $columna = $row["alias"];
			echo "<option value='$id1'>$columna</option>";
		}
		echo "</select>";
	}

	public function asignarLogica($idc)
	{
		$this->idc = (int) $idc;
	}

	public function mostrarLogica()
	{
		$db = new DB();
		$columnas = $db->select("SELECT columna, logica FROM SIS_Columnas WHERE id = {$this->idc}");
		$logic = '<select name="logica" class="select1" id="logica">';
		$logic .= '<option value="0"> -- Seleccione -- </option>';
		foreach((array) $columnas as $row){
			$columna = $row["columna"];
			$logica  = $row["logica"];
		  if($logica == 0) {
				$logic .= '<option value="<">Menor</option>';
				$logic .= '<option value=">">Mayor</option>';
				$logic .= '<option value="=">Igual</option>';
				$logic .= '<option value="<=">Menor o Igual</option>';
				$logic .= '<option value=">=">Mayor o Igual</option>';
				$logic .= '<option value="!=">Distinto</option></select>';
			} else {		  	
				$logic .= "<option value='='>Igual</option>";
				$logic .= "<option value='!=''>Distinto</option></select>";
			}
		}
		$logic .= '</select>';
		echo $logic;
	}

	public function asignarValor($idv)
	{
		$this->idv = (int) $idv;
	}

	public function mostrarValor()
	{
		$db = new DB();
		$qc = "SELECT tipo_dato,columna,relacion,orden,id_tabla,nombre_nulo FROM SIS_Columnas WHERE id = {$this->idv}";
		$this->logs->debug($qc);
		$columnas = $db->select($qc);
		$this->logs->debug($columnas);
		foreach((array) $columnas as $row) {
		    $tipo_dato = (int) $row["tipo_dato"];
		    $columna = $row["columna"];
		    $relacion = $row["relacion"];
		    $orden = $row["orden"];
		    $tabla = $row["id_tabla"];
		    $nombre_nulo = $row["nombre_nulo"];
		    $sql_tablas = $db->select("SELECT Nombre FROM SIS_Tablas WHERE id = {$tabla}");
		    foreach((array) $sql_tablas as $row) {
		      $tablas = $row["Nombre"];
		    }
		    //============================================================================================================
		    //Valor Entero
		    //============================================================================================================
		    if($tipo_dato==0) {
		      echo '<input type="number" name="valor" placeholder="  Ingrese Valor" id="valor" class="text1" required>';
		    }
		    //============================================================================================================
		    //Valor Fecha
		    //============================================================================================================
		    else if($tipo_dato==1) {
		      echo '<input type="date" name="valor" required placeholder="  Ingrese Valor" id="valor" class="text1" >';
		    }
		    //============================================================================================================
		    //Valor Varchar
		    //============================================================================================================
		    else if ($tipo_dato==3) {
		      echo '<input type="text" name="valor" placeholder="  Ingrese Valor" id="valor" class="text1" >';
		    }
		    //============================================================================================================
		    //Valor Distinto
		    //============================================================================================================
		    else if ($tipo_dato==4) {
		        echo '<select multiple="multiple" name="valor" id="valor" data-width="100%">';
		        $result = $db->select("SELECT {$columna} FROM {$tablas} GROUP BY {$columna}");
		        foreach((array) $result as $row) {
		          $valor = $row[$columna];
		          if($valor == NULL) {
		            echo "<option value='$valor'>&nbsp;$nombre_nulo</option>";
		          } else {
		            echo "<option value='$valor'>&nbsp;$valor</option>";
		          }
		        }
		        echo '</select>';
						echo '<script src="../../../js/multiple.js"></script>';
						echo '<script>';
            echo "$('#valor').multipleSelect({";
            echo 'isOpen: true,';
            echo 'keepOpen: true';
            echo '});';
            echo '</script>';
		    }
		    //============================================================================================================
		    //Relacion con Otra Tabla
		    //============================================================================================================
		    else if ($tipo_dato == 5) {
		      echo '<select multiple="multiple" name="valor" id="valor" data-width="100%">';
					$strQuery = "SELECT * FROM $relacion GROUP BY $columna ORDER BY $orden ASC";
					$this->logs->debug($strQuery);
		      $result=$db->select($strQuery);
		      foreach((array) $result as $row){
		        echo "<option value='$row[0]'>&nbsp;".utf8_encode($row[1])."</option>";
		      }
		      echo '</select>';
					echo '<script src="../../../js/multiple.js"></script>';
					echo '<script>';
          echo "$('#valor').multipleSelect({";
          echo 'isOpen: true,';
          echo 'keepOpen: true';
          echo '});';
          echo '</script>';
		    }
		}
	}

	public function asignarRelacion($tablas,$id_estrategia,$columnas,$logica,$valor,$siguiente_nivel,$nombre_nivel,$id_clases,$cedente)
	{
		$this->tablas=$tablas;
		$this->id_estrategia = (int) $id_estrategia;
		$this->columnas = $columnas;
		$this->logica = $logica;
		$this->valor = $valor;
		$this->siguiente_nivel = $siguiente_nivel;
		$this->nombre_nivel = $nombre_nivel;
		$this->id_clases = $id_clases;
		$this->cedente = (int) $cedente;
	}

	public function mostrarRelacion()
	{
		$db = new DB();
		//--------------------TABLAS----------------------
		$sql=$db->select("SELECT * FROM SIS_Tablas WHERE id = {$this->tablas}");
		foreach((array) $sql as $row){
		  $tablas = $row["nombre"];
		  $rel = $row["relacion"];
		}
		//--------------------COLUMNAS Y TIPO DE DATO : 0 INT - DATE, 1 STRING----------------------
		$sql=$db->select("SELECT columna,tipo,nulo FROM SIS_Columnas WHERE id = {$this->columnas}");
		foreach((array) $sql as $row){
		  $columnas=$row["columna"];
		  $tipo = $row["tipo"];
		  $nulo = $row["nulo"];
		}
		$valor = $this->valor;
		//-----------------------Creacion de Querys Dinamicas-------------------

		if ($this->logica == "!=")	{
			$this->logica = "=";
			$constante = "SELECT Rut FROM Persona WHERE NOT Rut IN ";
		  $constanteNot = "SELECT Rut FROM Persona WHERE Rut IN ";
		  $constanteDeuda = "SELECT Persona.Rut,Deuda.Deuda FROM Persona,Deuda WHERE NOT Persona.Rut IN ";
		  $constanteDeudaNot = "SELECT Persona.Rut,Deuda.Deuda FROM Persona,Deuda WHERE Persona.Rut IN ";
		}	else {
			$constante = "SELECT Rut FROM Persona WHERE Rut IN ";
		  $constanteNot = "SELECT Rut FROM Persona WHERE NOT Rut IN ";
		  $constanteDeuda = "SELECT Persona.Rut,Deuda.Deuda FROM Persona,Deuda WHERE Persona.Rut IN ";
		  $constanteDeudaNot = "SELECT Persona.Rut,Deuda.Deuda FROM Persona,Deuda WHERE NOT Persona.Rut IN ";
		}

		$or_array = explode(",", $valor);
		$or_count = count((array) $or_array);
		$or_query = '';
		if($or_count>0) {
			$m=0;
			while($m<$or_count)	{
						if ($tipo==0)	{
							$or_query = $columnas." ".$this->logica." ".$or_array[$m]." OR ".$or_query;
						}	else {
							$or_query = $columnas." ".$this->logica." ".'"'.$or_array[$m].'"'." OR ".$or_query;
						}
						$m++;
					}
					$or_query = substr($or_query, 0, -4);
					if($rel == 0) {
						$subQuery = "(SELECT Rut FROM $tablas WHERE $or_query) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $or_query) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
					}	else if($rel == 1)	{
						$subQuery = "(SELECT Rut FROM $tablas WHERE $or_query AND Id_Cedente = $this->cedente)  AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $or_query AND Id_Cedente = $this->cedente) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
					}	else	{
						$subQuery = "(SELECT Rut FROM $tablas WHERE $or_query AND FIND_IN_SET('".$this->cedente."',Id_Cedente))  AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $or_query AND FIND_IN_SET('".$this->cedente."',Id_Cedente)) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
					}
				}	else{
					if($rel == 0)	{
						$subQuery = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica $valor ) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica  $valor ) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1 ";
					}	else if($rel == 1)	{
						$subQuery = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica $valor AND Id_Cedente = $this->cedente) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica  $valor AND Id_Cedente = $this->cedente) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1 ";
					}	else	{
						$subQuery = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica $valor AND FIND_IN_SET('".$this->cedente."',Id_Cedente)) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
						$subQueryDeuda = "(SELECT Rut FROM $tablas WHERE $columnas $this->logica  $valor AND FIND_IN_SET('".$this->cedente."',Id_Cedente)) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1 ";
					}
				}
		//-----------------------QUERY 1-------------------
				$query1 = $constante.$subQuery;
				$query2 = $constanteNot.$subQuery;
		    $matriz1 = $constante;
				$matrizDeuda1 = $constanteDeuda;
				$matriz2 = $constanteNot;
				$matrizDeuda2 = $constanteDeudaNot;
		    $queryDeuda = $constanteDeuda.$subQueryDeuda;
		    $queryDeudaNot = $constanteDeudaNot.$subQueryDeuda;

		    $query_1=$db->select($query1);
				$monto_1 = 0;
		    foreach((array) $query_1 as $row2){
					$a = $row2['Rut'];
				}
		    $numero = count((array) $query_1);
		    $numero = number_format($numero, 0, "", ".");
		    $monto1 = $db->select($queryDeuda);
		    foreach((array) $monto1 as $row){
					$monto_1 = $monto_1 + $row['Deuda'];
				}
		    $monto_1 = '$  '.number_format($monto_1, 0, "", ".");
				//-----------------------QUERY 2-------------------
		    $query_2=$db->select($query2);
				$monto_2 = 0;
		    foreach((array) $query_2 as $row2){
					$a = $row2['Rut'];
				}
		    $numero2 = count((array) $query_2);
		    $numero2 = number_format($numero2, 0, "", ".");
		    $monto2 = $db->select($queryDeudaNot);
		    foreach((array) $monto2 as $row){
					$monto_2= $monto_2 + $row['Deuda'];
				}
		    $monto_2 = '$  '.number_format($monto_2, 0, "", ".");

				$subQuery = addslashes($subQuery);
				$query1 = addslashes($query1);
				$query2 = addslashes($query2);
				$queryDeuda = addslashes($queryDeuda);
				$queryDeudaNot = addslashes($queryDeudaNot);

				$db->query("INSERT INTO SIS_Querys(query,id_estrategia,cantidad,monto,cola,columna,condicion,matriz,matriz_deuda,Id_Cedente,query_deuda) VALUES('$query1',$this->id_estrategia,'$numero','$monto_1','$this->nombre_nivel','$subQuery','','$matriz1','$matrizDeuda1',$this->cedente,'$queryDeuda')");
				$db->query("INSERT INTO SIS_Querys(query,id_estrategia,cantidad,monto,cola,columna,condicion,matriz,matriz_deuda,Id_Cedente,query_deuda) VALUES('$query2',$this->id_estrategia,'$numero2','$monto_2','No Seleccionado','$subQuery','NOT','$matriz2','$matrizDeuda2',$this->cedente,'$queryDeudaNot')");

				$query_id1 = $db->select("SELECT id FROM SIS_Querys_Estrategias WHERE query = '{$query1}' AND id_estrategia = {$this->id_estrategia}");
				$query_id2 = $db->select("SELECT id FROM SIS_Querys_Estrategias WHERE query = '{$query2}' AND id_estrategia = {$this->id_estrategia}");
				foreach((array) $query_id1 as $row) {
					$id1=$row['id'];
				}

				foreach((array) $query_id2 as $row) {
					$id2 = $row['id'];
				}
				$array = array('first' => "<tr id='$id1'><td><i class='psi-folder-open' id='b$id1'  style='display: none;'></i> $this->nombre_nivel</td><td><center>$numero</center></td><td><center>$monto_1</center></td><td><center><select class='cambiar_prioridadjs' id='p$id1'><option value=1>Sin Prioridad</option><option value=2>Baja+</option><option value=3>Baja++</option><option value=4>Media+</option><option value=5>Media++</option><option value=6>Alta+</option><option value=7>Alta++</option></select></center></td><td><center><a class='subestrategia'  id='d$id1'  href='#'><i class='fa fa-sitemap'></i></a> </center></td><td><center><a   href='test2.php?id=$id1'><i class='psi-download-from-cloud'></i></a> </center></td></tr><tr id='$id2'><td><i class='psi-folder-open' id='b$id2'  style='display: none;'></i> No Seleccionado</td><td><center>$numero2</center></td><td><center>$monto_2</center></td><td><center><select class='cambiar_prioridadjs' id='p$id2'><option value=1>Sin Prioridad</option><option value=2>Baja+</option><option value=3>Baja++</option><option value=4>Media+</option><option value=5>Media++</option><option value=6>Alta+</option><option value=7>Alta++</option></select></center></td><td><center><a href='#' class='subestrategia' id='d$id2'><i class='fa fa-sitemap'></i></a></center></td><td><center><a   href='test2.php?id=$id2'><i class='psi-download-from-cloud'></i></a> </center></td></tr>", 'second' => "<input type='hidden' value='$id1' id='id_clases' name='id_clases'>");
				echo json_encode($array);
	}

	public function mostrarRelacionDos()
	{
		$db = new DB();
		$sql=$db->select("SELECT  * FROM SIS_Tablas WHERE id = {$this->tablas}");
		foreach((array) $sql as $row){
			$tablas = $row["nombre"];
			$rel = $row["relacion"];
		}

		$sqlColumnas = $db->select("SELECT columna, tipo, nulo FROM SIS_Columnas WHERE id = {$this->columnas}");
		foreach((array) $sqlColumnas as $row){
			$columnasQuery = $row["columna"];
			$tipo = $row["tipo"];
			$nulo = $row["nulo"];
		}

		//==================================================================================================================
		// Si Tipo == 0 es un INT y si es 1 es de tipo STRING
		//==================================================================================================================
		$valor = $this->valor;
		$id_subquery=$this->siguiente_nivel;
		$id_subquery_inicial=$this->siguiente_nivel;
		$id_estrategia = $this->id_estrategia;
		$nivel = $this->siguiente_nivel;

		//==================================================================================================================
		// Colsulta Condicion NOT o en blanco  al principio de la Query Estrategia
		//==================================================================================================================
		$queryCondicion=$db->select("SELECT condicion,matriz,matriz_deuda FROM SIS_Querys WHERE id = {$id_subquery} AND id_estrategia = {$this->id_estrategia}");
		foreach((array) $queryCondicion as $row3) {
		  $condicionFinal = $row3["condicion"];
			$constante= $row3["matriz"];
		  $constanteDeuda= $row3["matriz_deuda"];
		}

		//==================================================================================================================
		// Creacion de Querys Dinamicas QUERY 1
		//==================================================================================================================
		$r = 0;
		if ($this->logica == "!=") {
			$r = 1;
		}	else {
			$r = 0;
		}

		$count = 0;
		$condicion_x = '';
		$or_array = explode(",", $valor);
		$or_count = count((array) $or_array);
		$or_query = '';
		if($or_count > 0) {
			$m=0;
			while($m<$or_count)	{
				if ($tipo == 0) 	{
					if($r == 1)	{
						$or_query = $columnasQuery." "."="." ".$or_array[$m]." OR ".$or_query;
					}	else {
						$or_query = $columnasQuery." ".$this->logica." ".$or_array[$m]." OR ".$or_query;
					}
				}	else {
					if($r == 1)	{
						$or_query = $columnasQuery." "."="." ".'"'.$or_array[$m].'"'." OR ".$or_query;
					}	else{
						$or_query = $columnasQuery." ".$this->logica." ".'"'.$or_array[$m].'"'." OR ".$or_query;
					}
				}
				$m++;
			}

			$or_query = substr($or_query, 0, -4);
			if($rel == 0) {
				$columnas = "(SELECT Rut FROM $tablas WHERE $or_query) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $or_query) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			} else if($rel == 1) {
				$columnas = "(SELECT Rut FROM $tablas WHERE $or_query AND Id_Cedente = $this->cedente)  AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $or_query AND Id_Cedente = $this->cedente) AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			}	else {
				$columnas = "(SELECT Rut FROM $tablas WHERE $or_query AND FIND_IN_SET('".$this->cedente."',Id_Cedente))  AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $or_query AND FIND_IN_SET('".$this->cedente."',Id_Cedente))  AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			}
		} else {
			if($rel == 0)	{
				$columnas = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor ) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor )  AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			} else if($rel == 1){
				$columnas = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor AND Id_Cedente = $this->cedente) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor AND Id_Cedente = $this->cedente)  AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			} else {
				$columnas = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor AND FIND_IN_SET('".$this->cedente."',Id_Cedente)) AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
				$columnasDeuda = "(SELECT Rut FROM $tablas WHERE $columnasQuery $this->logica $valor AND FIND_IN_SET('".$this->cedente."',Id_Cedente))  AND Persona.Rut = Deuda.Rut AND FIND_IN_SET('".$this->cedente."',Persona.Id_Cedente) AND Persona.con_deudas = 1";
			}
		}

		if($r == 1)	{
		  $matriz=" AND NOT Rut IN ";
		  $matrizNot=" AND Rut IN ";
		  $matrizDeuda=" AND NOT Persona.Rut IN ";
		  $matrizDeudaNot=" AND  Persona.Rut IN ";
		}	else{
			$matriz =" AND Rut IN ";
			$matrizNot = " AND NOT Rut IN ";
			$matrizDeuda=" AND Persona.Rut IN ";
			$matrizDeudaNot=" AND NOT Persona.Rut IN ";
		}

		$condicion = $matriz.$columnas;
		$array_central = [];
		array_push($array_central, $condicion);
		$query_armar = $db->select("SELECT id_subquery,matriz, columna FROM SIS_Querys WHERE  id=$id_subquery_inicial AND id_estrategia=$this->id_estrategia");
		foreach((array) $query_armar as $row) {
			$id_subquery = $row["id_subquery"];
			$matriz_armar = $row["matriz"];
			$columna_armar = $row["columna"];
			$count++;
		  if ($id_subquery==0) {
				$condicion=$matriz_armar.$columna_armar;
				array_push($array_central, $condicion);
		  } else {
				$condicion=$matriz_armar.$columna_armar;
				array_push($array_central, $condicion);
		  }

		  while($id_subquery!=0) {
				$queryb=$db->select("SELECT id_subquery,matriz ,columna FROM SIS_Querys WHERE id=$id_subquery AND id_estrategia=$this->id_estrategia");
				foreach((array) $queryb as $row) {
					$id_subquery = $row["id_subquery"];
					$matriz_armar = $row["matriz"];
					$columna_armar = $row["columna"];
					$count++;
					if ($id_subquery==0) {
						$condicion = $matriz_armar.$columna_armar;
						array_push($array_central, $condicion);
					}	else {
						$condicion = $matriz_armar.$columna_armar;
						array_push($array_central, $condicion);
					}
				}
		  }
		}

		$count = count((array) $array_central);
		$i=0;
		$k=$count-1;
		$subQuery='';
		while($i<$count) {
		  $subQuery = $subQuery.$array_central[$k];
		  $i++;
		  $k--;
		}

		//____________________________________________________________________________________________________________________
		$condicion_not = $matrizNot.$columnas;
		$array_central_not = array();
		array_push($array_central_not, $condicion_not);
		$query_armar_not=$db->select("SELECT id_subquery,matriz, columna FROM SIS_Querys WHERE  id=$id_subquery_inicial AND id_estrategia=$this->id_estrategia");
		foreach((array) $query_armar_not as $row){
			$id_subquery = $row["id_subquery"];
			$matriz_armar = $row["matriz"];
			$columna_armar = $row["columna"];
			$count++;
		  if ($id_subquery==0) {
				$condicion_not=$matriz_armar.$columna_armar;
				array_push($array_central_not, $condicion_not);
		  } else{
				$condicion_not=$matriz_armar.$columna_armar;
				array_push($array_central_not, $condicion_not);
	  	}
	  	while($id_subquery!=0)	{
				$querybnot=$db->select("SELECT id_subquery,matriz ,columna FROM SIS_Querys WHERE id=$id_subquery AND id_estrategia=$this->id_estrategia");
				foreach((array) $querybnot as $row){
			  	$id_subquery = $row["id_subquery"];
					$matriz_armar = $row["matriz"];
					$columna_armar = $row["columna"];
			 		$count++;
			  	if ($id_subquery==0) {
						$condicion_not=$matriz_armar.$columna_armar;
						array_push($array_central_not, $condicion_not);
			  	}	else{
						$condicion_not=$matriz_armar.$columna_armar;
						array_push($array_central_not, $condicion_not);
			  	}
				}
		  }
		}

		$count = count((array) $array_central_not);
		$i=0;
		$k=$count-1;
		$subQueryNot='';
		while($i<$count)
		{
		  $subQueryNot = $subQueryNot.$array_central_not[$k];
		  $i++;
		  $k--;
		}

		$query2 = $subQueryNot;
		$query_2=$db->select($query2);
		foreach((array) $query_2 as $row){
		    $a=$row['Rut'];
		}
		$numero2 = count((array) $query_2);
		$numero2 = number_format($numero2, 0, "", ".");

        //____________________________________________________________________________________________________________________
		$count = 0;
		$condicion_deuda = $matrizDeuda.$columnasDeuda;
		$array_central_deuda = array();
		array_push($array_central_deuda, $condicion_deuda);
		$query_armar_deuda=$db->select("SELECT id_subquery,matriz_deuda, columna FROM SIS_Querys WHERE  id=$id_subquery_inicial AND id_estrategia=$this->id_estrategia");
		foreach((array) $query_armar_deuda as $row4){
			$id_subquery = $row4["id_subquery"];
			$matriz_armar = $row4["matriz_deuda"];
			$columna_armar = $row4["columna"];
			$count++;
		  	if ($id_subquery==0)
		  	{
				$condicion_deuda=$matriz_armar.$columna_armar;
				array_push($array_central_deuda, $condicion_deuda);
		  	}
		  	else
		  	{

				$condicion_deuda=$matriz_armar.$columna_armar;
				array_push($array_central_deuda, $condicion_deuda);

		  	}
		  	while($id_subquery!=0)
		  	{
				$querybdeuda=$db->select("SELECT id_subquery,matriz_deuda ,columna FROM SIS_Querys WHERE id=$id_subquery AND id_estrategia=$this->id_estrategia");
				foreach((array) $querybdeuda as $row){
			  		$id_subquery = $row["id_subquery"];
					$matriz_armar = $row["matriz_deuda"];
					$columna_armar = $row["columna"];
			 		$count++;
			  		if ($id_subquery==0)
			  		{
						$condicion_deuda=$matriz_armar.$columna_armar;
						array_push($array_central_deuda, $condicion_deuda);
			  		}
			  		else
			  		{

						$condicion_deuda=$matriz_armar.$columna_armar;
						array_push($array_central_deuda, $condicion_deuda);
			  		}
				}
		  }
		}

		$count2 = count((array) $array_central_deuda);
		$i1=0;
		$ka=$count2-1;
		$subQueryDeuda='';
		while($i1<$count2)
		{
		  $subQueryDeuda = $subQueryDeuda.$array_central_deuda[$ka];
		  $i1++;
		  $ka--;
		}

		$query1 = $subQuery;
		$query_1=$db->select($query1);
		foreach((array) $query_1 as $row2){
		    $a=$row2['Rut'];
		}
		$numero = count((array) $query_1);
		$numero = number_format($numero, 0, "", ".");


		$queryDeuda = $subQueryDeuda;
		$monto1 = $db->select($queryDeuda);
		$monto_1 = 0;
		foreach((array) $monto1 as $row){
		    $monto_1= $monto_1 + $row['Deuda'];
		}
		$monto_1 = '$  '.number_format($monto_1, 0, "", ".");

		$count = 0;
		$condicion_deuda_not = $matrizDeudaNot.$columnasDeuda;
		$array_central_deuda_not = array();
		array_push($array_central_deuda_not, $condicion_deuda_not);
		$query_armar_deuda_not=$db->select("SELECT id_subquery,matriz_deuda, columna FROM SIS_Querys WHERE  id=$id_subquery_inicial AND id_estrategia=$this->id_estrategia");
		foreach((array) $query_armar_deuda_not as $row){
			$id_subquery = $row["id_subquery"];
			$matriz_armar = $row["matriz_deuda"];
			$columna_armar = $row["columna"];
			$count++;
		  	if ($id_subquery==0)
		  	{
				$condicion_deuda_not=$matriz_armar.$columna_armar;
				array_push($array_central_deuda_not, $condicion_deuda_not);
		  	}
		  	else
		  	{

				$condicion_deuda_not=$matriz_armar.$columna_armar;
				array_push($array_central_deuda_not, $condicion_deuda_not);

		  	}
		  	while($id_subquery!=0)
		  	{
				$querybdeuda_not=$db->select("SELECT id_subquery,matriz_deuda ,columna FROM SIS_Querys WHERE id=$id_subquery AND id_estrategia=$this->id_estrategia");
				foreach((array) $querybdeuda_not as $row){
			  		$id_subquery = $row["id_subquery"];
					$matriz_armar = $row["matriz_deuda"];
					$columna_armar = $row["columna"];
			 		$count++;
			  		if ($id_subquery==0)
			  		{
						$condicion_deuda_not=$matriz_armar.$columna_armar;
						array_push($array_central_deuda_not, $condicion_deuda_not);
			  		}
			  		else
			  		{

						$condicion_deuda_not=$matriz_armar.$columna_armar;
						array_push($array_central_deuda_not, $condicion_deuda_not);
			  		}
				}
		  }
		}

		$count2 = count((array) $array_central_deuda_not);
		$i1=0;
		$ka=$count2-1;
		$subQueryDeudaNot='';
		while($i1<$count2)
		{
		  $subQueryDeudaNot = $subQueryDeudaNot.$array_central_deuda_not[$ka];
		  $i1++;
		  $ka--;
		}
		$queryDeudaNot = $subQueryDeudaNot;
		$monto2 = $db->select($queryDeudaNot);
		$monto_2 = 0;
		foreach((array) $monto2 as $row){
		    $monto_2= $monto_2 + $row['Deuda'];
		}
		$monto_2 = '$  '.number_format($monto_2, 0, "", ".");

		//==================================================================================================================
		//==================================================================================================================
		// Algotimo de Generacion de Espacios
		//==================================================================================================================
		$query_espacios=$db->select("SELECT espacios FROM SIS_Querys WHERE id='$nivel' AND id_estrategia='$this->id_estrategia' LIMIT 1");
		foreach((array) $query_espacios as $row){
			$num_espacios=$row["espacios"];
		}
		$num_espacios=$num_espacios+1;
		$espacios_total=$num_espacios*5;
		$espacios1='&nbsp;';
		$i=1;
		$espacios = '';
		while($i<$espacios_total) {
		  $espacios = $espacios.$espacios1;
		  $i++;
		}

		$columnas = addslashes($columnas);
		$matriz = addslashes($matriz);
		$matrizDeuda = addslashes($matrizDeuda);
		$query1 = addslashes($query1);
		$query2 = addslashes($query2);
		$queryDeuda = addslashes($queryDeuda);
		$queryDeudaNot = addslashes($queryDeudaNot);
		$matrizNot = addslashes($matrizNot);
		$matrizDeudaNot = addslashes($matrizDeudaNot);
		//$query2 = addslashes($query2);
		//==================================================================================================================
		// Guardar Querys
		//==================================================================================================================
		$db->query("UPDATE SIS_Querys SET carpeta=1,sub=0,eliminar=0 WHERE id='$this->siguiente_nivel' AND id_estrategia='$this->id_estrategia'");
		$db->query("INSERT INTO SIS_Querys(query,id_estrategia,cantidad,id_subquery,monto,cola,columna,condicion,matriz,matriz_deuda,espacios,Id_Cedente,query_deuda) VALUES('$query1','$this->id_estrategia','$numero','$this->siguiente_nivel','$monto_1','$this->nombre_nivel','$columnas','','$matriz','$matrizDeuda','$num_espacios',$this->cedente,'$queryDeuda')");
		$db->query("INSERT INTO SIS_Querys(query,id_estrategia,cantidad,id_subquery,monto,cola,columna,condicion,matriz,matriz_deuda,espacios,Id_Cedente,query_deuda) VALUES('$query2','$this->id_estrategia','$numero2','$this->siguiente_nivel','$monto_2','No Seleccionado','$columnas','NOT','$matrizNot','$matrizDeudaNot','$num_espacios',$this->cedente,'$queryDeudaNot')");

		//==================================================================================================================
		// ID para Metodo AJAX
		//==================================================================================================================
		$query_id1=$db->select("SELECT id FROM SIS_Querys WHERE query='$query1' AND id_estrategia='$this->id_estrategia'");
		$query_id2=$db->select("SELECT id FROM SIS_Querys WHERE query='$query2' AND id_estrategia='$this->id_estrategia'");
		foreach((array) $query_id1 as $row){
			$id1=$row['id'];
		}
		foreach((array) $query_id2 as $row) {
			$id2=$row['id'];
		}

		//==================================================================================================================
		// Tablas para Metodo AJAX
		//==================================================================================================================
		$array = array('uno' => "<tr id='$id1'><td>$espacios<i class='fa fa-folder-open' id='b$id1'  style='display: none;'></i> $this->nombre_nivel</td><td><center>$numero</center></td><td><center>$monto_1</center></td><td><center><select class='cambiar_prioridadj$this->id_clases' id='p$id1'><option value=1>Sin Prioridad</option><option value=2>Baja+</option><option value=3>Baja++</option><option value=4>Media+</option><option value=5>Media++</option><option value=6>Alta+</option><option value=7>Alta++</option></select></center></td><td><center><a href='#' class='subestrategia$this->id_clases' ><i class='fa fa-sitemap' id='d$id1'></i></a></center></td><td><center><a   href='test2.php?id=$id1'><i class='psi-download-from-cloud'></i></a> </center></td></tr><tr id='$id2'><td>$espacios <i class='fa fa-folder-open' id='b$id2'  style='display: none;'></i> No Seleccionado</td><td><center>$numero2</center></td><td><center>$monto_2</center></td><td><center><select class='cambiar_prioridadj$this->id_clases' id='p$id2'><option value=1>Sin Prioridad</option><option value=2>Baja+</option><option value=3>Baja++</option><option value=4>Media+</option><option value=5>Media++</option><option value=6>Alta+</option><option value=7>Alta++</option></select></center></td><td><center><a href='#' class='subestrategia$this->id_clases' ><i class='fa fa-sitemap' id='d$id2'></i></a></center></td><td><center><a   href='test2.php?id=$id2'><i class='psi-download-from-cloud'></i></a> </center></td></tr>", 'dos' => "<input type='hidden' value='$id1' id='id_clases' name='id_clases'>", 'tres' => "$error");
		echo json_encode($array);
	}

	public function crearEstrategia($nombre_estrategia,$tipo_estrategia,$comentario,$fecha,$hora,$usuario,$cedente,$idUsuario)
	{
		$db = new DB();
		$this->nombre_estrategia=$nombre_estrategia;
		$this->tipo_estrategia=$tipo_estrategia;
		$this->comentario=$comentario;
		$this->fecha=$fecha;
		$this->hora=$hora;
		$this->usuario=$usuario;
		$this->cedente=$cedente;
		$this->idUsuario=$idUsuario;

		$query=$db->query("INSERT INTO SIS_Estrategias(nombre,comentario,fecha,hora,usuario,tipo,Id_Cedente,Id_Usuario) VALUES('$this->nombre_estrategia','$this->comentario','$this->fecha','$this->hora','$this->usuario','$this->tipo_estrategia',$this->cedente,$this->idUsuario)");
		$query1=$db->select("SELECT id FROM SIS_Estrategias WHERE nombre='$this->nombre_estrategia'");
		foreach((array) $query1 as $row){
			$id_estrategia=$row['id'];

		}
		$array = array('uno' => "<input type='hidden' value='$id_estrategia' id='id_estrategia' name='id_estrategia'>", 'dos' => "$id_estrategia");
		echo json_encode($array);

	}

	public function asignarPrioridad($id_prioridad,$valor_prioridad)
	{
		$db = new DB();
		$this->id_prioridad=$id_prioridad;
		$this->valor_prioridad=$valor_prioridad;
		$update= "UPDATE SIS_Querys SET prioridad='$this->valor_prioridad' WHERE id=$this->id_prioridad";
		$mysql_update=$db->query($update);
		echo $this->id_prioridad;
	}

	public function asignarComentario($id_com,$valor_com)
	{
		$db = new DB();
		$this->id_com=$id_com;
		$this->valor_com=$valor_com;
		$update= "UPDATE SIS_Querys SET comentario='$this->valor_com' WHERE id=$this->id_com";
		$mysql_update=$db->query($update);
		echo $this->id_com;
	}

	public function asignarCola($id_cola,$valor_cola)
	{
		$db = new DB();
		$this->id_cola=$id_cola;
		$this->valor_cola=$valor_cola;
		$update= "UPDATE SIS_Querys SET cola='$this->valor_cola' WHERE id=$this->id_cola";
		$mysql_update=$db->query($update);
		echo $this->id_cola;
	}

	public function asignarCategoria($color,$tipo_contacto,$dias,$cant1,$cond1,$logica,$cant2,$cond2,$w,$mundo)
	{
		$db = new DB();
		$this->color=$color;
		$this->tipo_contacto=$tipo_contacto;
		$this->dias=$dias;
		$this->cant1=$cant1;
		$this->cond1=$cond1;
		$this->logica=$logica;
		$this->cant2=$cant2;
		$this->cond2=$cond2;
		$this->w=$w;
		$this->mundo=$mundo;
		$query_color = $db->select("SELECT * FROM SIS_Categoria_Fonos WHERE color = $this->color AND mundo = $this->mundo");
		if(count((array) $query_color) > 0)
		{
			echo "2";
		}
		else
		{
			$tipo_contacto_array = explode(",", $this->tipo_contacto);
			$or_count = count((array) $tipo_contacto_array);
			if($or_count>0)
			{
				$m=0;
				$or_tipo_hoy = '';
				while($m<$or_count)
				{
					$id_tipo = "Id_TipoGestion=";
					$or_tipo_hoy  = $id_tipo.$tipo_contacto_array[$m]." OR ".$or_tipo_hoy;
					$m++;
				}
				$or_tipo_hoy = substr($or_tipo_hoy , 0, -3);
			}
			else
			{
				$or_tipo_hoy = "Id_TipoGestion=".$this->tipo_contacto;
			}

			$tipo_contacto_array2 = explode(",", $this->tipo_contacto);
			$or_count2 = count((array) $tipo_contacto_array2);
			if($or_count2>0)
			{
				$m2=0;
				$or_tipo_hoy2 = '';
				while($m2<$or_count2)
				{
					$tipo = $tipo_contacto_array2[$m2];
					$query_tipo = $db->select("SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = $tipo");
					foreach((array) $query_tipo as $row){
						$nombre_tipo = $row['Nombre'];
					}
					$or_tipo_hoy2  = $nombre_tipo." -- ".$or_tipo_hoy2;
					$m2++;
				}
				$or_tipo_hoy2 = substr($or_tipo_hoy2 , 0, -3);
			}
			else
			{
				$query_tipo = $db->select("SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = $this->tipo_contacto");
				foreach((array) $query_tipo as $row){
						$nombre_tipo = $row['Nombre'];
				}
				$or_tipo_hoy2 = $nombre_tipo;
			}

			$color_hexa=$db->select("SELECT color,nombre FROM SIS_Colores WHERE id='$this->color'");
			foreach((array) $color_hexa as $row){
				$color_hex=$row['color'];
				$color_nombre=$row['nombre'];
			}
			$update_fono= "INSERT INTO SIS_Categoria_Fonos(color,tipo_contacto,tipo_contacto_query, dias, cond1, cant1, logica, cond2, cant2, w , color_hex ,color_nombre , tipo_var , mundo) VALUES ('$this->color','$this->tipo_contacto','$or_tipo_hoy','$this->dias','$this->cond1','$this->cant1','$this->logica','$this->cond2','$this->cant2','$this->w' ,'$color_hex','$color_nombre','$or_tipo_hoy2','$this->mundo')";
			$db->query($update_fono);
			echo "1";
		}
	}

	public function asignarCategoriaIvr($color,$tipo_contacto,$dias,$cant1,$cond1,$logica,$cant2,$cond2,$w,$mundo)
	{
		$db = new DB();
		$this->color=$color;
		$this->tipo_contacto=$tipo_contacto;
		$this->dias=$dias;
		$this->cant1=$cant1;
		$this->cond1=$cond1;
		$this->logica=$logica;
		$this->cant2=$cant2;
		$this->cond2=$cond2;
		$this->w=$w;
		$this->mundo=$mundo;

		$query_color = $db->select("SELECT * FROM SIS_Categoria_Fonos WHERE color = $this->color AND mundo = $this->mundo ");
		if(count((array) $query_color) > 0) {
			echo "2";
		}	else{
			$tipo_contacto_array = explode(",", $this->tipo_contacto);
			$or_count = count((array) $tipo_contacto_array);
			$or_tipo_hoy = '';
			if($or_count>0) {
				$m=0;
				while($m<$or_count)
				{
					$id_tipo = "Id_TipoGestion=";
					$or_tipo_hoy  = $id_tipo.$tipo_contacto_array[$m]." OR ".$or_tipo_hoy;
					$m++;
				}
				$or_tipo_hoy = substr($or_tipo_hoy , 0, -3);
			} else	{
				$or_tipo_hoy = "Id_TipoGestion=".$this->tipo_contacto;
			}

			$tipo_contacto_array2 = explode(",", $this->tipo_contacto);
			$or_count2 = count((array) $tipo_contacto_array2);
			if($or_count2>0)
			{
				$m2=0;
				$or_tipo_hoy2 = '';
				while($m2<$or_count2)
				{
					$tipo = $tipo_contacto_array2[$m2];
					$query_tipo = $db->select("SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = $tipo");
					foreach((array) $query_tipo as $row){
						$nombre_tipo = $row['Nombre'];
					}
					$or_tipo_hoy2  = $nombre_tipo." -- ".$or_tipo_hoy2;
					$m2++;
				}
				$or_tipo_hoy2 = substr($or_tipo_hoy2 , 0, -3);
			}
			else
			{
				$query_tipo = $db->select("SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = $this->tipo_contacto");
				foreach((array) $query_tipo as $row){
						$nombre_tipo = $row['Nombre'];
				}
				$or_tipo_hoy2 = $nombre_tipo;
			}

			$color_hexa=$db->select("SELECT color,nombre FROM SIS_Colores WHERE id='$this->color'");
			foreach((array) $color_hexa as $row){
				$color_hex=$row['color'];
				$color_nombre=$row['nombre'];
			}

			$update_fono= "INSERT INTO SIS_Categoria_Fonos(color,tipo_contacto,tipo_contacto_query, dias, cond1, cant1, logica, cond2, cant2, w , color_hex ,color_nombre , tipo_var , mundo) VALUES ('$this->color','$this->tipo_contacto','$or_tipo_hoy','$this->dias','$this->cond1','$this->cant1','$this->logica','$this->cond2','$this->cant2','$this->w' ,'$color_hex','$color_nombre','$or_tipo_hoy2','$this->mundo')";
			$db->query($update_fono);
			echo "1";
		}
	}

	public function javaGet($data)
	{
		$db = new DB();
		$proce=$db->select("SELECT * FROM SIS_Procesos ");
		$count_pro = count((array) $proce);
		if($count_pro>0) {
			echo "1";
		}	else	{
			$salida = shell_exec('java -jar ColorFono.jar > /dev/null 2>&1 &');
			$ran = rand(1000, 3000);
			$proceso= "INSERT INTO SIS_Procesos(numero) VALUES ('$ran')";
			$db->query($proceso);
			$proceso2= "UPDATE  SIS_Categoria_Fonos SET proceso='$ran'";
			$db->query($proceso2);
			echo "2";
		}
	}

	public function javaGetIvr($data)
	{
		$db = new DB();
		$proce=$db->select("SELECT * FROM SIS_Procesos ");
		$count_pro = count((array) $proce);
		if($count_pro>0)
		{
			echo "1";
		}
		else
		{
			$salida = shell_exec('java -jar ColorIvr.jar > /dev/null 2>&1 &');
			$ran = rand(1000, 3000);
			$proceso= "INSERT INTO SIS_Procesos(numero) VALUES ('$ran')";
			$db->query($proceso);
			$proceso2= "UPDATE  SIS_Categoria_Fonos SET proceso='$ran'";
			$db->query($proceso2);
			echo "2";
		}
	}
	
	public function estrategiasGuardadas($cedente, $nomUsuario)
	{
		try {
			$idCedente = (int) $_SESSION['cedente'];
			$nombreUsuario = trim($_SESSION['MM_Username']);
			$strSQL = "SELECT e.id, e.nombre, e.usuario, (SELECT t.nombre FROM SIS_Tipo_Estrategia AS t WHERE t.id = e.tipo) AS tipo, (SELECT COUNT(1) FROM SIS_Querys_Estrategias AS q WHERE q.id_estrategia = e.id) AS segmentos, CONCAT(DATE_FORMAT(e.fecha,'%d-%m-%Y'), ' ', DATE_FORMAT(e.hora,'%H:%i:%s')) AS creada FROM SIS_Estrategias AS e WHERE e.Id_Cedente = {$idCedente} AND e.estado = 0 ORDER BY e.id DESC;";
			$rsEstrategisas = $this->db->select($strSQL);
			echo '<table id="TablaVerEstrategia" class="table table-striped" cellspacing="0" width="100%">';
    	echo '<thead>'.PHP_EOL;
    	echo '<tr>'.PHP_EOL;
			echo '<th>Nombre</th>'.PHP_EOL;
			echo '<th class="min-desktop" style="width: 15%; text-align: center;">Tipo</th>'.PHP_EOL;
			echo '<th class="min-desktop" style="width: 15%; text-align: center;">Segmentos</th>'.PHP_EOL;
			echo '<th class="min-desktop" style="width: 15%; text-align: center;">Creada</th>'.PHP_EOL;
			echo '<th class="min-desktop" style="width: 10%; text-align: center;">&nbsp;</th>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
    	echo '</thead>'.PHP_EOL;
    	echo '<tbody>'.PHP_EOL;

			if ($rsEstrategisas) {
				foreach ((array) $rsEstrategisas as $estrategia) {
					$disabled = 'disabled="disabled"';
					echo '<tr>'.PHP_EOL;
					echo '<td>'.$estrategia['nombre'].'</td>'.PHP_EOL;
					echo '<td style="text-align: center;">'.$estrategia['tipo'].'</td>'.PHP_EOL;
					echo '<td style="text-align: center;">'.$estrategia['segmentos'].'</td>'.PHP_EOL;
					echo '<td style="text-align: center;">'.$estrategia['creada'].'</td>'.PHP_EOL;
					echo '<td style="text-align: center;">'.PHP_EOL;
					echo '<button class="fa fa-search btn btn-primary btn-icon VerEstrategia" id="'.$estrategia['id'].'" title="Ver segmentación"></button>';
					if ($nombreUsuario == $estrategia['usuario']) {
						echo '<button type="button" class="btn eliminar fa fa-trash btn-danger btn-icon icon-lg" data-toggle="modal" id="'.$estrategia["id"].'" style="margin-left: 5px;" title="Eliminar segmentación"></button>';
					}
					echo '</td>'.PHP_EOL;
					echo '</tr>'.PHP_EOL;
				}
			} else {
				echo '<tr><td colspan="5">No hay datos</td></tr>'.PHP_EOL;
			}

			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
			
		} catch (\Exception $ex) {
			$this->logs->error($ex->getMessage());
		}
	}

	public function estrategiasInactivas($cedente,$nombreUsuario)
	{
		$db = new DB();
    $cedente = $this->cedente=$cedente;
		$IdEstrategia = '';
    $sql_num = $db->select("SELECT * FROM SIS_Estrategias WHERE Id_Cedente = '".$cedente."' AND estado = 1");
		if(count((array) $sql_num) > 0) {
    	echo '<table id="TablaVerEstrategiaIna" class="table table-striped table-bordered" cellspacing="0" width="100%">';
    	echo '<thead>';
    	echo '<tr>';
      echo '<th>Nombre Estrategia</th>';
      echo '<th class="min-desktop"><center>Hora Creación</center></th>';
      echo '<th class="min-desktop"><center>Fecha Creación</center></th>';
      echo '<th class="min-desktop"><center>Creador</center></th>';
      echo '<th class="min-desktop"><center>Tipo</center></th>';
			echo '<th class="min-desktop"><center>Activar</center></th>';
    	echo '</tr>';
    	echo '</thead>';
    	echo '<tbody>';

      foreach((array) $sql_num as $row4){
        $IdEstrategia= $row4["id"]; 
				$fecha = date("d-m-Y", strtotime($row4["fecha"]));
        $hora = date("H:i:s", strtotime($row4["hora"]));
				echo "<tr id=".$row4["id"].">";
        echo "<td>".$row4['nombre']."</td>";
        echo "<td><center>".$hora."</center></td>";
        echo "<td><center>".$fecha."</center></td>";
        echo '<td><center>';
        $usuariob = $row4["usuario"];
        $sql_user = $db->select("SELECT nombre FROM Usuarios WHERE usuario = '".$usuariob."'");
        foreach((array) $sql_user as $row){
          echo $row["nombre"];
					// si el usuario conectado el mismo que creo la estrategia tiene derecho a eliminarla
					if ($nombreUsuario == $row["nombre"]) {
						$disabled = "";
					} else {
						//$visible = "disabled='".$visible."'";
						$disabled = "disabled='disabled'";
					}
        }

        echo '</center></td>';
        echo '<td><center>';

        $tipo = $row4["tipo"];
        $sql_tipo = $db->select("SELECT nombre FROM SIS_Tipo_Estrategia WHERE id = '".$tipo."'");
        foreach((array) $sql_tipo as $row){
          echo $row["nombre"]; // <a style='$visible' href='delete.php?id_estrategia=$row4["id"]'><i class='fa fa-trash'></i></a>
        } // <a href=''><button class='fa fa-search' disabled="disabled"></a></button>

        echo '</center></td>';
				echo "<td><center><button type='button' class='btn activar fa fa-check btn-success btn-icon icon-lg' ".$disabled." data-toggle='modal' id='".$row4["id"]."'></button></center>
				</td>";               
        echo '</tr>';
      }
      echo '</tbody>';
    	echo '</table>';			
    } else{
      echo "No hay estrategias inactivas para este Cedente";
    }
	}

	public function SesionEstrategia($Id)
	{
		$this->Id = (int) $Id;
		if(!isset($_SESSION)) session_start();
		$_SESSION['IdEstrategia'] = (int) $this->Id;
	}

	public function getEstrategias($Cedente)
	{
		$db = new DB();
		$ToReturn = "";
		$Query = $db->select("SELECT id,nombre FROM SIS_Estrategias WHERE Id_Cedente = '".$Cedente."'");
		foreach((array) $Query as $row) {
      $id = $row["id"];
      $nombre = $row["nombre"];
      $ToReturn .= "<option value='".$id."'>".$nombre."</option>";
		}
		return $ToReturn;
	}

	public function getColas($Estrategia)
	{
		$db = new DB();
		$ToReturn = "";
		$Query = $db->select("SELECT id,cola FROM SIS_Querys_Estrategias WHERE id_estrategia = '".$Estrategia."'");
		foreach((array) $Query as $row){
            $id = $row["id"];
            $cola = $row["cola"];
            $ToReturn .= "<option value='".$id."'>".$cola."</option>";
		}
		return $ToReturn;
	}

	public function mostrarTabla($lista,$periodo)
	{
		$db = new DB();
		$this->cedente = $_SESSION['cedente'];
		$this->lista = $lista;
		$this->periodo = $periodo;
		$qr = "QR_".$this->cedente."_".$this->lista;
		if($this->lista == -1) {
			echo '<table id="mitabla" class="table table-striped table-bordered" cellspacing="0" width="100%">';
			echo '<thead><tr>';
			echo '<th class="min-tablet">Tipo Gestión</th>';
			echo '<th class="min-tablet">Cant. Gestiones</th>';
			echo '<th class="min-tablet">Ultima Gestión</th>';
			echo '<th class="min-tablet">Ratio</th>';
			echo '<th class="min-tablet">Porcentaje</th>';
			echo '</tr>';
			echo '</thead><tbody>';

		  $q1 = $db->select("SELECT DISTINCT Rut FROM Deuda Where Id_Cedente = $this->cedente");
		  $total =  count((array) $q1);
		  $q6 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Id_Cedente = $this->cedente ");
		  $cg =  count((array) $q6);
		  $i = 0;
		  $q2 = $db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente)");
		  foreach((array) $q2 as $r) {
		    $rid = $r["Id"];
		    $rn = utf8_encode($r["Respuesta_N1"]);
		    echo "<tr id='$rid' class='$rid'>";
		    echo "<td><button class='btn btn-icon icon-lg fa fa-plus-square nivel1 lvl1'  id='d$rid' value=''></button><span class='text-xs'>$rn</span></td>";
		    echo "<td>";
		    $q3 = '';
		    if($this->periodo==1) {
					$q3 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.resultado = $rid AND g.cedente = $this->cedente and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");
					$q5 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.cedente = $this->cedente and  and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");
				} else if($this->periodo==2) {
					$q3 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g WHERE g.resultado = $rid AND g.cedente = $this->cedente ");
					$q5 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g WHERE g.cedente = $this->cedente  ");
				}

		    echo $r3 = count((array) $q3);
		    echo "</td>";
		    echo "<td>";
		    $q4 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N1 = $rid AND Id_Cedente = $this->cedente ");
        //$q5 = count((array) $q5);
				echo $r4 = count((array) $q4);
				echo "</td>";
				$ratio = $r4==0 ? number_format(0, 2, '.', '') : number_format($r3/$r4, 2, '.', '');
		    echo "<td>$ratio</td>";
		    $porcentaje = $total==0 ? number_format(0, 2, '.', '') : number_format(($r4/$total)*100, 2, '.', '');
		    echo "<td>$porcentaje %</td>";
		    echo "</tr>";
				$i++;
		  }

		  $sg = $total - $cg;
		  $psg = $total==0 ? number_format(0, 2, '.', '') : number_format(($sg/$total)*100, 2, '.', '');
		  echo "<tr>";
		  echo "<td><button class='btn btn-icon icon-lg fa fa-plus-square nivel1'  id='sg' value=''></button><span class='text-xs'>EN POBLAMIENTO DE DATOS</span></td>";
		  echo "<td>0</td>";
		  echo "<td>0</td>";
		  echo "<td>0.00</td>";
		  echo "<td>0 %</td>";
		  echo "</tr>";
		  echo "<tr>";
		  echo "<td><b>Total Periodo</b></td>";
		  $qt = $db->select("SELECT rut_cliente FROM gestion_ult_semestre WHERE cedente = $this->cedente and resultado in (1,2)");
		  $total_g = count((array) $qt);
		  echo "<td>$total_g</td>";
		  echo "<td>$total</td>";
		  $total_ratio = $total==0 ? number_format(0, 2, '.', '') : number_format($q5/$total, 2, '.', '');
		  echo "<td>$total_ratio</td>";
		  echo "<td>100.00 %</td>";
		  echo "</tr></tbody></table>";
		  echo "<input type='hidden' id='cant_total' value='$total'>";
		} else {
			echo '<table id="mitabla" class="table table-striped table-bordered" cellspacing="0" width="100%">';
			echo '<thead><tr>';
			echo '<th class="min-tablet">Tipo Gestión</th>';
			echo '<th class="min-tablet">Cant. Gestiones</th>';
			echo '<th class="min-tablet"Ultima Gestión</th>';
			echo '<th class="min-tablet">Ratio</th>';
			echo '<th class="min-tablet">Porcentaje</th>';
			echo '</tr>';
			echo '</thead><tbody>';

		    $q1 = $db->select("SELECT Rut FROM $qr ");
		    $total =  count((array) $q1);
		    $q6 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Id_Cedente = $this->cedente and lista=$this->lista");
		    $cg =  count((array) $q6);
		    $i = 0;
		    $q2 = $db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('".$this->cedente."',Id_Cedente)");
		    foreach((array) $q2 as $r){
		        $rid = $r["Id"];
		        $rn = utf8_encode($r["Respuesta_N1"]);
		        echo "<tr id='$rid' class='$rid'>";
		        echo "<td><button class='btn btn-icon icon-lg fa fa-plus-square nivel1 lvl1'  id='d$rid' value=''></button><span class='text-xs'>$rn</span></td>";
		        echo "<td>";
		        $q3 = '';
		        if($this->periodo==1)
		        {
					$q3 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.resultado = $rid AND g.cedente = $this->cedente and g.lista=$this->lista and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");
					$q5 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g,Periodo_Gestion p WHERE g.cedente = $this->cedente and g.lista=$this->lista and g.fechahora BETWEEN p.Fecha_Inicio and p.Fecha_Termino and p.cedente = g.cedente");
				}
				elseif($this->periodo==2)
				{
					$q3 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g WHERE g.resultado = $rid AND g.cedente = $this->cedente and g.lista=$this->lista ");
					$q5 = $db->select("SELECT rut_cliente FROM gestion_ult_semestre g WHERE g.cedente = $this->cedente and g.lista=$this->lista ");
				}
		        echo $r3 = count((array) $q3);
		        echo "</td>";
		        echo "<td>";
		        $q4 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N1 = $rid AND Id_Cedente = $this->cedente and lista=$this->lista");


		        //$q5 = count((array) $q5);
				echo $r4 = count((array) $q4);
				echo "</td>";
				$ratio = $r4==0 ? number_format(0, 2, '.', '') : number_format($r3/$r4, 2, '.', '');
		        echo "<td>$ratio</td>";
		        $porcentaje = $total == 0 ? number_format(0, 2, '.', '') : number_format(($r4/$total)*100, 2, '.', '');
		        echo "<td>$porcentaje %</td>";
		        echo "</tr>";
				$i++;
		    }
		    $sg = $total - $cg;
		    $psg = $total == 0 ? number_format(0, 2, '.', '') : number_format(($sg/$total)*100, 2, '.', '');
		    $qt = $db->select("SELECT rut_cliente FROM gestion_ult_semestre WHERE cedente = $this->cedente and resultado in (1,2)");
		    $total_g = count((array) $qt);
		    echo "<tr>";
		    echo "<td><button class='btn btn-icon icon-lg fa fa-plus-square nivel1'  id='sg' value=''></button><span class='text-xs'>EN POBLAMIENTO DE DATOS</span></td>";
		    echo "<td>0</td>";
		    echo "<td>$sg</td>";
		    echo "<td>0.00</td>";
		    echo "<td>$psg %</td>";
		    echo "</tr>";
		    echo "<tr>";
		    echo "<td><b>Total Periodo</b></td>";
		    echo "<td>$total_g</td>";
		    echo "<td>$total</td>";
		    $total_ratio = $total == 0 ? number_format(0, 2, '.', '') : number_format($total, 2, '.', '');
		    echo "<td>$total_ratio</td>";
		    echo "<td>100.00 %</td>";
		    echo "</tr></tbody></table>";
		    echo "<input type='hidden' id='cant_total' value='$total'>";
		}
	}

	public function eliminarEstrategia($idEstrategia)
	{
		$db = new DB();
    $colas = $db->select("SELECT id FROM SIS_Querys_Estrategias WHERE id_estrategia = $idEstrategia");
    foreach((array) $colas as $rowCola)
    {
    	$Prefix = "QR_".$_SESSION['cedente']."_".$rowCola['id'];
      $SqlTables = "SELECT * FROM sys.tables WHERE name  like '".$Prefix."%'";
      $Tables = $db->select($SqlTables);
      if(count((array) $Tables) > 0){
        foreach((array) $Tables as $Table){
          $Tabla = $Table["name"];
          $Sql = "drop table ".$Tabla."";
          $db->query($Sql);
        }
      }           
    }
		$db->query("DELETE FROM SIS_Estrategias WHERE id = '$idEstrategia' ");
		$db->query("DELETE FROM SIS_Querys WHERE id_estrategia = '$idEstrategia' ");
		return [
			'sussess' => true,
			'message' => 'OK'
		];
	}

	public function desactivarEstrategia($idEstrategia)
	{	
		$db = new DB();	
		$db->query("UPDATE SIS_Estrategias SET estado = '1' WHERE id = '".$idEstrategia."'");	
	}

	public function activarEstrategia($idEstrategia)
	{	
		$db = new DB();	
		$db->query("UPDATE SIS_Estrategias SET estado = '0' WHERE id = '".$idEstrategia."'");	
	}

	public function crearTablaExportable($id,$lista,$cedente)
	{
		$db = new DB();
		$this->id=$id;
		$this->cedente=$cedente;
		$this->lista=$lista;
		if($this->lista==-1)
		{
	        echo '<table id="tabla_super" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm" data-priority="1">Rut</th>';
	        echo '<th class="text-sm">Numero Operacion</th>';
	        echo '<th class="text-sm">Fecha Vencimiento</th>';
	        echo '<th class="text-sm" data-priority="2">Deuda Mora</th>';
	        echo '<th class="text-sm" data-priority="2">Tramo</th>';
	        echo '<th class="text-sm" data-priority="2">Fec. Mej. Gestion</th>';
	        echo '<th class="text-sm" data-priority="2">Accion Mej. Gest.</th>';
	        echo '<th class="text-sm" data-priority="2">Resp. Mej. Gest.</th>';
	        echo '<th class="text-sm" data-priority="2">Subresp. Mej. Gest.</th>';
	        //echo '<th class="text-sm">Fono Mej. Gest.</th>';
	        echo '<th class="text-sm" data-priority="2">Fec. Ult. Gestion</th>';
	        echo '<th class="text-sm" data-priority="2">Accion Ult. Gest.</th>';
	        echo '<th class="text-sm" data-priority="2">Resp. Ult. Gest.</th>';
	        echo '<th class="text-sm" data-priority="2">Subresp. Ult. Gest.</th>';
	        //echo '<th class="text-sm">Fono Ult. Gest.</th>';
	        echo '</thead><tbody>';

	        $q1 = $db->select("SELECT d.Rut,d.Numero_Operacion, d.Fecha_Vencimiento, d.Saldo_Mora,d.Tipo_Deudor, m.Fecha_Gestion as Fec_Mej_Gest, n1.Respuesta_N1 as N1_Mej_Gest, n2.Respuesta_N2 as N2_Mej_Gest, n3.Respuesta_N3 as N3_Mej_Gest,m.Fono_Gestion as Fono_Mej_Gest, u.Fecha_Gestion as Fec_Ult_gestion, u.Respuesta_N1 as N1_Ult_gestion, u.Respuesta_N2 as N2_Ult_gestion, u.Respuesta_N3 as N3_Ult_gestion, u.Fono_Gestion as Fono_Ult_gestion
			FROM Ultima_Gestion u, Ultima_Gestion m, Deuda d, Nivel1 n1, Nivel2 n2, Nivel3 n3
			WHERE d.Rut = u.Rut and d.Rut = m.Rut and m.Id_Cedente = d.Id_Cedente and d.Id_Cedente = u.Id_Cedente and m.Id_Cedente = '$this->cedente' and m.Respuesta_N1 = n1.Id and m.Respuesta_N2 = n2.id and m.Respuesta_N3 = '$this->id' and n3.Id_Nivel2 = n2.id and n2.Id_Nivel1 = n1.id  AND n3.id = m.Respuesta_N3 ORDER BY d.Rut, d.Fecha_Vencimiento DESC ");

		    foreach((array) $q1 as $row){
	        	$rut = $row["Rut"];
	        	$numop = $row["Numero_Operacion"];
	        	$fecvenc = $row["Fecha_Vencimiento"];
	        	$mora = $row["Saldo_Mora"];
	        	$tipodeudor = $row["Tipo_Deudor"];
	        	$fec_mej_gest = $row["Fec_Mej_Gest"];
	        	$n1_mej_gest = $row["N1_Mej_Gest"];
	        	$n2_mej_gest = $row["N2_Mej_Gest"];
	        	$n3_mej_gest = $row["N3_Mej_Gest"];
	        	$fono_mej_gest = $row["Fono_Mej_Gest"];
	        	$fec_ult_gest = $row["Fec_Ult_gestion"];
	        	$n1_ult_gest = $row["N1_Ult_gestion"];
	        	$n2_ult_gest = $row["N2_Ult_gestion"];
	        	$n3_ult_gest = $row["N3_Ult_gestion"];
	        	$fono_ult_gest = $row["Fono_Ult_gestion"];

	        	echo "<tr>";
			    echo "<td class='text-sm'><center>$rut</center></td>";
			    echo "<td class='text-sm'><center>$numop</center></td>";
			    echo "<td class='text-sm'><center>$fecvenc</center></td>";
			    echo "<td class='text-sm'><center>$mora</center></td>";
			    echo "<td class='text-sm'><center>$tipodeudor</center></td>";
			    echo "<td class='text-sm'><center>$fec_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n1_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n2_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n3_mej_gest</center></td>";
			    //echo "<td class='text-sm'><center>$fono_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$fec_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$n1_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$n2_ult_gest</center></td>";
			    echo "<td class='text-sm' ><center>$n3_ult_gest</center></td>";
			    //echo "<td class='text-sm'><center>$fono_ult_gest</center></td>";
			    echo '</tr>';
	 		}
	    	echo '</tbody></table>';
		}
		else
		{

			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Rut</th>';
	        echo '<th class="text-sm">Numero Operacion</th>';
	        echo '<th class="text-sm">Fecha Vencimiento</th>';
	        echo '<th class="text-sm">Deuda Mora</th>';
	        echo '<th class="text-sm">Tramo</th>';
	        echo '<th class="text-sm">Fec. Mej. Gestion</th>';
	        echo '<th class="text-sm">Accion Mej. Gest.</th>';
	        echo '<th class="text-sm">Resp. Mej. Gest.</th>';
	        echo '<th class="text-sm">Subresp. Mej. Gest.</th>';
	        echo '<th class="text-sm">Fono Mej. Gest.</th>';
	        echo '<th class="text-sm">Fec. Ult. Gestion</th>';
	        echo '<th class="text-sm">Accion Ult. Gest.</th>';
	        echo '<th class="text-sm">Resp. Ult. Gest.</th>';
	        echo '<th class="text-sm">Subresp. Ult. Gest.</th>';
	        echo '<th class="text-sm">Fono Ult. Gest.</th>';
	        echo '</thead><tbody>';

	        $q1 = $db->select("SELECT d.Rut,d.Numero_Operacion, d.Fecha_Vencimiento, d.Saldo_Mora,d.Tipo_Deudor, m.Fecha_Gestion as Fec_Mej_Gest, n1.Respuesta_N1 as N1_Mej_Gest, n2.Respuesta_N2 as N2_Mej_Gest, n3.Respuesta_N3 as N3_Mej_Gest,m.Fono_Gestion as Fono_Mej_Gest, u.Fecha_Gestion as Fec_Ult_gestion, u.Respuesta_N1 as N1_Ult_gestion, u.Respuesta_N2 as N2_Ult_gestion, u.Respuesta_N3 as N3_Ult_gestion, u.Fono_Gestion as Fono_Ult_gestion
			FROM Ultima_Gestion u, Ultima_Gestion m, Deuda d, Nivel1 n1, Nivel2 n2, Nivel3 n3
			WHERE d.Rut = u.Rut and d.Rut = m.Rut and m.Id_Cedente = d.Id_Cedente and d.Id_Cedente = u.Id_Cedente and m.Id_Cedente = '$this->cedente' and m.Respuesta_N1 = n1.Id and m.Respuesta_N2 = n2.id and m.Respuesta_N3 = '$this->id' and n3.Id_Nivel2 = n2.id and n2.Id_Nivel1 = n1.id and m.lista = '$this->lista' AND n3.id = m.Respuesta_N3 ORDER BY d.Rut, d.Fecha_Vencimiento DESC ");

			foreach((array) $q1 as $row){
	        	$rut = $row["Rut"];
	        	$numop = $row["Numero_Operacion"];
	        	$fecvenc = $row["Fecha_Vencimiento"];
	        	$mora = $row["Saldo_Mora"];
	        	$tipodeudor = $row["Tipo_Deudor"];
	        	$fec_mej_gest = $row["Fec_Mej_Gest"];
	        	$n1_mej_gest = $row["N1_Mej_Gest"];
	        	$n2_mej_gest = $row["N2_Mej_Gest"];
	        	$n3_mej_gest = $row["N3_Mej_Gest"];
	        	$fono_mej_gest = $row["Fono_Mej_Gest"];
	        	$fec_ult_gest = $row["Fec_Ult_gestion"];
	        	$n1_ult_gest = $row["N1_Ult_gestion"];
	        	$n2_ult_gest = $row["N2_Ult_gestion"];
	        	$n3_ult_gest = $row["N3_Ult_gestion"];
	        	$fono_ult_gest = $row["Fono_Ult_gestion"];

	        	echo "<tr>";
			    echo "<td class='text-sm'><center>$rut</center></td>";
			    echo "<td class='text-sm'><center>$numop</center></td>";
			    echo "<td class='text-sm'><center>$fecvenc</center></td>";
			    echo "<td class='text-sm'><center>$mora</center></td>";
			    echo "<td class='text-sm'><center>$tipodeudor</center></td>";
			    echo "<td class='text-sm'><center>$fec_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n1_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n2_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$n3_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$fono_mej_gest</center></td>";
			    echo "<td class='text-sm'><center>$fec_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$n1_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$n2_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$n3_ult_gest</center></td>";
			    echo "<td class='text-sm'><center>$fono_ult_gest</center></td>";
			    echo '</tr>';
	 		}
	    	echo '</tbody></table></div>';
	    }
	}

	public function getColorTableList()
	{
		$db = new DB();
		$query = "SELECT * FROM SIS_Colores";
		$Colores = $db->select($query);
		return $Colores;
	}

	public function crearColor($color,$nombre,$comentario){
		$db = new DB();
		$this->color = $color;
		$this->nombre = $nombre;
		$this->comentario = $comentario;
		$query = "SELECT * FROM SIS_Colores WHERE color = '$this->color' OR  nombre = '".$this->nombre."'";
		$color = $db->select($query);
		if(count((array) $color) > 0){
			echo "2";
		}else{
			$query = "INSERT INTO SIS_Colores(color,nombre,comentario) VALUES ('".$this->color."','".$this->nombre."','".$this->comentario."')";
			$db->query($query);
			echo "1";
		}
	}

	public function getColor($id)
	{
		$db = new DB();
		$query = "SELECT * FROM SIS_Colores WHERE id = '".$id."'";
		$Color = $db->select($query);
		$Color = $Color[0];
		return $Color;
	}

	public function updateColor($color,$nombre,$comentario,$id)
	{
		$db = new DB();
		$this->color=$color;
		$this->nombre = $nombre;
		$this->comentario = $comentario;
		$this->id = (int) $id;
		$query = "UPDATE SIS_Colores SET color = '".$this->color."', nombre = '".$this->nombre."', comentario = '".$this->comentario."' WHERE id = '".$this->id."'";
		$db->query($query);
		echo "1";		
	}

	public function deleteColor($id)
	{
		$db = new DB();
		$query = "DELETE FROM SIS_Colores WHERE id = '".$id."'";
		$result = $db->query($query);
		return $result;
	}

	function cambiarNombreEstrategia($idEstrategia, $nombreEstrategia)
	{
		$db = new DB();
		$ToReturn = array();
		$ToReturn["result"] = false;
		$Sqlupdate = "UPDATE SIS_Estrategias SET nombre = '".$nombreEstrategia."' WHERE id = '".$idEstrategia."'";
		$Update = $db->query($Sqlupdate);
		if($Update) $ToReturn["result"] = true;
		return $ToReturn;
	}
}
?>

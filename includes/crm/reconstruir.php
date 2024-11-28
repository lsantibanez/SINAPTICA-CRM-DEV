<?php 
$mysqli = new mysqli("localhost", "root", "w9i7s5p3A.,3019", "foco");
if (mysqli_connect_errno()) {
    printf("Error de conexión: %s\n", mysqli_connect_error());
    exit();
}




$tabla = $_POST['asignacion'];
$sqlTbl = $mysqli->query("SELECT Rut FROM $tabla");
foreach($sqlTbl as $rowTbl){
    $Rut = $rowTbl['Rut'];
    $cont = $mysqli->query("SELECT Rut FROM foco.Ultima_Gestion_Mes WHERE Rut = '$Rut'")->num_rows;
    if($cont > 0){
        $mysqli->query("UPDATE foco.`$tabla` SET estado = 1 WHERE Rut = '$Rut' ");
    }

}

$cedente = $_POST['cedente'];

if($cedente != 1){

}else{
    $arraySG = array();
    $arrayCG = array();
    $query  = $mysqli->query("SELECT * FROM $tabla");
    foreach($query as $row){
        $rut = $row['Rut'];
        $estado = $row['estado'];
        if($estado == 0){
            array_push($arraySG,$rut);
        }else{
            array_push($arrayCG,$rut);
        }
    }

    $mysqli->query("TRUNCATE TABLE $tabla");

    $i = 1;
    foreach($arraySG as $row){
        $mysqli->query("INSERT INTO $tabla(`Rut`, `estado`, `orden`) VALUES ('$row','0','$i')");
        $i++;
    }

    foreach($arrayCG as $row){
        echo $row;
        $mysqli->query("INSERT INTO $tabla(`Rut`, `estado`, `orden`) VALUES ('$row','1','$i')");
        $i++;
    }
}



?>
<?php
require_once('../class/db/DB.php');
$db = new DB();
?>
<table id="demo-dt-selection" class="table table-striped table-bordered" cellspacing="0" width="100%">
<thead>
    <tr>
        <th>Nombre Ejecutivo</th>
        <th>Cantidad de Gestiones</th>
        <th>Compromisos</th>
        <th>Titular</th>
        <th>Tercero</th>
        <th>No Contesta</th>
        <th>Inubicable</th>
        <th>Otro</th>
    
    
    </tr>
</thead>
<tbody>
<?php
    $QueryReportes = $db->select("SELECT COUNT(rut_cliente) AS Cantidad, nombre_ejecutivo FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') GROUP BY nombre_ejecutivo");
    foreach($QueryReportes as $row){
    $Cantidad = $row["Cantidad"];
    $Ejecutivo = $row["nombre_ejecutivo"];
    echo "<tr>";
    echo "<td>".$Ejecutivo."</td>";
    echo "<td>".$Cantidad."</td>";
    $QueryTipo5 = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 5 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo5 as $row){
        $Compromiso = $row["Cantidad"];
    }
    $QueryTipo1 = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 1 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo1 as $row){
        $Titular = $row["Cantidad"];
    }
    $QueryTipo2 = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 2 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo2 as $row){
        $Tercero = $row["Cantidad"];
    }
    $QueryTipo3 = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 3 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo3 as $row){
        $NoContesta = $row["Cantidad"];
    }
    $QueryTipo4 = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 4 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo4 as $row){
        $Inubicable = $row["Cantidad"];
    }
    $QueryTipo = $db->select("SELECT COUNT(rut_cliente) AS Cantidad FROM gestion_ult_trimestre WHERE fecha_gestion = DATE_FORMAT(NOW(),'%Y-%m-%d') AND Id_TipoGestion = 4 AND nombre_ejecutivo = '$Ejecutivo'");
    foreach($QueryTipo as $row){
        $Otro = $row["Cantidad"];
    }
    
    
    echo "<td>".$Compromiso."</td>";
    echo "<td>".$Titular."</td>";
    echo "<td>".$Tercero."</td>";
    echo "<td>".$NoContesta."</td>";
    echo "<td>".$Inubicable."</td>";
    echo "<td>".$Otro."</td>";
    echo "</tr>";

    }
?>    

</tbody>
</table>
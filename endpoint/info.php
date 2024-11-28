<?php
require __DIR__.'/../class/db/DB.php';

$response = array();

// Asumiendo que los datos se envían como JSON en el cuerpo de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$db = new Db();
$db->connect();
$mysqli = $db->getInstance();

if (isset($data['Id_Cedente'], $data['Rut'], $data['Cola'])) {
    $idCedente = (int) $data['Id_Cedente'];
    $rut = $data['Rut'];
    $Cola = (int) $data['Cola'];

    // Escapar los parámetros para prevenir inyecciones SQL
    $idCedente = $mysqli->real_escape_string($idCedente);
    $rut = $mysqli->real_escape_string($rut);

    $rsTabla = $mysqli->query("SELECT q.tabla, c.columnas FROM Querys_Segmentaciones AS q JOIN columnas_agentes AS c ON c.tabla = q.tabla WHERE q.id = {$Cola} LIMIT 1;");
    if ($rsTabla) {
        $datosTabla = $rsTabla->fetch_assoc();
    }

    $camposStr = '';
    $campos = json_decode($datosTabla['columnas'], true);
    if (count((array) $campos) > 0) $camposStr = ', '.implode(', ', $campos);
    $tabla = explode('_', $datosTabla['tabla'])[0];
    // Deudas
    $queryDeudas = "SELECT Deuda {$camposStr} FROM `{$tabla}` WHERE  Id_Cedente = '$idCedente' AND Rut = '$rut'";
    if ($resultDeudas = $mysqli->query($queryDeudas)) {
        $deudas = $resultDeudas->fetch_all(MYSQLI_ASSOC);
        /*
        while ($rowDeuda = $resultDeudas->fetch_assoc()) {
            $deudas[] = $item;
        }*/
        $response['deudas'] = $deudas;
    } else {
        die("Error en la consulta de Deudas: " . $mysqli->error);
    }

    // Gestiones
    $queryGestiones = "SELECT 
        (SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = cedente AND r.id = n1 LIMIT 1) AS n1,
        (SELECT r2.Respuesta_N2 FROM `Nivel2` AS r2 WHERE r2.Id_Nivel1 = n1 AND r2.id = n2 LIMIT 1) AS n2,
        (SELECT r3.Respuesta_N3 FROM `Nivel3` AS r3 WHERE r3.Id_Nivel2 = n2 AND r3.id = n3 LIMIT 1) AS n3,
        nombre_ejecutivo AS agente,
        DATE_FORMAT(fechahora, '%d-%m-%Y %H:%i:%s') AS fecha
    FROM gestion_ult_trimestre 
    WHERE cedente = '$idCedente' AND rut_cliente = '$rut'";
    if ($resultGestiones = $mysqli->query($queryGestiones)) {
        // $gestiones = array();
        $gestiones = $resultGestiones->fetch_all(MYSQLI_ASSOC);
        /*
        while ($rowGestion = $resultGestiones->fetch_assoc()) {
            $gestiones[] = array(
                'n1' => $rowGestion['n1'],
                'n2' => $rowGestion['n2'],
                'n3' => $rowGestion['n3'],
            );
        }
        */
        $response['gestion'] = $gestiones;
    } else {
        die("Error en la consulta de Gestiones: " . $mysqli->error);
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("error" => "Faltan datos en la solicitud POST. Se requieren Id_Cedente y Rut."));
}
?>

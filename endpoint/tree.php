<?php
require __DIR__.'/../class/db/DB.php';

$response = array();

if (isset($_GET['Id_Cedente'])) {
    $db = new Db();
    $db->connect();
    $mysqli = $db->getInstance();
    $idCedente = $_GET['Id_Cedente'];

    // Nivel 1
    $query1 = "SELECT id, Respuesta_N1 FROM `Nivel1` WHERE Id_Cedente = $idCedente";
    $result1 = $mysqli->query($query1);

    while ($row1 = $result1->fetch_assoc()) {
        $responseN1 = array(
            "Nivel 1" => array(
                "id" => $row1['id'],
                "respuesta" => $row1['Respuesta_N1'],
                "Nivel 2" => array()
            )
        );

        // Nivel 2
        $query2 = "SELECT id, Respuesta_N2 FROM `Nivel2` WHERE Id_Nivel1 = " . $row1['id'];
        $result2 = $mysqli->query($query2);
        while ($row2 = $result2->fetch_assoc()) {
            $responseN2 = array(
                "id" => $row2['id'],
                "respuesta" => $row2['Respuesta_N2'],
                "Nivel 3" => array()
            );

            // Nivel 3
            $query3 = "SELECT id, Respuesta_N3 FROM `Nivel3` WHERE Id_Nivel2 = " . $row2['id'];
            $result3 = $mysqli->query($query3);
            while ($row3 = $result3->fetch_assoc()) {
                $responseN2["Nivel 3"][] = array(
                    "id" => $row3['id'],
                    "respuesta" => $row3['Respuesta_N3']
                );
            }

            $responseN1["Nivel 1"]["Nivel 2"][] = $responseN2;
        }

        $response[] = $responseN1;
    }

    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($response);
} else {
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode(array("error" => "Parámetro no proporcionado."));
}
?>

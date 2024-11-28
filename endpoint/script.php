<?php
require __DIR__.'/../class/db/DB.php';

$response = [];

if (isset($_GET['Id_Cedente'])) {
    $idCedente = $_GET['Id_Cedente'];
    $db = new Db();
    $db->connect();
    $mysqli = $db->getInstance();

    // Obtener el script para el cedente
    $queryScript = "SELECT script FROM script_cedente WHERE id_cedente = ?";
    $stmt = $mysqli->prepare($queryScript);
    $stmt->bind_param("i", $idCedente);
    $stmt->execute();
    $resultScript = $stmt->get_result();
    $scriptData = $resultScript->fetch_assoc();
    $response['script'] = $scriptData ? $scriptData['script'] : '';

    // Nivel 1
    $query1 = "SELECT id, Respuesta_N1 FROM `Nivel1` WHERE Id_Cedente = ?";
    $stmt1 = $mysqli->prepare($query1);
    $stmt1->bind_param("i", $idCedente);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    $response['Nivel 1'] = array();  // Ensure this is an array even if empty

    while ($row1 = $result1->fetch_assoc()) {
        $responseN1 = array(
            "id" => $row1['id'],
            "respuesta" => $row1['Respuesta_N1'],
            "Nivel 2" => array()
        );

        // Nivel 2
        $query2 = "SELECT id, Respuesta_N2, Id_TipoGestion AS Tipo_Gestion, Ponderacion FROM `Nivel2` WHERE Id_Nivel1 = ?";
        $stmt2 = $mysqli->prepare($query2);
        $stmt2->bind_param("i", $row1['id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        while ($row2 = $result2->fetch_assoc()) {
            $responseN2 = array(
                "id" => (int) $row2['id'],
                "respuesta" => $row2['Respuesta_N2'],
                "tipo" => (int) $row2['Tipo_Gestion'],
                "ponderacion" => (int) $row2['Ponderacion'],
                "Nivel 3" => array()
            );

            // Nivel 3
            $query3 = "SELECT id, Respuesta_N3, Id_TipoGestion AS Tipo_Gestion, Peso FROM `Nivel3` WHERE Id_Nivel2 = ?";
            $stmt3 = $mysqli->prepare($query3);
            $stmt3->bind_param("i", $row2['id']);
            $stmt3->execute();
            $result3 = $stmt3->get_result();

            while ($row3 = $result3->fetch_assoc()) {
                // Nueva consulta para obtener los datos extra
                $queryExtra = "SELECT `Codigo`, `Titulo`,  `Tipo`, `Dinamico`, `CampoDB`, `Mandatorio`, `Deshabilitado`, `Cedente`, `Respuesta_Nivel3`, ValorPredeterminado FROM `campos_gestion` WHERE FIND_IN_SET(?, Respuesta_Nivel3) AND Cedente = ?";
                $stmtExtra = $mysqli->prepare($queryExtra);
                $stmtExtra->bind_param("ii", $row3['id'], $idCedente);
                $stmtExtra->execute();
                $resultExtra = $stmtExtra->get_result();            
                // Recoger todos los datos extra en un arreglo
                $extras = [];
                while($extraData = $resultExtra->fetch_assoc()) {
                    $extras[] = array(
                        "type" => $extraData['Tipo'],
                        "name" => $extraData['Titulo'],
                        "required" => (bool)$extraData['Mandatorio'], // Convertir a booleano si es necesario
                        "default" => $extraData['ValorPredeterminado'],
                        "column_name" => $extraData['Codigo']
                    );
                }
            
                $responseN3 = array(
                    "id" => $row3['id'],
                    "respuesta" => $row3['Respuesta_N3'],
                    "tipo" => (int) $row3['Tipo_Gestion'],
                    "ponderacion" => (int) $row3['Peso'],
                    "extra" => $extras // Ahora extra es un arreglo de elementos
                );
            
                $responseN2["Nivel 3"][] = $responseN3;
            }
            $responseN1["Nivel 2"][] = $responseN2;
        }
        $response['Nivel 1'][] = $responseN1;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode(array("error" => "Parámetro no proporcionado."));
}
?>
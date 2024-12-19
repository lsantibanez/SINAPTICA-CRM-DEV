<?php

require_once __DIR__ . '/../../../class/db/DB.php';
require_once __DIR__ . '/../../../class/session/session.php';
require_once __DIR__ . '/../../../class/global/global.php';
require_once __DIR__ . '/../../../class/logs.php';
require __DIR__ . '/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use League\Csv\Writer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$db = new DB();
$logs = new Logs();

$objetoSession = new Session('1,2,3,4,5,6', false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../../index.php");
}

/** Variables desde la sesión */
$usuario = $_SESSION['MM_Username'];
$cedente = (int)$_SESSION['cedente'];
$mandante = isset($_SESSION['mandante']) ? (int)$_SESSION['mandante'] : "";
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = (int)$_SESSION['IdEstrategia'];
$idUsuarioLogin = (int)$_SESSION['id_usuario'];

$datosPost = json_decode(file_get_contents("php://input"), true);
$body = [];
$logs->debug('Datos recibidos desde Axios: ' . json_encode($datosPost));

if (!$datosPost) {
    $body = [
        'success' => false,
        'message' => 'No se recibieron datos válidos.',
        'data' => null
    ];
    echo json_encode($body);
    exit;
}

$idQuery = (int)$datosPost['query_id'];

$nombreCedente = '';
$rsCedente = $db->select("SELECT * FROM Cedente WHERE Id_cedente = {$cedente}");
if ($rsCedente) {
    $nombreCedente = $rsCedente[0]['Nombre_Cedente'];
}

$query = '';
$query_segmentaciones = $db->select("SELECT query FROM querys_segmentaciones WHERE id= " . $datosPost['query_id']);

if ($query_segmentaciones) {
    $logs->debug('Contenido de $query_segmentaciones: ' . json_encode($query_segmentaciones));

    if (is_string($query_segmentaciones)) {
        $query_segmentaciones = json_decode($query_segmentaciones, true);
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        $logs->error('Error al decodificar JSON: ' . json_last_error_msg());
        exit;
    }

    if (is_array($query_segmentaciones) && isset($query_segmentaciones[0]['query'])) {
        $query = $query_segmentaciones[0]['query'];
        $logs->debug('Consulta obtenida: ' . $query);
    } else {
        $logs->error('No se encontró un query válido en $query_segmentaciones.');
        exit;
    }
}

$message = '';

try {
    $logs->debug('Entrando al try');
    $campaignData = [
        'name' => $datosPost['name'],
        'subject' => $datosPost['subject'],
        'schedule' => 0,
        'emailResponse' => $datosPost['emailResponse'],
        'sender' => $datosPost['sender'],
        'template_id' => $datosPost['template_id'],
        'status' => 'CARGADA',
        'unsubcribe' => $datosPost['unsubcribe'] ?? null,
        'created_at' => date('Y-m-d H:i:s'),
        'idCedente' => $cedente,
        'idMandante' => $mandante
    ];
    $logs->debug('Registrando Campaign');

    $campaignId = $db->insertWithParams('mail_campaigns', $campaignData);

    if (!$campaignId) {
        throw new Exception('Error al registrar la campaña.');
    }

    $logs->debug("Campaña registrada con ID: $campaignId");

    $sql = "
        INSERT INTO mail_data_emails (identity, fullName, email, customVariables, campaign_id, created_at, updated_at)
        SELECT 
            p.Rut AS IDENTIFICADOR, 
            p.Nombre_Completo AS NOMBRE, 
            e.Email AS EMAIL, 
            JSON_OBJECT('IDENTIFICADOR', p.Rut, 'NOMBRE', p.Nombre_Completo, 'EMAIL', e.Email) AS customVariables,
            ".$campaignId ." AS campaign_id, 
            NOW() AS created_at, 
            NOW() AS updated_at
        FROM persona AS p
        JOIN email AS e ON p.rut = e.rut
        WHERE p.Rut IN($query)
        GROUP BY p.rut, e.Email
    ";


    $db->insert($sql);

//    $firstFiveEmails = $db->select("
//        SELECT identity, fullName, email, customVariables
//        FROM mail_data_emails
//        WHERE campaign_id = :campaignId
//        LIMIT 5
//    ", [':campaignId' => $campaignId]);

    $body = [
        'success' => true,
        'message' => 'Proceso completado con éxito.',
        'data' => [
            'campaign' => $campaignId,
//            'emails' => $firstFiveEmails
        ]
    ];

} catch (Exception $e) {
    $logs->error($e->getMessage());
    $body = [
        'success' => false,
        'message' => $e->getMessage(),
        'data' => null
    ];
} finally {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body,$mandante);
    exit;
}
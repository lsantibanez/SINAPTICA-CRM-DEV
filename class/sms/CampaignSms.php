<?php

include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../db/DB.php');
include_once(__DIR__ . '/../logs.php');
include_once(__DIR__ . '/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use PhpOffice\PhpSpreadsheet\IOFactory;

class CampaignSms
{
    public $idCedente;
    public $idMandante;

    private $db;
    private $logs;

    function __construct()
    {
        $this->idMandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
        $this->idCedente = isset($_SESSION['cedente']) ? $_SESSION['cedente'] : "";
        $this->db = new Db();
        $this->logs = new Logs();
    }

    function getAllSms($search = null)
    {
        try {

            $sql = "SELECT id,name,quantity,preview,status,created_at 
                        FROM msj_campaign_sms 
                        WHERE idCedente='" . $this->idCedente . "' 
                        AND idMandante='" . $this->idMandante . "' 
                        AND isDeleted = 0";

            if ($search) {
                $sql .= " AND (name LIKE '%" . $search . "%')";
            }

            $sql .= " ORDER BY id DESC";

            $campaignSms = $this->db->select($sql);
            if ($campaignSms) {
                return ['success' => true, 'items' => $campaignSms];
            } else {
                return ['success' => false, 'items' => []];
            }
        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return ['success' => false, 'items' => [], 'error' => $e->getMessage()];
        }
    }

    function verifyExcel($file)
    {
        $allowedExtensions = ['xls', 'xlsx'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'El archivo debe ser un Excel con extensión .xls o .xlsx.'];
        }

        try {
            $tempPath = $file['tmp_name'];
            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();

            $highestColumn = $sheet->getHighestColumn();
            $headers = $sheet->rangeToArray("A1:{$highestColumn}1")[0];

            $fileHeaders = array_map('strtoupper', $headers);

            $rows = $sheet->rangeToArray("A2:{$highestColumn}11");
            $preview = [];
            foreach ($rows as $row) {
                $row = array_map('trim', $row);
                $preview[] = array_combine($fileHeaders, $row);
            }

            usort($preview, function ($a, $b) {
                return $a['IDENTIFICADOR'] <=> $b['IDENTIFICADOR'];
            });

            $firstFiveRecords = array_slice($preview, 0, 5);

            return [
                'success' => true,
                'message' => 'El archivo es válido.',
                'headers' => $fileHeaders,
                'topRecords' => $firstFiveRecords
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al procesar el archivo: ' . $e->getMessage()];
        }
    }

    function insert($request)
    {
        $requiredFields = ['name', 'phone', 'identity', 'message'];
        foreach ($requiredFields as $field) {
            if (empty($request[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
        }

        $file = $request['file'] ?? null;

        if (!$file) {
            return ['success' => false, 'message' => 'Debe subir un archivo Excel.'];
        }

        $allowedExtensions = ['xls', 'xlsx'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'El archivo debe ser un Excel con extensión .xls o .xlsx.'];
        }

        try {
            $tempPath = $file['tmp_name'];
            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();

            $highestColumn = $sheet->getHighestColumn();
            $headers = $sheet->rangeToArray("A1:{$highestColumn}1")[0];
            $fileHeaders = array_map('strtoupper', $headers);

            $requiredHeaders = [strtoupper($request['phone']), strtoupper($request['identity'])];
            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $fileHeaders)) {
                    return ['success' => false, 'message' => "El archivo no contiene la cabecera requerida: $header."];
                }
            }

            $quantity = strlen($request['message']);

            $campaignData = [
                'name' => $request['name'],
                'identity' => $request['identity'],
                'phone' => $request['phone'],
                'quantity' => $quantity,
                'preview' => $request['message'],
                'status' => 'CARGADO',
                'created_at' => date('Y-m-d H:i:s'),
                'idCedente' => $this->idCedente,
                'idMandante' => $this->idMandante
            ];
            $campaignId = $this->db->insertWithParams('msj_campaign_sms', $campaignData);

            if (!$campaignId) {
                return ['success' => false, 'message' => 'Error al insertar la campaña.'];
            }

            $rows = $sheet->rangeToArray("A2:{$highestColumn}" . $sheet->getHighestRow());
            $previewData = [];

            foreach (array_chunk($rows, 1000) as $chunk) {
                foreach ($chunk as $row) {
                    $row = array_combine($fileHeaders, array_map('trim', $row));
                    $customVariables = json_encode($row);

                    $message = $this->generateMessage($request['message'], $row);
                    $largoMensaje = strlen($message);

                    $regex = '/[^0-9a-zA-ZñÑ.+\-\/\(\)#%,@:\S+ ]/u';
                    $contieneAcentos = preg_match($regex, $message) ? 'SI' : 'NO';

                    $partes = 1;
                    if ($contieneAcentos === 'SI') {
                        if ($largoMensaje > 160) {
                            $partes = ceil($largoMensaje / 157);
                        }
                    } else {
                        if ($largoMensaje > 160) {
                            $partes = ceil($largoMensaje / 160);
                        }
                    }

                    if (count($previewData) < 5) {
                        $previewData[] = [
                            'largo' => $largoMensaje,
                            'contieneAcentos' => $contieneAcentos,
                            'cantSms' => $partes,
                            'customVariables' => $customVariables,
                            'updatedMessage' => $message,
                        ];
                    }

                    $smsData = [
                        'identity' => $row[strtoupper($request['identity'])],
                        'phone' => $row[strtoupper($request['phone'])],
                        'customVariables' => $customVariables,
                        'campaign_sms_id' => $campaignId,
                        'message' => $message,
                        'quantity' => $largoMensaje,
                        'cant_sms' => $partes,
                        'special_characters' => $contieneAcentos === 'SI' ? 0 : 1,
                        'status' => 'CARGADO',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insertWithParams('msj_data_sms', $smsData);
                }
            }

            return [
                'success' => true,
                'message' => 'Campaña creada y datos almacenados exitosamente.',
                'campaignSmsId' => $campaignId,
                'previewData' => $previewData
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al procesar la campaña: ' . $e->getMessage()];
        }
    }

    function changeStatus($id,$status){
        try {
            if (empty($id) || empty($status)) {
                return ['success' => false, 'message' => 'El ID y el estado son obligatorios.'];
            }

            $sql = " UPDATE msj_campaign_sms
            SET status = '".$status."', updated_at = NOW()
            WHERE id = ".$id;

            $result = $this->db->query($sql);

            if (!$result) {
                return ['success' => false, 'message' => 'Error al cambiar el estado de la campaña.'];
            }

            return ['success' => true, 'message' => 'Estado de la campaña actualizado correctamente.'];

        } catch (\Exception $e) {
            $this->logs->error("Error al cambiar el estado de la campaña: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al cambiar el estado de la campaña.',
                'error' => $e->getMessage(),
            ];
        }
    }

    function delete($id){
        $sql = "SELECT * FROM msj_campaign_sms WHERE id = ".$id;
        $template = $this->db->select($sql);

        if (!$template) {
            $this->logs->error("No se encontró la plantilla por el id: " . $id);
            return [
                'success' => false,
                'message' => 'No se encontró la plantilla',
            ];
        }
        try {

            $sqlDelete = "UPDATE msj_campaign_sms 
                      SET isDeleted = 1, deleted_at = NOW()
                      WHERE id = " . intval($id);

            $this->db->query($sqlDelete);

            return [
                'success' => true,
                'message' => 'Campaña eliminada correctamente',
            ];

        }catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error al clonar la plantilla',
                'error' => $e->getMessage(),
            ];
        }
    }


    private function generateMessage($template, $data)
    {
        return preg_replace_callback('/\[(\w+)\]/', function ($matches) use ($data) {
            $key = strtoupper($matches[1]);
            return $data[$key] ?? $matches[0];
        }, $template);
    }

    private function hasSpecialCharacters($message)
    {
        return preg_match('/[^0-9a-zA-ZñÑ.+-\\/\(\)#%,@:\S+]/', $message);
    }
}
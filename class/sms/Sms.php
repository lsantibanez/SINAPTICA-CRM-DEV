<?php

include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../db/DB.php');
include_once(__DIR__ . '/../logs.php');
include_once(__DIR__ . '/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Campaign
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

    function getAllSms($search = null) {
        try {

            $sqlTemplate = "SELECT id, name, urlPreview, created_at, enable 
                        FROM msj_sms 
                        WHERE idCedente='" . $this->idCedente . "' 
                        AND idMandante='" . $this->idMandante . "' 
                        AND isDeleted = 0";


            if ($search) {
                $sqlTemplate .= " AND (name LIKE '%" . $search . "%')";
            }

            $sqlTemplate .= " ORDER BY id DESC";

            $templates = $this->db->select($sqlTemplate);

            if ($templates) {
                return ['success' => true, 'items' => $templates];
            } else {
                return ['success' => false, 'items' => []];
            }
        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return ['success' => false, 'items' => [], 'error' => $e->getMessage()];
        }
    }


}
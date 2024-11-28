<?php

require_once __DIR__.'/../db/DB.php';
require_once __DIR__.'/../logs.php';

class Tablas 
{
  private $db;
  private $logs;
  private $idCedente;

  public function __construct()
  {
    $this->db = new Db();
    $this->logs = new Logs();
    $this->idCedente = (int) $_SESSION['cedente'];
  }

  public function getTablas()
  {
    $lista = [];
    try {
      $strSql = 'SELECT * FROM SIS_Tablas WHERE Id_Cedente = '.$this->idCedente.' AND view = 1 ORDER BY nombre ASC;';
      $result = $this->db->select($strSql);
      if ($result) {
        return (array) $result;
      }
    } catch (\Exception $ex){
      $this->logs->error($ex);
    }
    return $lista;
  }
}
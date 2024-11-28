<?php

class Logs
{

  private $file = null;

  public function __construct()
  {
    $this->init();
  }

  private function init($name = '')
  {
    $directory = realpath(__DIR__.'/../logs/');
    $archivo = $directory.'/app'.$name.'.log';
    if (!file_exists($directory)) mkdir($directory, 0777);
    $this->file = fopen($archivo, 'a');
  }
    
  public function debug($data)
  {
    $this->init('_debug'); 
    if (is_object($data) || is_array($data)) {
      $data = json_encode($data);
    }

    $data = date('d-m-Y H:i:s').': '.$data.PHP_EOL;
    fwrite($this->file, $data);
    fclose($this->file);
  }

  public function error($data)
  {
    $this->init('_error'); 
    if (is_object($data) || is_array($data)) {
      $data = json_encode($data);
    }

    $data = date('d-m-Y H:i:s').': '.$data.PHP_EOL;
    fwrite($this->file, $data);
    fclose($this->file);
  }
}
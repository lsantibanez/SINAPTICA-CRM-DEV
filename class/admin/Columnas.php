<?php

class Columnas
{
  private $db;
  private $idCedente;
  private $tiposDato = [
    '0' => 'Numérico',
    '1' => 'Fecha',
    '2' => '',
    '3' => 'Texto',
    '4' => 'Selección multiple'
  ];
  private $logicas = [
    '0' => [
      'Menor',
      'Mayor',
      'Igual',
      'Menor o Igual',
      'Mayor o Igual',
      'Distinto'
    ],
    '1' => [
      'Igual',
      'Distinto'
    ]
  ];

  public function __construct()
  {
    $this->db = new DB();
    $this->idCedente = (int) $_SESSION['cedente'];
  }

  public function getLista()
  {
    $htmlRespuesta = '<tr><td colspan="5" style="text-align: center;"><h5>No hay datos</h5></td></tr>';

    $lista = $this->db->select('SELECT t.nombre AS tabla, c.* FROM SIS_Tablas AS t JOIN SIS_Columnas_Estrategias AS c ON c.id_tabla = t.id WHERE t.Id_Cedente = '.$this->idCedente.' ORDER BY t.nombre ASC;');
    if ($lista) {
      // var_dump($lista);
      $htmlRespuesta = '';
      foreach ((array) $lista as $columna) {
        $tipoDato = (isset($this->tiposDato[$columna['tipo_dato']]))? $this->tiposDato[$columna['tipo_dato']]:'N/D';
        $logica = (isset($this->logicas[$columna['logica']]))? '<li>'.implode('<li>', array_map(function($i) { return $i.'</li>'; }, $this->logicas[$columna['logica']])) :'<li>N/D</li>';
        $htmlRespuesta .= '<tr>'.PHP_EOL;
        $htmlRespuesta .= '<td>'.$columna['tabla'].'</td>'.PHP_EOL;
        $htmlRespuesta .= '<td>'.$columna['columna'].'</td>'.PHP_EOL;
        $htmlRespuesta .= '<td style="text-align: center;">'.$tipoDato.'</td>'.PHP_EOL;
        $htmlRespuesta .= '<td style="text-align: left;"><ul>'.$logica.'</ul></td>'.PHP_EOL;
        $htmlRespuesta .= '<td>';
        //$htmlRespuesta .= '<div class="btn-group btn-group-sm">';
        $htmlRespuesta .= '<button type="button" class="btn btn-info" id="editColumn"><i class="fa fa-pencil"></i></button>';
        $htmlRespuesta .= '<button type="button" class="btn btn-danger" id="deleteColumn" style="margin-left: 5px;"><i class="fa fa-trash"></i></button>';
        //$htmlRespuesta .= '</div>';
        $htmlRespuesta .= '</>'.PHP_EOL;
        $htmlRespuesta .= '</td>'.PHP_EOL;
      }
    }
    return $htmlRespuesta;
  }
}
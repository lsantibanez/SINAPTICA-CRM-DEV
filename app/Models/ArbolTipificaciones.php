<?php
namespace Models;

use \Illuminate\Database\Eloquent\Model;

class ArbolTipificaciones extends Model
{  
  protected $table = 'arbol_tipificaciones';

  public function nivel2()
  {
    return $this->hasMany('\Models\ArbolTipificaciones', 'padre_id','id');
  }

  public function nivel3()
  {
    return $this->hasMany('\Models\ArbolTipificaciones', 'padre_id','id');
  }

  public function nivel4()
  {
    return $this->hasMany('\Models\ArbolTipificaciones', 'padre_id','id');
  }

}
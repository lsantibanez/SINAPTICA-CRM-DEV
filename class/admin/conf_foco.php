<?php
/**
* Clase para configuracion de Foco
*/
class ConfFoco
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

	public function getFocoConfiguracion()
    {
        //$db = new DB();
        $sql = "SELECT * FROM fireConfig WHERE id = 1 LIMIT 1";
        $result = $this->db->select($sql);
        return $result;
    }
    
    public function guardarConfiguracion($codigo, $sistema, $menu, $ipServidor, $mandantes, $cedentes, $evaluacion, $correos, $sonidoNotificaciones)
    {
        $db = new DB();
        $focoConfig = $this->getFocoConfiguracion();
        if(count((array) $focoConfig) > 0) {
            $sql = "UPDATE 
                        fireConfig 
                    SET 
                        CodigoFoco = '" . $codigo . "', 
                        tipoSistema = '" . $sistema . "', 
                        cantidadMaxMandantes = '" . $mandantes . "', 
                        cantidadMaxCedentes = '" . $cedentes . "', 
                        NotaMaximaEvaluacion = '" . $evaluacion . "', 
                        tipoMenu = '" . $menu . "', 
                        IpServidorDiscado = '" . $ipServidor . "', 
                        cantidadCorreos = '" . $correos . "',
                        sonidoNotificaciones = '" . $sonidoNotificaciones . "' 
                    WHERE 
                        id = '" . $focoConfig[0]["id"]. "'";
        } else {
            $sql = "INSERT INTO 
                        fireConfig 
                            (CodigoFoco, tipoSistema, cantidadMaxMandantes, cantidadMaxCedentes, NotaMaximaEvaluacion, 
                                tipoMenu, IpServidorDiscado, cantidadCorreos, sonidoNotificaciones) 
                    VALUES 
                        ('" . $codigo . "', '" . $sistema . "', '" . $mandantes . "', '" . $cedentes . "', 
                        '" . $evaluacion . "', '" . $menu . "', '" . $ipServidor . "', '" . $correos . "', '" . $sonidoNotificaciones . "')";
        }
        
        $response = $this->db->query($sql);
        if($response){
            $ToReturn = true;
        }else{
            $ToReturn = false;
        }
        return $ToReturn;
    }
}
?>
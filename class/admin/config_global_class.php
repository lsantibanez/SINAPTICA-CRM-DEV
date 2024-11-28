<?php
    require_once('../db/DB.php');
    
    class ConfigGlobal {
           
        public function crear_conf_global( $idioma, $longitud_telefono, $moneda, $simbolo, $time_start, $time_end){
        
            if(!empty($idioma) && !empty($moneda)  && !empty($simbolo) && !empty($time_start) && !empty($time_end) && !empty($longitud_telefono)){
                $idioma_clear = $moneda_clear = $simbolo_clear = $time_start_clear = $time_end_clear = $longitud_telefono_clear = '';
                $idioma_clear = trim($idioma);
                $moneda_clear = trim($moneda);
                $simbolo_clear = trim($simbolo);
                $time_start_clear = trim($time_start);
                $time_end_clear = trim($time_end);
                $longitud_telefono_clear = trim($longitud_telefono);
    
                $db = new DB();

                $sql = "INSERT INTO conf_global(idioma, moneda, simbolo, hora_inicio, hora_fin, longitud_telefono )
                            VALUES ('$idioma_clear', '$moneda_clear', '$simbolo_clear', '$time_start_clear', '$time_end_clear', '$longitud_telefono_clear')";

                $result = $db->query($sql);

                if($result){
                    echo "Registro Insertado";
                }else{
                    echo "Hubo un problema al tratar de insertar el registro";
                }
            } else {
                echo "Todos los campos son requeridos";
                return false;
            } 
        }
    }
?> 
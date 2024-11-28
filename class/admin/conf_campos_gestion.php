<?php
include_once __DIR__.'/../db/DB.php';

class ConfCamposGestion
{
    private $db;
    private $idCedente;

    public function __construct()
    {
        $this->db = new Db();
        $this->idCedente = (int) $_SESSION['cedente'];
    }
    
    public  function getCampoTableList()
    {
        // $db = new DB();
        $SqlCampoTableList = "SELECT
                                campos_gestion.id,
                                campos_gestion.Codigo,
                                campos_gestion.Titulo,
                                campos_gestion.ValorEjemplo,
                                campos_gestion.ValorPredeterminado,
                                tipos_campos_reclutamiento.Nombre AS Tipo,
                                campos_gestion.Dinamico,
                                campos_gestion.Mandatorio,
                                campos_gestion.Deshabilitado,
                                campos_gestion.Cedente
                             FROM
                                campos_gestion
                             INNER JOIN tipos_campos_reclutamiento 
                                ON tipos_campos_reclutamiento.id = campos_gestion.Tipo
                             WHERE campos_gestion.Cedente = {$this->idCedente}";
        $CampoTableList = $this->db->select($SqlCampoTableList);
        return $CampoTableList;
    }

    public function CrearCampo($Codigo,$Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$ArrayOpciones,$Cedente,$Respuesta_Nivel3)
    {
        $ToReturn = array();
        $ToReturn["result"] = false;
        // $db = new DB();
        $SqlInsert = "insert into campos_gestion (Codigo,Titulo,ValorEjemplo,ValorPredeterminado,Tipo,Dinamico,Mandatorio,Deshabilitado,Cedente,Respuesta_Nivel3) values ('".$Codigo."','".$Titulo."','".$ValorEjemplo."','".$ValorPredeterminado."','".$Tipo."','1','".$Mandatorio."','".$Deshabilitado."','".implode(',',$Cedente)."','".implode(',',$Respuesta_Nivel3)."')";
        $idCampo = $this->db->insert($SqlInsert);
        if($idCampo){
            switch($Tipo){
                case "3":
                case "4":
                    $this->AgregarOpcionesCampo($idCampo,$ArrayOpciones);
                break;
            }
            $ToReturn["result"] = true;
        }
        return $ToReturn;
    }

    public function ValidacionCodigoAgregar($Codigo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlValidacion = "select Codigo from campos_gestion where Codigo='".$Codigo."'";
            $Validacion = $this->db->select($SqlValidacion);
            if(count((array) $Validacion) == 0){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function AgregarOpcionesCampo($idCampo,$Opciones){
            // $db = new DB();
            foreach((array) $Opciones as $Opcion){
                $Prioridad = $Opcion["Prioridad"];
                $Texto = $Opcion["Nombre"];
                $Seleccionado = $Opcion["Seleccionado"];
                $SqlInsert = "insert into opciones_campos_gestion (id_campo,Prioridad,Nombre,Seleccionado) values ('".$idCampo."','".$Prioridad."','".$Texto."','".$Seleccionado."')";
                $Insert = $this->db->query($SqlInsert);
            }
        }
        function getCampo($idCampo){
            // $db = new DB();
            $SqlCampo = "select * from campos_gestion where id = '".$idCampo."'";
            $Campo = $this->db->select($SqlCampo);
            $Campo = $Campo[0];
            $Opciones = $this->getOpcionesCampo($idCampo);
            $Campo['Opciones'] = $Opciones;
            return $Campo;
        }
        function updateCampo($Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$Cedente,$Respuesta_Nivel3,$idCampo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlUpdate = "update campos_gestion set Titulo = '".$Titulo."', ValorEjemplo = '".$ValorEjemplo."', ValorPredeterminado = '".$ValorPredeterminado."', Tipo = '".$Tipo."', Mandatorio = '".$Mandatorio."', Deshabilitado = '".$Deshabilitado."', Cedente = '".implode(',',$Cedente)."', Respuesta_Nivel3 = '".implode(',',$Respuesta_Nivel3)."' where id = '".$idCampo."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function CrearOpcionCampo($Prioridad,$Opcion,$Seleccionado,$idCampo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlInsert = "insert into opciones_campos_gestion (id_campo,Prioridad,Nombre,Seleccionado) values ('".$idCampo."','".$Prioridad."','".$Opcion."','".$Seleccionado."')";
            $idOpcion = $this->db->insert($SqlInsert);
            if($idOpcion){
                $ToReturn["id"] = $idOpcion;
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteOpcionCampo($idOpcion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "delete from opciones_campos_gestion where id = '".$idOpcion."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar opciones_campos_gestion';   
            }
            return $ToReturn;
        }
        function deleteCampo($idCampo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "delete from campos_gestion where id = '".$idCampo."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $SqlDelete = "delete from orden_campos_gestion where id_campo = '".$idCampo."'";
                $Delete = $this->db->query($SqlDelete);
                if($Delete){
                    $SqlDelete = "delete from opciones_campos_gestion where id_campo = '".$idCampo."'";
                    $Delete = $this->db->query($SqlDelete);
                    if($Delete){
                        $SqlDelete = "delete from respuestas_campos_gestion where id_campo = '".$idCampo."'";
                        $Delete = $this->db->query($SqlDelete);
                        if($Delete){
                            $ToReturn["result"] = true;
                        }else{
                            $ToReturn["result"] = false;
                            $ToReturn["message"] = 'Error al eliminar respuestas_campos_gestion';
                        }
                    }else{
                        $ToReturn["result"] = false;
                        $ToReturn["message"] = 'Error al eliminar opciones_campos_gestion';
                    }
                }else{
                    $ToReturn["result"] = false;
                    $ToReturn["message"] = 'Error al eliminar orden_campos_gestion';
                }
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar campos_gestion';
            }
            return $ToReturn;
        }
        function getOrdenCampos($Respuesta_Nivel3){
            $ToReturn = array();
            // $db = new DB();
            $SqlOrden = "   SELECT
                                c.*,
                                o.Anchura
                            FROM
                                campos_gestion c
                            INNER JOIN 
                                orden_campos_gestion o ON o.id_campo = c.id
                            WHERE
                                FIND_IN_SET('".$this->idCedente."',c.Cedente)
                            AND
                                o.Cedente = ".$this->idCedente."
                            AND
                                (   
                                    FIND_IN_SET('".$Respuesta_Nivel3."',c.Respuesta_Nivel3)
                                OR 
                                    c.Respuesta_Nivel3 IS NULL
                                )
                            ORDER BY
                                o.Prioridad ASC";
            $Orden = $this->db->select($SqlOrden);
            if($Orden){
                foreach((array) $Orden as $Campo){
                    $ArrayTmp = array();
                    $ArrayTmp["idCampo"] = $Campo["id"];
                    $ArrayTmp["Codigo"] = $Campo["Codigo"];
                    $ArrayTmp["Titulo"] = $Campo["Titulo"];
                    $ArrayTmp["ValorEjemplo"] = $Campo["ValorEjemplo"];
                    $ArrayTmp["ValorPredeterminado"] = $Campo["ValorPredeterminado"];
                    $ArrayTmp["Tipo"] = $Campo["Tipo"];
                    $ArrayTmp["Dinamico"] = $Campo["Dinamico"];
                    $ArrayTmp["Mandatorio"] = $Campo["Mandatorio"];
                    $ArrayTmp["Deshabilitado"] = $Campo["Deshabilitado"];
                    $ArrayTmp["Anchura"] = $Campo["Anchura"];
                    $ArrayTmp["CampoDB"] = $Campo["CampoDB"];
                    array_push($ToReturn,$ArrayTmp);
                }
            }
            return $ToReturn;
        }
        function getOpcionesCampo($idCampo){
            $ToReturn = array();
            // $db = new DB();
            $SqlOpciones = "select
                                *
                            from
                                opciones_campos_gestion
                            where
                                opciones_campos_gestion.id_campo = '".$idCampo."'
                            order by
                                Prioridad ASC";
            $Opciones = $this->db->select($SqlOpciones);
            foreach((array) $Opciones as $Opcion){
                $ArrayTmp = array();
                $ArrayTmp["id"] = $Opcion["id"];
                $ArrayTmp["Nombre"] = utf8_encode($Opcion["Nombre"]);
                $ArrayTmp["Prioridad"] = $Opcion["Prioridad"];
                $ArrayTmp["Seleccionado"] = $Opcion["Seleccionado"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function CamposEstaticos(){
            // $db = new DB();
            $SqlCamposRespuestas = "select
                                        campos_gestion.Codigo,
                                        campos_gestion.Tipo,
                                        campos_gestion.CampoDB
                                    from
                                        campos_gestion
                                    where
                                        campos_gestion.Dinamico='0'";
            $CamposRespuestas = $this->db->select($SqlCamposRespuestas);
            return $CamposRespuestas;
        }
        function RespuestasCamposDinamicos(){
            // $db = new DB();
            $SqlCamposRespuestas = "select
                                        campos_gestion.Codigo,
                                        campos_gestion.Tipo,
                                        respuestas_campos_gestion.Valor
                                    from
                                        campos_gestion
                                            left join respuestas_campos_gestion on respuestas_campos_gestion.id_campo = campos_gestion.id
                                    where
                                        respuestas_campos_gestion.id_usuario='".$_SESSION["idUsuario_gestion"]."' and
                                        campos_gestion.Dinamico='1'";
            $CamposRespuestas = $this->db->select($SqlCamposRespuestas);
            return $CamposRespuestas;
        }
        function EliminarRespuestasCampos(){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "delete from respuestas_campos_gestion where id_usuario='".$_SESSION["idUsuario_gestion"]."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function RegistrarRespuestasCampos($Codigo,$Valor,$id_gestion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $Campo = $this->getCampoFromCodigo($Codigo);
            $Campo = $Campo[0];
            $SqlInsert = "insert into respuestas_campos_gestion (id_campo,id_gestion,Valor) values('".$Campo["id"]."','".$id_gestion."','".$Valor."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getCampoFromCodigo($Codigo){
            // $db = new DB();
            $SqlCampo = "select * from campos_gestion where Codigo='".$Codigo."'";
            $Campo = $this->db->select($SqlCampo);
            return $Campo;
        }
        function getCamposSinOrden($Cedente){
            // $db = new DB();
            $SqlCampos = "select
                                campos_gestion.id,
                                campos_gestion.Codigo,
                                tipos_campos_reclutamiento.Nombre as Tipo
                            from
                                campos_gestion
                                    inner join tipos_campos_reclutamiento on tipos_campos_reclutamiento.id = campos_gestion.Tipo
                            where 
                                campos_gestion.id NOT IN(SELECT id_campo FROM orden_campos_gestion WHERE Cedente = ".$Cedente.")
                            and
                                FIND_IN_SET('".$Cedente."',campos_gestion.Cedente)";
            $Campos = $this->db->select($SqlCampos);
            return $Campos;
        }
        function getCamposConOrden($Cedente){
            // $db = new DB();
            $WhereCedente = $Cedente != "" ? "where FIND_IN_SET('".$Cedente."',campos_gestion.Cedente) and orden_campos_gestion.Cedente = ".$Cedente : "";
            $SqlCampos = "select
                                campos_gestion.id,
                                campos_gestion.Codigo,
                                tipos_campos_reclutamiento.Nombre as Tipo,
                                orden_campos_gestion.Anchura
                            from
                                campos_gestion
                                    inner join orden_campos_gestion on orden_campos_gestion.id_campo = campos_gestion.id
                                    inner join tipos_campos_reclutamiento on tipos_campos_reclutamiento.id = campos_gestion.Tipo
                            ".$WhereCedente."
                            order by
                                orden_campos_gestion.Prioridad";
            $Campos = $this->db->select($SqlCampos);
            return $Campos;
        }
        function deleteOrdenCampos($Cedente){
            // $db = new DB();
            $SqlDelete = "delete from orden_campos_gestion where Cedente = '".$Cedente."'";
            $Delete = $this->db->query($SqlDelete);
        }
        function agregarOrdenCampos($Campos,$Cedente){
            // $db = new DB();
            $Cont = 1;
            foreach((array) $Campos as $Campo){
                $Anchura = $Campo["Anchura"];
                $idCampo = $Campo["Campo"];
                $SqlInsert = "insert into orden_campos_gestion (Prioridad,id_campo,Anchura,Cedente) values ('".$Cont."','".$idCampo."','".$Anchura."','".$Cedente."')";
                $Insert = $this->db->query($SqlInsert);
                $Cont++;
            }
        }
        function showNivel3(){

            $query = "  SELECT 
                            Nivel3.Id AS id, 
                            Nivel3.Respuesta_N3 AS nivel_3,
                            Nivel2.Respuesta_N2 AS nivel_2,
                            Nivel1.Respuesta_N1 AS nivel_1
                        FROM 
                            Nivel3
                        INNER JOIN 
                            Nivel2 
                        ON 
                            Nivel3.Id_Nivel2 = Nivel2.Id
                        INNER JOIN 
                            Nivel1 
                        ON 
                            Nivel2.Id_Nivel1 = Nivel1.Id
                        WHERE 
                            Nivel1.Id_Cedente = '".$this->idCedente."'";
            $db = new Db;
            $ToReturn = $this->db->select($query);

            return $ToReturn;

        }
        function RespuestaCampoDinamicoByUsuarioAndCampo($idUsuario,$idCampo){
            // $db = new DB();
            $ToReturn = "";
            $SqlCamposRespuesta = "select
                                        respuestas_campos_gestion.Valor
                                    from
                                        respuestas_campos_gestion
                                    where
                                        respuestas_campos_gestion.id_usuario='".$idUsuario."' and
                                        respuestas_campos_gestion.id_campo='".$idCampo."'";
            $CampoRespuesta = $this->db->select($SqlCamposRespuesta);
            $ToReturn = count((array) $CampoRespuesta) > 0 ? $CampoRespuesta[0]["Valor"] : "";
            return $ToReturn;
        }
    }
?>
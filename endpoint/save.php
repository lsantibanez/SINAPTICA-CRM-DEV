<?php
require __DIR__.'/../class/db/DB.php';
require_once __DIR__.'/../class/logs.php';

// Verificamos que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $logs = new Logs();
        $db = new Db();
        $db->connect();
        $mysqli = $db->getInstance();
        // Obtener el cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        $logs->debug($data);
        if($data) {
            $pesoNivel = 0;
            $idTipoGestion = 0;
            $tieneCompromiso = false;
            if (!isset($data['peso']) || empty($data['peso']) || is_null($data['peso'])) {
                if (isset($data['n4']) && !empty($data['n4'])) {
                    $stmt0 = $mysqli->prepare('SELECT Peso FROM Nivel4 WHERE id = ?');
                    $stmt0->bind_param('s',
                        $data['n4']
                    );
                    $stmt0->execute();
                    $result = $stmt0->get_result();
                    $dato = $result->fetch_assoc();
                    $pesoNivel = (int) $dato['Peso'];
                } else if (empty($data['n4']) && isset($data['n3']) && !empty($data['n3'])) {
                    $stmt0 = $mysqli->prepare('SELECT Peso FROM Nivel3 WHERE id = ?');
                    $stmt0->bind_param('s',
                        $data['n3']
                    );
                    $stmt0->execute();
                    $result = $stmt0->get_result();
                    $dato = $result->fetch_assoc();
                    $pesoNivel = (int) $dato['Peso'];
                } else if (empty($data['n3']) && (isset($data['n2']) && !empty($data['n2']))) {
                    $stmt0 = $mysqli->prepare('SELECT Ponderacion FROM Nivel2 WHERE id = ?');
                    $stmt0->bind_param('s',
                        $data['n2']
                    );
                    $stmt0->execute();
                    $result = $stmt0->get_result();
                    $dato = $result->fetch_assoc();
                    $pesoNivel = (int) $dato['Ponderacion'];
                }
            } else {
                $pesoNivel = (int) $data['peso'];
            }
    
            if (isset($data['tipo_contanto']) && !empty($data['tipo_contanto'])) {
                $idTipoGestion = (int) $data['tipo_contanto'];
            }

            if (!empty($data['monto_comp'])) {
                $data['monto_comp'] = str_replace('.','', $data['monto_comp']);
                $data['monto_comp'] = str_replace(',','.', $data['monto_comp']);
            }

            if (!empty($data['monto_comp']) && (!is_null($data['fec_compromiso']) && !empty($data['fec_compromiso']))) {
                $tieneCompromiso = true;
                $fechaCompromiso = $data['fec_compromiso'];
                $montoCompromiso = $data['monto_comp'];
                $logs->debug('Fecha compromiso: '. $fechaCompromiso);
                $logs->debug('Monto compromiso: '. $montoCompromiso);
            }
    
            $servicio = 'Discador';
            $stmt = $mysqli->prepare("INSERT INTO gestion_ult_trimestre (rut_cliente, fecha_gestion, hora_gestion, fechahora, resultado, observacion, fono_discado, lista, nombre_ejecutivo, duracion, cedente, fec_compromiso, monto_comp, url_grabacion, n1, n2, n3, Peso, canales, Id_TipoGestion, cod_campaign, cod_list, dst, dst_name, n4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssssssssdssssssdsssss",
                $data['rut_cliente'],
                $data['fecha_gestion'],
                $data['hora_gestion'],
                $data['fechahora'],
                $data['resultado'],
                $data['observacion'],
                $data['fono_discado'],
                $data['lista'],
                $data['ejecutivo'],
                $data['duracion'],
                $data['cedente'],
                $data['fec_compromiso'],
                $data['monto_comp'],
                $data['url_grabacion'],
                $data['n1'],
                $data['n2'],
                $data['n3'],
                $pesoNivel,
                $servicio,
                $idTipoGestion,
                $data['cod_campaign'],
                $data['cod_list'],
                $data['uniqueid'],
                $data['callerid'],
                $data['n4'],
            );
    
            if($stmt->execute()) {
                $respuesta = [
                    'success' => true,
                    'message' => 'Datos guardados correctamente',
                    'compromiso' => false,
                ];
                
                if ($tieneCompromiso) {
                    try {
                        $last_id = (int) $mysqli->insert_id;
                        $logs->debug('Gestión ID: '.$last_id);
                       
                        $stmt3 = $mysqli->prepare("INSERT INTO compromisos (compromiso_id,cedente_id,rut,telefono,ejecutivo,fecha_compromiso,monto_compromiso,fecha_operacion) VALUES (?,?,?,?,?,?,?,?);");
                        $stmt3->bind_param('iissssss',
                            $last_id,
                            $data['cedente'],
                            $data['rut_cliente'],
                            $data['fono_discado'],
                            $data['ejecutivo'],
                            $fechaCompromiso,
                            $montoCompromiso,
                            $data['fechahora']
                        );
                        $logs->debug('Aquí');
                        
                        if($stmt3->execute()) {
                            $respuesta['compromiso'] = true;
                            $lastId = (int) $mysqli->insert_id;
                            $logs->debug('Compromiso ID: '.$lastId);
                        }
                        
                    } catch (\Exception $ex) {
                        $logs->debug('Error: '.$ex->getMessage());
                        $logs->error($ex->getMessage());
                    }
                }
                echo json_encode($respuesta);
            } else {
                echo json_encode(["success" => false, "message" => "Error al guardar los datos"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "JSON inválido"]);
        }
    } catch (\Exception $ex) {
        $logs->error($ex->getMessage());
        echo json_encode(["success" => false, "message" => "Petición no válida"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$stmt->close();
$mysqli->close();

<?php

include_once(__DIR__.'/../../vendor/autoload.php');
include_once(__DIR__.'/../db/DB.php');
include_once(__DIR__.'/../logs.php');
include_once(__DIR__.'/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

class Template {
    public $idCedente;
    public $idMandante;

    private $db;
    private $logs;

    function __construct()
    {
        $this->idMandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
        $this->idCedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
        $this->db = new Db();
        $this->logs = new Logs();
    }

    function getAllTemplates($search = null) {
        try {
            $sqlTemplate = "SELECT id, name, urlPreview, created_at, enable 
                        FROM mail_templates 
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

    function getTemplate($id)
    {
        try{
            $sql = "SELECT * FROM mail_templates WHERE id= ".$id;
            $template = $this->db->select($sql);
            if (!$template) {
                $this->logs->error("No se encontró la plantilla por el id: " . $id);
                return [
                    'success' => false,
                    'items' => [],
                ];
            }
            return ['success' => true, 'item' => $template[0]];
        }catch(Exception $e){
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar el estado',
                'error' => $e->getMessage(),
            ];
        }
    }
    function insert($request)
    {
        $templateName = $this->db->escape($request['name']);
        $sqlCheck = "SELECT COUNT(*) as count FROM mail_templates WHERE name = '$templateName'";
        $result = $this->db->query($sqlCheck);

        if ($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count > 0) {
                throw new \Exception("Ya existe una plantilla con el nombre '$templateName'.");
            }
        } else {
            throw new \Exception("Error al ejecutar la consulta: " . $this->db->error);
        }

        $imageData = $request['screenshot'];
        $imageData = str_replace('data:image/png;base64,', '', $imageData);

        try {

            $imageData = $request['screenshot'];
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('La decodificación de Base64 falló.');
            }

            $imageDir = realpath(__DIR__ . '/../../') . '/document_storage/templates';

            if (!file_exists($imageDir)) {
                if (!mkdir($imageDir, 0775, true)) {
                    throw new \Exception("No se pudo crear el directorio '$imageDir'.");
                }
            }

            $imageFileName = uniqid() . '.png';
            $imagePath = $imageDir . '/' . $imageFileName;

            $stored = file_put_contents($imagePath, $imageData);

            if ($stored === false) {
                throw new \Exception("Error al guardar la imagen en $imagePath.");
            }

            $imageUrl = rtrim($_ENV['APP_URL'] ?? 'http://default-url.test/', '/') . '/document_storage/templates/' . $imageFileName;

            $jsonContent = $request['json_content'];
            $htmlContent = $request['html_content'];

            preg_match_all('/\|\|([A-Z0-9_]+)\|\|/', $htmlContent, $matches);

            $variables = [];
            if (!empty($matches[1])) {
                $variables = array_unique($matches[1]);
            }

            $sqlInsert = "INSERT INTO mail_templates (name, html_content, json_content, urlPreview, base64Image, customVariables, created_at, updated_at, idCedente, idMandante) 
              VALUES ('" . $this->db->escape($request['name']) . "', 
                      '" . $this->db->escape($htmlContent) . "', 
                      '" . $this->db->escape($jsonContent) . "', 
                      '" . $this->db->escape($imageUrl) . "', 
                      '" . $this->db->escape($request['screenshot']) . "', 
                      '" . $this->db->escape(json_encode($variables)) . "', 
                      NOW(), NOW(), 
                      '" . $this->idCedente . "', 
                      '" . $this->idMandante . "')";


            $this->logs->debug($sqlInsert);

            $this->db->query($sqlInsert);

            return [
                'success' => true,
                'message' => 'Plantilla creada correctamente!',
            ];

        } catch (\Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error al guardar la plantilla',
                'error' => $e->getMessage(),
            ];
        }
    }
    function update($request)
    {
        $templateId = $this->db->escape($request['id']);
        $templateName = $this->db->escape($request['name']);

        try {
            $sqlGetImage = "SELECT urlPreview FROM mail_templates WHERE id = '$templateId'";
            $result = $this->db->query($sqlGetImage);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentImageUrl = $row['urlPreview'];

                $imageDir = realpath(__DIR__ . '/../../') . '/document_storage/templates/';
                $currentImagePath = str_replace(rtrim($_ENV['APP_URL'] ?? 'http://default-url.test/', '/') . '/document_storage/templates/', $imageDir, $currentImageUrl);

                if (file_exists($currentImagePath)) {
                    unlink($currentImagePath);
                }
            }

            $imageData = $request['screenshot'];
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('La decodificación de Base64 falló.');
            }

            $imageDir = realpath(__DIR__ . '/../../') . '/document_storage/templates';

            if (!file_exists($imageDir)) {
                if (!mkdir($imageDir, 0775, true)) {
                    throw new \Exception("No se pudo crear el directorio '$imageDir'.");
                }
            }

            $imageFileName = uniqid() . '.png';
            $imagePath = $imageDir . '/' . $imageFileName;

            $stored = file_put_contents($imagePath, $imageData);

            if ($stored === false) {
                throw new \Exception("Error al guardar la imagen en $imagePath.");
            }

            $imageUrl = rtrim($_ENV['APP_URL'] ?? 'http://default-url.test/', '/') . '/document_storage/templates/' . $imageFileName;

            $jsonContent = $request['json_content'];
            $htmlContent = $request['html_content'];

            preg_match_all('/\|\|([A-Z0-9_]+)\|\|/', $htmlContent, $matches);

            $variables = [];
            if (!empty($matches[1])) {
                $variables = array_unique($matches[1]);
            }

            $sqlUpdate = "UPDATE mail_templates 
                      SET name = '$templateName',
                          html_content = '" . $this->db->escape($htmlContent) . "',
                          json_content = '" . $this->db->escape($jsonContent) . "',
                          urlPreview = '" . $this->db->escape($imageUrl) . "',
                          base64Image = '" . $this->db->escape($request['screenshot']) . "',
                          customVariables = '" . $this->db->escape(json_encode($variables)) . "',
                          updated_at = NOW()
                      WHERE id = '$templateId'";

            $this->logs->debug($sqlUpdate);

            $this->db->query($sqlUpdate);

            return [
                'success' => true,
                'message' => 'Plantilla actualizada correctamente!',
            ];
        } catch (\Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la plantilla',
                'error' => $e->getMessage(),
            ];
        }
    }
    function changeStatus($id, $enable)
    {

        try {

            $sql = "SELECT * FROM mail_templates WHERE id = ".$id;
            $template = $this->db->select($sql);

            if (!$template) {
                $this->logs->error("No se encontró la plantilla por el id: " . $id);
                return [
                    'success' => false,
                    'items' => [],
                ];
            }
            $newEnable = $enable ? 1 : 0;

            $sqlUpdate = "UPDATE mail_templates SET enable = ".$newEnable." WHERE id = ".$id;
            $this->db->query($sqlUpdate);

            return [
                'success' => true,
                'message' => 'Estado actualizado correctamente',
            ];

        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar el estado',
                'error' => $e->getMessage(),
            ];
        }
    }
    function double($id) {
        try {
            $sql = "SELECT * FROM mail_templates WHERE id = ".$id;
            $template = $this->db->select($sql);

            if (!$template) {
                $this->logs->error("No se encontró la plantilla por el id: " . $id);
                return [
                    'success' => false,
                    'message' => 'No se encontró la plantilla',
                ];
            }

            $newNameTemplate = $template[0]['name'];
            if (strpos($newNameTemplate, " Copia") !== false) {
                $newNameTemplate = preg_replace('/\sCopia(\s\(\d+\))?$/', '', $newNameTemplate);
            }
            $newNameTemplate .= " Copia";

            $sqlCheck = "SELECT name FROM mail_templates WHERE name LIKE '" . $newNameTemplate . "%'";
            $existingTemplates = $this->db->select($sqlCheck);

            if (!empty($existingTemplates)) {
                $i = 1;
                foreach ($existingTemplates as $existingTemplate) {
                    if (preg_match('/Copia \((\d+)\)$/', $existingTemplate['name'], $matches)) {
                        $i = max($i, (int)$matches[1] + 1);
                    }
                }
                $newNameTemplate .= " ($i)";
            }

            $base64Image = $template[0]['base64Image'];

            if (empty($base64Image)) {
                return [
                    'success' => false,
                    'message' => 'La plantilla no tiene imagen base64 asociada.',
                ];
            }

            $imageData = str_replace('data:image/png;base64,', '', $base64Image);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return [
                    'success' => false,
                    'message' => 'Error al decodificar la imagen base64.',
                ];
            }

            $imageDir = realpath(__DIR__ . '/../../') . '/document_storage/templates';

            if (!file_exists($imageDir)) {
                if (!mkdir($imageDir, 0775, true)) {
                    return [
                        'success' => false,
                        'message' => 'No se pudo crear el directorio para las imágenes.',
                    ];
                }
            }

            $imageFileName = uniqid() . '.png';
            $imagePath = $imageDir . '/' . $imageFileName;

            $stored = file_put_contents($imagePath, $imageData);

            if ($stored === false) {
                return [
                    'success' => false,
                    'message' => 'Error al guardar la imagen.',
                ];
            }

            $imageUrl = rtrim($_ENV['APP_URL'] ?? 'http://default-url.test/', '/') . '/document_storage/templates/' . $imageFileName;

            $name = $this->db->escape($newNameTemplate);
            $html_content = $this->db->escape($template[0]['html_content']);
            $json_content = $this->db->escape($template[0]['json_content']);
            $urlPreview = $this->db->escape($imageUrl);  // URL de la nueva imagen
            $customVariables = $this->db->escape($template[0]['customVariables']);
            $enable = (int) $template[0]['enable'];
            $idMandante = (int) $template[0]['idMandante'];
            $idCedente = (int) $template[0]['idCedente'];

            $sqlDouble = "INSERT INTO mail_templates (name, html_content, json_content, urlPreview, base64Image, customVariables, enable, created_at, updated_at, idMandante, idCedente) 
                      VALUES ('".$name."','".$html_content."','".$json_content."','".$urlPreview."','".$base64Image."','".$customVariables."',".$enable.", NOW(), NOW(),".$idMandante.",".$idCedente.")";

            $this->db->query($sqlDouble);

            return [
                'success' => true,
                'message' => 'Plantilla clonada correctamente.',
            ];

        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error al clonar la plantilla',
                'error' => $e->getMessage(),
            ];
        }
    }
    function delete($id){
        $sql = "SELECT * FROM mail_templates WHERE id = ".$id;
        $template = $this->db->select($sql);

        if (!$template) {
            $this->logs->error("No se encontró la plantilla por el id: " . $id);
            return [
                'success' => false,
                'message' => 'No se encontró la plantilla',
            ];
        }
        try {

            $sqlUpdate = "UPDATE mail_templates 
                      SET isDeleted = 1, deleted_at = NOW()
                      WHERE id = " . intval($id);

            $this->db->query($sqlUpdate);

            return [
                'success' => true,
                'message' => 'Plantilla eliminada correctamente',
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

    function selectTemplate($id,$campaignId){
        return [$id,$campaignId];
    }

}

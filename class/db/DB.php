<?php

require __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../logs.php';

class Db
{
    public $Server;
    public $User;
    public $Pass;
    public $Database;
    public $Link;
    public $CrearLog;
    // The database connection
    protected static $connection;
    protected static $connectionDiscador;
    protected static $connectionDiscador2;
    protected static $connectionDiscadorAsterisk;
    private $logs;

    /**
     * Connect to the database
     *
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function __construct($Link = "fire")
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
        
        $this->logs = new Logs();
        $this->Link = $Link;
        //$Conf = parse_ini_file("conf.ini");
        if($Link == "fire") {
            $this->Server = $_ENV['DB_HOST'];
            $this->Pass = $_ENV['DB_PASS'];
            $this->User = $_ENV['DB_USER'];
            $this->Database = $_ENV['DB_DATABASE'];
        } else if($Link == 'discador') {
            $this->Server = $_ENV['VICIDIAL_DB_HOST'];
            $this->Pass   = $_ENV['VICIDIAL_DB_PASS'];
            $this->User   = $_ENV['VICIDIAL_DB_USER'];
            $this->Database = $_ENV['VICIDIAL_DB_DATABASE'];
        } else if($Link == 'discador2') {
            $this->Server = $_ENV['VICIDIAL2_DB_HOST'];
            $this->Pass   = $_ENV['VICIDIAL2_DB_PASS'];
            $this->User   = $_ENV['VICIDIAL2_DB_USER'];
            $this->Database = $_ENV['VICIDIAL2_DB_DATABASE'];
        }      
        //$this->logs->debug($_ENV);
        if (!isset($_SESSION)) session_start();
    }

    public function connect()
    {
        $ToReturn = false;
        try {
            //mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
            switch($this->Link) {
                case "fire":
                    // Try and connect to the database
                    if(!isset(self::$connection)) {
                        //self::$connection = mysql_connect($this->Server,$this->User,$this->Pass);
                        self::$connection = mysqli_connect($this->Server,$this->User,$this->Pass);
                        if (!self::$connection) {
                            $this->logs->error('ERROR en: '.$this->Link);
                            $ToReturn = false;
                            return false;
                        }
                        //mysqli_report(MYSQLI_REPORT_ALL);
                        mysqli_select_db(self::$connection, $this->Database);
                        mysqli_set_charset(self::$connection, "utf8");
                        mysqli_options(self::$connection, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
                        //$this->query("SET NAMES 'utf8'");
                    }
                    $ToReturn = self::$connection;    
                    // If connection was not successful, handle the error
                    if(self::$connection === false) {
                        // Handle error - notify administrator, log to a file, show an error screen, etc.
                        $ToReturn = false;
                    }         
                    break;
                case "discador":
                    // Try and connect to the database
                    if(!isset(self::$connectionDiscador) || is_null(self::$connectionDiscador)) {
                        self::$connectionDiscador = @mysqli_connect($this->Server, $this->User, $this->Pass);
                        if (!self::$connectionDiscador) {
                            $this->logs->error('ERROR en: '.$this->Link);
                            $ToReturn = false;
                            return false;
                        }
                        //mysqli_report(MYSQLI_REPORT_ALL);
                        mysqli_select_db(self::$connectionDiscador, $this->Database);
                        mysqli_set_charset(self::$connectionDiscador, "utf8");
                        mysqli_options(self::$connection, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
                        //$this->query("SET NAMES 'utf8'");
                    }
                    $ToReturn = self::$connectionDiscador;
                    // If connection was not successful, handle the error
                    if(self::$connectionDiscador === false) {
                        // Handle error - notify administrator, log to a file, show an error screen, etc.
                        $ToReturn = false;
                    }                        
                    break;
                case "discador2":
                    // Try and connect to the database
                    if(!isset(self::$connectionDiscador2) || is_null(self::$connectionDiscador2)) {
                        self::$connectionDiscador2 = @mysqli_connect($this->Server, $this->User, $this->Pass);
                        if (!self::$connectionDiscador2) {
                            $this->logs->error('ERROR en: '.$this->Link);
                            $ToReturn = false;
                            return false;
                        }
                        //mysqli_report(MYSQLI_REPORT_ALL);
                        mysqli_select_db(self::$connectionDiscador2, $this->Database);
                        mysqli_set_charset(self::$connectionDiscador2, "utf8");
                        mysqli_options(self::$connection, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
                        //$this->query("SET NAMES 'utf8'");
                    }
                    $ToReturn = self::$connectionDiscador2;
                    // If connection was not successful, handle the error
                    if(self::$connectionDiscador2 === false) {
                        // Handle error - notify administrator, log to a file, show an error screen, etc.
                        $ToReturn = false;
                    }                        
                    break;
            }
        } catch (\Exception $ex) {
            $this->logs->error('ERROR en: '.$this->Link);
            $this->logs->error($ex->getMessage());
            $ToReturn = false;
        }
        return $ToReturn;
    }

    public function getInstance()
    {
        if ($this->Link == 'discador') {
            if (!self::$connectionDiscador || is_null(self::$connectionDiscador)) $this->connect();
            return self::$connectionDiscador;
        } else if ($this->Link == 'discador2') {
            if (!self::$connectionDiscador2 || is_null(self::$connectionDiscador2)) $this->connect();
            return self::$connectionDiscador2;
        } else {
            if (!self::$connection || is_null(self::$connection)) $this->connect();
            return self::$connection;
        }
    }

        /**
         * Query the database
         *
         * @param $query The query string
         * @return mixed The result of the mysqli::query() function
         */
    public function query($query)
    {
        // Connect to the database
        $connection = $this->connect();
        // Query the database
        try {
            //$this->logs->debug($query);
            if (!is_a($connection, 'mysqli')) return false;
            $result = mysqli_query($connection, $query);
            if ($result) {
                if(isset($_SESSION["id_usuario"])){
                    $this->registrarLogSistema($query);
                }
            } else {
                $result = mysqli_error($connection);
                $this->logs->error(' [DB->QUERY]['.$this->Link.'] '.$result);
                //$SqlInsert = "insert into errores_queries (fecha,query,message) values (NOW((),'".addslashes($query)."','".$result."')";
                //$Insert = $this->query($SqlInsert);
                // echo $query;
            }
            return $result;
        } catch (\Exception $ex) {
            $this->logs->error($ex->getMessage());
            return false;
        }
    }
    public function escape($value) {
        $connection = $this->connect();
        return mysqli_real_escape_string($connection, $value);
    }
    public function insert($query)
    {
        $connection = $this->connect();
        $return = null;
        try {
            if ($connection) {
                $result = mysqli_query($connection, $query);
                if ($result) {
                    $return = (int) mysqli_insert_id($connection); 
                } else {
                    $error = mysqli_error($connection);
                    $this->logs->error(' [DB->INSERT]['.$this->Link.'] '. $error);
                    /*$SqlInsert = "insert into errores_queries (fecha,query,message) values (NOW((),'".addslashes($query)."','".$return."')";
                    $Insert = $this->query($SqlInsert);*/
                }
            }
        } catch (\Exception $ex) {
            $this->logs->error($ex->getMessage());
        }
        return $return;
    }

    public function getErrorMessage(){
        //return $this->Server."/".mysql_error();
        //return $this->Server."/".mysqli_error($link);
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
    */
    public function select($query)
    {
        $rows = [];
        try {
            $result = $this->query($query);
            if($result === false || !is_a($result, 'mysqli_result')) return [];
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } catch (\Exception $ex) {
            $this->logs->error(' [DB->SELECT]['.$this->Link.'] '.$ex->getMessage());
        }
        return $rows;
    }

        /**
         * Fetch the last error from the database
         *
         * @return string Database error message
         */
    public function error()
    {
        $connection = $this->connect();
        $this->logs->error($connection->error);
        return $connection->error;
    }

        /**
         * Quote and escape value for use in a database query
         *
         * @param string $value The value to be quoted and escaped
         * @return string The quoted and escaped string
         */
    public function quote($value)
    {
        $connection = $this -> connect();
        //return "'" . $connection -> real_escape_string($value) . "'";
        return "'" . mysqli_real_escape_string($connection, $value) . "'";
    }

    public function getLastID()
    {
        $connection = $this -> connect();
        return mysqli_insert_id($connection);
    }
        
    public function getLastIDFromTable($field,$table)
    {
        $Sql = "SELECT MAX(".$field.") AS id FROM ".$table;
        $Id = $this->select($Sql);
        return $Id[0]["id"];
    }

    public function insertLog($tablaOperacion,$query)
    {
        $fechaHora = date('Y-m-d H:i:s');
        $id_registro = $this->getLastID(); // aun no tengo el id de los update y delete
        $sql="INSERT INTO log_sistema (fecha, id_usuario, operacion, id_registro, tabla, query) values ('".$fechaHora."','".$_SESSION["id_usuario"]."','".$tablaOperacion[0]."','".$id_registro."','".$tablaOperacion[1]."','".addslashes($query)."')";
        $connection = $this->connect();
        mysqli_query($connection, $sql);
    }

    public function registrarLogSistema($query)
    {
        $queryTmp = $query;
        $queryTmp = strtoupper(trim($queryTmp));
        $posSelect = strpos($queryTmp,"SELECT");

        if(($posSelect !== FALSE) && ($posSelect === 0)) { // sip es un select entra aca (si lo consigue en la posicion 0)
            // Registra LOG de select
            /*$tablaOperacion = $this->buscarOperacion($query);
            $this->insertLog("fffffffff",$query);*/
        }else{ // Registra LOG de insert update delete
            $posSelect = strpos($queryTmp,"DESCRIBE");
            if(($posSelect !== FALSE) && ($posSelect === 0)) {
            }else{
                $tablaOperacion = $this->buscarOperacion($query);
                $this->insertLog($tablaOperacion,$query);
            }
        }
    }

    public function buscarOperacion($query)
    {
        $query = strtoupper(trim($query));
        $array = array("INSERT","DELETE","UPDATE");
        $tablaOperacion = array();
        foreach ($array as $clave => $buscar){
            switch ($buscar) {
                case 'INSERT':
                    $queryTmp = str_replace("INSERT INTO ","",$query);
                    $posUltimoEspacio = strpos($queryTmp," ");
                    $tabla = substr($queryTmp,0,$posUltimoEspacio);
                    break;
                case 'DELETE':
                    $queryTmp = str_replace("DELETE FROM ","",$query);
                    $posUltimoEspacio = strpos($queryTmp," ");
                    $tabla = substr($queryTmp,0,$posUltimoEspacio);
                    break;
                case 'UPDATE':
                    $queryTmp = str_replace("UPDATE ","",$query);
                    $posUltimoEspacio = strpos($queryTmp," ");
                    $tabla = substr($queryTmp,0,$posUltimoEspacio);
                    break;
            }
            $tablaOperacion = array($buscar,$tabla);
            $resultado = strpos(strtoupper($query), strtoupper($buscar));
            if($resultado !== FALSE){
                break; // si lo encuentro cancelo el ciclo para no seguir buscando
            }
        }
        return $tablaOperacion; // envio el nombre de la tabla y la operacion
    }

        //esta funcion la coloque tal cual
    public function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    { //listo
        $connection = $this->connect();
        if (!$connection) return 'NULL';
        // if (PHP_VERSION < 6) $theValue = @get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        //$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connection, $theValue) : mysqli_escape_string($connection, $theValue);
        switch ($theType){
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

    public function isLocalhost($whitelist = ['127.0.0.1', '::1'])
    {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }
}
?>
<?php
    class Ftp {
        public $conn, $current_dir;
        public $log;
            
        function __construct($host, $port, $username, $password, $passive = false) {
            $this->log = new Log;
            
            $this->connect($host, $port, $username, $password, $passive);
        }
        
        function connect($host, $port, $username, $password, $passive = false) {
            $this->conn = ftp_connect($host,$port);
            print_r(error_get_last());
            $login = ftp_login($this->conn, $username, $password);
            ftp_pasv($this->conn, $passive);
            
            if(!$this->conn || !$login){
                $this->log->error('There was a login problem.', true);
            }else{
                $this->log->message('Sucessfully connected.');
            }
        }
        
        function ls($dir = '.', $raw = false) {
            $dir = ftp_pwd($this->conn);
            $ls = ($raw) ? ftp_rawlist($this->conn, $dir) : ftp_nlist($this->conn, $dir);
            if(!$ls)
                $this->log->error("Could not list contents of $dir", true);
            return $ls;
        }
        
        function isDir($dir) {
            $original_dir = ftp_pwd($this->conn);
            $result = @ftp_chdir($this->conn, $dir);
            if($result) {
                ftp_chdir($this->conn, $original_dir);
                return true;
            }	
            return false;
        }
            
        function isFile($file) {
            return in_array($file, $this->ls());
        }
        
        function chmod($path, $mode_dec) {
            $mode_oct = octdec(str_pad($mode_dec, 4, '0' ,STR_PAD_LEFT));
            $result = ftp_chmod($this->conn, $mode_oct, $path);
            if(!$result)
                $this->log->error("Error changing permissions for $path to $mode_dec", true);
            $this->log->message("Permissions for $path are now $mode_dec");
        }
            
        function cd($dir) {
            $result = ftp_chdir($this->conn, $dir);
            if(!$result)
                $this->log->error("Could not change directory to $dir", true);
            $this->log->message('Current directory is now' . ftp_pwd($this->conn));
        }
        
        function mkdir($dir, $mode_dec = null) {
            $current_dir = ftp_pwd($this->conn);
            $result = ftp_mkdir($this->conn, $dir);
            if(!$result)
                $this->log->error("Could not make directory $dir in " . ftp_pwd($this->conn), true);
            $this->log->message("Made directory $dir in " . ftp_pwd($this->conn));
            if(!is_null($mode_dec))
                $this->chmod($dir, $mode_dec);
        }
        
        function rmdir($dir) {
            if($dir == '.' || $dir == '..')
                return false; // quietly return
            $result = ftp_rmdir($this->conn, $dir);
            if(!$result)
                $this->log->error("Could not remove directory $dir - you should ensure that it is empty.", true);
            $this->log->message("Removed directory $dir");
        }
        
        function delete($path) {
            $result = ftp_delete($this->conn, $path);
            if(!$result)
                $this->log->error("Could not delete file $path", true);
            $this->log->message("Deleted file $path");
        }
        
        function uploadFile($from_path, $to_path, $mode = null) {
            if($mode != 'ascii' || $mode != 'binary' || $mode != FTP_ASCII || $mode != FTP_BINARY)
                $mode = self::determineUploadMode($from_path);
            else if(!defined($mode))
                $mode = constant('FTP_' . strtoupper($mode));
            
            ftp_pasv($this->conn, true);
            $result = ftp_put($this->conn, $to_path, $from_path, $mode);
            
            if(!$result)
                $this->log->error("Error uploading $from_path to $to_path", true);
            $this->log->message("Successfully uploaded $from_path to $to_path");			
        }

        function uploadFolder($from_path, $to_path, $mode = null) {
            ftp_pasv($this->conn, true);
            $gestor = opendir($from_path);
            while (false !== ($entrada = readdir($gestor))) {
                if ($entrada != "." && $entrada != ".."){
                    if(is_dir($from_path."/".$entrada)){
                        $isDir = $this->isDir($entrada);
                        if(!$isDir){
                            $this->mkdir($entrada,"0777");
                        }
                        $this->cd($entrada);
                        $this->uploadFolder($from_path."/".$entrada,$to_path."/".$entrada,$mode);
                        $this->cd("..");
                    }else{
                        /* if($mode != 'ascii' || $mode != 'binary' || $mode != FTP_ASCII || $mode != FTP_BINARY){
                            $mode = self::determineUploadMode($from_path);
                        }else{
                            if(!defined($mode)){
                                $mode = constant('FTP_' . strtoupper($mode));
                            }
                        } */
                        $current_dir = ftp_pwd($this->conn);
                        $FileList = ftp_nlist($this->conn, $current_dir);
                        $mode = FTP_BINARY;
                        $result = ftp_put($this->conn, $entrada, $from_path."/".$entrada, $mode);
                        
                        if(!$result){
                            $this->log->error("Error uploading $from_path to $to_path", true);
                        }else{
                            $this->log->message("Successfully uploaded $from_path to $to_path");
                        }
                    }
                }
            }			
        }

        function readFolder($Path){
            $gestor = opendir($Path);
            while (false !== ($entrada = readdir($gestor))) {
                if ($entrada != "." && $entrada != ".."){
                    if(is_dir($Path."/".$entrada)){
                        $this->readFolder($Path."/".$entrada);
                    }else{
                        echo $Path."/".$entrada;
                    }
                }
            }
        }
        
        function download($from_path, $to_path) {
            $mode = self::determineUploadMode($to_path);
            $result = ftp_get($this->conn, $to_path, $from_path, $mode, 0);
            if(!$result)
                $this->log->error('Error downloading $from_path to $to_path');
            $this->log->message('Successfully downloaded $from_path to $to_path');
        }
        
        function determineUploadMode($path) {
            $ascii_always = array('.htm', '.html', '.shtml', '.php', '.pl', '.cgi', '.js', '.py', '.cnf', '.css', '.forward', '.htaccess', '.map', '.pwd', '.txt', '.grp', '.ctl');
            //$extension = array_pop(explode('.', $path));
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if(in_array($extension, $ascii_always))
                return FTP_ASCII;
            return FTP_BINARY;
        }
        
        function __destruct() {
            if($this->conn) ftp_close($this->conn);
        }	
    }
    class Log {
        public $stack = array();
        function __construct(Log $base_log = null) {
            if(!is_null($base_log))
                $this->stack = $base_log->getAllItems();
        }
        
        function message($item) {
            $this->add($item, 'message');
        }
        
        function error($item, $throw_exception = false) {
            $this->add($item, 'error');
            
            if($throw_exception)
                throw new Exception($this->getLastItem()->message);
        }
        
        function add($item, $type = null) {
            if(!is_a($item, 'LogItem') && $type == null) 
                throw new Exception('$item passed to Log::add() must be LogItem or $type must be defined');
            if(!is_a($item, 'LogItem')) $item = new LogItem($item, $type);
            $this->stack[] = $item;
        }
        
        function getLastItem() {
            return end($this->stack);
        }
        
        function getAllItems() {
            return $this->stack;
        }
    }
    class LogItem {
        public $message, $type;
        function __construct($message, $type = 'error') {
            $this->message = $message;
            $this->type = $type;
        }
        function isType($type) {
            return ($this->type == $type);
        }
    }
?>
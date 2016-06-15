<?php
    require_once("../../../library/Local/config.load.php");
    /**
     * database
     */
    class database extends PDO
    {
        private $dbname;
        private $username;
        private $password;
        private $host;
        private $dsn;
        private $connection;
        private $rootFolder;
        function __construct()
        {
            $data = getConfig();
            $this->dbname = $data->database->db->config->dbname;
            $this->username = $data->database->db->config->username;
	        $this->password = $data->database->db->config->password;
            $this->host = $data->database->db->config->host;
            $this->rootFolder = $data->path->sys->dirroot;
	        $this->dsn = "mysql:dbname=".$this->dbname.";host=".$this->host.";charset=".$data->database->db->config->charset;
            parent::__construct($this->dsn,$this->username,$this->password);
        }
        public function getRootFolder(){
            return $this->rootFolder;
        }
    }
    
    
?>
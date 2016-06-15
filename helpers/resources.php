<?php

    require_once("database.php");
    /**
     * resources
     */
    class resources extends database
    {
        
        public $id;
        public $resource_path;
        private $table_name = "mdl_learningunit_resources";
        private $extensions = ['png','svg','jpeg','jpg','gif','mp3','ogg'];
                
        function __construct($path,$id=false)
        {
            parent::__construct();
            $this->resource_path = $path;
            $this->validatePath();
            $this->id = ($id)?:"";
            $this->getResource($this->resource_path);
        }
        public function save(){
            $q = $this->prepare('INSERT INTO '.$this->table_name.' (resource_path) VALUES
                            (:path)');
            if($q->execute(array(':path'=>$this->resource_path))){
                $this->id = $this->lastInsertId();
            }
            return false;
        }
        public function getResource($path){
            $q = $this->prepare('SELECT * FROM '.$this->table_name.' WHERE resource_path = :path');
            $q->execute(array(':path'=> $path));
            $result = $q->fetch();
            if(count($result)>0){
                $this->id = $result['id'];
            }
        }
        public function getJSON(){
            return json_encode(array(
                    'id' => $this->id, 
                    'resource_path' =>$this->resource_path));
        }
        public function createZip($metadata){
            $this->validatePath();
            $xml = $this->getXML($metadata);
            $date = new DateTime();
            $timestamp = $date->getTimestamp();
            $zip = new ZipArchive();
            $zipName = "/tmp/red".$timestamp.'.zip';
            if($zip->open($zipName,ZipArchive::CREATE) !== TRUE){
                exit("cannot open <".$zipName.">\n");
            }
            $zip->addFromString('metadata.xml',$xml);
            $this->resource_path = str_replace('../',"",$this->resource_path);
            $this->resource_path = "/".$this->resource_path;
            $this->resource_path = str_replace('//',"/",$this->resource_path);
            $source = str_replace('\\', '/', $this->getRootFolder().$this->resource_path);
            if (is_dir($source) === true)
            {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                foreach ($files as $file)
                {
                    $file = str_replace('\\', '/', $file);
                    if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                        continue;
                    $file = realpath($file);
                    if (is_dir($file) === true)
                    {
                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                    }
                    else if (is_file($file) === true)
                    {
                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                    }
                }
            }
            else if (is_file($source) === true)
            {
                $zip->addFromString(basename($source), file_get_contents($source));
            }
            $zip->close();
            return $zipName;
        }
        public function validatePath(){
            $path_info = pathinfo($this->resource_path);
            if(!in_array($path_info['extension'],$this->extensions)){
                $this->resource_path = $path_info['dirname'];
            }
        }
        public function getXML($class){
            $xml = new SimpleXMLElement('<xml/>');
            
            foreach ($class as $key => $value) {
                $xml->addChild($key,$value);
            }
            return $xml->asXML();
        }
    }
    

?>
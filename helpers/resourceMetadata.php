<?php
    
    require_once("database.php");
    /**
    * resourceMetadata
    */
    class resourceMetadata extends database
    {
        public $title;
        public $author_id;
        public $author;
        public $resource_type;
        public $description;
        public $created;
        public $last_modified;
        private $table = "mdl_learningunit_resource_metadata";
        private $types_table = "mdl_learningunit_resource_types";
        private $users_table = "au_usuario";
        function __construct($title="",$author_id="",$resource_type="",$description="",$created="",$last_modified="")
        {
        $this->title = $title;
        $this->author_id = $author_id;
        $this->resource_type = $resource_type;
        $this->description = $description;
        $this->created = $created;
        $this->last_modified = $last_modified;
        parent::__construct();
        }
        public function save(){
            $q = $this->prepare("INSERT INTO ".$this->table."(
                fk_resource_type, fk_resource_id, fk_autor_id, title, description, created, last_modified) 
                VALUES 
                (:type, :resource_id, :author, :title, :description, :created, :last_modified)");
            if($q->execute(array(
                ':type' => $this->resource_type,
                ':resource_id'=>$this->resource_id,
                ':author' => $this->author_id,
                ':title' => $this->title,
                ':description' => $this->description,
                ':created' => $this->created,
                ':last_modified' => $this->last_modified
                 ))){
                     return json_encode(array('status' => 'success' ));
                 }
            return json_encode(array('status' => 'fail' ));
        }
        public function getMetadata($id){
            $q = $this->prepare('SELECT fk_resource_type, fk_resource_id,fk_autor_id,title,description,
                 DATE_FORMAT(created,"%Y-%m-%d") as "created", DATE_FORMAT(last_modified,"%Y-%m-%d") as "last_modified" 
                 FROM mdl_learningunit_resource_metadata WHERE fk_resource_id = :id');
            $q->execute(array(':id'=>$id));
            $result = $q->fetch();
            if(count($result)>0){
                $this->title = $result['title'];
                $this->author_id = $result['fk_autor_id'];
                $this->author = $this->getAuthor($this->author_id);
                $this->resource_type = $result['fk_resource_type'];
                $this->description = $result['description'];
                $this->created = $result['created'];
                $this->last_modified = $result['last_modified'];
            }
        }
        public function getAuthor($id){
            $q = $this->prepare("SELECT CONCAT(usu_nombres,' ',usu_apellidos) as username FROM ".$this->users_table." WHERE usu_usuario=:id");
            $q->execute(array(':id'=>$id));
            $result = $q->fetch();
            return $result['username'];
        }
        public function getType($typeStr){
            $q = $this->prepare("SELECT id FROM ".$this->types_table." WHERE name=:name");
            $q->execute(array(':name'=>$typeStr));
            $result = $q->fetch();
            return $result['id'];
        }
    }
    
?>
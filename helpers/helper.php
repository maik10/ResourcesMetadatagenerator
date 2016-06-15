<?php

    require_once("resourceMetadata.php");
    require_once("resources.php");
    session_start();
    $metadata = new resourceMetadata();
    $resource = new resources($_REQUEST['path']);
    switch ($_REQUEST['action']) {
        case 'findData':
            $data = [];
            if($resource->id){
                $data['resource_id'] = $resource->id;
                $data['path'] = $resource->resource_path;
                $metadata->getMetadata($resource->id);
                $data['title'] = $metadata->title;
                $data['author_id'] = $metadata->author_id;
                $data['author'] = $metadata->author;
                $data['description'] = $metadata->description;
                $data['creation'] = $metadata->created;
                $data['last_modified'] = $metadata->last_modified;
                $data['resource_type'] = $metadata->resource_type;
            }else{
                $data['creation'] = false;
                $data['author'] = $_SESSION['nombre']." ".$_SESSION['apellido'];
                $data['author_id'] = $_SESSION['user_id'];
            }
            echo json_encode($data);
            break;
        case 'save':
            $resource->resource_path = $_REQUEST['path'];
            $metadata->title = $_REQUEST['title'];
            $metadata->description = $_REQUEST['description'];
            $metadata->author_id = $_REQUEST['author'];
            $metadata->author=$metadata->getAuthor($metadata->author_id);
            $metadata->created = $_REQUEST['creation_date'];
            $metadata->last_modified = $_REQUEST['last_modified'];
            $metadata->resource_type = intval($metadata->getType($_REQUEST['type']));
            $file = $resource->createZip($metadata);
            $resource->save();
            $metadata->resource_id = $resource->id;
            $metadata->save();
            echo $file;
            break;
        case 'download':
            $file = $_REQUEST['path']; 
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".basename($file)."");
            header("Content-Transfer-Encoding: binary");
            header("Content-Type: binary/octet-stream");
            readfile($file);exit();
            break;
        default:
            # code...
            break;
    }
    
    //$metadata->save();

?>

<?php
class Sound {
    private $name;
    private $id;
    private $bytes;
    private $contentType;

    function __constructor($name) {
        $this->name = $name;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function setBytes($bytes){
        $this->bytes = $bytes;
    }
    public function setContentType($contentType){
        $this->contentType = $contentType;
    }
    public function getName(){
        return $this->name;
    }
    public function getId(){
        return $this->id;
    }
    public function getBytes(){
        return $this->bytes;
    }
    public function getContentType(){
        return $this->contentType;
    }
}
?>
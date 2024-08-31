<?php

class Animal implements JsonSerializable{
    private $nom;
    private $espece;
    private $age;

    public function __construct($nom , $espece , $age){
        $this->nom = $nom;
        $this->espece = $espece;
        $this->age = $age;
    }


    public function getNom(){
        return $this->nom;
    }

    public function getEspece(){
        return $this->espece;
    }

    public function getAge(){
        return $this->age;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function setEspece($espece){
        $this->espece = $espece;
    }

    public function setAge($age){
        $this->age = $age;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }
}

?>
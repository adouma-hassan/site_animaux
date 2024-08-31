<?php
require_once  'model/AnimalStorage.php';
class AnimalStorageStub implements AnimalStorage{

    private array $animalsTab;


    public function __construct(){
        $this->animalsTab = [
                            'medor' => new Animal("Médor","chien",4),
                            'felix' => new Animal("Félix","chat",6),
                            'denver' => new Animal("Denver","chien",7),
                            ];
    }

    public function read($id){

        if(key_exists($id,$this->animalsTab)){
            $tmp = $this->animalsTab[$id];
            return $tmp;
        }
        else{
            return null;
        }

    }

    public function readAll(){
        return $this->animalsTab;
    }

    public function create(Animal $a){
        //attribtion d'un identifiant unique pour le nouvel animal

        $id = uniqid('animal_',true);

        //Ajouter le nouvel animal au tableau

        $this->animalTab[$id] = $a;

        //retourner l'identifiant du nouvell animal

        return $id;
   }
   
   public function delete($id){
       throw new Exception('Exception message');
   }

   public function update($id, Animal $a){
      throw new Exception('Exception message');
   }
    
}



?>
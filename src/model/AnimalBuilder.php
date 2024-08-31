<?php

class AnimalBuilder {

    private array $data;
    private $error;

    public const NAME_REF = 'NOM';
    public const SPECIES_REF = 'ESPECE';
    public const AGE_REF = 'AGE';

    public function __construct($data, $error = null) {
        $this->data = $data;
        $this->error = $error;
    }

    // Crée une instance de la classe Animal à partir des données stockées
    public function createAnimal() {
        return new Animal($this->data[self::NAME_REF], $this->data[self::SPECIES_REF], (int)$this->data[self::AGE_REF]);
    }

    /* Met à jour une instance d'animal avec les données
	 * fournies. */
	public function updateAnimal(Animal $a) {
		if (key_exists(self::NAME_REF, $this->data))
			$a->setNom($this->data[self::NAME_REF]);
		if (key_exists(self::SPECIES_REF, $this->data))
			$a->setEspece($this->data[self::SPECIES_REF]);
        if (key_exists(self::AGE_REF, $this->data))
			$a->setAge($this->data[self::AGE_REF]);
	}

    // Vérifie la validité des données pour la création d'un animal
    public function isValid() {
        if (key_exists(self::NAME_REF, $this->data)) {
            $nom = $this->data[self::NAME_REF];
            if (empty($nom)) {
                $this->error = "Vous devez entrer un nom.";
            }
        }

        if (key_exists(self::SPECIES_REF, $this->data)) {
            $espece = $this->data[self::SPECIES_REF];
            if (empty($espece)) {
                $this->error = "Vous devez entrer une espèce.";
            }
        }

        if (key_exists(self::AGE_REF, $this->data)) {
            $age = $this->data[self::AGE_REF];
            if (!is_numeric($age) || $age <= 0) {
                $this->error = "Vous devez entrer un âge valide.";
            }
        }
    }

    // Récupère les données
    public function getData() {
        return $this->data;
    }

    // Récupère l'erreur
    public function getError() {
        return $this->error;
    }

    // Modifie les données
    public function setData($data) {
        $this->data = $data;
    }

    // Modifie l'erreur
    public function setError($error) {
        $this->error = $error;
    }

    /* Renvoie une nouvelle instance de ColorBuilder avec les données
 	 * modifiables de la couleur passée en argument. */
	public static function buildFromAnimal(Animal $animal) {
		return new AnimalBuilder(array(
			self::NAME_REF => $animal->getNom(),
			self::SPECIES_REF => $animal->getEspece(),
            self::AGE_REF => $animal->getAge(),
		));
	}
}

?>

<?php

class Controller {
    private $view;
    private $viewJSON;
    private $animalStorage;

    public function __construct(?View $view, ?ViewJSON $viewJSON,  $animalStorage) {
        $this->view = $view;
        $this->viewJSON = $viewJSON;
        $this->animalStorage = $animalStorage;
    }

    // Permet d'afficher les informations d'un animal s'il est présent dans le tableau.
    // Sinon, affiche la page d'erreur.
    public function showInformation($id) {
        if (key_exists($id, $this->animalStorage->readAll())) {
            $animalData = $this->animalStorage->read($id);
            $this->view->prepareAnimalPage($animalData, $id);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showAnimalCreationPage() {
        $this->view->prepareAnimalCreationPage(new AnimalBuilder([]));
    }

    public function showUnknownAnimalPage() {
        $this->view->prepareUnknownAnimalPage();
    }

    public function showHome() {
        $this->view->prepareHomePage();
    }

    // Permet d'afficher la liste de tous les animaux que connaît le site.
    public function showList() {
        $animalList = $this->animalStorage->readAll();
        $this->view->prepareListPage($animalList);
    }

    public function showEditAnimalPage($id) {
        /* On récupère en BD l'animal à modifier */
		$a = $this->animalStorage->read($id);
		if ($a === null) {
			$this->view->prepareUnknownAnimalPage();
		} else {
			/* Extraction des données modifiables */
			$animalbuilder = AnimalBuilder::buildFromAnimal($a);
			/* Préparation de la page de formulaire */
			$this->view->prepareAnimalEditPage($animalbuilder, $id);
		}
    }

    public function updateAnimal(array $data, $id) {
        /* On récupère en BD l'animal à modifier */
		$animal = $this->animalStorage->read($id);
		if ($animal === null) {
			/* L'animal n'existe pas en BD */
			$this->view->prepareUnknownAnimalPage();
		} else {
			$ab = new AnimalBuilder($data);
			/* Validation des données */
            $ab->isValid();
			if (!$ab->getError()) {
				/* Modification de l'animal */
				$ab->updateAnimal($animal);
				/* On essaie de mettre à jour en BD. */
				$ok = $this->animalStorage->update($id, $animal);
				if (!$ok)
					throw new Exception("Identifier has disappeared?!");
				/* Préparation de la page de animal */
				$this->view->displayAnimalUpdateSuccess($id);
			} else {
				$this->view->prepareAnimalEditPage($ab, $id);
			}
		}
    }

    public function showDeleteAnimalPage($id) {
         /* On récupère en BD l'animal à modifier */
		$a = $this->animalStorage->read($id);
		if ($a === null) {
			$this->view->prepareUnknownAnimalPage();
		} else {
			/* Préparation de la page de formulaire */
			$this->view->prepareAnimalDeleteConfirmationPage($a, $id);
		}
    }

    public function deleteAnimal($id) {
        /* L'utilisateur confirme vouloir supprimer
		* l'animal. On essaie. */
		$ok = $this->animalStorage->delete($id);
		if (!$ok) {
			/* La couleur n'existe pas en BD */
			$this->view->prepareUnknownAnimalPage();
		} else {
			/* Tout s'est bien passé */
			$this->view->displayAnimalDeleteSuccess($id);
		}
    }


    public function saveNewAnimal(array $data) {
        $animalBuilder = new AnimalBuilder($data);
        $animalBuilder->isValid();

        if (is_null($animalBuilder->getError())) {
            $animal = $animalBuilder->createAnimal();
            $id = $this->animalStorage->create($animal);
            $this->view->displayAnimalCreationSuccess($id);
        } else {
            $this->view->prepareAnimalCreationPage($animalBuilder);
        }
    }
    
    public function getJSON($id) {
        if (key_exists($id, $this->animalStorage->readAll())) {
            $animalData = $this->animalStorage->read($id);
            $this->viewJSON->prepareJSON($animalData);
        } else {
            $this->emptyJSON();
        }
    }

    public function emptyJSON() {
        $this->viewJSON->prepareJSON([]);
    }
}
?>

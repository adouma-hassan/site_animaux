<?php

class Router {

    public function __construct() {

    }

    // Génère l'URL pour afficher les informations d'un animal spécifié par son ID
    public function getAnimalURL($id) {
        return "site.php?action=afficher&id=$id";
    }

    // Génère l'URL de la page d'accueil
    public function getHomeURL() {
        return "site.php";
    }

    // Génère l'URL de la page de liste des animaux
    public function getListURL() {
        return "site.php?liste";
    }

    // Génère l'URL du formulaire de création d'un nouvel animal
    public function getAnimalCreationURL() {
        return "site.php?action=nouveau";
    }

    // Génère l'URL pour sauvegarder un nouvel animal
    public function getAnimalSaveURL() {
        return "site.php?action=sauverNouveau";
    }

    // Génère l'URL du fomrulaire de modification d'un animal
    public function getAnimalEditURL($id) {
        return "site.php?action=modifier&id=" . $id;
    }

    // Génère l'url de modification d'un animal
    public function getAnimalUpdateURL($id) {
        return "site.php?action=sauverModification&id=" . $id;
    }

    // Génère l'url de la page de confimation de suppression d'un animal
    public function getAnimalDeleteConfirmationURL($id) {
        return "site.php?action=supprimer&id=" . $id;
    }

    // Génère l'url  de suppression d'un animal
    public function getAnimalDeleteURL($id) {
        return "site.php?action=confirmerSuppression&id=" . $id;
    }

    // Génère l'url  de recuperation d'un json
    public function getJSONURL() {
        return "site.php?action=json&id=" ;
    }


    // Effectue une redirection en POST vers une URL spécifiée avec un message de feedback
    public function POSTredirect($url, $feedback) {
        $_SESSION['feedback'] = $feedback;
        return header("Location: " . $url, true, 303);
        #die;
    }

    // Fonction principale du routeur
    public function main(AnimalStorage $animalStorageStub) {
        // Récupère le feedback de la session et le réinitialise
        $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
        $_SESSION['feedback'] = '';

        

        // Initialise le contrôleur avec la vue et le stockage d'animaux
        if(key_exists('action', $_GET) && $_GET['action'] == 'json') {
            // Initialise la vue JSON
            $view = new ViewJSON($this);
            $controller = new Controller(null, $view, $animalStorageStub);
        } else {
            // Initialise la vue html avec le routeur et le feedback
            $view = new View($this, $feedback);
            $controller = new Controller($view, null, $animalStorageStub);
        }
        

        // Routage en fonction des paramètres de l'URL
        $id = null;

        if (key_exists('id', $_GET)) {
            $id = $_GET['id'];
        }

        if (key_exists('liste', $_GET)) {
            $controller->showList();
        } 
        else if (key_exists('action', $_GET)) {
            
            
            if ($_GET['action'] == 'nouveau') { // Affiche le formulaire de création d'un animal
                $controller->showAnimalCreationPage();
            } elseif ($_GET['action'] == 'sauverNouveau') { // Affiche la page destinée à recevoir les données du nouvel animal
                $controller->saveNewAnimal($_POST);
            } elseif ($_GET['action'] == 'afficher') {
                ($id === null) ? $controller->showUnknownAnimalPage() : $controller->showInformation($id);
            } elseif ($_GET['action'] == 'modifier') {
                ($id === null) ? $controller->showUnknownAnimalPage() : $controller->showEditAnimalPage($id);
            } elseif ($_GET['action'] == 'sauverModification') {
                ($id === null) ? $controller->showUnknownAnimalPage() : $controller->updateAnimal($_POST, $id);
            } elseif ($_GET['action'] == 'supprimer') {
                ($id === null) ? $controller->showUnknownAnimalPage() : $controller->showDeleteAnimalPage($id);
            } elseif ($_GET['action'] == 'confirmerSuppression') {
                ($id === null) ? $controller->showUnknownAnimalPage() : $controller->deleteAnimal($id);
            } elseif($_GET['action'] == 'json') {
                ($id === null) ? $controller->emptyJSON() : $controller->getJSON($id);
            }  else {
                $controller->showHome();
            }

        } else {
            // Affiche la page d'accueil par défaut
            $controller->showHome();
        }

        // Affiche la vue après le routage
        $view->render();
    }
}

?>

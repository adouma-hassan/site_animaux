<?php

class View {
    private $title;
    private $content;
    private array $menu;
    private array $tabLiensHref;
    private Router $router;
    private array $list;
    private string $feedback;
    private string $scriptjs;

    public function __construct(Router $router, $feedback) {
        $this->tabLiensHref = [];
        $this->router = $router;
        $this->menu = array(
            'Home' => $router->getHomeURL(),
            'List' => $router->getListURL(),
            'Nouveau' => $router->getAnimalCreationURL(),
        );
        $this->feedback = $feedback;
        $this->scriptjs = "";

    }

    public function render() {
        $title = $this->getTitle();
        $content = $this->getContent();
        $menu = $this->getMenu();
        $feedback = $this->getFeedBack();
        $scriptjs = $this->getScriptJS();

        include("squelette.php");
    }

    public function getFeedBack() {
        return $this->feedback;
    }

    public function getScriptJS() {
        return $this->scriptjs;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getMenu() {
        return $this->menu;
    }

    // Prépare la page d'accueil du site
    public function prepareHomePage() {
        $this->title = "Bienvenue sur le site des animaux";
        $this->content = '<div class="home"><p>Si les animaux vous passionne tant et que vous souhaitez partager <br>
        votre connaissance sur les animaux <br>
        Vous pouviez créer autant que vous souhaitiez </p></div>';
    }

    // Prépare la page d'information sur un animal spécifique
    public function prepareAnimalPage(Animal $animal, $id) {
        $this->title = " Page sur " . self::htmlesc($animal->getNom());
        $this->content = "" . self::htmlesc($animal->getNom()) . " est un animal de l'espèce " . self::htmlesc($animal->getEspece()) . " et il a " . self::htmlesc($animal->getAge()).
        "<div><ul>
        <li><a href=\"".$this->router->getAnimalEditURL($id)."\">Modifier</a></li>
        <li><a href=\"".$this->router->getAnimalDeleteConfirmationURL($id)."\">Supprimer</a></li></ul></div>";
    }

    // Prépare la page d'erreur pour un animal inconnu
    public function prepareUnknownAnimalPage() {
        $this->title = " Animal inconnue";
        $this->content = " rien";
    }

    // Prépare la page d'erreur pour une page d'accueil incorrecte
    public function prepareUnknowHome() {
        $this->title = " Veuillez entrer un ID dans l'URL";
        $this->content = " ";
    }

    // Prépare la page d'erreur pour des informations d'animal non valides
    public function prepareErrorPageValide() {
        $this->title = "Les informations de l'animal ne sont pas valides";
    }

    // Prépare la page d'erreur pour des informations d'animal manquantes ou incorrectes
    public function prepareErrorPageManquante() {
        $this->title = "Les informations fournies sur l'animal sont manquantes ou incorrectes. Veuillez compléter !!!";
    }

    // Prépare la page de liste des animaux
    public function prepareListPage($animal) {
        $this->title = "Liste des Animaux";
        $this->scriptjs = 'function getDetails(id) {
            if( document.getElementById("button" + id).innerHTML != "Details") {
                document.getElementById("button" + id).innerHTML = "Details"
                document.getElementById("details" + id).innerHTML = "";
                return;
            } 
 
            let xhr = new XMLHttpRequest();
    
            let url = "'.$this->router->getJSONURL().'" + id;
            xhr.open("GET", url, true);
    
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if( document.getElementById("button" + id).innerHTML == "Details") {
                        document.getElementById("button" + id).innerHTML = "Cahcer les details"
                    } 
                    document.getElementById("details" + id).innerHTML = this.responseText;
                }
            }
            xhr.send();
        }';
        $this->content = "Voici la liste de tous les animaux dont le site connaît l'espèce";

        $this->content .= "<nav>
        <ul>";

        foreach ($animal as $cle => $valeur) {
            $id = '"'.$cle.'"';
            $idd = '"details'.$cle.'"';
            $idb = '"button'.$cle.'"';
            $this->content .= '<li><a href="'.$this->router->getAnimalURL($cle).'">'.self::htmlesc($valeur->getNom()).'</a> 
            <button id='.$idb.' type="button" ' . "onClick=getDetails($id)" .'>Details</button></li><p id='.$idd.'><p>';
        }

        $this->content .= " </ul>
        </nav>";
    }

    // Méthode d'affichage du succès de la création d'un animal
    public function displayAnimalCreationSuccess($id) {
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Creation d'un animal avec succès");  
    }

    // Méthode d'affichage du succès de la modification d'un animal
    public function displayAnimalUpdateSuccess($id) {
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Modification d'un animal avec succès");  
    }

    // Méthode d'affichage du succès de la modification d'un animal
    public function displayAnimalDeleteSuccess($id) {
        $this->router->POSTredirect($this->router->getListURL(), "Suppression d'un animal avec succès");  
    }

    // Méthode de préparation de la page de débogage
    public function prepareDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true), ENT_QUOTES, 'UTF-8') . '</pre>';
    }


    // Méthode de préparation de la page de création d'un animal
    public function prepareAnimalCreationPage(AnimalBuilder $animalbuild) {
        $this->title = 'Création d\'un animal';
        $errorContent = $animalbuild->getError() ? '<p style="color:red;">' . htmlspecialchars($animalbuild->getError(), ENT_QUOTES, 'UTF-8') . '</p>' : '';
        $this->content = '
            ' . $errorContent . '
            <form method="POST" action="' . $this->router->getAnimalSaveURL() . '">
            '.self::getFormFields($animalbuild).'
            </form>
        ';
    }

    public function prepareAnimalEditPage(AnimalBuilder $animalbuild, $id) {
        $this->title = 'Modification d\'un animal';
        $errorContent = $animalbuild->getError() ? '<p style="color:red;">' . htmlspecialchars($animalbuild->getError(), ENT_QUOTES, 'UTF-8') . '</p>' : '';
        $this->content = '
            ' . $errorContent . '
            <form method="POST" action="' . $this->router->getAnimalUpdateURL($id) . '">
                '.self::getFormFields($animalbuild).'
            </form>
        ';
    }

    public function prepareAnimalDeleteConfirmationPage(Animal $animal, $id) {
        $aname = self::htmlesc($animal->getNom());

		$this->title = "Suppression de l'animal $aname";
		$this->content = "<p>L'animal « {$aname} » va être supprimée.</p>\n";
		$this->content .= '<form action="'.$this->router->getAnimalDeleteURL($id).'" method="POST">'."\n";
		$this->content .= "<button>Confirmer</button>\n</form>\n";
    }


    protected function getFormFields($animalbuild) {
        return '<label>Nom : <input type="text" id="' . $animalbuild::NAME_REF . '" name="' . $animalbuild::NAME_REF . '" value="' . htmlspecialchars($animalbuild->getData()[$animalbuild::NAME_REF] ?? '', ENT_QUOTES, 'UTF-8') . '" /> </label>
        <label>Espèce : <input type="text" id="' . $animalbuild::SPECIES_REF . '" name="' . $animalbuild::SPECIES_REF . '" value="' . htmlspecialchars($animalbuild->getData()[$animalbuild::SPECIES_REF] ?? '', ENT_QUOTES, 'UTF-8') . '" /> </label>
        <label>Âge : <input type="text" id="' . $animalbuild::AGE_REF . '" name="' . $animalbuild::AGE_REF . '" value="' . htmlspecialchars($animalbuild->getData()[$animalbuild::AGE_REF] ?? '', ENT_QUOTES, 'UTF-8') . '" /> </label>
        <input type="submit" value="Enregistrer">';
    }

    /* Une fonction pour échapper les caractères spéciaux de HTML,
	* car celle de PHP nécessite trop d'options. */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			/* on échappe guillemets _et_ apostrophes : */
			ENT_QUOTES
			/* les séquences UTF-8 invalides sont
			* remplacées par le caractère �
			* au lieu de renvoyer la chaîne vide…) */
			| ENT_SUBSTITUTE
			/* on utilise les entités HTML5 (en particulier &apos;) */
			| ENT_HTML5,
			'UTF-8');
	}

    
}

?>

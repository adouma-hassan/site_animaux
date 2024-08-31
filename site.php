<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");

require_once("view/View.php");

require_once("view/ViewJSON.php");

require_once("control/Controller.php");

require_once("model/Animal.php");

require_once("model/AnimalStorage.php");

require_once("model/AnimalStorageStub.php");


require_once("model/AnimalStorageSession.php");
require_once("model/AnimalBuilder.php");
session_start();

/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
$router->main(new AnimalStorageSession());
?>
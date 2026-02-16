<?php

use app\controllers\HomeController;
use app\controllers\BesoinAchatController;
use app\controllers\RecapitulationController;

// ==================== TABLEAU DE BORD ====================
Flight::route('GET /', [HomeController::class, 'index']);

// ==================== BESOIN ====================
Flight::route('GET /besoin', [HomeController::class, 'besoinList']);
Flight::route('POST /besoin', [HomeController::class, 'besoinInsert']);
Flight::route('POST /besoin/update/@id', [HomeController::class, 'besoinUpdate']);
Flight::route('GET /besoin/delete/@id', [HomeController::class, 'besoinDelete']);

// ==================== DONS ====================
Flight::route('GET /dons', [HomeController::class, 'donList']);
Flight::route('POST /dons', [HomeController::class, 'donInsert']);
Flight::route('POST /dons/update/@id', [HomeController::class, 'donUpdate']);
Flight::route('GET /dons/delete/@id', [HomeController::class, 'donDelete']);

// ==================== VILLE ====================
Flight::route('GET /ville', [HomeController::class, 'villeList']);
Flight::route('POST /ville', [HomeController::class, 'villeInsert']);
Flight::route('POST /ville/update/@id', [HomeController::class, 'villeUpdate']);
Flight::route('GET /ville/delete/@id', [HomeController::class, 'villeDelete']);

// ==================== ACHATS BESOINS ====================
Flight::route('GET /besoin/achats', [BesoinAchatController::class, 'index']);
Flight::route('GET /besoin/achats/ville/@id', [BesoinAchatController::class, 'listByVille']);
Flight::route('GET /besoin/simulation', [BesoinAchatController::class, 'simulationForm']);
Flight::route('POST /besoin/simulation', [BesoinAchatController::class, 'simulation']);
Flight::route('POST /besoin/simulation/valider', [BesoinAchatController::class, 'validateSimulation']);

// ==================== RECAPITULATION ====================
Flight::route('GET /recap', [RecapitulationController::class, 'show']);
Flight::route('POST /recap/actualiser', [RecapitulationController::class, 'refresh']);

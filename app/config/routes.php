<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../controllers/HomeController.php';

use app\controllers\HomeController;


Flight::set('flight.views.path', __DIR__ . '/../views');


Flight::route('/', [HomeController::class, 'index']);


Flight::route('/besoin', [HomeController::class, 'besoinList']);

Flight::route('POST /besoin', function () {
    HomeController::besoinInsert();
});

Flight::route('POST /besoin/update/@id:[0-9]+', function ($id) {
    HomeController::besoinUpdate($id);
});

Flight::route('/besoin/delete/@id:[0-9]+', function ($id) {
    HomeController::besoinDelete($id);
    Flight::redirect('/besoin');
});


Flight::route('/dons', [HomeController::class, 'donList']);

Flight::route('POST /dons', function () {
    HomeController::donInsert();
});

Flight::route('POST /dons/argent', function () {
    HomeController::donArgentInsert();
});

Flight::route('POST /dons/update/@id:[0-9]+', function ($id) {
    HomeController::donUpdate($id);
});

Flight::route('/dons/delete/@id:[0-9]+', function ($id) {
    HomeController::donDelete($id);
});


Flight::route('/ville', [HomeController::class, 'villeList']);

Flight::route('POST /ville', function () {
    HomeController::villeInsert();
});

Flight::route('POST /ville/update/@id:[0-9]+', function ($id) {
    HomeController::villeUpdate($id);
});

Flight::route('/ville/delete/@id:[0-9]+', function ($id) {
    HomeController::villeDelete($id);
    Flight::redirect('/ville');
});

// Achats
Flight::route('GET /achats', [HomeController::class, 'achatPage']);
Flight::route('POST /achats/process', [HomeController::class, 'achatProcess']);

// Simulation
Flight::route('GET /simulation', [HomeController::class, 'simulationPage']);
Flight::route('POST /simulation/run', [HomeController::class, 'runSimulation']);
Flight::route('POST /simulation/validate', [HomeController::class, 'validateSimulation']);
Flight::route('POST /simulation/reset', [HomeController::class, 'resetData']);

// Récapitulation
Flight::route('GET /recap', [HomeController::class, 'recapPage']);
Flight::route('GET /recap/data', [HomeController::class, 'recapData']);


Flight::start();

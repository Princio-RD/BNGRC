<?php
// app/config.php
require __DIR__ . '/../vendor/autoload.php';

// Configure your MySQL connection here
$dsn = 'mysql:host=127.0.0.1;dbname=taxi_coop;charset=utf8mb4';
$user = 'root';
$pass = '';

Flight::register('db', 'PDO', [$dsn, $user, $pass], function($db){
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

// Method override to support PUT/DELETE from forms
Flight::before('start', function(){
    $req = Flight::request();
    if ($req->method === 'POST' && isset($req->data['_method'])) {
        $req->method = strtoupper($req->data['_method']);
    }
});

// Helper escape
Flight::map('e', function($v){ return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); });

<?php
// header('Content-Type: application/json; charset=utf-8');

require '../vendor/autoload.php';

// config setting
require './config.php';

$app = new \Slim\App(["settings" => $config]);

// to get dependency injection container
$container = $app->getContainer();

// PDO database library
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    // fixed character encoding problem
    $pdo -> exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


$app->get('/', function () {
   print "API Version 1.0";
});

$app->get('/list', function () {
   // print "some list here";

   $sth = $this->db->prepare("SELECT * FROM site_member_list ORDER BY seq");
    $sth->execute();
    $results = $sth->fetchAll();
    return $this->response->withJson($results);

});

$app->get('/name/{name}', function ($request, $response, $args) {
   print $args['name'];
});

$app->run();

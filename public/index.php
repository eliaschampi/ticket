<?php

/**
 * Api printer
 * @author Elias
 * @see facebook.com/elias_champi
 * @version 0.1.2019
 */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
require("TP.php");

$app = AppFactory::create();
$app->addRoutingMiddleware();

// CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// get call
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("listo para imprimir");
    return $response;
});

// POST call
$app->post('/print', function (Request $request, Response $response) {
    $data = json_decode($request->getBody(), true);
    try {
        $instance = new TP();
        $instance->print($data);
        $res = array('success' => true, 'message' => "exito");
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\Exception $ex) {
        $err = array('success' => false, 'message' => $ex->getMessage());
        $response->getBody()->write(json_encode($err));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->run();
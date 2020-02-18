<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->post('/login', function (Request $request, Response $response, $args){
    $attr = $request->getParsedBody()['login'];
    $requestforDB='select user_name, user_firstname from user where user_login="'.$attr.'"';
    $data=requestDB($requestforDB);
    $jsonData= json_encode($data);
    $response->getBody()->write($jsonData);
    return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Content-Type', 'application/json');;
});

$app->post('/register', function (Request $request, Response $response, $args){

});

$app->get('/getToken', function (Request $request, Response $response, $args){

});



function requestDB($request){

    $base = new mysqli('localhost', 'coren', '1', 'tp4Web');
    //$base->exec("SET CHARACTER SET utf8");
    $retour = $base->query($request);
    $base = null;

    return $retour->fetch_all();
}

$app->run();
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
    $login = $request->getParsedBody()['login'];
    $passwd = $request->getParsedBody()['passwd'];
    $requestforDB='select user_password from user where user_login="'.$login.'"';
    $data=extractformDB($requestforDB);
    if(!$data){
        $jsonData= json_encode(['error'=>'mot de passe ou login incorrect']);
        $response->getBody()->write($jsonData);
    }else if (!password_verify($passwd, $data[0][0])){
        $jsonData= json_encode($data[0]);
        $response->getBody()->write($jsonData);
    }
    //$jsonData= json_encode($data);
    //$response->getBody()->write($jsonData);
    return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Content-Type', 'application/json');
});

$app->post('/register', function (Request $request, Response $response, $args){
    $infos=$request->getParsedBody();
    $requestforBD='insert into user values(null, "'.$infos['name'].'", "'.$infos['firstname'].'", "'.$infos['email'].'", "'.$infos['login'].'", "'.password_hash($infos['password'], PASSWORD_BCRYPT).'", "hello");';
    $error=insertintoDB($requestforBD);
    $jsonData= json_encode($error);
    $response->getBody()->write($jsonData);
    return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Content-Type', 'application/json');
});

//$app->get('/register2', function (Request $request, Response $response, $args){
    //$requestforBD='insert into user values(null, "hello", "hello", "hello", "hello", "'.password_hash("hello", PASSWORD_BCRYPT).'", "hello");';
    //$error=insertintoDB($requestforBD);
    //$jsonData= json_encode($error);
    //$response->getBody()->write($jsonData);
  // return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Content-Type', 'application/json');
//});

$app->get('/getToken', function (Request $request, Response $response, $args){


    return $response;
});

$app->get('/news', function (Request $request, Response $response, $args){

   return $response;
});



function extractformDB($request){

    $base = new mysqli('localhost', 'coren', '1', 'tp4Web');

    if ($base->connect_errno) {
        printf("Echec de la connexion: %s\n", $base->connect_error);
        exit();
    }
    $retour = $base->query($request);
    $base = null;

    return $retour->fetch_all();
}

function insertintoDB($request){
    $errors=[];
    $base = new mysqli('localhost', 'coren', '1', 'tp4Web');
    if ($base->connect_errno) {
        echo ($base->sqlstate);
        printf("Echec de la connexion: %s\n", $base->connect_error);
        exit();
    }


    if (!$base->query($request)) {
        //echo("Message d'erreur : %s\n". $base->sqlstate);
        if ($base->sqlstate==23000){
            $error=$base->sqlstate;
            array_push($errors, [$error=>'Vous avez entré un login qui existe déjà']);
            //echo ("Le login entré existe déjà");
        }
        //$errors[0]=["erreur"=>$base->sqlstate];

    }
    $base = null;

    //return $errors;row
    return $errors;
}

$app->run();
<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/dataentry',function (Request $request,Response $response) {
    $data = $request->getParsedBody();
    $this->logger->info("Data entry : ".$data['char']);
    //var_dump($cred);
    $img = $data['image'];
    $char = $data['char'];
    $response->getBody()->write("Test");
    return $response->withStatus(200);
});


$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    // $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    $string = file_get_contents(__DIR__ ."/characters.json");
    $json = json_decode($string, true);
    $args["chars"] = $json;
    
    return $this->renderer->render($response, 'index.phtml', $args);
});
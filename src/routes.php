<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/dataentry',function (Request $request,Response $response) {
    $data = $request->getParsedBody();
    $this->logger->info("Data entry : ".$data['char']);
    //var_dump($cred);
    $img = base64_decode( explode("base64,", $data['image'] )[1]);

    //Test
    $constants = require __DIR__ . '/../src/constants.php';
    $folder = $constants['data_folder'];

    $string = file_get_contents(__DIR__ ."/../src/characters.json");
    $json = json_decode($string, true);
    $char = $data['char'];

    $folder = $folder . 'character_' . $char . '/';
    // Find name for new file
    $files = array();
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
        $name = 1;
    }
    else {
        $dir = opendir($folder); // open the cwd..also do an err check.
        while(false != ($file = readdir($dir))) {
            if(($file != ".") and ($file != "..") and ($file != "index.php")) {
                $files[] = $file; // put in array.
            }
        }

        natsort($files); // sort.
        $file = end($files);
        $name = explode('.png', $file)[0];
        $name++;
    }


    $file = $folder . $name . '.png';

    file_put_contents($file, $img);

    $this->logger->info("Image :".$img);
    $char = $data['char'];
    $response->getBody()->write($char);
    return $response->withStatus(200);
});

$app->get('/charactermap', function (Request $request,Response $response) {
    $string = file_get_contents(__DIR__ ."/../src/characters.json");
    $json = json_decode($string, true);
    return $response->withJson($json);
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    // $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    $string = file_get_contents(__DIR__ ."/../src/characters.json");
    $json = json_decode($string, true);
    $args["chars"] = $json;

    return $this->renderer->render($response, 'index.phtml', $args);
});

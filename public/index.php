<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use CodewarsApiClient\Client;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$app = AppFactory::create();
$client = new Client($_ENV["CODEWARS_API_KEY"]);

$app->get('/user/{username}', function (Request $request, Response $response, $args) use ($client) {
    $user = $client->user($args["username"]);
    $response_json = json_encode($user);

    $response->getBody()->write($response_json);
    return $response;
});

$app->get('/user/{username}/completed', function (Request $request, Response $response, $args) use ($client) {
    $user_completed = $client->completed($args["username"]);
    $response_json = json_encode($user_completed);

    $response->getBody()->write($response_json);
    return $response;
});

$app->get('/user/{username}/authored', function (Request $request, Response $response, $args) use ($client) {
    $user_authored = $client->authored($args["username"]);
    $response_json = json_encode($user_authored);

    $response->getBody()->write($response_json);
    return $response;
});

$app->get('/challenge/{challenge_id}', function (Request $request, Response $response, $args) use ($client) {
    $challenge = $client->challenge($args["challenge_id"]);
    $response_json = json_encode($challenge);

    $response->getBody()->write($response_json);
    return $response;
});

$app->get("/challenges[/{challenge_ids:.*}]", function (Request $request, Response $response, $args) use ($client) {
    $challenge_ids = explode('/', $args['challenge_ids']);
    $challenges = $client->challenges($challenge_ids);
    $response_json = json_encode($challenges);

    $response->getBody()->write($response_json);
    return $response;
});

$app->run();
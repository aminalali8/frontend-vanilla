<?php

// Require composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Generate REquest object
$request = new HTTP_Request2();
$request->setConfig(array(
    'follow_redirects' => TRUE
));

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();


// Create Router instance
$router = new \Bramus\Router\Router();

// Define routes
$router->get('/', function () use ($request) {
    $request->setUrl('http://app/api/authors');
    $request->setMethod(HTTP_Request2::METHOD_GET);

    try {
        $response = $request->send();
        if ($response->getStatus() == 200) {
            echo '<pre>' . $response->getBody() . '</pre>';
        }
        else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                $response->getReasonPhrase();
        }
    }
    catch(HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
});

$router->get('/create-author', function () use ($request, $faker) {
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->setUrl('http://app/api/authors');
    $request->addPostParameter(array(
        'name' => $faker->name(),
        'email' => $faker->email(),
        'github' => $faker->url(),
        'twitter' => $faker->url(),
        'location' => $faker->city(),
        'latest_article_published' => $faker->date()
    ));
    try {
        $response = $request->send();
        if ($response->getStatus() == 200 || $response->getStatus() === 201) {
            echo '<pre>' . $response->getBody() . '</pre>';
        }
        else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                $response->getReasonPhrase();
        }
    }
    catch(HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
});

// Run it!
$router->run();
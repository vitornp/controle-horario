<?php

$app = require_once __DIR__ . '/app.php';

$app->get('/', function() use ($app) {
    return $body = $app['twig']->render('index.html.twig', array(
        'index' => 'Home page'
    ));
});

return $app;
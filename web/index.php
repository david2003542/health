<?php

require('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/signup', function() use($app) {
  return $app['twig']->render('signup.twig');
});

$app->post('/signup', function (Request $request) {
  $username = $request->get('name');
  $account = $request->get('account');
  $account = $request->get('password');
  return new Response('Thank you for your sign up!'.$username.'<br><a href=/login>', 301);
});

$app->get('/login', function() use($app) {
  $app['monolog']->addDebug('entere login page');
  return $app['twig']->render('login.twig');
});

$app->post('/login', function (Request $request) {
  $message = $request->get('account');
  return new Response('Thank you for your sign in!'.$message, 201);

});

$app->run();

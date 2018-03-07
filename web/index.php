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

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
      'driver' => 'pdo_mysql',
      'dbhost' => 'us-cdbr-iron-east-05.cleardb.net',
      'dbname' => 'heroku_ae0820ac4f0cc52',
      'user' => 'b4ecfd51a422f3',
      'password' => '67e76038',
  ),
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
  $password = $request->get('password');
  $app['db']->insert('member', array(
      'name' => $username,
      'account' => $account,
      'password' => $password
    )
  );
  return new Response('Thank you for your sign up! '.$username.'<br><a href=/login>return to login</a>', 201);
});

$app->get('/login', function() use($app) {
  $app['monolog']->addDebug('entere login page');
  return $app['twig']->render('login.twig');
});

$app->post('/login', function (Request $request) {
  $message = $request->get('account');
  return new Response('Thank you for your sign in! '.$message, 201);

});

$app->get('/analytics', function() use($app) {
  return $app['twig']->render('analytics.twig');
});

$app->get('/daily_post', function() use($app) {
  return $app['twig']->render('daily_post.twig');
});

$app->post('/daily_post', function (Request $request) {
  $kilograms = $request->get('kilograms');
  $picture = $request->get('picture');
  return new Response('Thank you for your daily log ! ', 201);

});

$app->run();

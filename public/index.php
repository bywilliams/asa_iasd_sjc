<?php 

use Slim\Factory\AppFactory;
use Slim\Middleware\StaticFiles;
use Slim\Routing\RouteCollectorProxy;
use Slim\Middleware\MethodOverrideMiddleware;

require '../vendor/autoload.php';

// Objeto DotEnv para trabalhar com variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();

// Slim App
$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Method Override Middleware
$app->add(new MethodOverrideMiddleware());

$routes = require'../app/routes/routes.php';

$routes($app);

$app->addErrorMiddleware(true, true, true);

$app->run();

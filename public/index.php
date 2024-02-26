<?php 

use Slim\Factory\AppFactory;
use Slim\Middleware\StaticFiles;
use app\Middleware\JwtMiddleware;
use Slim\Routing\RouteCollectorProxy;

require '../vendor/autoload.php';

// Objeto DotEnv para trabalhar com variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();

// Slim App
$app = AppFactory::create();

$routes = require'../app/routes/routes.php';

$routes($app);

$app->addErrorMiddleware(true, true, true);

$app->run();

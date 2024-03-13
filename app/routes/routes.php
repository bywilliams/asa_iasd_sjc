<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use app\controllers\HomeController;
use app\controllers\UserController;
use app\controllers\FamilyController;
use app\controllers\FoodController;
use app\controllers\FoodStockController;
use app\controllers\TransactionController;
use app\controllers\EventController;


return function (App $app) {

    $app->get('/', [HomeController::class, 'index']);

    // User routes
    $app->group('/usuario', function ($group) {
        $group->post('/login', [UserController::class, 'validarUser']);
        $group->get('/dashboard', [UserController::class, 'dashboard']);
        $group->get('/logout', [UserController::class, 'logout']);
        $group->get('/equipe-asa', [UserController::class,'team']);
    });

    // Family Routes
    $app->group('/family', function ($group) {
        $group->get('/index', [FamilyController::class, 'index']);
        $group->post('/store', [FamilyController::class, 'store']);
        $group->put('/update/{id}', [FamilyController::class, 'update']);
        $group->delete('/delete/{id}', [FamilyController::class, 'destroy']);
    });

    // Food routes
    $app->group('/food', function ($group) {
        $group->get('/index', [FoodStockController::class, 'index']);
        $group->post('/stock-store', [FoodStockController::class, 'stockStore']);
    });;

    // Transaction routes
    $app->group('/transacao', function ($group) {
        $group->get('/index', [TransactionController::class, 'index']);
        $group->get('/receitas', [TransactionController::class,'revenues']);
        $group->get('/despesas', [TransactionController::class,'expenses']);
        $group->post('/store', [TransactionController::class, 'store']);
    });;

    // Events routes
    $app->group('/event', function ($group) {
        $group->post('/store', [EventController::class,'store']);
        $group->get('/index', [EventController::class,'index']);
    });

    $app->put('/basket/donated', [FoodStockController::class,'donatedBasket']);

    // Rota fallback exibi uma mensagem
    $app->any('{route:.*}', function (Request $request, Response $response) {
        $response->getBody()->write('
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height:  100vh;
                        margin:  0;
                        background-color: #ccc;
                        font-family: Arial, sans-serif;
                    }
                    h1 {
                        text-align: center;
                    }
                    p {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div>
                <h1>A página que você tentou acessar não existe!</h1>
                <p> Volte para página inicial clicando: <a href="/">aqui</a> </p>
                </div>
            </body>
            </html>
        ');
        return $response;
    });
};

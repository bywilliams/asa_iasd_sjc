<?php
use Slim\App;
use app\controllers\HomeController;
use app\controllers\UserController;
use app\controllers\FamilyController;
use app\controllers\FoodController;
use app\controllers\FoodStockController;
use app\controllers\TransactionController;

return function (App $app)
{
    $app->get('/', [HomeController::class, 'index']);

    // User routes
    $app->group('/usuario', function($group){
        $group->post('/login', [UserController::class, 'validarUser']);
        $group->get('/dashboard', [UserController::class, 'dashboard']);
        $group->get('/logout', [UserController::class, 'logout']);
    });

    // Family Routes
    $app->group('/family', function ($group){
        $group->get('/index', [FamilyController::class, 'index']);
        $group->post('/store', [FamilyController::class, 'store']);
    });

    // Food routes
    $app->group('/food', function($group){
        $group->get('/index', [FoodStockController::class, 'index' ]);
        $group->post('/stock-store', [FoodStockController::class, 'stockStore']);
    });

    // Transaction routes
    $app->group('/transacao', function($group){
        $group->get('/index', [ TransactionController::class, 'index']);
        $group->post('/store', [TransactionController::class, 'store']);
    });

};
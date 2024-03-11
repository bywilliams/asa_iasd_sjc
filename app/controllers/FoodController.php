<?php 

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\models\FoodModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FoodController
{
    use GlobalControllerTrait;

    private $model;


    /**
     * Método __construct()
     * 
     * Este Método ira construir o objeto da classe do controlador e ao mesmo tempo instância um objeto FoodModel
     */
    public function __construct()
    {
        $this->model = new FoodModel();
    }

    public function index(Request $request, Response $response) 
    {

        if (!$this->validateJwtToken()) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }

        view('alimentos', ['title' => 'Lista de Alimentos']);
        return $response;
    }


}
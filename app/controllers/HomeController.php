<?php
namespace app\controllers;
use app\traits\GlobalControllerTrait;
session_start();
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Classe HomeController
 * 
 * Esta classe é responsável apresentar a página inicial da aplicação ou seja os formulários Register e Login
 */
class HomeController
{

    use GlobalControllerTrait;

    /**
     * Método index
     * 
     * Este método apresenta a página home da aplicação
     */
    public function index(Request $request, Response $response)
    {
        // Gera CSRF Token
        generateCsrfToken();

        // variáveis para apresentação de erros de validação
        $status = $_SESSION['status'] ?? '';
        $status_message = $_SESSION['status_message'] ?? '';
        unset($_SESSION['status']);
        unset($_SESSION['status_message']);

        view('home', ['title' => 'Bem vindo!', 'status' => $status, 'status_message' => $status_message]);
        return $response;
    }

}

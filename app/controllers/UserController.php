<?php

namespace app\controllers;
session_start();
use app\models\UserModel;
use app\models\FoodModel;
use app\models\FoodStockModel;
use app\models\FoodStockosModel;
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * Classe UserController
 * 
 * Esta classe é responsável por CRUD e demais ações de usuários
 */
class UserController
{
    use GlobalControllerTrait;
    use SessionMessageTrait;

    private $model;


    /**
     * Construtor da classe UserController
     * 
     * Inicializa a classe UserController e cria uma nova instância do modelo UserModel
     */
    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * Método dashboard
     * 
     * Este método apresenta a página Dashboard da aplicação ao usuário logado
     */
    public function dashboard(Request $request, Response $response)
    {
        if(!$this->validateToken($response)) {
            $this->setMessage('error', 'O token de autenticação é inválido ou expirou. Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        // Gera CSRF Token
        generateCsrfToken();

        $userLogged = $this->getUserName();

        // traz os produtos disponíveis para cadastro de estoque e lista os alimentos (estoque) cadastrados para a dashboard
        $foodModel = new FoodModel();
        $foodStockModel = new FoodStockModel();
        $allFoods = $foodModel->findAllFoods();
        $latestStockFoods = $foodStockModel->latestStockFoods();

        $totalBaskets = $foodStockModel->calculateBasicBaskets();

        view('dashboard_main', [
            'title' => 'Bem vindo a ASA da IASD de SJC!',
            'user' => $userLogged,
            'allFoods' => $allFoods,
            'latestStockFoods' => $latestStockFoods,
            'totalBaskets' => $totalBaskets
        ]);
        return $response;
    }

    /**
     * Método login 
     * 
     * Este método valida e efetua login na aplicação leavando o usuário a Dashboard principal
     */
    public function validarUser(Request $request, Response $response)
    {

        // Pega o corpo da requisição com Slim
        $formData = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($formData)) {
            $this->setMessage('error', 'Ação inválida!');
            return $response->withRedirect('/');
        }

        // Sanitiza os dados
        $data = $this->sanitizeData($formData);
        
        if (empty($data['email']) || empty($data['password'])) {
            $this->setMessage('error', 'Preencha os campos email e senha!');
            return $response->withRedirect('/');
        }

        // Variável que recebe os dados do usuário existente
        $userFound = (object) $this->model->checkUserExist($data['email']);

        // Checa se a variável do usuário possui valor
        if (empty($userFound->name)) {
            $this->setMessage('error', 'Usuário não encontrado.');
            return $response->withRedirect('/');
        } 
        
        // Checa igualdade das senhas
        if (!password_verify($data['password'], $userFound->password)) {
            $this->setMessage('error', 'Usuário e/ou senha inválidos!');
            return $response->withRedirect('/');
        }

        // Gera o JWT Token para usuário
        $this->generateJwtToken($userFound);        

        // Configura uma mensagem de boas vindas
        $this->setMessage('success', "Seja bem vindo $userFound->name!");

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');
    }

}

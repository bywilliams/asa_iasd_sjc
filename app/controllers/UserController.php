<?php

namespace app\controllers;
session_start();
use app\models\UserModel;
use app\models\FoodModel;
use app\models\FoodStockModel;
use app\models\TransactionModel;
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
        if(!$this->validateToken()) {
            $this->setMessage('error', 'O token de autenticação é inválido ou expirou. Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        // Gera CSRF Token
        generateCsrfToken();

        // dados do usuário logado
        $userLogged = $this->getUserName();

        // Traz as categorias (receitas|despesas) financeiras para o modal de transação
        $transactionModel = new TransactionModel();
        $revenueCategories = $transactionModel->getRevenueCategories();
        $expenseCategories = $transactionModel->getExpenseCategories();

        // traz os produtos disponíveis para cadastro de estoque
        $foodModel = new FoodModel();
        $allFoods = $foodModel->findAllFoods();
        // Lista os alimentos (estoque) cadastrados para a dashboard
        $foodStockModel = new FoodStockModel();
        $latestStockFoods = $foodStockModel->latestStockFoods();

        // Total de cestas disponíveis
        $totalBaskets = $foodStockModel->calculateBasicBaskets();
        
        // mantêm dados dos inputs caso erro nas validações dos forms 
        $old = $_SESSION['old'] ?? null;

        view('dashboard_main', [
            'title' => 'Bem vindo a ASA da IASD de SJC!',
            'user' => $userLogged,
            'revenueCategories' => $revenueCategories,
            'expenseCategories' => $expenseCategories,
            'allFoods' => $allFoods,
            'latestStockFoods' => $latestStockFoods,
            'totalBaskets' => $totalBaskets,
            'old' => $old
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
        if (empty($userFound)) {
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

<?php

namespace app\controllers;
use app\models\UserModel;
use app\models\FoodModel;
use app\models\FoodStockModel;
use app\models\TransactionModel;
use app\models\FamilyModel;
use app\models\EventModel;
use app\traits\GlobalControllerTrait;
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
        if(empty($this->validateJwtToken())) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }
        
        // Gera CSRF Token
        generateCsrfToken();
        
        // Obtenha os dados do usuário decodificados do atributo 'user' 
        $userLogged = (object) $this->validateJwtToken();
        
        if ($userLogged == null) {
            manageMessages('error',9);
            return $response->withRedirect('/');
        }
        
        // Traz as categorias (receitas|despesas) financeiras para o modal de transação
        $transactionModel = new TransactionModel();
        $revenueCategories = $transactionModel->getRevenueCategories();
        $expenseCategories = $transactionModel->getExpenseCategories();
        
        // traz os produtos disponíveis para cadastro de estoque
        $foodModel = new FoodModel();
        $allFoods = $foodModel->findAllFoods();

        // Lista os últimos alimentos (estoque) cadastrados
        $foodStockModel = new FoodStockModel();
        $latestStockFoods = $foodStockModel->latestStockFoods();
        $totalStockFoods = $foodStockModel->getTotalFoods();

        // traz total receitas, despesas e balance total
        $transactionModel = new TransactionModel();
        $totalRevenue = $transactionModel->getTotalTransactionsByType('receita');
        $totalExpense = $transactionModel->getTotalTransactionsByType('despesa');
        $totalBalance = $transactionModel->getTotalBalance();

        // Traz a quantidade de famílias cadastradas que  estão ativas
        $familyModel = new FamilyModel();
        $getActiveFamilies = $familyModel->getActiveFamilies();
        $totalActiveFamilies = $familyModel->getTotalActiveFamilies();

        // Traz os últimos eventos cadastrados
        $eventModel = new EventModel();
        $lastestEvents = $eventModel->latestEvents();
        
        // Total de cestas disponíveis
        $totalBaskets = $foodStockModel->calculateBasicBaskets();
        
        // Mantêm dados dos inputs caso erro nas validações dos forms 
        $old = $_SESSION['old'] ?? null;
        
        view('dashboard_main', [
            'title' => 'Bem vindo a ASA da IASD de SJC!',
            'user' => $userLogged,
            'revenueCategories' => $revenueCategories,
            'totalRevenue' => $totalRevenue,
            'expenseCategories' => $expenseCategories,
            'totalExpense' => $totalExpense,
            'totalBalance' => $totalBalance,
            'totalActiveFamilies' => $totalActiveFamilies,
            'getActiveFamilies' => $getActiveFamilies,
            'allFoods' => $allFoods,
            'totalStockFoods' => $totalStockFoods,
            'latestStockFoods' => $latestStockFoods,
            'latestEvents' => $lastestEvents,
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
            manageMessages('error','1');
            return $response->withRedirect('/');
        }

        // Sanitiza os dados
        $data = $this->sanitizeData($formData);
        
        if (empty($data['email']) || empty($data['password'])) {
            manageMessages('error','3');
            return $response->withRedirect('/');
        }

        // Variável que recebe os dados do usuário existente
        $userFound = (object) $this->model->checkUserExist($data['email']);

        // Checa se a variável do usuário possui valor
        if (empty($userFound)) {
            manageMessages('error','10');
            return $response->withRedirect('/');
        } 
        
        // Checa igualdade das senhas
        if (!password_verify($data['password'], $userFound->password)) {
            manageMessages('error','11');
            return $response->withRedirect('/');
        }
        
        // Gera o JWT Token para usuário
        $this->generateJwtToken($userFound);               

        // Configura uma mensagem de boas vindas 
        manageMessages('success','4', $userFound);

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');
    }

    public function team(Request $request, Response $response)
    {
        if(empty($this->validateJwtToken())) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }

        // Obter os dados do usuário decodificados
        $userLogged = (object) $this->validateJwtToken();

        view('team_asa',[
            'title' => 'Equipe da ASA 2024',
            'user' => $userLogged
        ]);

        return $response;
    }

}

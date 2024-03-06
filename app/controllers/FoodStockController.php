<?php 

namespace app\controllers;
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use app\models\FoodStockModel;
use app\models\FoodModel;
use app\models\UserModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FoodStockController
{
    use GlobalControllerTrait, SessionMessageTrait;

    private $model;

    /**
     * Método __construct()
     * 
     * Este Método ira construir o objeto da classe do controlador e ao mesmo tempo instância um objeto FoodModel
     */
    public function __construct()
    {
        $this->model = new FoodStockModel();
    }
    
    /**
     * Método index()
     * 
     * Este método apresenta a view de estoque de alimentos rota /food/index
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response) 
    {
        if(!$this->validateJwtToken()) {
            $this->setMessage('error', 'Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        unset($_SESSION['old']); // Limpa a sessão old
        
        $userLogged = (object) $this->validateJwtToken(); // checa e recebe dados do usuário logado

        $foods = new FoodModel();
        $foodsList = $foods->findAllFoods(); // Lista de alimentos para form da view

        $users = new UserModel();
        $usersList = $users->index(); // Lista de usuários para o form da view

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ler o parâmetro da página da url
        $itemsPerPage = 10;

        $inicio = ($page - 1) * $itemsPerPage; // calcula o indice de início de fim dos dados para a página atual

        $sql = '';
        if (!empty($_GET)) {
            $params = ['id', 'food_id', 'basic_basket',  'user_id'];
            $aliases = ['id' => 'fs', 'food_id' => 'fs', 'user_id' => 'fs', 'basic_basket' => 'fs'];
            $sql = $this->createSqlConditions($params, $_GET, $aliases);
            $_SESSION['old'] = $_GET;
        } 

        $old = $_SESSION['old'] ?? null; // Mantêm os valores dos inputs de pesquisa

        $foodsStock = $this->model->index($inicio, $itemsPerPage, $sql); // Variável que obtêm a query padrão ou personalizada 

        $totalRegistros = $foodsStock['totalRegistros']; // Total de registros na tela

        $totalPaginas = ceil($totalRegistros / $itemsPerPage); // Calcular o número total de páginas

        view('alimentos', [
            'title' => 'Lista de Alimentos',
            'user' => $userLogged,
            'usersList' => $usersList,
            'foodsList' => $foodsList,
            'foodsStock' => $foodsStock,
            'page' => $page,
            'totalPaginas' => $totalPaginas,
            'old' => $old
        ]);
        return $response;
    }

    public function stockStore(Request $request, Response $response)
    {
        // Pega o corpo da requisição com Slim
        $data = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '');

            // redireciona e apresenta mensagem de erro
            $this->setMessage('error', 'Ação inválida!');
            return $response->withRedirect('/');
        }
        
        // Converte os dados em objeto
        $formData = (object) $this->sanitizeData($data);

        if (empty($formData->food_id) || empty($formData->qtde) || empty($formData->created_at)) {
            $_SESSION['old'] = $_POST;
            $this->setMessage('error', 'Preencha os campos obrigatórios!');
            return $response->withRedirect('/usuario/dashboard');
        }

        // Limpa a sessão old
        unset($_SESSION['old']);

        // checar se alimento já existe no estoque
        $foodStockExists = $this->model->findFoodById($formData);

        // fluxo condicional para decidir se o alimento será inserido (novo) ou atualizado de acordo com a etapa 1
        if (!$foodStockExists) { 
            // Salva registro no banco de dados
            $this->model->store($formData);
            $this->setMessage('success', 'Alimento cadastrado no estoque com sucesso!');
        } else {
            $this->model->updateFoodStock($formData);
            $this->setMessage('success', 'Alimento atualizado no estoque com sucesso!');
        }
        
        return $response->withRedirect('/usuario/dashboard');
    }

    public function donatedBasket(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '');

            // redireciona e apresenta mensagem de erro
            $this->setMessage('error', 'Ação inválida!');
            return $response->withRedirect('/');
        }

        if(empty($data['family_id'])) {
            $_SESSION['old'] = $_POST;
            $this->setMessage('error', 'Preencha os campos obrigatórios!');
            return $response->withRedirect('/usuario/dashboard');
        }

        if($this->model->donatedBasket($data['family_id'])) {
            $this->setMessage('success', 'Cesta doada com sucesso!');
        } else {
            $this->setMessage('error', 'Falha ao doar cesta consulte administrador do sistema');
        }

        return $response->withRedirect('/usuario/dashboard');

    }

}
<?php 

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use app\models\FoodStockModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FoodStockController
{
    use GlobalControllerTrait;
    use SessionMessageTrait;

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

    public function index(Request $request, Response $response) 
    {

        if(!$this->validateToken()) {
            $this->setMessage('error', 'Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        // dados do usuário logado
        $userLogged = $this->getUserName();

        // Ler o parâmetro da página da url
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itenPerPage = 10;

        // calcula o indice de início de fim dos dados para a página atual
        $inicio = ($page - 1) * $itenPerPage;

        // Monta o SQL de pesquisa personalizada
        $sql = '';
        if ($_GET) {
            $params = ['full_name', 'id', 'qtde_childs', 'gender', 'sits_family_id'];
            $sql = $this->createSqlConditions($params, $_GET);
            $_SESSION['old'] = $_GET;
        }

        // Mantêm os valores dos inputs de pesquisa
        $old = $_SESSION['old'] ?? null;

        // Variável que obtêm a query padrão ou personalizada 
        $foodsStock = $this->model->index($inicio, $itenPerPage, $sql);

        // Total de registros na tela
        $totalRegistros = $foodsStock['totalRegistros'];

        // Calcular o número total de páginas
        $totalPaginas = ceil($totalRegistros / $itenPerPage);

        // print_r($userLogged); die;

        view('alimentos', [
            'title' => 'Lista de Alimentos',
            'user' => $userLogged,
            'foodsStock' => $foodsStock['data'],
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

        //TODO: 1 checar se alimento já existe no estoque
        $foodStockExists = $this->model->findFoodById($formData);

        // TODO: 2 fluxo condicional para decidir se o alimento será inserido (novo) ou atualizado de acordo com a etapa 1
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

}
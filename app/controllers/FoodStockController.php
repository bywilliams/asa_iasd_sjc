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
            $this->setMessage('error', 'O token de autenticação é inválido ou expirou. Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        view('alimentos', ['title' => 'Lista de Alimentos']);
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
        
        // Converte em objeto
        $formData = (object) $this->sanitizeData($data);

        if (empty($formData->food_id) || empty($formData->qtde) || empty($formData->created_at)) {
            $_SESSION['old'] = $_POST;
            $this->setMessage('error', 'Preencha os campos obrigatórios!');
            return $response->withRedirect('/usuario/dashboard');
        }

        // Limpa a sessão old
        unset($_SESSION['old']);

        // Salva registro no banco de dados
        //TODO: criar model e método para inserção de alimentos no Stock
        $this->model->store($formData);
        
        $this->setMessage('success', 'Alimento cadastrado no estoque com sucesso!');

        return $response->withRedirect('/usuario/dashboard');
    }

}
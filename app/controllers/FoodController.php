<?php 

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use app\models\FoodModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FoodController
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
        $this->model = new FoodModel();
    }

    public function index(Request $request, Response $response) 
    {

        if(!$this->validateToken($response)) {
            $this->setMessage('error', 'O token de autenticação é inválido ou expirou. Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        view('alimentos', ['title' => 'Lista de Alimentos']);
        return $response;
    }

    public function store(Request $request, Response $response)
    {
        // Pega o corpo da requisição com Slim
        $data = $request->getParsedBody();

        var_dump($data); die;

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '');

            // redireciona e apresenta mensagem de erro
            $_SESSION['status'] = 'error';
            $_SESSION['status_message'] = 'Ação inválida!';
            return $response->withRedirect('/');
        }

        // Converte em objeto
        $formData = (object) $this->sanitizeData($data);

        if (empty($formData->food_id) || empty($formData->qtde) || empty($formData->created_at)) {
            $this->setMessage('error', 'Preencha os campos obrigatórios!');
            return $response->withRedirect('/usuario/dashboard');
        }

        // Salva registro no banco de dados
        //TODO:: criar model e método para inserção de alimentos no Stock
        $this->model->store($formData);

        $this->setMessage('success', 'Alimento cadastrado com sucesso!');

        return $response->withRedirect('/usuario/dashboard');
    }

}
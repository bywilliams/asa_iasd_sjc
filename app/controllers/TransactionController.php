<?php 

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use app\models\TransactionModel;
use PDOException;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransactionController 
{

    use GlobalControllerTrait;
    use SessionMessageTrait;

    private $model;

    public function __construct()
    {
        $this->model = new TransactionModel();
    }

    public function index(Request $request, Response $response)
    {
        echo 'Chegou aqui'; die;
    }

    public function store(Request $request, Response $response)
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

        // Sanitiza os dados
        $formData = (object) $this->sanitizeData($data);

        //print_r($formData); die;

        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['title', 'type', 'category_id', 'value', 'created_at'];

        foreach($fieldsToCheck as $field) {
            if (empty($formData->$field)) {
                $this->setMessage('error', 'Preencha os campos obrigatórios!');
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        // Salva transação no banco de dados
        $this->model->store($formData);

        $this->setMessage('success', 'Transação inserida com sucesso!');

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');

        
    }

}
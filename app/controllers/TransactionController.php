<?php 

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\models\TransactionModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransactionController 
{

    use GlobalControllerTrait;

    private $model;

    public function __construct()
    {
        $this->model = new TransactionModel();
    }

    public function revenues(Request $request, Response $response)
    {
        if (!$this->validateJwtToken()) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }

        // checa e recebe dados do usuário logado
        $userLogged = (object) $this->validateJwtToken();

        // Mantêm os valores dos inputs de pesquisa
        $old = $_SESSION['old'] ?? null;

        $revenues = $this->model->getRevenues();

        view('receitas', [
            'title' => 'Lista de receitas',
            'user' => $userLogged,
            'old' => $old,
            'revenues' => $revenues
        ]);

        return $response;
    }

    public function expenses(Request $request, Response $response)
    {
        if (!$this->validateJwtToken()) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }

        // checa e recebe dados do usuário logado
        $userLogged = (object) $this->validateJwtToken();

        // Mantêm os valores dos inputs de pesquisa
        $old = $_SESSION['old'] ?? null;

        $expenses = $this->model->getExpenses();

        view('despesas', [
            'title' => 'Lista de despesas',
            'user' => $userLogged,
            'old' => $old,
            'expenses' => $expenses
        ]);

        return $response;
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '');

            // redireciona e apresenta mensagem de erro
            manageMessages('error', 1);
            return $response->withRedirect('/');
        }

        // Sanitiza os dados
        $formData = (object) $this->sanitizeData($data);

        //print_r($formData); die;

        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['title', 'type', 'category_id', 'value', 'created_at'];

        foreach($fieldsToCheck as $field) {
            if (empty($formData->$field)) {
                manageMessages('error',3);
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        // Salva transação no banco de dados
        if($this->model->store($formData)) {
            manageMessages('success',1);
        } else {
            manageMessages('error',6);
        }

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');

        
    }

}
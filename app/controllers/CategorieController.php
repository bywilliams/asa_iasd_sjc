<?php 

namespace app\controllers;
use app\traits\GlobalControllerTrait;
use app\models\CategoriesModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategorieController 
{

    use GlobalControllerTrait;

    private $model;

    public function __construct()
    {
        $this->model = new CategoriesModel();
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Válida e checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '');

            // redireciona e apresenta mensagem de erro
            manageMessages('error', 1);
            return $response->withRedirect('/');
        }

        $formData = (object) $this->sanitizeData($data);

        if (empty($formData->category_name) && empty($formData->category_type)) {
            $_SESSION['old'] = $_POST;
            manageMessages('error', 3);
            return $response->withRedirect('/usuario/dashboard');
        }

        if ($this->model->store($formData)) {
            manageMessages('success',1);
        } else {
            manageMessages('error',6);
        }

        return $response->withRedirect('/usuario/dashboard');
    }

}
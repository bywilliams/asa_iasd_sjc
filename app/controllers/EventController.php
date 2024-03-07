<?php 
namespace app\controllers;
use app\traits\GlobalControllerTrait;
use app\traits\SessionMessageTrait;
use app\models\EventModel;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Classe EventController
 * 
 * Está calsse é responsável pelo CRUD e demais operações de eventos
 */
class EventController
{
    use GlobalControllerTrait, SessionMessageTrait;

    private $model;

    public function __construct()
    {
        $this->model = new EventModel();
    }

    /**
     * Método store 
     * 
     * Este método irá salvar um novo registro(evento) na tabela 'events'
     *
     * @param Request $request A requisição efetuada
     * @param Response $response A resposta 
     * @return response O retorno da resposta
     */
    public function store (Request $request, Response $response): response 
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

        // Limpa a sessão old
        unset($_SESSION['old']);

        $formData = (object) $this->sanitizeData($data);
        
        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['name', 'place', 'user_id', 'description', 'event_date'];

        foreach ($fieldsToCheck as $field) {
            if ($formData->$field == null) {
                $_SESSION['old'] = $_POST;
                manageMessages('error',3);
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        if($this->model->store($formData)) {
            manageMessages('success',1);
        } else {
            manageMessages('error',6);
        }

        return $response->withRedirect('/usuario/dashboard');
    }

}
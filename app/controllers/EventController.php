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
            $this->setMessage('error', 'Ação inválida!');
            return $response->withRedirect('/');
        }

        // Limpa a sessão old
        unset($_SESSION['old']);

        $formData = (object) $this->sanitizeData($data);

        //print_r($formData); die;

        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['name', 'place', 'user_id', 'description', 'event_date'];

        foreach ($fieldsToCheck as $field) {
            if ($formData->$field == null) {
                $_SESSION['old'] = $_POST;
                $this->setMessage('error', 'Preencha os campos obrigatórios!');
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        if($this->model->store($formData)) {
            $this->setMessage('success','Evento cadastrado com sucesso!');
        } else {
            $this->setMessage('error','Falha ao cadastrar evento!');
        }


        return $response->withRedirect('/usuario/dashboard');
    }

}
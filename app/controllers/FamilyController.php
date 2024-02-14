<?php

namespace app\controllers;
session_start();
use app\traits\GlobalControllerTrait;
use app\models\FamilyModel;
use app\traits\SessionMessageTrait;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * Classe FamilyController
 * 
 * Esta classe é responsável pelo CRUD e demais ações de Familias
 */
class FamilyController
{

    use GlobalControllerTrait;
    use SessionMessageTrait;

    private $model;

    public function __construct()
    {
        $this->model = new FamilyModel();
    }

    /**
     * Método index()
     * 
     * Este método irá apresentar a página com a listagem das famílias cadastradas
     */
    public function index(Request $request, Response $response)
    {   
        if(!$this->validateToken()) {
            echo "entrou"; die;
            $this->setMessage('error', 'O token de autenticação é inválido ou expirou. Por favor, faça login novamente!');
            return $response->withRedirect('/');
        }

        view('familias', ['title' => 'Listagem de familias.']);
        return $response;
    }

    /**
     * Método show()
     * 
     * Este método irá apresentar um registro(família) especifíco cadastrado
     */
    public function show()
    {

    }

    /**
     *  Método store()
     * 
     * Este método irá salvar um novo registro(família) na tabela
     */
    public function store(Request $request, Response $response) 
    {
        // Pega o corpo da requisição com Slim
        $data = $request->getParsedBody();

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
        
        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['fullname', 'gender', 'end', 'contact', 'qtde_childs', 'criterion'];

        foreach($fieldsToCheck as $field) {
            if (empty($formData->$field)) {
                $this->setMessage('error', 'Preencha os campos obrigatórios!');
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        // checka se a familia já foi cadastrada
        if ($this->model->checkFamilyExist($formData->contact)) {
            $this->setMessage('error', 'Familia já existente!');
            return $response->withRedirect('/usuario/dashboard');
        }
        
        // Salva registro no banco de dados
        $this->model->store($formData);

        // Configura mensagem de status de sucesso
        $this->setMessage('success', 'Registro inserido com sucesso!');

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');
    }

    /**
     *  Método update()
     * 
     * Este método irá atualizar um registro(família) na tabela
     */
    public function update() {} 

    /**
     *  Método destroy()
     * 
     * Este método irá deletar um registro(família) especifíco na tabela
     */
    public function destroy(){}


}
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
        if (!$this->validateToken()) {
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

        $params = ['full_name', 'gender', 'id', 'qtde_childs'];
        $sql = '';
        $bindParams = [];

        foreach ($params as $param) {
            if (isset($_GET[$param]) && !empty(trim($_GET[$param]))) {
                $sql .= " AND $param = $_GET[$param]";
            }
        }

        $familyModel = new FamilyModel();
        $families = $familyModel->index($inicio, $itenPerPage, $sql);

        // Total de registros na tela
        $totalRegistros = $families['totalRegistros'];

        // Calcular o número total de páginas
        $totalPaginas = ceil($totalRegistros / $itenPerPage);

        view('familias', [
            'title' => 'Listagem de familias.',
            'user' => $userLogged,
            'families' => $families['data'],
            'page' => $page,
            'totalPaginas' => $totalPaginas
        ]);
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
        $fieldsToCheck = ['fullname', 'gender', 'address', 'qtde_childs', 'contact', 'criteria_id'];

        foreach ($fieldsToCheck as $field) {
            if ($formData->$field == null) {
                $_SESSION['old'] = $_POST;
                $this->setMessage('error', 'Preencha os campos obrigatórios!');
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        // Limpa a sessão old
        unset($_SESSION['old']);

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
    public function update()
    {
    }

    /**
     *  Método destroy()
     * 
     * Este método irá deletar um registro(família) especifíco na tabela
     */
    public function destroy()
    {
    }
}

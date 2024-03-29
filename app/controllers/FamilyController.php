<?php

namespace app\controllers;
use app\traits\GlobalControllerTrait;
use app\models\FamilyModel;
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
    public function index(Request $request,Response $response)
    {
        if (!$this->validateJwtToken()) {
            manageMessages('error', 4);
            return $response->withRedirect('/');
        }

        // check e recebe dados do usuário logado
        $userLogged = (object) $this->validateJwtToken();

        // Ler o parâmetro da página da url
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itenPerPage = 10;

        // calcula o indice de início de fim dos dados para a página atual
        $inicio = ($page - 1) * $itenPerPage;

        // Monta o SQL de pesquisa personalizada
        $sql = '';
        if ($_GET) {
            $params = ['id', 'full_name', 'gender', 'address', 'qtde_childs', 'sits_family_id', 'created_at'];
            $aliases = ['id' => 'f', 'full_name' => 'f', 'gender' => 'f', 'address' => 'f',  'qtde_childs' => 'f', 'sits_family_id' => 'f', 'created_at' => 'f'];
            $sql = $this->createSqlConditions($params, $_GET, $aliases);
            $_SESSION['old'] = $_GET;
        }

        // Mantêm os valores dos inputs de pesquisa
        $old = $_SESSION['old'] ?? null;

        // Variável que obtêm a query padrão ou personalizada 
        $families = $this->model->index($inicio, $itenPerPage, $sql);

        // Total de registros na tela
        $totalRegistros = $families['totalRegistros'];

        // Calcular o número total de páginas
        $totalPaginas = ceil($totalRegistros / $itenPerPage);

        view('familias', [
            'title' => 'Listagem de familias.',
            'user' => $userLogged,
            'families' => $families,
            'page' => $page,
            'totalPaginas' => $totalPaginas,
            'old' => $old
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

        // Converte em objeto
        $formData = (object) $this->sanitizeData($data);

        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['fullname', 'gender', 'address', 'qtde_childs', 'contact', 'criteria_id'];

        foreach ($fieldsToCheck as $field) {
            if ($formData->$field == null) {
                $_SESSION['old'] = $_POST;
                manageMessages('error',3);
                return $response->withRedirect('/usuario/dashboard');
            }
        }

        // checka se a familia já foi cadastrada
        if ($this->model->checkFamilyExist($formData->contact)) {
            manageMessages('error',2);
            return $response->withRedirect('/usuario/dashboard');
        }

        // Salva registro no banco de dados e apresenta mensagem status
        if($this->model->store($formData)) {
            manageMessages('success',1);
        } else {
            manageMessages('error',6);
        }

        // Redireciona para a rota da dashboard
        return $response->withRedirect('/usuario/dashboard');
    }

    /**
     *  Método update()
     * 
     * Este método irá atualizar um registro(família) na tabela
     */
    public function update(Request $request, Response $response, $args)
    {

        $data = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '', time() - 3600, "/"); // Adiciona tempo no passado e o caminho do cookies

            // redireciona e apresenta mensagem de erro
            manageMessages('error', 1);
            return $response->withRedirect('/');
        }

        // Converte em objeto
        $formData = (object) $this->sanitizeData($data);

        // Checa se os campos do form estão válidos
        $fieldsToCheck = ['full_name', 'gender', 'address', 'qtde_childs', 'sits_family_id', 'contact', 'criteria_id'];

        foreach ($fieldsToCheck as $field) {
            if ($formData->$field == null) {
                $_SESSION['old'] = $_POST;
                manageMessages('error',3);
                return $response->withRedirect('/family/index');
            }
        }

        // Atualiza família e apresenta mensagem status
        if($this->model->update($args['id'], $formData)) {
            manageMessages('success',3);
        } else {
            manageMessages('error',7);
        }

        return $response->withRedirect('/family/index');
    }

    /**
     *  Método destroy()
     * 
     * Este método irá deletar um registro(família) especifíco na tabela
     */
    public function destroy(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();

        // Válida checa a válidade do CSRF Token
        if (!$this->validateCsrfToken($data)) {
            // Limpa o Cookie
            setcookie('token', '', time() - 3600, "/"); // Adiciona tempo no passado e o caminho do cookies

            // redireciona e apresenta mensagem de erro
            manageMessages('error', 1);
            return $response->withRedirect('/');
        }

        if($this->model->destroy($args['id'])) {
            manageMessages('success', 2);
        } else {
            manageMessages('error', 5);
        }

        return $response->withRedirect('/family/index');

    }
}

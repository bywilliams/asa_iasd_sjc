<?php

/**
 * Função view()
 * 
 * Está função é usada para rendereizar uma view
 *
 * @param string $view O nome da view
 * @param array $data O conjunto de arrays associativos desejado
 * @return void
 */
function view(string $view, array $data = [])
{
    $path = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'views';

    // Cria uma nova instância do Plates 
    $templates = new League\Plates\Engine($path);

    // Renderiza um template
    echo $templates->render($view, $data);
}

/**
 * Função Helper generateCsrfToken()
 * 
 * Está função gera 64 caracteres aleatórios para usar como um ID único em todos os forms
 *
 */
function generateCsrfToken()
{
    $newCsrfToken = bin2hex(random_bytes(32)); // 64 caracteres aleatórios
    $_SESSION['csrf_token'] = $newCsrfToken;
}

/**
 * Função helper manageMessages()
 * 
 * Está função é responsável por gerenciar as mensagens do sistema tanto avisos de erros quanto para avisos de sucesso
 * Em caso de dúvida consulte o escopo da função
 * @param string $type O tipo da mensagem ('error', 'success')
 * @param integer $messageKey A chave da mensagem com seu respectivo valor (ex: manageMessages('error', 1) = 'Ação inválida!' ) 
 * @return void
 */
function manageMessages(string $type, int $messageKey, object $data = null) {

    // Define as mensagens pré-definidas
    $messages = new stdClass();
    $messages->success = array(
        1 => 'Registro inserido com sucesso!',
        2 => 'Registro deletado com sucesso!',
        3 => 'Registro atualizado com sucesso',
        4 => 'Seja bem vindo (a) '. $data->name . ' ao sistema da ASA!',
        6 => 'Cesta doada com sucesso!',
        7 => 'Loggof efetuado com sucesso!'
    );

    $messages->error = array(
        1 => 'Ação inválida!',
        2 => 'Família já existente!',
        3 => 'Preencha os campos obrigatórios!',
        4 => 'Por favor, faça login novamente!',
        5 => 'Erro ao deletar registro, consulte o administrador do sistema.',
        6 => 'Erro ao inserir registro, consulte o administrador do sistema.',
        7 => 'Erro ao doar cesta, consulte o administrador do sistema!',
        8 => 'Erro ao atualizar registro, consulte o administrador do sistema',
        9 => 'Acesso negado, faça login novamente!',
        10 => 'Usuário não encontrado.',
        11 => 'Usuário e/ou senha inválidos!'        
    );

    // Verifica tipo e chave são válidos
    if (isset($messages->$type) && isset($messages->$type[$messageKey])) {
        $_SESSION['status_report'] = $type;
        $_SESSION['message'] = $messages->$type[$messageKey];
    } else {
        // Mensagem de erro genérica 
        $_SESSION['status_report'] = 'error';
        $_SESSION['message'] = 'Mensagem desconhecida';
    }
}




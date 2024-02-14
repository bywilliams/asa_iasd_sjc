<?php 
namespace app\traits;

/**
 * trait SessionMessageTrait
 * 
 * Este trait é utilizado para configurar mensagem de retorno de ações dentro do sistema
 * 
 */
trait SessionMessageTrait
{  

    /**
     * Função setMessage()
     * 
     * Está função seta um status (error, success) para uma mensagem de retorno parta alguma ação realizada pelo usuário
     * 
     * @param mixed $type O tipo da mensagem (error) ou (success)
     * @param mixed $message A mensagem referênte ao status 
     */
    private function setMessage($type, $message)
    {
        $_SESSION['status_report'] = $type;
        $_SESSION['message'] = $message;
    }

}
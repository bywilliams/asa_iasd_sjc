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
     * Função log_error()
     * 
     * Esta função é reponsável por alimentar o log de erros proveniente do banco de dados,
     * ela grava as mensagens no arquivo db.log dentro do path public/log
     *
     * @param string $error_message A mensagem vinda do PDOException
     * @return void
     */
    private function log_error(string $error_message)
    {
        $currentTime = date('d-m-Y H:i:s');

        // path do arquivo de log
        $arquivo_log = $_SERVER['DOCUMENT_ROOT'] . '/log/db.log';

        $formatted_msg = $currentTime . " - " . $error_message . "\n";

        file_put_contents($arquivo_log, $formatted_msg, FILE_APPEND);

    }

}
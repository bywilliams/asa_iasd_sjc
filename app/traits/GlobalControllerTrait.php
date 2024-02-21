<?php

namespace app\traits;

use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait GlobalControllerTrait
{
    /**
     * Método private validateCsrfToken
     * 
     * Este método verifica se o CSRF Token existe e é válido
     * 
     * $param Request $request A resposta HTTP
     */
    private function validateCsrfToken($request)
    {
        return isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['csrf_token'];
    }

    private function sanitizeData($formData)
    {
        foreach ($formData as $key => $value) {
            $formData[$key] = htmlspecialchars($value, ENT_QUOTES);
            $formData[$key] = trim($value);
            $formData[$key] = stripslashes($value);
        }

        return $formData;
    }


    private function generateJwtToken($userFound)
    {

        $header = [
            'alg' => 'HS256',
            'type' => 'JWT'
        ];

        // duração do token 15 minutos
        $duration = time() + (60 * 60);

        $payload = [
            'exp' => $duration,
            'iat' => time(),
            'id' => $userFound->id,
            'email' => $userFound->email,
            'username' => $userFound->name . ' ' . $userFound->lastname,
            'nivel_acesso' => $userFound->access_level_id
        ];

        function base64url_encode($data)
        {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }

        // Codificar o cabeçalho e a carga útil para Base64 URL
        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));

        //gerar assinatura
        $signature = hash_hmac('sha256', "$header.$payload", $_ENV['KEY']);

        // Sanitiza a assinatura
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Monta o JWT final
        $token = "$header.$payload.$signature";

        // Salva o cookie para todas as rotas do mesmo site
        setcookie('token', $token, [
            'path' => '/',
            'samesite' => 'Strict'
        ]);
    }

    private function validateToken()
    {

        $token = $_COOKIE['token'];
        $tokenArray = explode('.', $token);

        $header = $tokenArray[0];
        $payload = $tokenArray[1];
        $providedSignature = $tokenArray[2];

        // Calcula a assinatura esperada
        $expectedSignature = hash_hmac('sha256', "$header.$payload", $_ENV['KEY']);

        // Codifica para base64
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        // Verifica se a assinatura fornecida é igual do token
        if ($providedSignature == $expectedSignature) {

            // Verifica tempo de expiração do token
            $dadosToken = base64_decode($payload);
            $dadosToken = json_decode($dadosToken);

            if ($dadosToken->exp > time()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    private function getUserName()
    {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $tokenArray = explode('.', $token);
            $payload = $tokenArray[1];

            $payload = base64_decode($payload);
            $payload = json_decode($payload);

            return $payload;
        } else {
            return '';
        }
    }
    
    /**
     * Função createSqlCOnditions()
     * 
     * Esta função cria SQL conditions personalziado
     *
     * @param [type] $params Nomes dos campos 
     * @param [type] $getParams Valores do input form
     * @return string $sql a(s) condição(ões) personalizadas para o WHERE 
     */
    function createSqlConditions($params, $getParams): string
    {
        $sql = '';

        foreach ($params as $field) {
            if (isset($getParams[$field]) && !empty($getParams[$field])) {
                if ($field == 'full_name') {
                    $sql .= " AND f.{$field} LIKE '%{$getParams[$field]}%'";
                } else {
                    $sql .= " AND f.{$field} = '{$getParams[$field]}'";
                }
            }
        }

        return $sql;
    }


    public function logout(Request $request, Response $response)
    {
        if (isset($_COOKIE['token'])) {
            setcookie('token', '');
            $this->setMessage('success', "Loggof efetuado com sucesso!");
            return $response->withRedirect('/');
        }
    }
}

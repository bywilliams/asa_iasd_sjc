<?php

namespace app\traits;

session_start();

use Exception;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

        // duração do token 1 hora
        $duration = time() + (60 * 60);

        $token = JWT::encode(
            array(
                'exp' => $duration,
                'iat' => time(),
                'id' => $userFound->id,
                'email' => $userFound->email,
                'username' => $userFound->name . ' ' . $userFound->lastname,
                'nivel_acesso' => $userFound->access_level_id
            ),
            $_ENV['KEY'],
            'HS256'
        );


        // Salva o cookie para todas as rotas do mesmo site
        setcookie('token', $token, [
            'path' => '/',
            'samesite' => 'Strict'
        ]);


        return $token;
    }

    private function validateJwtToken()
    {

        try {

            // Decodifica o token
            $decoded = JWT::decode($_COOKIE['token'], new Key($_ENV['KEY'], 'HS256'));

            // Verifica se o token expirou
            if ($decoded->exp < time()) {
                return false;
            }

            // O token é válido, retorna os dados do usuário
            return array(
                'id' => $decoded->id,
                'email' => $decoded->email,
                'username' => $decoded->username,
                'nivel_acesso' => $decoded->nivel_acesso
            );
        } catch (Exception $e) {
            // O token não é válido
            return false;
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
    function createSqlConditions(array $params, array $getParams, array $aliases): string
    {
        $sql = '';

        foreach ($params as $field) {
            if (isset($getParams[$field]) && !empty($getParams[$field])) {
                $alias = $aliases[$field] ?? $field;
                if ($field == 'full_name' || $field == 'address') {
                    $sql .= " AND {$alias}.{$field} LIKE '%{$getParams[$field]}%'";
                } elseif ($field == 'created_at') {
                    $sql .= " AND DATE({$alias}.{$field}) = '{$getParams[$field]}'";
                } else {
                    $sql .= " AND {$alias}.{$field} = '{$getParams[$field]}'";
                }
            }
        }

        return $sql;
    }



    public function logout(Request $request, Response $response)
    {
        if (isset($_COOKIE['token'])) {
            setcookie('token', '', time() - 3600, "/");
            manageMessages('success', 7);
            return $response->withRedirect('/');
        }
    }
}

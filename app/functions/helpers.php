<?php

function view(string $view, array $data = [])
{
    $path = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'views';

    // Cria uma nova instância do Plates 
    $templates = new League\Plates\Engine($path);

    // Renderiza um template
    echo $templates->render($view, $data);
}

function generateCsrfToken()
{
    $newCsrfToken = bin2hex(random_bytes(32)); // 64 caracteres aleatórios
    $_SESSION['csrf_token'] = $newCsrfToken;
}



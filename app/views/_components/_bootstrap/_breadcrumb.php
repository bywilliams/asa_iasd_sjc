<?php 


function generate_breadcrumb() {
    $url = $_SERVER["REQUEST_URI"];
    
    // Divide a URL na primeira ocorrência de '?'
    $urlParts = explode('?', $url);
    $url = $urlParts[0];
    
    $parts = explode('/', $url);
    $breadcrumb = '<nav aria-label="breadcrumb"><ol class="breadcrumb py-1 bg-transparent">';

    foreach ($parts as $part) {
        if (!empty($part)) {
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . ucfirst($part) . '</li>';
        }
    }

    $breadcrumb .= '</ol></nav>';
    return $breadcrumb;
}

echo generate_breadcrumb();



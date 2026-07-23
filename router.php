<?php

// =============================================
// PHP BUILT-IN SERVER ROUTER
// =============================================

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// =============================================
// BLOCK API ACCESS FROM BROWSER
// =============================================

if (strpos($uri, '/api/') === 0) {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    
    // Check if it's a browser request
    $isBrowser = false;
    $browserAgents = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'MSIE', 'Trident', 'Mobile', 'Android', 'iPhone', 'iPad'];
    foreach ($browserAgents as $agent) {
        if (stripos($userAgent, $agent) !== false) {
            $isBrowser = true;
            break;
        }
    }
    
    // Also check Accept header for text/html
    if (strpos($accept, 'text/html') !== false) {
        $isBrowser = true;
    }
    
    // Check for X-Requested-With header (AJAX)
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    // If it's a browser request and NOT AJAX, block it
    if ($isBrowser && !$isAjax) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Forbidden. API access not allowed from browser.'
        ]);
        exit;
    }
}

// =============================================
// SERVE STATIC FILES
// =============================================

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    // Serve static files
    $path = __DIR__ . '/public' . $uri;
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'json' => 'application/json',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'html' => 'text/html',
    ];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    readfile($path);
    exit;
}

// =============================================
// BOOT LARAVEL
// =============================================

require_once __DIR__ . '/public/index.php';

<?php
// router.php
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove parâmetros de query string
$path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $path);

// Se for uma requisição para a API
if (strpos($path, '/backend/') === 0) {
    $file = __DIR__ . $path;
    
    // Verifica se o arquivo existe
    if (file_exists($file) && is_file($file)) {
        // Serve o arquivo PHP
        include $file;
        return true;
    } else {
        // Arquivo não encontrado
        header("HTTP/1.0 404 Not Found");
        echo "Arquivo API não encontrado: " . $path;
        return true;
    }
}

// Para a raiz (/), serve o index.php da pasta public
if ($path === '/' || $path === '') {
    include __DIR__ . '/public/index.php';
    return true;
}

// Para outros arquivos na pasta public
$public_file = __DIR__ . '/public' . $path;
if (file_exists($public_file) && is_file($public_file)) {
    // Serve arquivos estáticos (CSS, JS, imagens)
    if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico)$/', $public_file)) {
        $ext = pathinfo($public_file, PATHINFO_EXTENSION);
        $content_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon'
        ];
        
        if (isset($content_types[$ext])) {
            header('Content-Type: ' . $content_types[$ext]);
        }
        
        readfile($public_file);
        return true;
    }
    
    // Serve outros arquivos PHP na pasta public
    include $public_file;
    return true;
}

// Se não encontrou nenhum arquivo, mostra erro 404
header("HTTP/1.0 404 Not Found");
echo "Página não encontrada: " . $path;
?>
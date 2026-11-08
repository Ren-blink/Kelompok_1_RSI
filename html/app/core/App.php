<?php

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Cek Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            if (file_exists(__DIR__ . '/../controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Cek Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Kelola Parameter
        $this->params = $url ? array_values($url) : [];

        // 4. Jalankan controller dan method dengan parameter yang ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        // 1. Prioritas 1: Ambil dari query string ?url= (dari .htaccess Apache)
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        // 2. Prioritas 2: Parse dari REQUEST_URI (PHP Built-in Server atau Path Info)
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = dirname($scriptName);

        if ($baseDir !== '/' && $baseDir !== '\\' && strpos($requestUri, $baseDir) === 0) {
            $requestUri = substr($requestUri, strlen($baseDir));
        }

        $url = trim($requestUri, '/');
        if (strpos($url, 'index.php') === 0) {
            $url = trim(substr($url, strlen('index.php')), '/');
        }

        if (!empty($url)) {
            return explode('/', filter_var($url, FILTER_SANITIZE_URL));
        }

        return [];
    }
}

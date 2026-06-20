<?php

namespace App\Core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;

    public Database $db;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        
        $dbConfig = [
            'dsn' => $_ENV['DB_CONNECTION'] === 'sqlite' 
                     ? 'sqlite:' . self::$ROOT_DIR . '/' . $_ENV['DB_DATABASE'] 
                     : $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'] ?? '',
            'password' => $_ENV['DB_PASSWORD'] ?? ''
        ];
        $this->db = new Database($dbConfig);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}

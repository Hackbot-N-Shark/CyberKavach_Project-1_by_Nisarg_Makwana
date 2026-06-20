<?php

namespace App\Core;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    protected array $middlewares = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback, $middlewares = [])
    {
        $this->routes['get'][$path] = $callback;
        $this->middlewares['get'][$path] = $middlewares;
    }

    public function post($path, $callback, $middlewares = [])
    {
        $this->routes['post'][$path] = $callback;
        $this->middlewares['post'][$path] = $middlewares;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView("_404", ['title' => '404 - Not Found', 'pageId' => '404']);
        }

        // Execute Middlewares
        $routeMiddlewares = $this->middlewares[$method][$path] ?? [];
        foreach ($routeMiddlewares as $middlewareClass) {
            // $middlewareClass could be a string or an object depending on implementation.
            // If it's a string, we instantiate it. If it's an object, we use it directly.
            $middleware = is_string($middlewareClass) ? new $middlewareClass() : $middlewareClass;
            $middleware->execute();
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent($params);
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent($params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/resources/views/layouts/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/resources/views/$view.php";
        return ob_get_clean();
    }
}

<?php

namespace mg\FrameworkPhpMvcCore;


use mg\FrameworkPhpMvcCore\Response;
/**
 * class Router
 *
 * @package mg\FrameworkPhpMvcCore
 *
 */
class Router
{
    public Request $request;
    public Response $response;

    protected array $routes = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }


    public function resolve()
    {
        $path  = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false)
        {
            throw new \mg\FrameworkPhpMvcCore\exception\NotFoundException();
        }
        if (is_string($callback))
        {
            return  $this->renderView($callback);
        }
        if (is_array($callback))
        {
            /** @var \mg\FrameworkPhpMvcCore\Controller $controller  */
            $controller = new $callback[0]();
            Application::$app->controller = $controller ;
            Application::$app->controller->action = $callback[1];
            $callback[0] = Application::$app->controller;

            foreach ($controller->getMiddlewares() as $middleware)
            {
                $middleware->execute();
            }


        }

        return call_user_func($callback, $this->request, $this->response);

    }

}
<?php

namespace mg\FrameworkPhpMvcCore;

use mg\FrameworkPhpMvcCore\Application;
use mg\FrameworkPhpMvcCore\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    /**
     *  @var mg\FrameworkPhpMvcCore\middlewares\BaseMiddleware[]
     * */
    protected array $middlewares = [];

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    public function view(string $view,array $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleWare $middleware)
    {
        $this->middlewares[] = $middleware;
    }
}
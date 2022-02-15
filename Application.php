<?php

namespace mg\FrameworkPhpMvcCore;

use mg\FrameworkPhpMvcCore\View;
use mg\FrameworkPhpMvcCore\db\DataBase;
use  mg\FrameworkPhpMvcCore\db\DbModel;

/**
 * class Application
 *
 * @package mg\FrameworkPhpMvcCore
 *
 */

class Application
{
    public static string $ROOT_DIR;
    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public DataBase $db;
    public static Application $app;
    public ?Controller $controller = null;
    public ?DbModel $user;
    public View $view;

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config)
    {

        $this->user = null;
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new DataBase($config['db']);
        $this->view = new View();

        $userId = Application::$app->session->get('user');
        if ($userId) {
            $key = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$key => $userId]);
        }
    }

    public function run()
    {
        try {
            echo $this->router->resolve();

        } catch (\Exception $exception){
            $this->response->setStatusCode($exception->getCode());
           echo $this->view->renderView('_error', ['exception' => $exception  ]);
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }
    
    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $value = $user->{$primaryKey};
        Application::$app->session->set('user', $value);

        return true;
    }

    public function logout()
    {
        $this->user = null;
        self::$app->session->remove('user');
    }

}
<?php 

    namespace app\core;

    use app\core\Application;
    use app\core\Router;
    use app\core\middlewares\BaseMiddleware;
    


    class Controller 
    {
        public string $layout = 'main';
        public string $action = '';

        /** @var \app\core\middlewares\BaseMiddleware[] */

        protected array $middlewares = [];

        public function setlayout($layout)
        {
            # code...
            $this->layout = $layout;
        }

        public function render($view , $params = [])
        {
            # code...
            return Application::$app->view->renderView($view, $params);
        }

        public function registerMiddleware(BaseMiddleware $middleware)
        {
            $this->middlewares[] =$middleware;
        }

        /**
         * @return BaseMiddleware[]
         */
        public function getMiddlewares(): array
        {
            return $this->middlewares;
        }


    }

?>
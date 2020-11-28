<?php 

    namespace edj\mvcframecore;

    use edj\mvcframecore\Application;
    use edj\mvcframecore\Router;
    use edj\mvcframecore\middlewares\BaseMiddleware;
    


    class Controller 
    {
        public string $layout = 'main';
        public string $action = '';

        /** @var \edj\mvcframecore\middlewares\BaseMiddleware[] */

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
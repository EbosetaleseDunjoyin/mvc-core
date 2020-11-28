<?php 

    namespace edj\mvcframecore\middlewares;

    use edj\mvcframecore\Application;
    use edj\mvcframecore\exception\ForbiddenException;

    class  AuthMiddleware extends BaseMiddleware
    {

        public array $actions = [];

        public function __construct(array $actions = [] ) {
            $this->actions = $actions;
        }

        public function execute()
        {
            if (Application::isGuest()){
                if (empty($this->actions) || \in_array(Application::$app->controller->action, $this->actions)){
                    //throw new ForbiddenException();
                    echo Application::$app->response->redirect('/login');
                }
            }
        }
        
    }
    






?>
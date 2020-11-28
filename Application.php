<?php 

   
    namespace app\core ;
    
    use app\core\db\Database;
    use app\core\Controller;
use app\models\User;

/* @package app/core */

    class Application{

        public static string $ROOT_DIR;

        public string $layout ='main';
        public string $userClass;
        public Request $request;
        public Router $router;
        public View $view;
        public Response $response;
        public Session $session;
        public Database $db;
        public ?UserModel $user;

        public static Application $app;
        public ?Controller $controller = null;

        /**
         * Application constructor.
         * @param $rootPath
         */
        public function __construct($rootPath, array $config){

            

            self::$ROOT_DIR = $rootPath;
            self::$app = $this;
            $this->userClass = $config['userClass'];
            $this->request = new Request();
            $this->response = new Response();
            $this->session = new Session();
            $this->router = new Router($this->request, $this->response);
            $this->view = new View();

            $this->db = new Database($config['db']);

            $primaryValue = $this->session->get('user');

            if ($primaryValue) {
                # code...
                $primaryKey = $this->userClass::primaryKey();
                $this->user = $this->userClass::findone([$primaryKey => $primaryValue]);
            } else {
                $this->user = null;
            }
            
        }


        public function run() {
            //todo
            try {
                echo  $this->router->resolve();
            } catch (\Exception $e){
                $this->response->setStatusCode($e->getCode());
                echo $this->view->renderView('_error',[
                    'exception' => $e,
                ]);
            }

        }

        /**
         * @return mixed
         */
        public function getController() {
            return $this->controller;
        }

        /**
         * @param \app\core\Controller $controller
         */
        public function setController(Controller $controller) :void {
             $this->controller= $controller;
        }


        //Login
        public function login(UserModel $user)
        {
            # code...
            $this->user = $user;
            $primaryKey = $user->primaryKey();
            $primaryValue = $user->{$primaryKey};
            $this->session->set('user', $primaryValue);

            return true;

        }

        public static function isGuest()
        {
            return !self::$app->user;
        }

        //Logout
        public function logout()
        {
            # code...
            $this->user = null;
           $this->session->remove('user');

        }






    }



?>


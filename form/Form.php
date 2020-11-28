<?php 

    namespace edj\mvcframecore\form;

    use edj\mvcframecore\Model;

    class Form
    {
        public static function begin($action, $method)
        {
            echo sprintf('<form action="%s" method="%s" >', $action , $method) ;
            return new Form();
        }


        public static function end()
        {
           echo '</form>';
        }

        public function field(Model $model, $attribute)
        {
            # code...
            return new InputField($model, $attribute);
        }

    }
    






?>
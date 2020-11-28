<?php


namespace edj\mvcframecore\form;


use edj\mvcframecore\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        # code...

        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput():string ;

    public function __toString()
    {
        return sprintf('
                <div class="form-group">
                    <label> %s</label>
                    %s
                    <div class="invalid-feedback">
                        %s
                    </div>
                </div>'
            , $this->model->getlabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}
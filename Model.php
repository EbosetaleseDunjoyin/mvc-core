<?php

namespace edj\mvcframecore;




abstract class Model
{
    //Authentication
    public const RULE_REQUIRED= 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MAX = 'max';
    public const RULE_MIN = 'min';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';


    public function loadData($data)
    {
        # code...
        foreach ($data as $key => $value) {
            # code...
            if (property_exists($this, $key)) {
                # code...
                $this->{$key} = $value;
            }
        }


    }



    abstract public function rules(): array ;

    public function labels(): array
    {
        return [];
    }

    //get the label

    public function getlabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    //Error Bank
    public array $errors =[];

    //Validate forms
    public function validate()
    {
        # iterating through the array

        foreach ($this->rules() as $attribute => $rules) {
            # code...
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                # code...
                $ruleName = $rule;
                //if not a string then it is an array
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }

                //Required
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    # code...
                    $this->addErrorForRule($attribute,self::RULE_REQUIRED);
                }

                //Email
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    # code...
                    $this->addErrorForRule($attribute,self::RULE_EMAIL);
                }

                //Minimum
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    # code...
                    $this->addErrorForRule($attribute, self::RULE_MIN , $rule);
                }

                //maximum
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    # code...
                    $this->addErrorForRule($attribute , self::RULE_MAX , $rule);
                }

                //Match
                if ($ruleName === self::RULE_MATCH &&  $value !== $this->{$rule['match']}) {
                    # code...
                    $rule['match'] = $this->getlabel($rule['match']);
                    $this->addErrorForRule($attribute , self::RULE_MATCH , $rule);
                }

                //Unique 
                if ($ruleName === self::RULE_UNIQUE ) {
                    # code...
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();

                    $statement=Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr =:attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();

                    $record =$statement->fetchObject();

                    if ($record) {
                        # code...
                        $this->addErrorForRule($attribute , self::RULE_UNIQUE , ['field' => $this->getlabel($attribute)]);
                    }
                    
                }

            }

        }

        return empty($this->errors);

    }

    //fing Errors
    private function addErrorForRule(string $attribute, string $rule, $params =[]){
        $message = $this->errorMessage()[$rule] ?? '';

        foreach ($params as $key => $value){
            $message = str_replace("{{$key}}" , $value, $message);
        }
        $this->errors[$attribute][]= $message;
    }

    public function addError(string $attribute, string $message){

        $this->errors[$attribute][]= $message;
    }

    //errorMessages
    public function errorMessage()
    {
        # code...
        return [
            self::RULE_REQUIRED => 'The field is required',
            self::RULE_EMAIL => 'The field must have a valid E-mail ',
            self::RULE_MIN => 'The minimum length required is {min}',
            self::RULE_MAX => 'The maximum length required is {max}',
            self::RULE_MATCH => 'This field does not match the {match}',
            self::RULE_UNIQUE => 'This {field} has already been used',
        ];
    }

    //specify errors
    public function hasError($attribute)
    {
        # code...
        return $this->errors[$attribute] ?? false;
    }

    //Add its errors
    public function getFirstError($attribute)
    {
        # code...
        return $this->errors[$attribute][0] ?? false;
    }

}










?>
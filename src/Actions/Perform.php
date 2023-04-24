<?php

namespace MyDpo\Actions;

// use MyDpo\Helpers\Validator;
// use ComptechSoft\MyHelpers\Classes\Core\Success;
// use ComptechSoft\MyHelpers\Classes\Core\Exception;

class Perform {

    public $input = NULL;
    public $rules = NULL;
    public $messages = NULL;

    public $payload = NULL;

    /**
     * mesajul de success
     */
    public $success = '';
    
    public $exception = NULL;

    public function __construct($input, $rules = NULL, $messages = []) {
        $this->input = $input;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public function SetSuccessMessage($message) {
        $this->success = $message;
        return $this;
    }

    public function SetExceptionMessage($message) {
        $this->exception = $message;
        return $this;
    }

    public function GetExceptionMessage($e) {

        $class = get_class($e);
        if(is_array($this->exception))
        {
            foreach($this->exception as $exception => $message)
            {
                if($exception == $class)
                {
                    return $message ? $message : $e->getMessage();
                }
            }
        }
        return $e->GetMessage();
    }

    /**
     * Uneori vine ceva si trebuie validat altceva
     */
    public function GetValidationInput() {
        return $this->input;
    }

    /**
     * Uneori vine ceva si trebuie prelucrate altceva
     */
    public function PrepareInput() {
        return $this->input;
    }

    /**
     * Imediat inainte de actiune
     */
    public function BeforeAction() {
    }

    /**
     * Imediat dupa de actiune
     */
    public function AfterAction() {
    }

    /**
     * Aceasta metoda este punctul de intrare si trebuie apelata
     */
    public function Perform() {

        if($this->rules)
        {   
            $validationInput = $this->GetValidationInput();            

            $valid = (new Validator($validationInput, $this->rules, $this->messages))->Validate();

            if( ! ($valid === true) )
            {
                return $valid;
            }
        }

        \DB::beginTransaction();
        try
        {
            $this->PrepareInput();
            $this->BeforeAction();
            $this->Action(); 
            $this->AfterAction();

            \DB::commit();
            return Success::Response($this->success, $this->payload);
        }
        catch(\Exception $e)
        {
            \DB::rollBack();
            $message = $this->GetExceptionMessage($e);
            return Exception::Response($e, $message);
        }

    }

    public function __get($property) {

        if( array_key_exists($property, $this->input) )
        {
            return $this->input[$property];
        }
        return NULL;
    }

    public function __set($property, $value) {

        if( array_key_exists($property, $this->input) )
        {
            $this->input[$property] = $value;
        }

    }

}
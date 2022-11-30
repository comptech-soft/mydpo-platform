<?php

namespace MyDpo\Helpers;

use MyDpo\Helpers\Validator;
use MyDpo\Helpers\Response;

class Perform {

    public $input = NULL;
    public $rules = NULL;
    public $messages = NULL;

    public $payload = NULL;
    public $success = NULL;             // mesajul de success
    public $exception = NULL;

    public function __construct($input, $rules = NULL, $messages = []) {
        $this->input = $input;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public function IsBoolean($fields) {
        foreach($fields as $i => $field)
        {
            if(array_key_exists($field, $this->input))
            {
                if(is_string($this->input[$field]))
                {
                    $this->input[$field] = in_array($this->input[$field], ['true', '1']); 
                }
                else
                {
                    if(is_null($this->input[$field])) 
                    {
                        $this->input[$field] = false;
                    }
                }
            } 
            else
            {
                $this->input[$field] = false;
            }
        }
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

            dd($validationInput, $this->rules, $this->messages);

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
            return Response::OK($this->success, $this->input, $this->payload);
        }
        catch(\Exception $e)
        {
            \DB::rollBack();
            $message = $this->GetExceptionMessage($e);
            return Response::Exception($e, $this->input, $message);
        }

    }

}
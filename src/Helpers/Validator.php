<?php

namespace MyDpo\Helpers;

use MyDpo\Helpers\Response;

class Validator {

    public $input = NULL;

    public $rules = NULL;

    public $messages = NULL;

    public $validator = NULL;

    public function __construct($input, $rules, $messages) {

        $this->input = $input;
        $this->rules = $rules;
        $this->messages = $messages;

        $this->validator = \Validator::make($input, $rules, $messages);
    }

    public function Validate() {

        return $this->validator->fails() ? Response::Invalid($this->validator, $this->input) : true;

    }

}
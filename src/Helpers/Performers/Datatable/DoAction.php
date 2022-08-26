<?php

namespace B2B\Classes\Comptech\Performers\Datatable;

use B2B\Classes\Comptech\Helpers\Perform;

class DoAction extends Perform {

    public $action = NULL;  
    public $model = NULL;
    public $addCreatedBy = true;
    public $addUpdatedBy = true;
    public $record = NULL;

    public function __construct($action, $input, $model) {
        /** La inceput stim 
         *      - actiunea: insert|update|delete
         *      - inputul
         *      - modelul 
         */
        parent::__construct($input, NULL, NULL);
        $this->action = $action;
        $this->model = $model;

        $this->rules = $this->GetRules();
        $this->messages = $this->GetMessages();

        $this->SetSuccessMessage($this->GetSuccessMessage());
    }

    public function DefaultSuccessMessage() {
        if($this->action == 'insert')
        {
            return 'Adăugare reușită'; //'The record successfully added.';
        }
        if($this->action == 'duplicate')
        {
            return 'Duplicare reușită'; //'The record successfully duplicated.';
        }
        if($this->action == 'update')
        {
            return 'Modificare reușită'; //'The record successfully updated.';
        }
        if($this->action == 'delete')
        {
            return 'Ștergere reușită'; //'The record successfully deleted.';
        }
    }

    public function GetSuccessMessage() {
        return method_exists($this->model, 'GetSuccessMessage') 
            ? call_user_func([$this->model, 'GetSuccessMessage'], $this->action, $this->input) 
            : $this->DefaultSuccessMessage();
    }

    public function GetRules() {
        return method_exists($this->model, 'GetRules') 
            ? call_user_func([$this->model, 'GetRules'], $this->action, $this->input) 
            : [];
    }

    public function GetMessages() {
        return method_exists($this->model, 'GetMessages') 
            ? call_user_func([$this->model, 'GetMessages'], $this->action, $this->input) 
            : [];
    }

    public function GetValidationInput() {
        return method_exists($this->model, 'GetValidationInput') 
            ? call_user_func([$this->model, 'GetValidationInput'], $this->action, $this->input) 
            : $this->input;
    }

    public function PrepareActionInput() {

        $r = array_merge([], $this->input);

        if(method_exists($this->model, 'PrepareActionInput'))
        {
            $r = call_user_func([$this->model, 'PrepareActionInput'], $this->action, $this->input);
        }

        if( ( ($this->action == 'insert') || ($this->action == 'duplicate') ) && $this->addCreatedBy)
        {   
            $r['created_by'] = \Sentinel::check() ? \Sentinel::check()->id : NULL;
        }

        if(($this->action == 'update') && $this->addUpdatedBy)
        {   
            $r['updated_by'] = \Sentinel::check() ? \Sentinel::check()->id : NULL;
        }

        return $r;
    }

    /**
     * Imediat inainte de actiune
     */
    public function BeforeAction() {
        if(method_exists($this->model, 'BeforeAction') )
        {
            $this->payload['before'] = call_user_func([$this->model, 'BeforeAction'], $this->action, $this->input);
        }
    }

    /**
     * Imediat dupa de actiune
     */
    public function AfterAction() {
        if(method_exists($this->model, 'AfterAction') )
        {
            $this->payload['after'] = call_user_func([$this->model, 'AfterAction'], $this->action, $this->input, $this->payload);
        }
    }

    public function GetRecord() {
        $id = $this->input['id'];
        if(method_exists($this->model, 'GetRecord') )
        {
            return call_user_func([$this->model, 'GetRecord'], $id);
        }
        return call_user_func([$this->model, 'find'], $id);
    }

    public function doInsert() {
        return call_user_func([$this->model, 'create'], $this->input);
    }

    public function doDuplicate() {
        $input = collect($this->input)->except(['id'])->toArray();
        return call_user_func([$this->model, 'create'], $input);
    }

    public function doUpdate() {
        $this->record->update($this->input);
        if(method_exists($this->model, 'afterUpdate'))
        {
            call_user_func([$this->model, 'afterUpdate'], $this->input, $this->record);
        }
        return $this->record;
    }

    public function doDelete() {
        $this->record->delete();
        return $this->record;
    }

    public function DispatchAction() {
        if( array_key_exists('id', $this->input))
        {
            if($this->input['id'])
            {
                $this->record = $this->GetRecord();
            }
        }       

        $this->input = $this->PrepareActionInput();

        $method = 'do' . ucfirst($this->action);

        if(method_exists($this->model, $method) )
        {
            return call_user_func([$this->model, $method], $this->input, $this->record);
        }
        return $this->{$method}();
    }

    public function Action() {
        $this->payload = [
            'record' => $this->DispatchAction(),
        ];
    }

}